<?php
/**
 * Created by PhpStorm.
 * User: kelvi
 * Date: 2017/5/5
 * Time: 17:28
 */

namespace app\admin\controller;

use app\admin\model\Userlevel;
use app\common\model\Util;
use think\Controller;
use think\Db;

/**
 * Class User 用户控制器
 * @package app\admin\controller
 */
class User extends Controller
{
    /**
     * ajax获取用户列表
     */
    public function getUserList() {
        //$this->loadModel('User');
        $user_model = new \app\admin\model\User();

        //从GET变量获取用户信息
        //$gid      = $this->pGet('gid');
        if (input('?get.gid')){
            $gid = input('get.gid');
        } else {
            $gid = '';
        }
        //$phone    = $this->pGet('phone');
        if (input('?get.phone')){
            $phone = input('get.phone');
        } else {
            $phone = '';
        }
        //$name     = urldecode($this->pGet('uname'));
        if (input('?get.uname')){
            $name = urldecode(input('get.uname'));
        } else {
            $name = '';
        }
        //$page     = Util::digitDefault($this->pGet('page'), 0);
        if (input('?get.page')){
            $page = Util::digitDefault(input('get.page'));
        } else {
            $page = 0;
        }
        //$pagesize = Util::digitDefault($this->pGet('pagesize'), 30);
        if (input('?get.pagesize')){
            $pagesize = Util::digitDefault(input('get.pagesize'));
        } else {
            $pagesize = 30;
        }


        $WHERE            = [];
        $WHERE['deleted'] = 0;


        if (!empty($gid)) {
            $WHERE['client_level'] = $gid;
        }
        if (!empty($phone)) {
            $WHERE['client_phone'] = ['like', '%' . $phone . '%'];
        }
        if (!empty($name)) {
            $WHERE['client_name'] = ['like', '%' . $name . '%'];
        }
        $count = Db::name('clients')
            ->where($WHERE)
            ->count();

        //$this->Dao->select('')
        //    ->count()
        //    ->from(TABLE_USER)
        //    ->where($WHERE);
        //!empty($gid) && $WHERE['client_level'] = $gid;
        //!empty($phone) && $this->Dao->aw("client_phone LIKE '%$phone%'");
        //!empty($name) && $this->Dao->aw("client_name LIKE '%$name%'");
        //$count = $this->Dao->getOne();

        //$this->Dao->select()
        //    ->from(TABLE_USER)
        //    ->where($WHERE);
        //!empty($gid) && $WHERE['client_level'] = $gid;
        //!empty($phone) && $this->Dao->aw("client_phone LIKE '%$phone%'");
        //!empty($name) && $this->Dao->aw("client_name LIKE '%$name%'");
        //$list = $this->Dao->orderby('client_id')
        //    ->desc()
        ////    ->limit($pagesize * $page, $pagesize)
         //   ->exec();

        $list = Db::name('clients')
            ->where($WHERE)
            ->order('client_id desc')
            ->limit($pagesize * $page, $pagesize)
            ->select();

        //$this->echoJson([
        //    'total' => intval($count),
        //    'list' => $list
        //]);
        return json([
            'total' => intval($count),
            'list' => $list
        ]);
    }

    /**
     * 获取用户分组
     */
    public function getUserLevel() {
        $userLevelModel = new Userlevel();
        $list = $userLevelModel->getList();
        return json([
            'ret_code' => 0,
            'ret_msg' => $list
        ]);
    }

    /**
     * 获取用户信息
     */
    public function getUserInfo() {
        $id = intval(input('get.id'));
        if ($id > 0) {
            //$ret = $this->Dao->select()
            //    ->from(TABLE_USER)
            //    ->where(['client_id' => $id])
            //    ->getOneRow();
            $ret = Db::name('clients')
                ->where('client_id', $id)
                ->find();
            return json(['ret_code' => 0, 'ret_msg' => $ret]);
        } else {
            return json(['ret_code' => -1, 'ret_msg' => '']);
        }
    }

    /**
     * ajax编辑用户 | 添加用户（该功能废弃）
     */
    public function alterUser() {
        //$clientId = $this->pPost('client_id');
        $clientId = input('post.client_id');
        $data = input('post.');
        //$data     = $this->post();
        /*
        if ($clientId == 0) {
            $field                        = array();
            $values                       = array();
            $data['client_joindate']      = date('Y-m-d');
            $data['client_wechat_openid'] = hash('md4', uniqid() . time());
            foreach ($data as $key => $value) {
                $field[]  = $key;
                $values[] = $value;
            }
            if ($this->Dao->insert(TABLE_USER, implode(',', $field))
                ->values($values)
                ->exec()
            ) {
                $this->echoSuccess();
            } else {
                $this->log('新增会员失败,SQL:' . $this->Dao->getSQL());
                $this->echoFail();
            }
        } else {
        */
            // 更新用户信息
        //if ($this->Dao->update(TABLE_USER)
         //   ->set($data)
        //    ->where(['client_id' => $clientId])
         //   ->exec()
        if (Db::name('clients')
            ->where('client_id', $clientId)
            ->update($data)
        ) {
            return json(['ret_code' => 0, 'ret_msg' => '']);
        } else {
            return json(['ret_code' => -1, 'ret_msg' => '']);
        }
        //}
    }

    /**
     * 编辑用户分组
     */
    public function alterUserLevelInfo() {
        $userlevel_model = new Userlevel();

        if (input('?post.id')) {
            $id = intval(input('post.id'));
        } else {
            $id = false;
        }

        if (input('?post.level_name')) {
            $level_name = input('post.level_name');
        } else {
            $level_name = false;
        }

        if (input('?post.level_credit')) {
            $level_credit = input('post.level_credit');
        } else {
            $level_credit = false;
        }

        if (input('?post.level_discount')) {
            $level_discount = input('post.level_discount');
        } else {
            $level_discount = false;
        }

        if (input('?post.level_credit_feed')) {
            $level_credit_feed = input('post.level_credit_feed');
        } else {
            $level_credit_feed = false;
        }

        if (input('?post.remark')) {
            $remark = input('post.remark');
        } else {
            $remark = false;
        }

        if ($id >= 0) {
            $ret = $userlevel_model->addLevel($id, $level_name, $level_credit, $level_discount, $level_credit_feed, $remark, 1);
        } else {
            $ret = $userlevel_model->addLevel(false, $level_name, $level_credit, $level_discount, $level_credit_feed, $remark, 1);
        }

        if ($ret) {
            return json(['ret_code' => 0, 'ret_msg' => '']);
        } else {
            return json(['ret_code' => -1, 'ret_msg' => '']);
        }
    }

    /**
     * 获取用户分组详情
     */
    public function getUserLevelInfo() {
        if (input('?get.id')) {
            $id = input('get.id');
        } else {
            $id = false;
        }
        $userlevel_model = new Userlevel();
        $info = $userlevel_model->getInfo($id);
        return json(['ret_code' => 0, 'ret_msg' => $info]);
    }

    /**
     * 删除用户分组
     */
    public function deleteLevel() {
        $userlevel_model = new Userlevel();
        if (input('?post.id')) {
            $id = intval(input('post.id'));
        } else {
            return json(['ret_code' => -1, 'ret_msg' => '错误：id为空']);
        }
        try {
            $userlevel_model->deleteLevel($id);
            return json(['ret_code' => 0, 'ret_msg' => '']);
        } catch (\Exception $ex) {
            return json(['ret_code' => -1, 'ret_msg' => $ex->getMessage()]);
        }
    }
}