<?php
/**
 * Created by PhpStorm.
 * User: kelvi
 * Date: 2017/9/22
 * Time: 14:25
 */

namespace app\weshop\model;


use think\Db;
use think\Model;

/**
 * Class Search 搜索模型
 * @package app\weshop\model
 */
class Search extends Model
{
    /**
     * 记录搜索信息
     * @param $openid
     * @param $key
     * @return bool|int|string
     */
    public function record($openid, $key) {
        if (!empty($openid) && $openid !== '') {
            $r = Db::name('search_record')
                ->insert([
                    'openid' => $openid,
                    'key' => $key,
                    'time' => 'NOW()'
                ]);
            return $r;
        }
        return false;
    }
}