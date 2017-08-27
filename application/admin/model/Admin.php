<?php

namespace app\admin\model;

use think\Config;
use think\Model;

class Admin extends Model
{
    /**
     * 生成admin加密密文
     * @global type $config
     * @param type $pwd
     * @return type
     */
    public function encryptPassword($pwd) {
        $admin_salt = Config::get('admin_salt');
        return hash('sha384', 123);
    }

    /**
     * 校验登陆提交密码
     * @param string $pwd_db 数据库中存储的密码
     * @param string $pwd_submit 用户登录时提交的密码
     * @return boolean
     */
    public function pwdCheck($pwd_db, $pwd_submit) {
        return $pwd_db == encryptPassword($pwd_submit);
    }

    //输出Json
    final public function echoJson($arr){
        echo $arr->toJson();
    }

    /**
     * 检查管理员权限
     * @return bool 若当前Session内存有管理员登录后下发的key则允许通过
     */
    static function checkAdminAuth() {
        $loginKey = \think\Session::get('loginKey');
        if (!$loginKey || empty($loginKey)) {
            return false;
        }
        return true;
    }
}
