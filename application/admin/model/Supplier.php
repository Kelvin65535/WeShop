<?php
/**
 * Created by PhpStorm.
 * User: Kelvin
 * Date: 2017/5/3
 * Time: 5:56
 */

namespace app\admin\model;

use think\Db;
use think\Model;

/**
 * 商户处理模块
 * Class Supplier
 * @package app\admin\model
 */
class Supplier extends Model
{
    /**
     * 获取商户列表
     * @return mixed
     */
    public function getList() {
        return Db::name('suppliers')
            ->select();
    }
}