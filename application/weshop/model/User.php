<?php

namespace app\weshop\model;

use think\Db;
use think\Model;
use app\wechat\model\Wechatsdk;
use think\Session;

/**
 * Class User
 * @package app\weshop\model
 * 用户模型
 */

class User extends Model
{

    /**
     * 判断是否在微信浏览器
     * @return type
     */
    final public static function inWechat()
    {
        return strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false;
    }

    /**
     * 获取用户openid
     * @param type $redirect_uri 是否手动指定重定向的URI
     * @param type $both 是否同时获取accesstoken
     * @return boolean | object 若没有获取到openid则返回false，有则返回openid
     */
    final public function getOpenId($redirect_uri = false, $both = false)
    {
        //从session处获得openid
        $openid = session('openid');
        if (Session::has('openid')) {
            return $openid;
        } else {
            if ($this->inWechat()) {
                //使用原始回调地址
                //原始URL地址
                $this_uri = $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
                $redirect_uri = !$redirect_uri ? self::convURI($this_uri) : $redirect_uri;
                $AccessCode = Wechatsdk::getAccessCode($redirect_uri, "snsapi_base");
                if ($AccessCode !== FALSE) {
                    // 获取Openid
                    $Result = Wechatsdk::getAccessToken($AccessCode);
                    if (!empty($Result->openid)) {
                        //将用户的openid存入session
                        Session::set('openid', $Result->openid);
                        //根据用户的openid从数据库找到uid
                        $uid = $this->getUidByOpenId($Result->openid);
                        //将用户的UID存入session
                        Session::set('uid', $uid);
                        // 跳转原始回调地址
                        header("location:" . $redirect_uri);
                        exit(0);
                    }
                } else {
                    $openid = false;
                }

                return $openid;
            }
        }


    }

    /**
     * 获取用户uid
     * @return int | false
     * @todo Session
     */
    public function getUid() {
        return Session::get('uid');
    }



    /**
     * 根据用户uid获取用户信息
     * @param type $uid
     * @return <object>
     */
    public function getUserInfoRaw($uid = false) {
        if (!$uid) {
            $uid = $this->getUid();
        }

        $userInfosq = Db::name('clients')
            ->where('client_id', $uid)
            ->find();

        return $userInfosq;
    }

    /**
     * 通过用户openid获取用户 uid
     * @param string $openid
     * @return int
     */
    public function getUidByOpenId($openid) {
        $uid = Db::name('clients')
            ->where('client_wechat_openid', "$openid")
            ->value('client_id');
        return $uid;
    }

    /**
     * 转换回调地址
     * 我也不知道什么鬼东西
     * @param string $url
     * @return string
     */
    public static function convURI($url) {
        $url = preg_replace("/(\?|\&)from=(timeline|singlemessage|groupmessage)&isappinstalled=0/", "", $url);
        //dump($url);
        $tmp = parse_url($url);
        //dump($tmp);
        if (array_key_exists('query', $tmp)){
            parse_str($tmp['query'], $t);
            //dump($t);
            //if ($t['code'] || $t['state']) {
            if (array_key_exists('code', $t) || array_key_exists('state', $t)){
                $a = array('code' => $t['code'], 'state' => $t['state']);
                $s = http_build_query($a);
                $r = preg_replace("/(\?|\&)" . $s . "/", "", $url);
            } else {
                $r = $url;
            }
        }else{
            $r = $url;
        }

        return $r;
    }

    /**
     * 微信进入自动注册
     * @todo 事务？
     */
    public function wechatAutoReg($openid = '') {
        // 检查用户是否注册
        if (!empty($openid) && $this->inWechat() && !$this->userCheckReg($openid)) {
            // 微信用户资料 UNIONID机制
            $WechatUserInfo = WechatSdk::getUserInfo(WechatSdk::getServiceAccessToken(), $openid, true);
            dump($WechatUserInfo);
            if ($WechatUserInfo->subscribe > 0) {
                // 如果已经关注，那么已经获取到信息
                // 跳过以下步骤
            } else {
                // 未关注，网页授权方式获取信息
                //###########################################################
                $this_uri = $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
                //使用原始回调地址
                $AccessCode = WechatSdk::getAccessCode(self::convURI($this_uri), "snsapi_userinfo");
                //###########################################################
                if ($AccessCode !== FALSE) {
                    // 获取到accesstoken和openid
                    $Result = WechatSdk::getAccessToken($AccessCode);
                    // 微信用户资料
                    $WechatUserInfo = WechatSdk::getUserInfo($Result->access_token, $Result->openid);
                    $openid = $Result->openid; //设定openid
                }
            }

            // TODO 导入用户注册默认积分
            //$reg_credit_default = intval($this->getSetting('reg_credit_default'));
            $reg_credit_default = config('config.user_credit_default');

            // 写入用户信息
            $uid = $this->createUser([
                'client_nickname' => $WechatUserInfo->nickname,
                'client_name' => $WechatUserInfo->nickname,
                'client_sex' => $this->wechatSexConv($WechatUserInfo->sex),
                'client_head' => substr($WechatUserInfo->headimgurl, 0, strlen($WechatUserInfo->headimgurl) - 2),
                'client_wechat_openid' => $openid,
                'client_province' => $WechatUserInfo->province,
                'client_city' => $WechatUserInfo->city,
                'client_address' => $WechatUserInfo->province . $WechatUserInfo->city,
                'client_credit' => $reg_credit_default
            ]);

            echo ("用户UID为：");
            dump($uid);


            if ($uid > 0) {

                // 用户注册成功
                Session::set('uid', $uid);
                Session::set('openid', $openid);


                // TODO 红包绑定uid
                /*
                $this->Dao->update(TABLE_USER_ENVL)
                    ->set(['uid' => $uid])
                    ->where("openid = '$openid'")
                    ->aw("uid IS NULL")
                    ->exec();
                */

                // TODO 查找 代理-会员 对应关系
                /*
                $ret = $this->Dao->update(TABLE_COMPANY_USERS)
                    ->set(['uid' => $uid])
                    ->where("openid='$openid'")
                    ->exec();
                if ($ret) {
                    // 如果确实有代理推荐
                    $comid = $this->Dao->select('comid')
                        ->from(TABLE_COMPANY_USERS)
                        ->where("openid='$openid'")
                        ->getOne();
                    // 更新代理对应
                    if ($comid > 0 && $this->bindCompany($uid, $comid)) {
                        // 执行钩子程序
                        (new HookNewCompanyLinked($this->Controller))->deal([
                            'uid' => $uid,
                            'openid' => $openid,
                            'companyid' => $comid
                        ]);
                    }

                }
                */
                return true;

            } else {
                \think\Log::write('新用户注册失败，信息写入出错' . json_encode($WechatUserInfo));
                // 无法注册
               return false;
            }
        } else {
            return false;
        }

    }

    /**
     * 获取用户所在组的折扣
     * @param int $uid 用户UID
     * @return float 用户拥有的折扣，例如85折返回0.85，原价返回1
     */
    public function getDiscount($uid) {
        $uid = intval($uid);
        if ($uid > 0) {
            $discount = Db::name("clients")->alias("user")
                ->join("weshop_client_level level", "user.client_level = level.id", "LEFT")
                ->where("user.client_id", $uid)
                ->value("level_discount");
            $discount = $discount / 100;
            if ($discount > 0 && $discount <= 1) {
                return $discount;
            } else {
                return 1;
            }
        } else {
            return 1;
        }
    }

    /**
     * 检查用户是否在数据库中存在
     * @param type $openid
     * @return boolean
     */
    public function userCheckReg($openid) {
        if (empty($openid)) {
            return false;
        } else {
            $result = Db::name('clients')
                ->where('client_wechat_openid', $openid)
                ->count();
            return $result > 0;
        }
    }

    /**
     * 根据给定的数组信息创建用户，并返回该新用户的UID
     * @param type $userData
     * 数组内容如下：
     * $userData =
     *  [
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
     * @return int 插入成功后返回主键（即新用户的UID）
     */
    public function createUser($userData = array()) {

        //当前时间
        $current_time = date("Y-m-d H:i:s");    //登录时间

        //设定用户的加入日期
        if (!isset($userData['client_joindate'])) {
            $userData['client_joindate'] = $current_time;
        }

        //设定用户头像的上次修改时间
        if (!isset($userData['client_head_lastmod'])) {
            $userData['client_head_lastmod'] = $current_time;
        }

        //写入数据库并返回主键值
        Db::name('clients')
            ->insert($userData);

        $userID = Db::name('clients')
            ->getLastInsID();

        return $userID;
    }

    /**
     * 微信性别转换int为string
     * 微信返回的userinfo信息中性别为int类型：未知 => 0，男 => 1，女 => 2
     * 该方法将转换为：未知 => NULL，男 => m，女 => f
     * @param type $sexInt
     * @return string
     */
    private function wechatSexConv($sexInt) {
        $sex_arr = array(
            'NULL',
            "m",
            "f"
        );
        return $sex_arr[($sexInt ? $sexInt : 0)];
    }

    /**
     * 判断微信用户是否已经关注
     * @return type
     */
    public function isSubscribed() {
        if ($this->inWechat()) {
            $openid = $this->getOpenId();
            $wechat_model = new Wechatsdk();
            $WechatUserInfo = $wechat_model->getUserInfo($wechat_model->getServiceAccessToken(), $openid, true);
            return $WechatUserInfo->subscribe == 1;
        }
        return false;
    }

}


