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
}