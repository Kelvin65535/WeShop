<?php
/**
 * Created by PhpStorm.
 * User: kelvi
 * Date: 2017/5/9
 * Time: 19:04
 */

namespace app\admin\controller;


use think\Db;
use think\Controller;

/**
 * Class Productspec 商品规格控制器
 * @package app\admin\controller
 */
class Productspec extends Controller
{
    public function ajaxAlterSpec() {
        $spec_model = new \app\admin\model\Productspec();
        if (input('?post.id')){
            $id = input('post.id');
        } else {
            $id = '';
        }
        if (input('?post.spec_name')){
            $spec_name = input('post.spec_name');
        } else {
            $spec_name = '';
        }
        $spec_remark = input('post.spec_remark');
        $dets = input('post.dets/a');
        if (input('?post.append')){
            $append = input('post.append');
        } else {
            $append = false;
        }
        if ($id < 0) {
            // 删除
            $id = abs($id);
            $ret = 0;
            $ret += Db::name('spec')
                ->where('id', $id)
                ->delete();
            $ret += Db::name('spec_det')
                ->where('spec_id', $id)
                ->delete();
            return json($ret);
        } else if ($spec_name) {
            // 添加
            $result = $spec_model->alterSpec($id, $spec_name, $spec_remark, $dets, $append);
            return json($result);
        } else {
            return json(0);;
        }
    }
}