<?php

namespace app\weshop\model;

use think\Db;
use think\Model;

/**
 * Class Homesection 首页商品板块处理模块
 * @package app\weshop\model
 */
class Homesection extends Model
{
    /**
     * 根据所选的板块类型获取首页展示板块列表
     * @param $Type int 获取的板块类型
     * 输入参数如下：
     * 0：产品分类
     * 1：产品列表
     * 2：图文信息
     * 3：超链接
     * @return array 获取的数据库结果
     * 返回值如下：
     * [0] => array(9) {
        ["id"] => string(1)     板块ID
        ["name"] => string(7)   板块名称
        ["pid"] => string(1)    板块内的商品ID
        ["banner"] => NULL      TODO 待补充
        ["reltype"] => string(1) 板块类型，见上述Type类型
        ["relid"] => string(1)  TODO 待补充
        ["bsort"] => string(1)  板块排序，越大越靠前
        ["ftime"] => NULL       有效时间
        ["ttime"] => NULL       无效时间
        }
     */
    public function getSectionFromType($Type){
        return Db::name('settings_section')
                ->where('(ttime IS NULL AND ftime IS NULL) AND reltype IN ('.$Type.')')
                ->whereOr('NOW() <= ttime aNd ftime <= NOW() AND reltype IN ('.$Type.') ')
                ->order('bsort desc')
                ->select();
    }
}
