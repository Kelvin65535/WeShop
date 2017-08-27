<?php

namespace app\wechat\controller;

use think\Controller;
use think\Request;

/**
 * Class Messagehandler 微信消息回复器
 * @package app\wechat\controller
 */
class Messagehandler extends Controller
{

    /**
     * 接收方账号（微信发送的openID）
     * @var
     */
    public $openID;

    /**
     * 开发者微信号
     * @var
     */
    public $serverID;

    /**
     * 消息创建时间
     * @var
     */
    public $time;

    /**
     * @var WXBizMsgCrypt
     */
    public $aesHelper;

    /**
     * 是否使用aes加密
     * @var bool
     */
    public $aesOn = false;

    /**
     * 随机串
     */
    private $nonce = '';

    /**
     * 初始化消息处理对象
     * @param $postObj 经过XML解包后的微信post对象
     */
    public final function init(&$postObj) {

        $this->time     = time();
        $this->openID   = $postObj->FromUserName;
        $this->serverID = $postObj->ToUserName;
        $this->nonce    = isset($_GET['nonce']) ? $_GET['nonce'] : uniqid();

        try {
            $this->handle($postObj);//
        } catch (Exception $ex) {
            // 内错误捕捉
            \think\Log::write($ex->getMessage());
        }

        // 多客服接口转发 @see http://dkf.qq.com/
        //if ($postObj->MsgType == 'text') {
            //echo "<xml><ToUserName><![CDATA[$this->openID]]></ToUserName><FromUserName><![CDATA[$this->serverID]]></FromUserName><CreateTime>$this->time</CreateTime><MsgType><![CDATA[transfer_customer_service]]></MsgType></xml>";
        //}

    }
    /**
     * 消息处理方法
     * @param WechatPostObject $postObj
     */
    protected function handle(&$postObj) {
        // 被子类覆盖
    }

    /**
     * 向用户发送文本消息
     * @param string $contentStr
     */
    public final function responseText($contentStr) {
        //if (empty($contentStr)) {
         //   throw new Exception("回复普通文本失败, 内容不能为空!");
       // }
        $data = [
            'ToUserName' => $this->openID,
            'FromUserName' => $this->serverID,
            'CreateTime' => $this->time,
            'MsgType' => 'text',
            'Content' => $contentStr,
            'FuncFlag' => 0
        ];
        die($this->toXML($data));
    }

    /**
     * 数组转换XML
     * @param array $arr 要转换的数组
     * @return string 生成的XML字符串
     */
    public function toXML($arr) {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }
}
