<?php

namespace app\wechat\controller;

use app\admin\model\Order;
use think\Controller;
use think\Request;
use think\Log;
use app\common\model\Util;

use wxAesHelper;
use test\test;
use app\wechat\controller\Textmessagehandler;

class Index extends Controller
{

    //微信消息处理器
    //text:文本
    //event:
    //voice:语音
    public $wechat_msg_handler = [
        'text' => ['Textmesssagehandler'] //若发送的消息为文本则调用Textmessagehandler控制器里的方法
        //'event' => ['EventHandler'],
        //'voice' => ['VoiceHandler']
    ];


    /**
     * 微信服务器接入入口函数
     * @var WechatPostObject $postObj
     */
    public function index() {

        //接入验证
        if (isset($_GET['echostr'])) {
            if (!$this->checkSignature()) {
                // 验证
                Log::write("微信消息签名错误");
            } else {
                echo $_GET['echostr'];
                Log::write("微信服务器认证通过:" . $_GET['echostr']);
            }
        }

        //获取微信发送消息
        $postStr = file_get_contents('php://input');    //微信服务端发送端XML信息
        Log::write("微信服务器发送postStr消息：" . $postStr);

        //对信息进行解包
        $postObj = $this->unpackXML($postStr);  //获得XML的对象

        //解析XML对象
        if ($postObj) {

            $msgData = $postObj;
            //处理数据
            Log::write("MsgType: " . $msgData->MsgType);
            Log::write("Content: ". $msgData->Content);
            $this->handleRequest($msgData, $this->wechat_msg_handler);
        }
    }

    /**
     * 处理微信请求数据
     * @param $msgData 微信服务端发送的数据
     * @param $wechat_msg_handler 请求处理器
     */
    private function handleRequest(&$msgData, $wechat_msg_handler) {
        if ($wechat_msg_handler && $msgData) {
            $type = strtolower($msgData->MsgType);//消息类型，转换成小写字符
            //消息类型包括 text:文本 image:图片 voice:语音 video:视频 link:链接
            if (array_key_exists($type, $wechat_msg_handler)) {
                if (is_array($wechat_msg_handler[$type])) {
                    foreach ($wechat_msg_handler[$type] as $cHandler) {
                        $this->dispatchHandler($cHandler, $msgData);
                    }
                } else if (is_string($wechat_msg_handler[$type])) {
                    $this->dispatchHandler($wechat_msg_handler[$type], $msgData);
                }
            }
        } else {
            Log::write("微信消息处理失败,参数无效" . json_encode($msgData));
        }
    }

    /**
     * 根据微信消息类型调用对应的处理方法
     * @param $className 处理方法，text类型->Textmesssagehandler对象
     * @param $msgData 微信服务端发送的数据
     */
    private function dispatchHandler($className, &$msgData) {
        //$object            = new $className();
        $object = new Textmessagehandler();
        $object->init($msgData);
    }

    /**
     * 验证签名
     * @return bool
     */
    private function checkSignature() {

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];

        $token  = config('config.TOKEN');
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        return $tmpStr == $signature;
    }

    /**
     * 对微信发送对XML字符串进行解包
     * @param $postStr 微信服务端发送的XML字符串
     * @return WechatPostObject|bool XML内含的对象
     */
    private function unpackXML($postStr) {
        if (empty($postStr)) {
            return false;
        }
        return simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
    }

    /**
     * 微信支付成功回调入口
     */
    public function paymentnotify() {
        //解决$GLOBAL限制导致无法获取xml数据
        $sourceStr = file_get_contents('php://input');
        // 读取数据
        $postObj = simplexml_load_string($sourceStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        // 数据参考 systemtest/payment_notify.xml
        Log::info($sourceStr);
        if (!$postObj) {
            Log::error("支付回调处理失败，数据包解析失败");
        } else {

            // 对数据包进行签名验证
            $postArr = (array)$postObj;
            $sign    = Util::paySign($postArr);

            if ($sign == $postObj->sign) {
                // order serial number
                $serial = $postObj->out_trade_no;
                $this->order_callback($postObj);
            }

        }
    }

    /**
     * 处理常规支付回调
     * @param $postObj
     */
    public function order_callback($postObj) {
        $serial = $postObj->out_trade_no;
        // 微信交易单号
        $transaction_id = $postObj->transaction_id;
        if (!empty($transaction_id)) {
            try {
                $order_model = new Order();
                // 获取订单信息
                $orderInfo = $order_model->getOrderInfoBySerialNumber($serial);
                $orderId   = intval($orderInfo['order_id']);
                if ($orderInfo && $orderInfo['status'] != 'payed' && empty($orderInfo['wepay_serial'])) {
                    // 更新订单信息
                    // 修改为已支付
                    if (
                        $order_model->updateOrder([
                            'wepay_serial' => $transaction_id,
                            'wepay_openid' => $postObj->openid,
                            'status' => 'payed',
                            'wepayed' => 1
                        ], [
                            'serial_number' => $serial
                        ])
                    ) {
                        // TODO 执行钩子程序
//                        (new HookNewOrder($this))->deal([
//                            'serial_number' => $serial,
//                            'openid' => strval($postObj->openid),
//                        ]);
                        // 商户订单通知
                        @$this->mOrder->comNewOrderNotify($orderId);
                        // 用户订单通知 模板消息
                        @$this->mOrder->userNewOrderNotify($orderId, $postObj->openid);
                        // 积分结算
                        @$this->mOrder->creditFinalEstimate($orderId);
                        // 减库存
                        @$this->mOrder->cutInstock($orderId);
                        // 返回success
                        echo "<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>";
                    } else {
                        Log::error("支付回调处理失败:" . $this->sourceStr);
                    }
                }
            } catch (\Exception $ex) {
                Log::error($ex->getMessage());
            }
        }
    }

}
