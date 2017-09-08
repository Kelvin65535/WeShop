<?php
/**
 * Created by PhpStorm.
 * User: kelvin
 * Date: 2017/9/7
 * Time: 下午11:34
 */

namespace app\wechat\model;

use curl\Curl;
use think\Model;
use think\Log;

/**
 * Class Jssdk 微信公众平台JSSDK模型
 * @package app\wechat\model
 */
class Messager extends Model
{
    /**
     * 发送微信模板消息
     * @param int $template_id 模版ID
     * @param string $openid 用户openid
     * @param array $data 发送数据
     * @param string $url 消息点击访问地址
     * @return array
     */
    public static function sendTemplateMessage($template_id, $openid, $data, $url = '') {
        $stoken = Wechatsdk::getServiceAccessToken();
        foreach ($data as &$d) {
            $d = array(
                'value' => $d,
                'color' => '#173177'
            );
        }
        $PostData = array(
            "touser" => "$openid",
            "template_id" => "$template_id",
            "url" => "$url",
            "topcolor" => "#FF0000",
            "data" => $data
        );
        $Result   = Curl::post("https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$stoken",
            str_replace('\/', '/', WechatSdk::decodeUnicode(json_encode($PostData))));
        $Result   = json_decode($Result, true);
        if ($Result['errmsg'] != 'ok') {
            Log::error("模板消息发送出错：" . json_encode($Result, JSON_UNESCAPED_UNICODE));
        }
        return $Result;
    }
}