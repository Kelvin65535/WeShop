<?php

namespace app\weshop\controller;

use app\weshop\model\Products;
use app\weshop\model\Productspec;
use app\weshop\model\User;
use app\wechat\model\Jssdk;
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

    /**
     * 商品列表
     * @param type $Query
     */
    public function viewList($brand = 0, $page = 0, $searchkey = '', $serial = false, $cat = false, $orderby = "", $level = false, $in = false) {
        $user_model = new User();
        $product_model = new Products();
        $openid = $user_model->getOpenId();

//        !isset($Query->brand) && $Query->brand = 0;
//        !isset($Query->page) && $Query->page = 0;
//        !isset($Query->searchkey) && $Query->searchkey = '';
//        !isset($Query->serial) && $Query->serial = false;
//        !isset($Query->cat) && $Query->cat = false;
//        !isset($Query->orderby) && $Query->orderby = "";
//        !isset($Query->level) && $Query->orderby = false;
//        $Query->searchkey = urldecode($Query->searchkey);

        $searchkey = urldecode($searchkey);
//
//        $this->cacheId = $this->getRequestHash();

        // 推荐com，90分钟
//        if (!isset($this->pGet['com']) && isset($Query->com)) {
//            setcookie("com", $Query->com, time() + 5400);
//        }

        // params
        if ($searchkey != '') {
            $catInfo = array(
                'cat_id' => $cat,
                'cat_name' => $searchkey . ' 的搜索结果'
            );
        } else if ($serial) {
            $serialInfo = $product_model->getSerialInfo($serial);
            $catInfo    = array(
                'cat_name' => $serialInfo['serial_name']
            );
        } else if ($brand) {
            $catInfo = array(
                //'cat_name' => $this->Db->getOne("SELECT `brand_name` FROM `product_brand` WHERE `id` = $Query->brand;")
                'cat_name' => Db::name('product_brand') -> where('id', $brand) -> value('brand_name')
            );
        } else if ($cat) {
            $catInfo = $product_model->getCatInfo($cat);
        } else {
            $catInfo = array(
                'cat_name' => '商品列表'
            );
        }

        $this->assign('brand', $brand);
        $this->assign('serial', $serial);
        //$this->assign('query', false);
        $this->assign('searchkey', $searchkey);
        $this->assign('cat', $cat);
        $this->assign('level', $level);
        $this->assign('catInfo', $catInfo);
        $this->assign('orderby', $orderby);
        $this->assign('title', $catInfo['cat_name']);
        $this->assign('in', $in);

        return $this->fetch('product/view_list');
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
        $jssdk_model = new Jssdk();

        //获取open_id
        $openid = $user_model->getOpenId();
        //判断用户是否已关注
        $isSubscribed = $user_model->isSubscribed();

        //获取产品基本信息
        $product_info = $product_model->getProductInfoById($id);

        // 获取价格表
        $specs = $product_spec_model->getProductSpecs($id);

        // 获取价格表（详细）
        $specsDistinct = $product_spec_model->getProductSpecsDistinct($id);

        // 获取会员折扣
        $discount = $user_model->getDiscount($user_model->getUid());

        // 随机product推荐
        $sList = $product_model->randomGetProducts($product_info['product_cat'], $id, 6);

        // TODO 促销判断
        // if (strtotime($productInfo['product_prom_limitdate']) < $this->now) {
        //     $productInfo['product_prom'] = 0;
        // }
        // 
        // TODO 红包信息
        // $promInfo = $this->Envs->getPdEnvs($Query->id, 1);
        // 
        // TODO 商品是否已收藏
        // $isLiked = false;

        if ($user_model->inWechat()) {
            $signPackage = $jssdk_model->GetSignPackage();
            $this->assign('signPackage', $signPackage);
        }

        $this->assign('openid', $openid); //微信openid
        $this->assign('isSubscribed', $isSubscribed); //微信用户是否已关注

        $this->assign('productid', $id); //产品id
        $this->assign('title', $product_info['product_name']); //标题（产品名称）
        $this->assign('productInfo', $product_info); //产品信息
        $this->assign('specs', $specs); //产品分类
        $this->assign('specsDistinct', $specsDistinct); //详细产品分类
        $this->assign('images', $product_info['images']); //产品图片
        $this->assign('images_count', count($product_info['images'])); //产品图片数
        $this->assign('slist', $sList); //随机推荐产品
        $this->assign('discount', $discount); //会员折扣
        $this->assign('prominfo', false);
        $this->assign('isLiked', false); //商品是否已收藏

        // 增加点击数
        $product_model->upReadi($id);

        return $this->fetch();
    }

    /**
     * Ajax返回商品列表 分页
     * @param type $Query
     */
    public function ajaxProductList() {

        $product_model = new Products();
        $product_model_admin = new \app\admin\model\Products();
        // 搜索条件
        $searchkey = input('?get.searchKey') ? urldecode(input('get.searchKey')) : false;
        $in = input('?get.in') ? urldecode(input('get.in')) : false;
        // 商品系列
        $serial = input('?get.serial') ? input('get.serial') : false;
        // 系列等级
        $level = input('?get.level') ? input('get.level') : 0;
        // 分页号
        $page = input('?get.page') ? input('get.page') : 0;
        // 商品分类
        $cat = input('?get.cat') ? input('get.cat') : 1;
        // 列表宫格样式标记
        $stype = input('?get.stype') ? input('get.stype') : false;
        // 特殊分页标记
        if ($page != 0) {
            $pdlists1 = cookie('pdlist-serial');
            $pdlists2 = cookie('pdlist-start');
        } else {
            $pdlists1 = false;
            $pdlists2 = 0;
        }
        // 排序
        if (!input('?get.orderby') || input('get.orderby') == "") {
            $orderby = 'product_cat ASC';
        } else {
            $orderby = trim(urldecode(input('get.orderby')));
        }

        //TODO 修正children
        if (intval($cat) > 0) {
            $children = $product_model->get_children($cat);
        } else {
            $children = '';
        }
        // 数据处理
        if ($serial) {
            // 系列展示列表
            if (is_numeric($serial)) {

                $_categorys = $product_model->getCategoryByLevel($level, $cat);

                $categorys = array();
                foreach ($_categorys as $ca) {
                    $categorys[$ca['cat_id']] = array(
                        'cat_image' => $ca['cat_image'],
                        'cat_name' => $ca['cat_name'],
                        'cat_id' => $ca['cat_id'],
                        'pd' => array()
                    );
                }

                if ($searchkey) {
                    //TODO weshop_orders_detail数据库为全称，修改为加上前缀
                    $pds = Db::name('products_info info')
                        ->join('weshop_product_onsale onsale', 'info.product_id = onsale.product_id', 'LEFT')
                        ->join('weshop_product_serials serial', 'serial.id = info.product_serial', 'LEFT')
                        ->join('weshop_product_category category', 'category.cat_id = info.product_cat', 'LEFT')
                        ->where('is_delete', '<>', 1)
                        ->where('product_online', 1)
                        ->where('product_name', 'LIKE', "%%$searchkey%%")
                        ->order($orderby)
                        ->limit($pdlists2, 1000)
                        ->field([
                            'info.*',
                            'onsale.sale_prices',
                            'category.cat_parent',
                            "(SELECT SUM(product_count) FROM `weshop_orders_detail` WHERE `weshop_orders_detail`.product_id = `info`.product_id) AS sale_count"
                        ])
                        ->select();
                } else {
                    $pds = Db::name('products_info info')
                        ->join('weshop_product_onsale onsale', 'info.product_id = onsale.product_id', 'LEFT')
                        ->join('weshop_product_serials serial', 'serial.id = info.product_serial', 'LEFT')
                        ->join('weshop_product_category category', 'category.cat_id = info.product_cat', 'LEFT')
                        ->where('is_delete', '<>', 1)
                        ->where('product_online', 1)
                        ->order($orderby)
                        ->limit($pdlists2, 1000)
                        ->field([
                            'info.*',
                            'onsale.sale_prices',
                            'category.cat_parent',
                            "(SELECT SUM(product_count) FROM `weshop_orders_detail` WHERE `weshop_orders_detail`.product_id = `info`.product_id) AS sale_count"
                        ])
                        ->select();
                }

                // 已加载商品列表数量
                $pdLoaded = count($pds);

                foreach ($pds as $pd) {
                    if (!array_key_exists($pd['product_cat'], $categorys)) {
                        // level catid 转换
                        $catId = $product_model->getCatIdUtilLevel($pd['product_cat'], $level);
                    } else {
                        $catId = $pd['product_cat'];
                    }
                    $categorys[$catId]['pd'][] = $pd;
                }

                cookie('pdlist-start', $pdlists2 + $pdLoaded);
                $this->assign('pdloaded', $pdLoaded);
                $this->assign('categorys', $categorys);
                unset($pds);
                unset($_categorys);
            }
        } else {
            // 搜索展示列表
            $pdLoaded = 0;
            $limit    = 10;
            // 获取所有系列
            $serials      = $product_model_admin->getSerials($pdlists1);
            $serialsCount = count($serials) - 1;
//
//            if (isset($Query->searchKey) && $Query->searchKey != '') {
//                $Query->searchKey = urldecode($Query->searchKey);
//            }

            foreach ($serials as $index => &$seri) {
                $seri['s'] = $index == 0 && $page != 0;
                // 商品列表

                $where = [];
                if ($in) {$where['in'] = $in; }
                if ($searchkey) {$where['po.`product_name`'] = ['LIKE', "%%$searchkey%%"]; }

                $seri['pd'] = Db::name('products_info info')
                    ->join('weshop_product_onsale onsale', 'info.product_id = onsale.product_id', 'LEFT')
                    ->join('weshop_product_serials serial', 'serial.id = info.product_serial', 'LEFT')
                    ->where('is_delete', '<>', 1)
                    ->where('product_online', 1)
                    ->where(intval($cat) > 0 ? $children : '')
                    ->where($where)
                    ->order($orderby)
                    ->limit($pdlists2, 1000)
                    ->select();

                // 商品计数
                $seri['pdCount'] = count($seri['pd']);
                $pdLoaded += $seri['pdCount'];
                $limit -= $seri['pdCount'];
                if ($limit <= 0 || $index == $serialsCount) {
                    cookie('pdlist-serial', $seri['sort']);
                    if ($seri['sort'] == $pdlists1) {
                        cookie('pdlist-start', $pdlists2 + $seri['pdCount']);
                    } else {
                        cookie('pdlist-start', $seri['pdCount']);
                    }
                    $serials = array_slice($serials, 0, $index + 1);
                    break;
                }
                $pdlists2 = 0;
            }
            $this->assign('pdloaded', $pdLoaded);
        }
        $this->assign('serials', $serials);


        // 缓存文件判断加载位置
        if ($serial) {
            // 系列产品列表
            $tpl = 'product/ajaxproductlist_serials';
        } else {
            $tpl = 'product/ajaxproductlist';
        }

        // final show
        $this->show($tpl);
    }

    /**
     * ajax切换商品收藏状态
     * 使用POST方法
     * 传入参数：
     * id:要更改商品收藏状态的商品ID号，若要增加收藏则id = 商品id，移除收藏状态则id = 商品id的倒数
        返回json：
        成功时返回：0
        失败时返回：-1
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
                return json(0);
            }else{
                return json(-1);
            }

        }else if ($id < 0 && $openid != ''){
            $id = abs($id);
            $result = $product_model->deleteProductLike($openid, $id);
            if ($result > 0){
                return json(0);
            }else{
                return json(-1);
            }
        }else {
            return json(-1);
        }
    }

    /**
     * 检查该商品是否已收藏
     * 使用GET方法
     * data:
        id：要查询状态的商品ID号
       返回json：
        若商品在收藏列表中：{"ret_code": 0}
        若不在收藏列表或查询失败：{"ret_code": -1}
     */
    public function checkLike($id) {
        $user_model = new User();
        if ($id > 0) {
            $openid = $user_model->getOpenId();
            $result = Db::name("client_product_likes")
                ->where("openid", $openid)
                ->where("product_id", $id)
                ->find();
            if (!empty($openid) && $result > 0) {
                return json(['ret_code' => 0]);
            } else {
                return json(['ret_code' => -1]);
            }
        } else {
            return json(['ret_code' => -1]);
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
     * ajax获取商品描述
     * @param type $id 商品id
     */
    public function ajaxGetContent($id) {
        if ($id > 0) {
            $desc = Db::name('products_info')
                ->where('product_id', $id)
                ->value('product_desc');
            echo $desc;
            return;
        }
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
