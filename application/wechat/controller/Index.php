<?php

namespace app\wechat\controller;

use think\Controller;
use think\Request;
use think\Log;

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
     * @return WechatPostObject XML内含的对象
     */
    private function unpackXML($postStr) {
        if (empty($postStr)) {
            return false;
        }
        return simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
    }

}
