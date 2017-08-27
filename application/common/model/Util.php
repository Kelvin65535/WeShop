<?php

namespace app\common\model;

use think\Model;

/**
 * Class Util 公共函数库
 * @package app\common\model
 */

class Util extends Model
{
    /**
     * 格式化时间字符串
     * @param string $timestamp Y:m:d H:i:s格式的时间字符串
     * @return string 根据输入时间与当前时间的关系自动转换显示的格式
     */
    public static function dateTimeFormat($timestamp) {
        $timestamp = strtotime($timestamp);
        $curTime   = time();
        $space     = $curTime - $timestamp;
        //1分钟
        if ($space < 60) {
            $string = "刚刚";
            return $string;
        } elseif ($space < 3600) { //一小时前
            $string = floor($space / 60) . "分钟前";
            return $string;
        }
        $curtimeArray = getdate($curTime);
        //$timeArray    = getDate($timestamp);
        $timeArray    = getdate($timestamp);
        if ($curtimeArray['year'] == $timeArray['year']) {
            if ($curtimeArray['yday'] == $timeArray['yday']) {
                $format = "%H:%M";
                $string = strftime($format, $timestamp);
                return "今天 {$string}";
            } elseif (($curtimeArray['yday'] - 1) == $timeArray['yday']) {
                $format = "%H:%M";
                $string = strftime($format, $timestamp);
                return "昨天 {$string}";
            } else {
                $string = sprintf("%d月%d日 %02d:%02d", $timeArray['mon'], $timeArray['mday'], $timeArray['hours'], $timeArray['minutes']);
                return $string;
            }
        }
        $string = sprintf("%d-%d-%d %d:%d", $timeArray['year'], $timeArray['mon'], $timeArray['mday'], $timeArray['hours'], $timeArray['minutes']);
        return $string;
    }

    /**
     * 生成随机字符串
     * @param int $length 随机字符串的长度
     * @return string 随机字符串
     */
    public static function createNoncestr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str   = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            //$str .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $str;
    }

    /**
     * 获得当前访问的客户端的IP地址
     * @return string IP地址
     */
    public static function getIps() {
        $cIP  = getenv('REMOTE_ADDR');
        $cIP1 = getenv('HTTP_X_FORWARDED_FOR');
        $cIP2 = getenv('HTTP_CLIENT_IP');
        $cIP1 ? $cIP = $cIP1 : null;
        $cIP2 ? $cIP = $cIP2 : null;
        return $cIP;
    }

    /**
     * 生成微信支付所需的签名
     * @param array $pack 需要打包到签名的参数
     * @return string 签名
     */
    public static function paySign($pack) {
        //对参数按照key=value的格式，并按照参数名ASCII字典序排序生成字符串
        ksort($pack);
        $string = self::ToUrlParams($pack);
        //连接商户key
        $string = $string . "&key=" . config("config.MCHKEY");
        //MD5编码并转成大写
        $string = md5($string);
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 格式化参数格式化成url参数
     */
    public static function ToUrlParams($arr) {
        $buff = "";
        foreach ($arr as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 数组转换XML
     * @param array $arr
     * @return string
     */
    public static function toXML($arr) {
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

    public static function convName($expType) {
        if ($expType == 1) {
            return "OrderExport";
        } else {
            return false;
        }
    }

    /**
     * digitDefault
     * @param string $input
     * @param int $default
     * @return string
     */
    public static function digitDefault($input, $default = 0) {
        return (is_numeric($input) && $input > 0) ? intval($input) : $default;
    }
}
