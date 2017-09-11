<?php

namespace app\weshop\controller;

use app\common\model\Util;
use app\wechat\model\Jssdk;
use app\wechat\model\Wechatsdk;
use app\weshop\model\Cart;
use app\weshop\model\User;
use curl\Curl;
use think\Controller;
use think\Db;
use think\Log;
use think\Request;
use think\Session;

class Order extends Controller
{
    //店铺全局设置
    public $settings;

    /**
     * 显示购物车页面
     */
    public function cart() {
        $cart = new Cart();
        $user = new User();

        //申请用于JSSDK的SignPackage
        $jssdk = new Jssdk();
        $signPackage = $jssdk->getSignPackage();

        $this->assign("signPackage", $signPackage);
        $this->assign('title', '购物车');
        $this->assign('userInfo', (array)$user->getUserInfoRaw($user->getUID()));
        return $this->fetch();
    }

    /**
     * 显示总订单列表
     */
    public function orderList() {
        $this->redirect("Usercenter/ajaxOrderlist");
        return $this->fetch();
    } 

    /**
     * Ajax获取订单请求数据包
     * @see 参见：https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_1
     * @return mixed 返回用于JSAPI发起微信支付所需到参数打包
     */
    public function ajaxGetBizPackage() {
        //加载模型
        $user_model = new User();
        //从post获得订单ID
        $orderId = input("post.orderId");
        if (!empty($orderId)) {

            //用户openid
            $openid = $user_model->getOpenId();
            // 随机字符串 16位
            $nonceStr = Util::createNoncestr();
            // 时间戳
            $timeStamp = strval(time());

            //订单数据
            $orderInfo = Db::name("orders")
                ->where("order_id", $orderId)
                ->find();
            //订单金额，单位为分
            //TODO 加上余额支付功能
            //$totalFee = (floatval($ordrInfo['order_amount']) - floatval($ordrInfo['order_balance'])) * 100;
            $totalFee = (floatval($orderInfo['order_amount'])) * 100;
            //商品描述，取订单的商品名称
            $products = Db::name("orders_detail")->alias("detail")
                ->join("weshop_products_info info", "detail.product_id = info.product_id", "LEFT")
                ->where("detail.order_id", $orderId)
                ->field("product_name")
                ->select();
            if (sizeof($products) > 1) {
                $productName = $products[0]['product_name'] . ' (多种商品)';
            } else {
                Log::info($products);
                $productName = $products[0]['product_name'];
            }
            // 流水号
            $serial_number = $orderInfo['serial_number'];

            //打包微信支付统一下单的请求参数
            $pack = array(
                // 公众号ID
                'appid' => config("config.APPID"),
                // 商户号ID
                'mch_id' => config("config.MCHID"),
                // 随机字符串
                'nonce_str' => $nonceStr,
                // 商品描述
                'body' => $productName,
                // 商户订单号
                'out_trade_no' => $serial_number,
                // 标价金额
                'total_fee' => $totalFee,
                // 终端IP
                'spbill_create_ip' => Util::getIps(),
                // 通知地址
                'notify_url' => config("config.order_wxpay_notify"),
                // 交易类型
                'trade_type' => 'JSAPI',
                // 用户openid
                'openid' => $openid,
                // 时间戳
                'timeStamp' => $timeStamp,
            );

            // 签名
            $pack['sign'] = Util::paySign($pack);

            //将最终的请求参数打包成XML
            $xml = Util::toXML($pack);

            //发送到统一下单接口，并将返回值记录到ret中
            $ret = Curl::post('https://api.mch.weixin.qq.com/pay/unifiedorder', $xml);
            //解包XML返回值
            $postObj = json_decode(json_encode(simplexml_load_string($ret, 'SimpleXMLElement', LIBXML_NOCDATA)));

            if (empty($postObj->prepay_id) || $postObj->return_code == "FAIL") {

                // 支付发起错误 记录到logs
                Log::write('生成签名失败:' . $postObj->return_msg . ' ' . $xml);
                Log::write('请求参数:' . $xml);
                Log::write('返回结果:' . $ret);

                return json(['package' => '']);

            } else {

                $packJs = array(
                    'appId' => config("config.APPID"),
                    'timeStamp' => $timeStamp,
                    'nonceStr' => $nonceStr,
                    //预支付交易会话标识
                    'package' => "prepay_id=" . $postObj->prepay_id,
                    'signType' => 'MD5'
                );

                $JsSign = Util::paySign($packJs);

                unset($packJs['timeStamp']);

                $packJs['timestamp'] = $timeStamp;

                $packJs['paySign'] = $JsSign;

                return json([$packJs]);

            }
        } else {
            return json([]);
        }
    }

    /**
     * Ajax生成订单
     * POST请求：
     * addrData：用户地址信息
     * cartData: 用户待购买商品信息
     * remark：订单备注
     * expfee：订单邮费
     * exptime：订单配送时间
     * 返回json：
     * 订单创建成功：code => 0, msg => 订单ID
     * 订单创建失败：code => -1, msg => 失败信息
     * 说明：
     * addrData为数组，按照下列格式存放用户的地址信息：
     * array(6) {
        ["userName"] => 姓名
        ["telNumber"] => 电话
        ["postalCode"] => 邮政编码
        ["Address"] => 详细地址
        ["Province"] => 省
        ["City"] => 市
        }
     * cartData为数组，按照下列格式存放用户待购买商品信息：
     * array(4) {
        ["cart_id"] => 可选，若填写该id则从购物车中移除该id的商品条目
        ["pid"] => 商品ID
        ["spid"] => 商品规格ID
        ["count"] => 商品数量
    }
     */
    public function ajaxCreateOrder() {
        //从POST中获取以下信息：
        //TODO 用户地址数据
        $addrData = input("post.addrData/a");
        //订单备注
        $remark = input("post.remark");
        //邮费
        $expfee = input("post.expfee");
        //TODO 用户红包id
        //$envsid = input("post.envsid");
        //订单配送时间
        $exptime = input("post.exptime");
        //TODO 是否使用余额支付
        if (input("?post.balancePay")){
            $balancePay = true;
        }else{
            $balancePay = false;
        }
        //用户购物车信息
        $cartData = input("post.cartData/a");

        //TODO test用例
        /*
        $addrData = [
            "userName" => "叶嘉永",
            "telNumber" => 18819258367,
            "postalCode" => 510000,
            "Address" => "天河区",
            "Province" => "广东省",
            "City" => "广州市"
        ];
        $remark = "remark";
        $expfee = 100.00;
        $exptime = date("Y-m-d");
        $openid = "olwYzwM5h_hcnOj9w8uWQK2DxGnM";
        */

        //加载模型
        $order_model = new \app\weshop\model\Order();
        $user_model = new User();
        $cart_model = new Cart();

        //从数据库中获取以下信息：
        //用户openid
        $openid = $user_model->getOpenId();

        //用户购物车信息
        //$cartData = $cart_model->getCartDataSimple($openid);



        if (!$cartData || sizeof($cartData) == 0) {
            return json(['code' => -1, 'msg' => "订单数据非法"]);
        }
        if (empty($addrData)) {
            return json(['code' => -1, 'msg' => "地址数据非法"]);
        }

        //解包POST购物车信息，从中获取购物车id并从购物车中删除该条目
        foreach ($cartData as &$cartItem){
            //查找是否有cart_id变量
            if (array_key_exists("cart_id", $cartItem)){
                //从购物车中删除该id值的数据
                Db::name("client_cart")
                    ->where("id", $cartItem["cart_id"])
                    ->delete();
                unset($cartItem["cart_id"]);
            }
        }


        try {
            $orderId = $order_model->createOrder($openid, $cartData, $addrData, [
                //订单评论
                'remark' => addslashes($remark),
                //配送时间
                'exptime' => addslashes($exptime),
                //是否使用余额支付
                'balancePay' => addslashes($balancePay),
                //邮费
                'expfee' => floatval($expfee),
                //TODO 红包ID
                //'envsid' => intval($this->post('envsId')),
            ]);
            //返回订单ID
            return json(['code' => 0, 'msg' => intval($orderId)]);
        } catch (\Exception $ex) {
            Log::write("订单创建错误：" . $ex->getMessage() . " 在ajaxCreateOrder()中");
            return json(['code' => -1, 'msg' => $ex->getMessage()]);
        }
    }

    /**
     * ajax订单取消
     * POST请求：
     * orderId: 要取消的订单号
     * 返回json：
     * 若取消成功则返回code => 0，失败返回code => -1
     */
    public function ajaxCancelOrder() {
        //要取消的订单ID
        $orderId = $_POST['orderId'];
        if (is_numeric($orderId)) {
            $orderId = intval($orderId);
            $status = Db::name("orders")
                ->where("order_id", $orderId)
                ->update(['status' => 'canceled']);
            if ($status > 0){
                return json(['code' => 0]);
            }else{
                return json(['code' => -1]);
            }
        } else {
            return json(['code' => -1]);
        }
    }

    /**
     * ajax获取用户地址信息
     * 使用GET方法
     * @return \think\response\Json 返回json信息
     * 返回值详细如下：
     * 成功获取时：
    {
        "code": 0,
        "msg": [
        包含用户地址的数组，每条目详细如下：
        { "id": 该条目的数据库唯一id, "uid": 用户uid, "name": 姓名, "phone": 电话, "province": 省, "city": 市,  "dist": 区, "addrs": 详细地址, "postcode": 邮政编码 }
        ]
    }
    获取失败时： { "code": -1,  "msg": 错误信息 }
     */
    public function ajaxGetAddress(){
        //载入模型
        $user_model = new User();

        //获取用户openid
        $openid = $user_model->getOpenId();
        //获取用户uid
        $uid = $user_model->getUidByOpenId($openid);
        if (!isset($uid) || $uid == ""){
            return json(['code' => -1, 'msg' => 'UID获取失败']);
        }
        //获取用户地址信息
        $data = Db::name("client_address")
            ->where("uid", $uid)
            ->select();

        return json(['code' => 0, 'msg' => $data]);
    }

    /**
     * ajax删除地址信息
     * 使用POST传入以下信息：
     * id：要删除的地址id
     * @return \think\response\Json 返回json信息
     * 返回值详细如下：
     * 成功删除时：'code' => 0, 'msg' => 'success'
     * 删除失败时：'code' => -1, 'msg' => 错误信息
     */
    public function ajaxDelAddress(){
        //载入模型
        $user_model = new User();
        //获取用户openid
        $openid = $user_model->getOpenId();
        //获取用户uid
        $uid = $user_model->getUidByOpenId($openid);
        if (!isset($uid) || $uid == ""){
            return json(['code' => -1, 'msg' => 'UID获取失败']);
        }

        //获取要删除的地址id
        if (input("?post.id")){
            $address_id = input("post.id");
        }else{
            return json(['code' => -1, 'msg' => '地址id获取失败']);
        }
        if ($address_id == null || $address_id == ''){
            return json(['code' => -1, 'msg' => '地址id获取失败']);
        }

        //删除地址
        try{
            Db::name("client_address")
                ->where("id", $address_id)
                ->where("uid", $uid)
                ->delete();
            return json(['code' => 0, 'msg' => 'success']);
        }catch (\Exception $ex) {
            Log::write("地址创建错误：" . $ex->getMessage() . " ajaxDelAddress()中");
            return json(['code' => -1, 'msg' => $ex->getMessage()]);
        }


    }

    /**
     * ajax新建用户地址
     * 使用post传入以下信息：
     * name: 姓名（必填）
     * phone: 电话（必填）
     * province: 省
     * city: 市
     * dist: 区
     * addrs: 详细地址
     * postcode: 邮政编码
     * @return \think\response\Json 若成功则返回code => 0, msg => success; 失败返回code => -1, msg => failed
     */
    public function ajaxSetAddress(){
        //载入模型
        $user_model = new User();

        //获取用户openid
        $openid = $user_model->getOpenId();
        //获取用户uid
        $uid = $user_model->getUidByOpenId($openid);
        if (!isset($uid)){
            return json(['code' => -1, 'msg' => 'UID获取失败']);
        }

        //从post中获取用户设置的地址
        if (input("?post.name")){
            $name = input("post.name");
        }
        else{
            return json(['code' => -1, 'msg' => '地址信息错误']);
        }
        if (input("?post.phone")){
            $phone = input("post.phone");
        }
        else{
            return json(['code' => -1, 'msg' => '电话信息错误']);
        }
        $province = input("post.province");
        $city = input("post.city");
        $dist = input("post.dist");
        $addrs = input("post.addrs");
        $postcode = input("post.postcode");

        //存放到数据库
        $data = [
            'uid' => $uid,
            'name' => $name,
            'phone' => $phone,
            'province' => $province,
            'city' => $city,
            'dist' => $dist,
            'addrs' => $addrs,
            'postcode' => $postcode
        ];
        try{
            Db::name("client_address")
                    ->insert($data);
            return json(['code' => 0, 'msg' => 'success']);
        }catch (\Exception $ex) {
            Log::write("地址创建错误：" . $ex->getMessage() . " ajaxSetAddress()中");
            return json(['code' => -1, 'msg' => $ex->getMessage()]);
        }
    }

    /**
     * 获取店铺设置
     */
    public function ajaxGetSettings() {
        $jsonA = array();
        // 获取快递首重，续重参数
        //$datas = $this->Dao->select()
        //    ->from('wshop_settings')
        //    ->where("`key` IN ('exp_weight1', 'exp_weight2', 'dispatch_day_zone', 'dispatch_day')")
        //    ->exec();
        $datas = Db::name("settings")
            ->where("`key` IN ('exp_weight1', 'exp_weight2', 'dispatch_day_zone', 'dispatch_day')")
            ->select();
        foreach ($datas as $da) {
            $jsonA[$da['key']] = $da['value'];
        }
        return json($jsonA);
    }

    /**
     * 获取运费模板
     */
    public function ajaxGetExpTemplate() {
        $arr = Db::name("settings_expfee")
            ->select();
        return json($arr);
    }

    /**
     * 初始化函数
     * 新建本控制器实例时调用该方法
     * 加载全局模版变量
     */
    public function _initialize()
    {
        parent::_initialize();
        //获取店铺全局设置
        //从数据库获取店铺配置信息
        $query = Db::name('settings')
            ->select();
        //处理query
        $this->settings = array();
        foreach ($query as $item){
            $this->settings[$item['key']] = $item['value'];
        }
        //加载模版变量
        //全局商铺设置
        $this->assign('settings', $this->settings);
        //商铺根目录
        $this->assign('docroot', config('config.docroot'));
        $request = Request::instance();
        $this->assign('controller', $request->controller());   //控制器名称
    }
}
