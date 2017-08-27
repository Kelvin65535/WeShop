<?php
/**
 * Created by PhpStorm.
 * User: kelvi
 * Date: 2017/6/2
 * Time: 10:50
 */

namespace app\admin\model;


use think\Model;
use think\Db;

/**
 * Class Setting 商城系统设置管理器
 * @package app\admin\model
 */
class Setting extends Model
{

    /**
     * 获取首页板块列表
     * @return type
     */
    public function getHomeNavigation() {
        return Db::name("settings_nav")
            ->order("sort asc")
            ->select();
    }

    /**
     * 返回首页导航
     */
    public function getNav() {
        $nav = Db::name("settings_nav")
            ->order("sort ASC")
            ->select();
        for ($i = 0; $i < count($nav); $i++) {
            if ($nav[$i]['nav_type'] == 1) {
                // 商品分类
                $nav[$i]['nav_content'] = "/weshop/Products/view_list/cat/" . $nav[$i]['nav_content'];
            }
        }
        return $nav;
    }
}