<?php
/**
 * Created by PhpStorm.
 * User: Kelvin
 * Date: 2/20/2017
 * Time: 8:15 PM
 */

namespace app\weshop\model;

use think\Db;
use think\Model;
use think\Session;

/**
 * Class Order 订单处理模型
 * @package app\weshop\model
 */
class Order extends Model
{
    // 商品售价数组
    private $productSalePrices;
    // 订单商品数量
    private $product_count = 0;
    // 发货通知接口
    private $deliver_notify_url = "https://api.weixin.qq.com/pay/delivernotify?access_token=";
    //用户openid
    private $openid = false;
    //用户uid
    private $uid;

    /**
     * 创建订单
     * @param string $openid 用户OPENID
     * @param mixed $orderlist 订单列表
     * @param array $address 地址信息
     * @param array $params 其他参数，详见以下说明
     * @return int 若创建订单成功，则返回order_id
     * 说明：
     * orderList为订单列表，数组形式存放用户订单的商品信息，格式如下：
     * array(3) {
        ["pid"] => 商品ID
        ["spid"] => 商品规格ID
        ["count"] => 数量
        }
     * 地址信息为数组，存放用户订单的地址信息，格式如下：
     * array(6) {
        ["userName"] => 姓名
        ["telNumber"] => 电话
        ["postalCode"] => 邮政编码
        ["Address"] => 详细地址
        ["Province"] => 省
        ["City"] => 市
        }
     * params为附加的参数，格式如下：
     * array(6) {
        ["expfee"] => 运费，若不设这个参数则为0.00，即包邮
        ["remark"] => 备注，若不设这个参数则默认为空
        ["status"] => 订单状态，可选为：'unpay'（未支付）,'payed'（已支付）,'received'（已接收）,'canceled'（已取消）,'closed'（已关闭）,'refunded'（已退款）,'delivering'（投递中）,'reqing'（请求中）
        ["exptime"] => 订单配送时间
        ["wepayed"] => 订单是否已支付，默认为0 TODO 等待补充该参数含义
        ["wepay_serial"] => 微信支付序列号 TODO 等待补充该参数含义
        }
     */
    public function createOrder($openid, $orderlist, $address, $params = []){
        if (empty($openid)) {
            exception("错误：openid不能为空");
        }
        if (!is_array($orderlist)) {
            exception("错误：订单列表不能为空");
        }
        if (!is_array($address)) {
            exception("错误：地址不能为空");
        }

        //载入model
        $user_model = new User();

        //用户openid
        $this->openid = $openid;
        //用户uid
        $this->uid = $user_model->getUidByOpenId($this->openid);

        // 运费
        if (!isset($params['expfee'])) {
            $params['expfee'] = 0.00;
        } else {
            $params['expfee'] = $params['expfee'] > 0 ? floatval($params['expfee']) : 0.00;
        }

        //订单原价总计计算
        //$originalAmount 订单原价
        $originalAmount = $this->sumOrderOriginalAmount($orderlist);

        //计算订单总价
        //$orderAmount 订单总价
        $orderAmount = $this->sumOrderAmount($orderlist) + $params['expfee'];

        // 订单备注
        //$remark 订单备注
        if (isset($params['remark'])) {
            $remark = trim($params['remark']);
        } else {
            $remark = '';
        }

        // 如果使用余额支付
        //$orderBalance 用于抵扣订单总价的金额数
        //TODO 目前将余额抵扣的功能删除，有需要再加上
        /*
        if (isset($params['balancePay']) && $params['balancePay']) {
            $uinfo = $this->User->getUserInfo();
            // 计算将扣减的余额
            if ($uinfo->balance >= $orderAmount) {
                $orderBalance = $orderAmount;
            } else {
                // 使用全部余额进行抵扣
                $orderBalance = floatval($uinfo->balance);
            }
            // 减余额
            $this->User->mantUserBalance($orderBalance, $uinfo->uid, $type = User::MANT_BALANCE_DIS);
        } else {
            $orderBalance = 0.00;
        }
        */
        $orderBalance = 0.00;

        // $orderStatus 订单状态
        if (isset($params['status'])) {
            $orderStatus = $params['status'];
        } else {
            // 如果订单金额为0，直接已付款
            if (($orderBalance > 0 && $orderBalance - $orderAmount <= 0) || $orderAmount == 0) {
                // 余额抵扣等于订单金额
                $orderStatus       = 'payed';
                $params['wepayed'] = 0;
            }
        }
        if (empty($orderStatus)) {
            $orderStatus = 'unpay';
        }

        // 生成序列号
        $serial_number = date("Ymdhis") . mt_rand(10, 99);

        // 订单创建时间
        $time = date("Y-m-d H:i:s");

        // 写入订单数据
        $insertParams = [
            //订单状态
            'status' => $orderStatus,
            //代理ID
            //TODO 删除代理功能
            //'company_id' => $companyCom,
            //用户UID
            'client_id' => $this->uid,
            //商品数量
            'product_count' => $this->product_count,
            //用于抵扣总价的金额
            'order_balance' => $orderBalance,
            //订单总价
            'order_amount' => $orderAmount,
            //订单原价
            'original_amount' => $originalAmount,
            //订单运费
            'order_expfee' => $params['expfee'],
            //订单创建时间
            'order_time' => $time,
            //TODO 微信支付序列号
            //'wepay_serial' => $params['wepay_serial'],
            //TODO 红包ID
            //'envs_id' => intval($params['envsid']),
            //微信openid
            'wepay_openid' => $openid,
            //订单评论
            'leword' => $remark,
            //订单序列号
            'serial_number' => $serial_number,
            //订单配送时间
            'exptime' => $params['exptime'],
            //支付状态
            //'wepayed' => $params['wepayed']
        ];



        //启动事务，插入订单信息到数据库
        $order_id = Db::transaction(function() use (&$insertParams, &$address, &$orderlist){
            //记录订单基本信息，生成唯一的订单id
            $id = Db::name("orders")
                ->insertGetId($insertParams);
            $order_model = new Order();
            //记录订单地址信息，返回订单地址哈希值
            $addrHash = $order_model->writeAddressData($id, $address);
            //将订单哈希值记录到订单信息中
            Db::name("orders")
                ->where("order_id", $id)
                ->update(['address_hash' => $addrHash]);
            //记录订单详情
            $orderDetails = array();
            foreach ($orderlist as &$order){
                $detail = [
                    'order_id' => $id,
                    'product_id' => $order['pid'],
                    'product_count' => $order['count'],
                    'product_discount_price' => $this->productSalePrices[$order['pid']],
                    'product_spec_id' => $order['spid']
                ];
                array_push($orderDetails, $detail);
            }
            //将订单详情插入到数据库中
            Db::name("orders_detail")
                ->insertAll($orderDetails);

            return $id;
        });

        return $order_id;

    }

    /**
     * 写入订单地址
     * @todo 自动归集
     * @param int $orderid 订单ID号
     * @param array $addrData 订单地址信息
     * @return string $hash 将addrData数组用md4加密后生成的hash值，用于表示唯一地址
     * 订单地址信息为数组，包括以下内容：
     * userName => 姓名
     * telNumber => 电话号码
     * postalCode => 邮政编码
     * Address => 详细地址
     * Province => 省
     * City => 城市
     * 在存储地址数组的时候会将上述信息用md4生成hash值
     */
    public function writeAddressData($orderid, $addrData) {
        //获取用户uid
        $client_id = Session::get('uid');
        //TODO test用例
        $client_id = 2;
        //根据传入的地址信息json序列化后用md4生成唯一的哈希值
        $hash      = hash('md4', json_encode($addrData));
        $insert_data = [
            //订单ID
            'order_id' => $orderid,
            //用户UID
            'client_id' => $client_id,
            //用户姓名
            'user_name' => $addrData['userName'],
            //用户电话
            'tel_number' => $addrData['telNumber'],
            //邮编
            'postal_code' => $addrData['postalCode'],
            //详细地址
            'address' => $addrData['Address'],
            //省
            'province' =>$addrData['Province'],
            //市
            'city' => $addrData['City'],
            //哈希值
            'hash' => $hash
        ];
        $status = Db::name("orders_address")
            ->insert($insert_data);
        return $status ? $hash : false;
    }

    /**
     * 计算订单原价总额
     * @param array $orderList
     * @return int OriginalAmount
     * orderList数组说明：
     * array(1) {
     * 每条购物车项目为数组中的一个项
        [0] => array(3) {
        ["pid"] => string(1) "1"    商品ID
        ["spid"] => string(1) "1"   商品规格ID
        ["count"] => string(1) "1"  数量
        }
     */
    private function sumOrderOriginalAmount($orderList) {
        $OriginalAmount = 0;
        foreach ($orderList as $ord) {
            $pid   = $ord['pid'];
            $pinfo = Db::name("products_info")
                ->where("product_id", $pid)
                ->find();
            $OriginalAmount += ($pinfo['supply_price'] * $ord['count']);
        }
        return $OriginalAmount;
    }

    /**
     * 计算订单总金额
     * @param array $orderList 订单列表
     * @return int orderAmount 订单总金额
     * orderList数组说明：
     * array(1) {
     * 每条购物车项目为数组中的一个项
        [0] => array(3) {
        ["pid"] => string(1) "1"    商品ID
        ["spid"] => string(1) "1"   商品规格ID
        ["count"] => string(1) "1"  数量
        }
     */
    private function sumOrderAmount($orderList) {
        $return = 0;

        //加载模型
        $user_model = new User();

        // 订单商品总数量
        $this->product_count = 0;
        //解包orderList里面的商品信息，统计商品总金额
        foreach ($orderList as $ord) {
            //产品ID
            $pid      = $ord['pid'];
            //获取用户折扣
            $discount = $user_model->getDiscount($this->uid);
            //获取商品信息
            $pinfo = Db::name("products_info")
                ->where("product_id", $pid)
                ->find();
            if ($pinfo['product_prom'] == 1 && time() < strtotime($pinfo['product_prom_limitdate'])) {
                $discount = $pinfo['product_prom_discount'] / 100;
            }
            if ($ord['spid'] > 0) {
                //商品售价
                //如果商品包含规格id的话则到product_spec表中寻找对应规格id的售价
                $salePrice = Db::name("product_spec")
                    ->where("product_id", $pid)
                    ->where("id", $ord["spid"])
                    ->value("sale_price");
            } else {
                //若规格id为0，则该商品不包含规格，使用默认规格的售价
                $salePrice = Db::name("product_onsale")
                    ->where("product_id", $pid)
                    ->value("sale_prices");
            }
            if ($salePrice > 1) {
                $this->productSalePrices[$pid] = $salePrice * $discount;
            } else {
                $this->productSalePrices[$pid] = $salePrice;
            }
            $this->product_count += $ord['count'];
            $return += $this->productSalePrices[$pid] * $ord['count'];
        }
        return $return;
    }

    /**
     * 根据订单id获取订单的详细信息
     * @param int $id
     * @return array|bool|false|\PDOStatement|string|Model
     */
    public function getOrderDetail($id){
        //加载模型
        $product_model = new Products();
        if ($id > 0) {

            //订单基本信息
            $orderData = Db::name("orders")
                ->where("order_id", $id)
                ->find();
            //订单地址信息
            $orderData['address'] = Db::name("orders_address")
                ->where("order_id", $id)
                ->find();
            //订单详细信息
            $orderData['products'] = array();
            $orderDetails = Db::name("orders_detail")
                ->where("order_id", $id)
                ->select();
            foreach($orderDetails as &$detail){
                $product_info = $product_model->getProductInfoWithSpec($detail['product_id'], $detail['product_spec_id']);
                array_push($orderData['products'], $product_info);
            }
            return $orderData;
        } else {
            return false;
        }
    }
}