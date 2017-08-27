<?php

namespace app\admin\controller;

use app\admin\model\Gmess;
use app\admin\model\Products;
use app\admin\model\Productspec;
use app\admin\model\Setting;
use app\admin\model\Statoverview;
use app\admin\model\Supplier;
use app\admin\model\Userlevel;
use app\weshop\model\User;
use think\Controller;
use think\Request;
use think\Db;
use think\View;

class mainpage extends Controller
{
    /**
     * @var array 商铺整体设置
     */
    public $settings;

    /**
     * 微店总览
     */
    //微店总览
    public function overview(){
        //echo ("微店总览");
        //$this->Smarty->cache_id = 'stat-overview';
        //$this->loadModel('StatOverView');
        $stat_model = new Statoverview();
        // 获取统计最新日期
        //$datendStr = $this->Dao->select('ref_date')
        //    ->from(TABLE_USER_SUMMARY)
        //    ->orderby('ref_date')
        //    ->desc()
        //    ->getOne(false);
        $datendStr = Db::name('user_summary')
            ->order('ref_date desc')
            ->value('ref_date');
        // 新增粉丝
        /*
        $newFans = $this->Dao->select("SUM(new_user) AS new_user, SUM(cancel_user) AS cancel_user")
            ->from(TABLE_USER_SUMMARY)
            ->where("ref_date = '$datendStr'")
            ->orderby('ref_date')
            ->desc()
            ->limit(1)
            ->getOneRow();
        */
        $newFans = Db::name('user_summary')
            ->where('ref_date', $datendStr)
            ->order('ref_date desc')
            ->field(['SUM(new_user)' => 'new_user', 'SUM(cancel_user)'=> 'cancel_user'])
            ->find();
        $this->assign('newFans', $newFans);
        // 粉丝总数
        /*
        $totalFans = $this->Dao->select()
            ->from(TABLE_USER_CUMULATE)
            ->orderby('ref_date')
            ->desc()
            ->limit(1)
            ->getOneRow();
        */
        $totalFans = Db::name('user_cumulate')
            ->order('ref_date desc')
            ->find();
        $this->assign('totalFans', $totalFans);
        $this->assign('Datas', $stat_model->getOverViewDatas());
        return $this->fetch('mainpage/stat/overview');
    }


    /********************
     *
     *  订单管理
     *
     *********************/

    //订单管理
    public function orders_manage(){
        return $this->fetch('mainpage/orders/manage');
    }

    /**
     * 商品管理
     */
    //商品管理
    public function list_products(){
        //echo ("商品管理");
        //$this->assign('docroot', config('docroot'));
        return $this->fetch('mainpage/products/list_products');
    }
    //iframe
    /**
     * iframe商品列表
     * @param type $Query
     */
    public function iframe_list_product($cat) {

        //$cat = isset($Query->cat) ? intval($Query->cat) : false;

        if (input('?cookie.comid')) {
            $iscom = input('cookie.comid');
        } else {
            $iscom = '';
        }

        $this->assign('iscom', $iscom);
        //$this->show(self::TPL . 'products/iframe_list_products.tpl');
        //$this->assign('docroot', config('config.docroot'));
        $this->assign('cat', $cat);
        return $this->fetch('mainpage/products/iframe_list_products');
    }

    /**
     * 编辑商品信息
     * @param type $Query
     */
    public function iframe_alter_product($mod, $cat = false, $id = false) {

        // 加载模型
        $product_model = new Products();
        $productspec_model = new Productspec();
        $supplier_model = new Supplier();
        //$this->loadModel('Product');
        //$this->loadModel('mProductSpec');
        //$this->loadModel('Supplier');


        // 获取系列
        //$serials = $this->Product->getSerials();
        $serials = $product_model->getSerials();

        // 获取分类
        //$categorys = $this->Product->getAllCats();
        $categorys = $product_model->getAllCats();

        // 获取品牌
        //$brands = $this->Product->getAllBrands();
        $brands = $product_model->getAllBrands();

        // 编辑模式
        if ($mod == 'edit') {
            $pid = $id;
            $productInfo = $product_model->getProductInfoById($pid);
            //$productInfo = $this->Product->getProductInfo($pid, false);
            $this->assign('ed', true);
            $this->assign('pd', $productInfo);
            $this->assign('cat', $productInfo['product_cat']);
        } else {

            $this->assign('ed', null);
            $this->assign('cat', $cat);
            $this->assign('pd', null);
        }

        // 获取规格列表
        //$speclist = $this->mProductSpec->getSpecList();
        $speclist = $productspec_model->getSpecList();

        // 获取商家列表
        //$suppliers = $this->Supplier->getList();
        $suppliers = $supplier_model->getList();

        $this->assign('suppliers', $suppliers);
        $this->assign('speclist', $speclist);
        $this->assign('brands', $brands);
        $this->assign('categorys', $categorys);
        $this->assign('serials', $serials);
        $this->assign('mod', $mod);
        return $this->fetch('mainpage/products/iframe_alter_product');
    }

    /**
     * 商品分类列表
     */
    public function alter_products_category() {
        //$this->show(self::TPL . 'products/alter_products_category.tpl');
        return $this->fetch('mainpage/products/alter_products_category');
    }

    /**
     * 编辑商品分类
     * @param type $Query
     */
    public function alter_category($id) {
        //$id = intval($Query->id);
        if (is_numeric($id)) {
            $product_model = new Products();
            $cati      = $product_model->getCatInfo($id);
            $categorys = $product_model->getAllCats();
            $this->assign('id', $id);
            $this->assign('cat', $cati);
            $this->assign('categorys', $categorys);
            return $this->fetch('mainpage/products/alter_category');
        }
    }

    /**
     * ajax 添加商品分类
     */
    public function ajax_add_category() {
        $product_model = new Products();
        $categorys = $product_model->getAllCats();
        $this->assign('categorys', $categorys);
        echo $this->fetch('mainpage/products/ajax_add_category');
    }

    /**
     * 商品回收站
     */
    public function deleted_products() {
        $this->loadModel('Product');
        // 加载模型
        $product_model = new Products();
        $productList = $product_model->getDeletedProducts(1000);
        $this->assign('list', $productList);
        //$this->show(self::TPL . 'products/deleted_products.tpl');
    }

    //商品规格
    /**
     * 编辑商品规格
     */
    public function alter_product_specs() {
        $product_spec_model = new Productspec();
        $specs = $product_spec_model->getSpecList();
        $this->assign('specs', $specs);
        return $this->fetch('mainpage/products/alter_product_specs');
    }

    /**
     * ajax编辑商品规格
     */
    public function ajax_alter_product_spec($id='') {
        $spec_model = new Productspec();
        if ($id) {
            $spec = $spec_model->getSpecData($id);
            $this->assign('spec', $spec);
            $this->assign('add', false);
        } else {
            $this->assign('add', true);
        }

        return $this->fetch('mainpage/products/ajax_alter_product_spec');
    }

    //ajax

    /********************
     *
     *  用户管理
     *
     *********************/

    /**
     * 会员列表
     */
    public function list_customers() {
        //$this->loadModel('UserLevel');
        $userlevel_model = new Userlevel();
        $group = $userlevel_model->getList();
        foreach ($group as &$g) {
            // 用户组计数
            //$g['count'] = $this->Db->getOne("SELECT COUNT(*) FROM `clients` WHERE `deleted` = 0 AND `client_level` = $g[id];");
            $g['count'] = Db::name('clients')
                ->where('deleted', 0)
                ->where('client_level', $g['id'])
                ->count();
        }
        //在数组开头插入“全部用户”的计数
        array_unshift($group, array(
            'id' => '',
            'level_name' => '全部用户',
            'count' => (Db::name('clients')
                ->where('deleted', 0)
                ->count())
                //$this->Db->getOne("SELECT COUNT(*) FROM `clients` WHERE `deleted` = 0;")
        ));
        $this->assign('group', $group);
        //$this->assign('iscom', $this->pCookie('comid') ? 1 : '');
        $this->assign('iscom', input('?cookie.comid') ? 1 : '');
        //$this->show(self::TPL . 'users/list_customers.tpl');
        return $this->fetch('mainpage/users/list_customers');
    }

    /**
     * iframe 用户列表
     * @param int $gid
     */
    public function iframe_list_customer($gid = '') {
        $this->assign('gid', $gid);
        //$this->show(self::TPL . 'users/iframe_list_customer.tpl');
        return $this->fetch('mainpage/users/iframe_list_customer');
    }

    /********************
     *
     *  店铺设置
     *
     *********************/

    public function settings_base(){
        //echo ("基础设置");
        $gmess_model = new Gmess();
        if ($this->settings['welcomegmess'] > 0) {
            $gm = $gmess_model->getGmess($this->settings['welcomegmess']);
            $this->assign('gm', $gm);
        } else {
            $this->assign('gm', '');
        }
        return $this->fetch('mainpage/settings/settings_base');
    }

    /**
     * 首页导航
     */
    public function settings_navigation() {
        $setting_model = new Setting();
        $list = $setting_model->getHomeNavigation();
        $this->assign('nav', $list);
        return $this->fetch('mainpage/settings/settings_navigation');
    }

    /**
     * 编辑首页导航
     * @param $id
     */
    public function alter_navigation($id = 0) {
        $product_model = new Products();
        if ($id > 0) {
            $sec = Db::name("settings_nav")
                ->where("id", $id)
                ->find();
            $this->assign('sec', $sec);
        }else {
            $this->assign('products', false);
            $this->assign('sec', false);
        }
        $cats = $product_model->getAllCats();
        $this->assign('categorys', $product_model->getAllCats());
        return $this->fetch('mainpage/settings/alter_navigation');

    }

    /**
     * 自定义菜单设置
     */
    public function settings_menu() {
        $product_model = new Products();
        $categorys = $product_model->getAllCats();
        $this->assign('categorys', $categorys);
        return $this->fetch('mainpage/settings/settings_menu');
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
