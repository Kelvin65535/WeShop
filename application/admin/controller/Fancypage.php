<?php
/**
 * Created by PhpStorm.
 * User: kelvi
 * Date: 2017/6/2
 * Time: 16:27
 */

namespace app\admin\controller;


use app\admin\model\Products;
use think\Controller;

class Fancypage extends Controller
{
    /**
     * ajax获取商品选择块
     * @param type $Q
     */
    public function ajaxPdBlocks($id = false, $key = false) {
        $product_model = new Products();
        $productList = $product_model->getList(isset($id) ? $id : false, 0, 100, 'info.product_id DESC', $key ? $key : false);
        //$productList           = $this->Product->getList(isset($Q->id) ? $Q->id : false, 0, 100, 'pds.`product_id` DESC', $Q->key ? $Q->key : false);
        $this->assign('products', $productList);
        echo $this->fetch("fancypage/ajax_pd_blocks");
    }
}