<?php
/**
 * Created by PhpStorm.
 * User: kelvin
 * Date: 2017/1/25
 * Time: 上午11:30
 */

return [
    //微信配置
    //微信公众号ID
    'APPID' => 'wx928bb94d6de96ab2',

    //微信公众号APPSECRET
    'APPSECRET' => 'db6ce5b862c353e98d82b7d0e216faba',

    //微信公众号TOKEN
    'TOKEN' => 'wechat',

    //微信支付商户号
    'MCHID' => '',

    //微信支付商户key
    'MCHKEY' => '',

    //商城配置
    //商城根目录(记得使用完整的域名) 示例：http://baidu.com
    'docroot' => 'http://shop.hakaei.iego.cn/weshop/',

    //商城名称
    'shopname' => '微信商城',

    //用户设置
    //新用户注册时的默认积分
    'user_credit_default' => 0,

    //商品设置
    //当商品没有设置详细规格信息时，默认的商品规格名称
    'default_spec_name' => '默认规格',

    // 不需要微信支付直接下单 测试用
    'order_nopayment' => FALSE,

    // 是否已经通过微信认证
    'wechatVerifyed' => true,



    //系统设置
    // 系统根目录
    'shoproot' => '/weshop/'
];