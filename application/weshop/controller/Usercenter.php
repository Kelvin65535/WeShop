<?php

namespace app\weshop\controller;

use app\common\model\Util;
use app\wechat\model\Jssdk;
use app\wechat\model\Wechatsdk;
use app\weshop\model\Products;
use think\Controller;
use think\Request;
use app\weshop\model\User;
use think\Session;
use think\Db;

/**
 * Class UserCenter
 * 个人中心控制器
 * @package app\weshop\controller
 */

class UserCenter extends Controller
{
    /**
     * @var array 订单状态
     */
    public $orderStatus = array(
        'unpay' => '未支付',
        'payed' => '已支付',
        'canceled' => '已取消',
        'received' => '已完成',
        'delivering' => '快递中',
        'closed' => '已关闭',
        'refunded' => '已退款',
        'reqing' => '代付'
    );

    /**
     * 商铺的整体设置
     * 定义如下：
     * ->
     */
    public $settings;

    /**
     * 个人中心首页
     *
     */
    public function home()
    {
        //个人中心页面

        $user = new User();
        //获取openid
        $openid = $user->getOpenId();
        //微信自动注册，若当前用户未注册则自动注册，否则跳过该步骤
        $user->wechatAutoReg($openid);
        //获取用户uid
        $uid = $user->getUid();

        //用户个人信息
        //userInfo数组的定义如下：
        /*
         * $userInfo = [
        'client_nickname'       微信用户名
        'client_name'           商城中的用户名
        'client_sex'            性别
        'client_head'           微信头像链接
        'client_wechat_openid'  微信openid
        'client_province'       用户所在省份
        'client_city'           用户所在城市
        'client_address'        用户地址（默认为省份+城市）
        'client_credit'         用户积分
        ]
         */
        $userInfo = false;

        if (!empty($openid)){
            //TODO 过期订单回收

            //获取UID
            if (!$uid){
                //UID cookie过期或者未注册
                $uid = $user->getUidByOpenId($openid);
                $userInfo = $user->getUserInfoRaw($uid);
            }
            else{
                //已注册
                $userInfo = $user->getUserInfoRaw($uid);
                // 刷新微信头像
                if (time() - strtotime($userInfo['client_head_lastmod']) > 432000 && $user->inWechat()) {
                    $this_uri = $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]; //当前URI
                    $AccessCode = WechatSdk::getAccessCode($this_uri, "snsapi_userinfo");
                    if ($AccessCode) {
                        // 获取到accesstoken和openid
                        $Result = WechatSdk::getAccessToken($AccessCode);
                        // 微信用户资料
                        $WechatUserInfo = WechatSdk::getUserInfo($Result->access_token, $Result->openid, false);
                        // 将新的头像链接存进数据库
                        $current_time = date("Y-m-d H:i:s");    //当前时间
                        Db::name('clients')
                            ->where('client_wechat_openid', $Result->openid)
                            ->update([
                                'client_head' => preg_replace("/\\/0/", "", $WechatUserInfo->headimgurl),
                                'client_head_lastmod' => $current_time,
                            ]);
                    }
                }
            }
        }

        //嵌入模版变量
        //用户信息
        $this->assign('userinfo', $userInfo);

        return $this->fetch();
    }

    /**
     * 查看"我的收藏"页面
     */
    public function productLikes(){
        $product_model = new Products();
        $user_model = new User();
        $openid = $user_model->getOpenId();
        $data = $product_model->getProductLike($openid, 1);
        dump($data);
        return $this->fetch();
    }

    /**
     * 查看"订单列表"页面
     */
    public function orderlist($status = '') {
//        if (input('?get.status')) {
//            $status = '';
//        } else {
//            $status = input('get.status');
//        }
        $user = new User();
        $JsSdk_model = new Jssdk();
        //获取openid
        $openid = $user->getOpenId();
        $signPackage = $JsSdk_model->GetSignPackage();
        $this->assign('signPackage', $signPackage);
        $this->assign('status', $status);
        $this->assign('title', '我的订单');
        return $this->fetch();
    }


    /**
     * Ajax获取订单列表
     * 分页显示
     * @param int $page 按照5条数据为1页，传入要显示的页数
     * @param mixed $status 要显示的订单状态，默认为空则选择所有状态的订单
     * status订单状态说明：
     * 输入以下字符串代表查询对应状态的订单
     *  'unpay' => '未支付',
        'payed' => '已支付',
        'canceled' => '已取消',
        'received' => '已完成',
        'delivering' => '快递中',
        'closed' => '已关闭',
        'refunded' => '已退款',
        'reqing' => '代付' （已废弃）
     */
    public function ajaxOrderlist($page = 0, $status = '') {
        //载入模型
        $user_model = new User();
        $product_model = new Products();
        //用户openid
        $openID = $user_model->getOpenId();

        if (empty($openID)) {
            die(0);
        } else {
            //!isset($Query->page) && $Query->page = 0;
            //$limit用于限制从数据库获取的条目数量
            $limit = (5 * $page) . ",5";

            // 按照status标示查找对应状态的订单
            if ($status == '' || !$status) {
                // status为空，列出所有订单
                $orders = Db::name("orders")
                    ->where("wepay_openid", $openID)
                    ->order("order_time desc")
                    ->page($limit)
                    ->select();
            } else {
                if ($status == 'canceled') {
                    // 已关闭订单列表
                    $orders = Db::name("orders")
                        ->where("wepay_openid", $openID)
                        ->where("status", $status)
                        ->where("wepay_serial", "<>", "")
                        ->order("order_time desc")
                        ->page($limit)
                        ->select();
                } else if ($status == 'received') {
                    // 已接受，待评价订单列表
                    $orders = Db::name("orders")
                        ->where("wepay_openid", $openID)
                        ->where("status", $status)
                        ->where("is_commented", 0)
                        ->order("order_time desc")
                        ->page($limit)
                        ->select();
                } else {
                    // 其他普通列表
                    $orders = Db::name("orders")
                        ->where("wepay_openid", $openID)
                        ->where("status", $status)
                        ->order("order_time desc")
                        ->page($limit)
                        ->select();
                }
            }

            // 解包从数据库获取的订单列表
            foreach ($orders as &$order){
                //订单状态
                $order['statusX'] = $this->orderStatus[$order['status']];
                //订单创建时间
                $order['order_time'] = Util::dateTimeFormat($order['order_time']);
                //订单详细数据
                $order['data'] = Db::name("orders_detail")->alias("detail")
                    ->join("weshop_products_info info", "info.product_id = detail.product_id", "LEFT")
                    ->where("detail.order_id", $order['order_id'])
                    ->field("catimg, info.product_name, info.product_id, detail.product_count, detail.product_discount_price, detail.product_spec_id")
                    ->select();
                //整理商品规格信息
                foreach ($order['data'] as &$data){
                    $spec_info = $product_model->getProductInfoWithSpec($data['product_id'], $data['product_spec_id']);
                    $data['spec1'] = $spec_info['spec_detail_name1'];
                    $data['spec2'] = $spec_info['spec_detail_name2'];
                }
            }

            $this->assign('orders', $orders);
        }
        return $this->fetch();
        //dump($orders);
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
