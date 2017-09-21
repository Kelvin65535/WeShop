<?php
/**
 * Created by PhpStorm.
 * User: kelvin
 * Date: 2017/9/20
 * Time: 下午9:36
 */

namespace app\admin\controller;


use think\Controller;
use think\Db;

class Gmess extends Controller
{
    /**
     * 获取素材列表
     * @example /?/wGmess/getGmessList/
     */
    public function getGmessList($key = '', $page = 0) {
        $gmess_model = new \app\admin\model\Gmess();
        $list    = $gmess_model->getGmessList($page, 20, $key);
        $count   = $gmess_model->getGmessCount();
        return json(['list' => $list, 'count' => $count]);
    }

    /**
     * 编辑素材
     * @param type $Query
     * @example /?/wGmess/gmess_edit/
     */
    public function gmess_edit($id = 0) {
        if (isset($id) && is_numeric($id) && $id > 0) {
            $id              = intval($id);
            $gmess_model = new \app\admin\model\Gmess();
            $gmess = $gmess_model->getGmess($id);
            $this->assign('g', $gmess);
        } else {
            $id = 0;
        }
        $this->assign('ed', $id > 0);
        return $this->fetch('mainpage/gmess/gmess_edit');
    }


}