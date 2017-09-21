<?php

namespace app\wechat\model;

use think\Model;
use think\Cache;
use curl;

/**
 * 微信SDK
 * Class Wechatsdk
 * @package app\wechat\model
 */
class Wechatsdk extends Model
{
    /**
     * 获取服务号access token
     * 若缓存保存有token则直接返回，否则向服务器请求token
     * @see http://mp.weixin.qq.com/wiki/11/0e4b294685f817b95cbed85ba5e82b8f.html
     * @return string
     */
    public static function getServiceAccessToken() {
        //从缓存中查找ACCESS_TOKEN
        $cache_access_token = cache('access_token');
        //缓存中没有access_token
        if (!$cache_access_token){
            $cache_access_token = self::_getServiceAccessToken();
            cache('access_token', $cache_access_token, 3600);
        }
        return $cache_access_token;
    }

    /**
     * 向服务器获取accesstoken
     * @return null
     */
    private static function _getServiceAccessToken() {
        //构造URL
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . config('config.APPID') . "&secret=" . config('config.APPSECRET');
        //发送get请求
        $res = json_decode(curl\Curl::get($url));
        if ($res && isset($res->access_token)) {
            return $res->access_token;
        } else {
            return null;
        }
    }

    /**
     * 获取用户授权凭证code
     * @param $redirect_uri
     * @param type $scope 为snsapi_base或snsapi_userinfo
     * 以snsapi_base为scope发起的网页授权，是用来获取进入页面的用户的openid的
     * 以snsapi_userinfo为scope发起的网页授权，是用来获取用户的基本信息的。但这种授权需要用户手动同意
     * @return bool
     * @see http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html
     */
    public static function getAccessCode($redirect_uri, $scope) {
        //构造向服务器询问请求的URL
        $request_access_token_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . config('config.APPID') . "&redirect_uri=[REDIRECT_URI]&response_type=code&scope=[SCOPE]&state=STATE#wechat_redirect";
        if (empty($_GET['code'])) {
            // 未授权而且是拒绝
            if (!empty($_GET['state'])) {
                return FALSE;
            } else {
                // 未授权
                $redirect_uri = urlencode($redirect_uri);
                $RequestUrl   = str_replace("[REDIRECT_URI]", $redirect_uri, $request_access_token_url);
                $RequestUrl   = str_replace("[SCOPE]", $scope, $RequestUrl);
                // 获取授权
                header("location:" . $RequestUrl);
                exit(0);
            }
        } else {
            // 授权成功 返回 access_token 票据
            return $_GET['code'];
        }
    }

    /**
     * 获取用户授权access token，使用access_code凭证
     * @param string $code access_code
     * @return \stdClass return->access_token：获取的网页授权token， return->openid：获取的openid
     */
    public static function getAccessToken($code) {
        // @return object{access_token,openid}
        //    {
        //       "access_token":"ACCESS_TOKEN",
        //       "expires_in":7200,
        //       "refresh_token":"REFRESH_TOKEN",
        //       "openid":"OPENID",
        //       "scope":"SCOPE"
        //    }
        $RequestUrl            = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . config('config.APPID') . "&secret=" . config('config.APPSECRET') . "&code=" . $code . "&grant_type=authorization_code";
        $response = curl\Curl::get($RequestUrl);    //微信授权后返回的json字符串
        $Result                = json_decode($response, true);
        $_return               = new \stdClass();
        $_return->access_token = $Result['access_token'];
        $_return->openid       = $Result['openid'];
        return $_return;
    }

    /**
     * 获取微信用户信息
     * @param string $access_token
     * @param string $openid
     * @param boolean $union
     * @return \stdClass 存放userinfo的stdclass对象
     * @see http://mp.weixin.qq.com/wiki/14/bb5031008f1494a59c6f71fa0f319c66.html
     */
    public static function getUserInfo($access_token, $openid, $union = false) {
        // 获取用户信息 scope 必须为 snsapi_userinfo
        //{
        //   "openid":" OPENID",
        //   " nickname": NICKNAME,
        //   "sex":"1",
        //   "province":"PROVINCE"
        //   "city":"CITY",
        //   "country":"COUNTRY",
        //    "headimgurl":    "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/46",
        //   "privilege":[
        //    "PRIVILEGE1"
        //    "PRIVILEGE2"
        //    ]
        //}
        if ($openid != '') {
            // unionid 获取用户信息
            $url = $union ? "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN" : "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
            // 缓存文件判断
            $cache_name = "userinfo_" . $openid;
            if (Cache::get($cache_name)){
                //从缓存中取出userinfo数组
                $userInfo = Cache::get($cache_name);
            } else {
                // 缓存已过期或者不存在
                // 向微信服务器发出get请求个人信息
                $response = curl\Curl::get($url);
                $userInfo = json_decode($response);
                if ($userInfo->nickname) {
                    //存入缓存
                    Cache::set($cache_name, $userInfo, 3600);
                }
            }
            //将数组转换成stdclass
            $info_class = new \stdClass();
            foreach ($userInfo as $key => $value){
                $info_class->$key = $value;
            }
            return $info_class;
        } else {
            return false;
        }
    }

    /**
     * 获取自定义菜单
     * @param type $access_token
     * @return type
     */
    public static function getMenu() {
        $access_token = self::getServiceAccessToken();
        $ret          = curl\Curl::get("https://api.weixin.qq.com/cgi-bin/menu/get?access_token=$access_token");
        if (!empty($ret)) {
            $res = json_decode($ret, true);
            return $res['menu'];
        }
        return null;
    }

    /**
     * 更新自定义菜单
     * @param type $access_token
     * @param type $jsonStr
     * @return type
     */
    public static function setMenu($jsonStr) {
        $access_token = self::getServiceAccessToken();
        $jsonStr      = str_replace('\"', '"', $jsonStr);
        return json_decode(curl\Curl::post("https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access_token", $jsonStr), true);
    }

    /**
     * 转码unicode
     * @param string $str
     * @return type
     */
    public static function decodeUnicode($str) {
        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', create_function('$matches', 'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'), $str);
    }

    /**
     * 上传图片到微信服务器
     * @param string $imagePath
     * @return mixed
     */
    public static function upLoadImage($imagePath) {
        $stoken   = self::getServiceAccessToken();
        //$PostData = array("media" => "@" . $imagePath); 修正PHP5.6问题
        $PostData = array("media" => new \CURLFile($imagePath));
        $Result   = curl\Curl::post("https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=$stoken", $PostData, true);
        $Result   = json_decode($Result, true);
        return $Result;
    }

    /**
     * 上传多媒体内容
     * @param string $imagePath
     * @param type $type
     * @return mixed
     */
    public static function upLoadMedia($imagePath, $type = 'image') {
        $stoken   = self::getServiceAccessToken();
        //$PostData = array("media" => "@" . $imagePath); 修正PHP5.6问题
        $PostData = array("media" => new \CURLFile($imagePath));
        $Result   = curl\Curl::post("http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$stoken&type=$type", $PostData, true);
        $Result   = json_decode($Result, true);
        return $Result;
    }

    /**
     * 上传永久素材
     * @param int $thumb_media_id 图片ID
     * @param string $title 标题
     * @param string $content 内容
     * @param string $digest 摘要
     * @param int $show_cover_pic 显示图片在正文
     * @return type
     */
    public static function upLoadGmess($thumb_media_id, $title, $content, $digest, $show_cover_pic = 1) {
        $stoken   = self::getServiceAccessToken();
        $PostData = array(
            'articles' => array(
                array(
                    'thumb_media_id' => $thumb_media_id,
                    'title' => $title,
                    'content' => $content,
                    'digest' => $digest,
                    "show_cover_pic" => $show_cover_pic
                )
            )
        );
        $Result   = curl\Curl::post("https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token=$stoken", str_replace('\/', '/', self::decodeUnicode(json_encode($PostData))));
        return json_decode($Result, true);
    }

    /**
     * 发送群发消息，高级接口
     * @param type $mediaId
     * @param type $istoAll
     * @param type $groupId
     * @return type
     */
    public static function sendGmessAll($mediaId, $istoAll = false) {
        $stoken   = self::getServiceAccessToken();
        $PostData = array(
            'filter' => array(
                "is_to_all" => $istoAll
            ),
            'mpnews' => array("media_id" => $mediaId),
            "msgtype" => "mpnews"
        );
        return curl\Curl::post("https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=$stoken", json_encode($PostData));
    }
}
