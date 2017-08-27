<?php

namespace app\weshop\controller;

use app\weshop\model\Products;
use app\weshop\model\Productspec;
use app\weshop\model\User;
use think\Controller;
use think\Request;
use think\Db;

class Product extends Controller
{
    /**
     * 查看产品分类介绍
     * @param type $Query
     */
    public function category($cat = 0, $serial_id = 0, $searchkey = '', $orderby = '') {
        //加载模型
        $product_model = new Products();
        $cat_parent_id = 0;
        $countSubcat = intval(
            Db::name("product_category")
                ->where("cat_parent", $cat_parent_id)
                ->count()
        );

        if ($countSubcat == 0 && $cat == 0) {
            //$this->redirect("?/vProduct/view_list/cat=$Query->cat");
        }

        //顶级分类下的产品
        $topCats = $product_model->getCatList(0);

        //顶级分类下的子分类
        $secCats = array();
        foreach ($topCats as $tc) {
            $secCats = array_merge($secCats, $product_model->getCatList($tc['cat_id']));
        }

        //分类信息
        $catInfo    = $product_model->getCatInfo($cat);
        //子分类信息
        $subCatInfo = $product_model->getCatList($catInfo['cat_id']);

        $this->assign('searchkey', $searchkey);
        $this->assign('orderby', $orderby);

        $this->assign('topcat', $topCats);
        $this->assign('subcat', $subCatInfo);
        $this->assign('title', '产品搜索');
        $this->assign('cat', $cat);
        $this->assign('serial_id', $serial_id);
        $this->assign('cat_descs', $catInfo['cat_descs']);

        return $this->fetch();
    }

    public function ajaxCatList($id, $serial_id = false){
        //加载模型
        $product_model = new Products();

        $id = intval($id);

        if (isset($id)){

            //一周新品
            if ($id == -1) {
                $products = $product_model->getNewEst();
                $this->assign('products', $products);
                $this->assign('stype', ""); //TODO
                echo $this->fetch("product/ajax_newproduct");
                return;
            }

            //一周热搜
            else if ($id == -2) {
                $products = $product_model->getHotEst();
                $this->assign('stype', ""); //TODO
                $this->assign('products', $products);
                echo $this->fetch("product/ajax_hotproduct");
                return;
            }

            else if ($id >= 0){
                // 分类对应的品牌
                //$brands = $this->Brand->getCatBrand($this->cacheId);
                //$this->assign('brands', $brands);
                //该分类的子分类列表
                $subCatInfo = $product_model->getCatList($id);
                if (sizeof($subCatInfo) > 0){
                    //如果该分类的子分类存在
                    foreach ($subCatInfo as &$item){
                        $children = $product_model->getCatList($item['cat_id']);
                        if (count($children) > 0){
                            $item['child'] = $children;
                        }else{
                            $item['child'] = false;
                        }
                    }
                    $this->assign('subcat', $subCatInfo);
                    echo $this->fetch("product/ajaxcatlist");
                    return;
                } else {
                    // 分类下面无子分类
                    $this->assign('products', $product_model->getNewEst($id));
                    $this->assign('stype', ""); //TODO
                    echo $this->fetch('product/ajax_hotproduct');
                    return;
                }
            }

            else {

            }
        }
    }

    public function viewList(){

    }

    /**
     * 根据商品ID号显示商品详情
     * @param $id int 商品id号
     */
    public function detail($id){
        //加载model
        $user_model = new User();
        $product_model = new Products();
        $product_spec_model = new Productspec();

        //获取open_id
        $openid = $user_model->getOpenId();

        //获取产品基本信息
        $product_info = $product_model->getProductInfoById($id);

        $this->assign('product', $product_info);
        $this->assign('openid', $openid);
        return $this->fetch();
    }



    /**
     * ajax切换商品收藏状态
     * 使用POST方法
     * 传入参数：
     * id:要更改商品收藏状态的商品ID号，若要增加收藏则id = 商品id，移除收藏状态则id = 商品id的倒数
        返回json：
        成功时返回：{"code": 0}
        失败时返回：{"code": -1}
        注释：
        id的使用示例：若要将id为1的商品加入收藏，则id值设置为1；若要将id为1的商品移除收藏，则id设置为-1
     */
    public function ajaxAlterProductLike() {
        //加载模型
        $user_model = new User();
        $product_model = new Products();
        //获取用户的openid
        $openid = $user_model->getOpenId();

        //商品ID
        $id = input("post.id");
        if ($id > 0 && $openid != ''){
            $result = $product_model->addProductLike($openid, $id);
            if ($result > 0){
                return json(['code' => 0]);
            }else{
                return json(['code' => -1]);
            }

        }else if ($id < 0 && $openid != ''){
            $id = abs($id);
            $result = $product_model->deleteProductLike($openid, $id);
            if ($result > 0){
                return json(['code' => 0]);
            }else{
                return json(['code' => -1]);
            }
        }else {
            return json(['code' => -1]);
        }
    }

    /**
     * 检查该商品是否已收藏
     * 使用POST方法
     * data:
        id：要查询状态的商品ID号
       返回json：
        若商品在收藏列表中：{"code": 0}
        若不在收藏列表或查询失败：{"code": -1}
     */
    public function checkLike() {
        $user_model = new User();
        $id = input("post.id");
        if ($id > 0) {
            $openid = $user_model->getOpenId();
            $result = Db::name("client_product_likes")
                ->where("openid", $openid)
                ->where("product_id", $id)
                ->find();
            if (!empty($openid) && $result > 0) {
                return json(['code' => 0]);
            } else {
                return json(['code' => -1]);
            }
        } else {
            return json(['code' => -1]);
        }
    }

    /**
     * 获取最热商品列表
     * @param type $cat
     * @param type $limit
     * @return type
     */
    public function getHotEst($cat = 0, $limit = 10) {

        $pds = $this->Dao->select("po.*,ps.sale_prices")
            ->from(TABLE_PRODUCTS)
            ->alias('po')
            ->leftJoin(TABLE_PRODUCT_ONSALE)
            ->alias('ps')
            ->on('ps.product_id=po.product_id')
            ->where('`is_delete` <> 1')
            ->orderby('product_readi')
            ->desc()
            ->limit($limit)
            ->exec();
        return $pds;
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
