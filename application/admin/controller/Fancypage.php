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
use think\Request;
use think\Db;

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

    /**
     * 商品选择块
     */
    public function ajaxSelectProduct() {
        // 以ajax形式返回渲染好的fancybox弹出框内容
        echo $this->fetch("fancypage/ajax_select_product");
    }

    /**
     * 初始化函数
     * 新建本控制器实例时调用该方法
     * 加载全局模版变量
     */
    public function _initialize()
    {
        parent::_initialize();
        //获取店铺全局设置
        //从数据库获取店铺配置信息
        $query = Db::name('settings')
            ->select();
        //处理query
        $this->settings = array();
        foreach ($query as $item){
            $this->settings[$item['key']] = $item['value'];
        }
        //加载模版变量
        //全局商铺设置
        $this->assign('settings', $this->settings);
        //商铺根目录
        $this->assign('docroot', config('config.docroot'));
        $request = Request::instance();
        $this->assign('controller', $request->controller());   //控制器名称
    }
}