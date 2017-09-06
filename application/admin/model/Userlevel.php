<?php
/**
 * Created by PhpStorm.
 * User: kelvi
 * Date: 2017/5/5
 * Time: 16:31
 */

namespace app\admin\model;

use think\Model;
use think\Db;

/**
 * Class Userlevel 用户等级管理模型
 * @package app\admin\model
 */
class Userlevel extends Model
{
    /**
     * 获取等级列表
     * @return mixed
     */
    public function getList() {
         return Db::name('client_level')
            ->select();
    }

    /**
     * 获取等级信息
     * @param type $levId
     * @return mixed
     */
    public function getInfo($levId) {
        return Db::name('client_level')
            ->where('id', $levId)
            ->find();
    }

    /**
     * 添加一个会员级别
     */
    public function addLevel($id, $name, $credit, $discount, $feed, $remark = '', $upable = true) {
        if ($id !== false && $id >= 0) {
            return Db::name('client_level')
                ->where("id", $id)
                ->update([
                    'level_name'  => $name,
                    'level_credit' => $credit,
                    'level_discount' => $discount,
                    'level_credit_feed' => $feed,
                    'remark' => $remark,
                    'upable' => intval($upable)
                ]);
        } else {
            return Db::name('client_level')
                ->insertGetId([
                    'level_name'  => $name,
                    'level_credit' => $credit,
                    'level_discount' => $discount,
                    'level_credit_feed' => $feed,
                    'remark' => $remark,
                    'upable' => intval($upable)
                ]);
        }
    }

    /**
     * 删除一个会员级别
     */
    public function deleteLevel($id) {
        return Db::name('client_level')
            ->where('id', $id)
            ->delete();
    }
}