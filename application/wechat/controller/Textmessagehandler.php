<?php

namespace app\wechat\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\sae\Log;

/**
 * 文本消息处理器
 * Class Textmessagehandler
 * @package app\wechat\controller
 */
class Textmessagehandler extends Messagehandler
{
    /**
     * 文本消息处理逻辑
     * @param WechatPostObject $postObj 微信服务端发送的数据
     */
    public function handle(&$postObj)
    {
        $postObj->Content = trim($postObj->Content);
        \think\Log::write($postObj->Content . "in handle()");
        $this->autoResponse($postObj->Content);

        //$this->responseText("思聪快来撸代码");

    }

    /**
     * 系统定义自动回复
     * @param $Content
     * @throws Exception
     */
    public function autoResponse($Content){

        // 系统定义自动回复
        $rep = $this->getAutoRspData((string)$Content); //回复的字符串

        //没有设定关键词的默认回复，默认关键词为 default
        if (!$rep) {
            $Content = 'default';
            $rep = $this->getAutoRspData((string)$Content);
        }

        if ($rep) {
            // 自动回复已匹配
        //    $this->Db->query("INSERT INTO `client_messages` (`openid`,`msgcont`,`autoreped`,`send_time`) VALUES ('$this->openID','$Content',1,NOW());");
        //    if ($rep['rel'] != 0 && $rep['reltype'] == 1) {
        //        $this->echoGmess($rep['rel']);
       //     } else {
                $this->responseText($rep);
         //   }
        //} else {
        //    @$this->Db->query("INSERT INTO `client_messages` (`openid`,`msgcont`,`autoreped`,`send_time`) VALUES ('$this->openID','$Content',0,NOW());");
        }
        //@$this->Db->query("REPLACE INTO `client_message_session` (`openid`,`undesc`,`unread`,`lasttime`) VALUES ('$this->openID','$Content',(SELECT COUNT(*) FROM `client_messages` WHERE `openid` = '$this->openID' AND `msgtype` = 0 AND `sreaded` = 0),NOW());");

    }

    /**
     * 从数据库获取自动回复信息
     * @param $Content
     */
    public function getAutoRspData($Content){
        return Db::name('wechat_autoresponse')
            ->where('key', $Content)
            ->value('message');
    }

}
