<?php
/**
 * Created by PhpStorm.
 * User: kelvi
 * Date: 2017/5/3
 * Time: 8:44
 */

namespace app\admin\model;

use think\Db;
use think\Model;

/**
 * Class Gmess 素材管理控制器
 * @package app\admin\model
 */
class Gmess extends Model
{
    /**
     * 获取指定id的素材
     * @param type $id
     * @return type
     */
    public function getGmess($id) {
        return Db::name('gmess_page')
            ->where('id', $id)
            ->find();
    }
}