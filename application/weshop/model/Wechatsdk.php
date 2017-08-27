<?php

namespace app\weshop\model;

use think\Model;

/**
 * 微信SDK
 * Class Wechatsdk
 * @package app\weshop\model
 */
class Wechatsdk extends Model
{
    /**
     * 获取服务号access token
     * @return string
     */
    public static function getServiceAccessToken() {
        //从缓存中查找ACCESS_TOKEN
        $cache_access_token = cache('access_token');
        //缓存中没有access_token
        if (!$cache_access_token){
            $cache_access_token = self::getServiceAccessToken();
            cache('access_token', $cache_access_token, 3600);
        }
        return $cache_access_token;
    }

    /**
     * 获取accesstoken
     * @return null
     */
    public static function _getServiceAccessToken() {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . config('config.APPID') . "&secret=" . config('config.APPSECRET');
        $res = json_decode(Curl::get($url));
        if ($res && isset($res->access_token)) {
            return $res->access_token;
        } else {
            return null;
        }
    }
}
