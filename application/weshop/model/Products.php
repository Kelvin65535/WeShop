<?php

namespace app\weshop\model;

use think\Db;
use think\Model;

/**
 * Class Products 商品处理模块
 * @package app\weshop\model
 */
class Products extends Model
{
    /**
     * 根据In字符串获取商品的基本信息
     * @param string $in In字符串，即要查询的商品id号以字符串形式连接，示例：1、2、3号商品=>In='1，2，3'
     * @return false|array 数据库的查询结果，以数组形式返回
     */
    public function getIn($in){
        return Db::name('products_info') ->alias('pi')
            ->join('weshop_product_onsale po', 'pi.product_id=po.product_id', 'LEFT')
            ->where('is_delete', '<>', 1)
            ->where('pi.product_online', 1)
            ->where('pi.product_id', 'in', $in)
            ->order('pi.product_id desc')
            ->select();
    }

    /**
     * 根据产品ID号获取商品的基本信息
     * @param $productID int 商品ID号
     * @return array|bool|false 产品信息数组
     */
    public function getProductInfoById($productID){
        if (is_numeric($productID)){
            $productID = intval($productID);
            //获取产品基本信息
            $product_info = Db::name('products_info') -> alias('pi')
                ->join("weshop_product_onsale ps", "pi.product_id = ps.product_id", "LEFT")
                ->where("pi.product_id", $productID)
                ->field("pi.*, ps.sale_prices, ps.discount")
                ->find();
            //产品图片
            $product_info['images'] = $this->getProductImages($productID);
            //获取产品价格表
            /*
             * 产品规格价格表
             * 数组定义如下：
             *  ["id"] => 规格项ID号（对应spec表的主键）
                ["instock"] => 某项规格对应的库存量
                ["market_price"] => 市场价
                ["id1"] => 主规格ID（没有主规格则ID为0）
                ["name1"] => 主规格名称
                ["id2"] => 子规格ID（没有子规格则ID为0）
                ["name2"] => 子规格名称（没有子规格则name为null）
                ["sale_price"] => 售价
             */
            $product_info['specs'] = Db::name("product_spec")->alias("sp")
                ->join("weshop_spec_det detail1", "detail1.id = sp.spec_det_id1", "LEFT")
                ->join("weshop_spec_det detail2", "detail2.id = sp.spec_det_id2", "LEFT")
                ->where("product_id", $productID)
                ->field("sp.id, sp.instock, sp.market_price, sp.spec_det_id1 as id1, detail1.det_name as name1, sp.spec_det_id2 as id2, detail2.det_name as name2, sp.sale_price")
                ->select();

            return $product_info;

        }else{
            return false;
        }
    }

    /**
     * 根据产品id和规格id获取特定规格的商品信息
     * @param int $productId 产品id
     * @param int $spec_id 规格id
     * @return mixed
     */
    public function getProductInfoWithSpec($productId, $spec_id) {
        if (is_numeric($productId)) {
            $productId = intval($productId);
            if (is_numeric($spec_id) && $spec_id > 0) {
                $productInfo = Db::name("products_info")->alias("info")
                    ->join("weshop_product_spec spec", "info.product_id = spec.product_id and spec.id = $spec_id", "LEFT")
                    ->join("weshop_spec_det detail1", "detail1.id = spec.spec_det_id1", "LEFT")
                    ->join("weshop_spec_det detail2", "detail2.id = spec.spec_det_id2", "LEFT")
                    ->where("info.product_id", $productId)
                    ->field("info.*, spec.sale_price as sale_prices, detail1.det_name as spec_detail_name1, detail2.det_name as spec_detail_name2")
                    ->find();
            } else {
                $productInfo = Db::name("products_info")->alias("info")
                    ->join("weshop_product_onsale onsale", "info.product_id = onsale.product_id", "LEFT")
                    ->field("info.*, onsale.sale_prices, onsale.discount")
                    ->find();
                $productInfo['spec_detail_name1'] = '默认规格';
                $productInfo['spec_detail_name2'] = '';
            }
            return $productInfo;
        } else {
            return false;
        }
    }

    /**
     * 根据商品ID号获取商品的图片
     * @param int $productID 商品ID号
     * @param null|int $limit 限制图片数量，为空代表没有限制
     * @return false|array 商品图片数组
     */
    public function getProductImages($productID, $limit = null){
        $limit = intval($limit);
        if ($limit > 0){
            return Db::name('product_images')
                ->where("product_id = $productID AND image_path <> ''")
                ->order("image_sort asc")
                ->limit($limit)
                ->select();
        }else{
            return Db::name('product_images')
                ->where("product_id = $productID AND image_path <> ''")
                ->order("image_sort asc")
                ->select();
        }
    }

    /**
     * 添加用户收藏
     * @param int $openid 用户openid
     * @param int $productId 收藏商品id
     * @return int 若成功返回大于0的值，失败返回0
     */
    public function addProductLike($openid, $productId) {
        return Db::name("client_product_likes")
            ->insert(['openid' => $openid, 'product_id' => $productId]);
    }

    /**
     * 删除用户收藏
     * @param int $openid 用户openid
     * @param int $productId 要删除的商品ID
     * @return int 若成功返回大于0的值，失败返回0
     */
    public function deleteProductLike($openid, $productId) {
        return Db::name("client_product_likes")
            ->where("openid", $openid)
            ->where("product_id", $productId)
            ->delete();
    }

    public function getProductLike($openid, $page) {
        if ($openid != '') {
            return Db::name("client_product_likes")->alias("likes")
                ->join("weshop_products_info info", "info.product_id = likes.product_id", "LEFT")
                ->join("weshop_product_onsale onsale", "onsale.product_id = likes.product_id", "LEFT")
                ->where("likes.openid", $openid)
                ->page($page, 10)
                ->select();
        } else {
            return false;
        }
    }

    /**
     * 根据父分类信息（可选）查找商品分类列表
     * @param int $cat_parent
     * @return array 查询结果
     */
    public function getCatList($cat_parent = 0){
        $result = Db::name("product_category")
            ->where("cat_parent", $cat_parent)
            ->order("cat_order desc")
            ->select();
        return $result;
    }

    /**
     * 获取商品分类信息
     * @param type $catid
     * @return <string>
     */
    public function getCatInfo($catid) {
        $catid = intval($catid);
        $result = Db::name("product_category")
            ->where('cat_id', $catid)
            ->find();
        return $result ? $result : null;
    }

    /**
     * 获取最新商品列表
     * @param type $cat
     * @param type $limit
     * @return type
     */
    public function getNewEst($cat = false, $limit = 10) {
        if ($cat > 0) {
            /*
            $pds = $this->Dao->select("po.*,ps.sale_prices")
                ->from(TABLE_PRODUCTS)
                ->alias('po')
                ->leftJoin(TABLE_PRODUCT_ONSALE)
                ->alias('ps')
                ->on('ps.product_id=po.product_id')
                ->where('`is_delete` <> 1')
                ->aw('`product_online` = 1')
                ->aw('`product_cat` = ' . $cat)
                ->orderby('product_id')
                ->desc()
                ->limit($limit)
                ->exec();
            */
            $pds = Db::name("products_info")->alias("info")
                ->join("weshop_product_onsale onsale", "info.product_id = onsale.product_id")
                ->where('is_delete', '<>', 1)
                ->where('product_online', 1)
                ->where('product_cat', $cat)
                ->order('info.product_id desc')
                ->limit($limit)
                ->field("info.*, onsale.sale_prices")
                ->select();
        } else {
            /*
            $pds = $this->Dao->select("po.*,ps.sale_prices")
                ->from(TABLE_PRODUCTS)
                ->alias('po')
                ->leftJoin(TABLE_PRODUCT_ONSALE)
                ->alias('ps')
                ->on('ps.product_id=po.product_id')
                ->where('`is_delete` <> 1')
                ->aw('`product_online` = 1')
                ->orderby('product_id')
                ->desc()
                ->limit($limit)
                ->exec();
            */
            $pds = Db::name("products_info")->alias("info")
                ->join("weshop_product_onsale onsale", "info.product_id = onsale.product_id")
                ->where('is_delete', '<>', 1)
                ->where('product_online', 1)
                ->order('info.product_id desc')
                ->limit($limit)
                ->field("info.*, onsale.sale_prices")
                ->select();
        }
        return $pds;
    }

    /**
     * 获取最热商品列表
     * @param type $cat
     * @param type $limit
     * @return type
     */
    public function getHotEst($cat = 0, $limit = 10) {
        /*
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
        */
        $pds = Db::name("products_info")->alias("info")
            ->join("weshop_product_onsale onsale", "info.product_id = onsale.product_id")
            ->where('is_delete', '<>', 1)
            ->where('product_online', 1)
            ->order('info.product_readi desc')
            ->limit($limit)
            ->field("info.*, onsale.sale_prices")
            ->select();
        return $pds;
    }

    /**
     * 随机获取商品列表
     * @param type $limit
     * @return type
     */
    public function randomGetProducts($cat, $notId, $limit = 10) {
        $catParent = Db::name('product_category')
            ->where('cat_id', $cat)
            ->value('cat_parent');
        $slev = Db::name('product_category')
            ->where('cat_parent', $catParent)
            ->where('cat_id', '<>', $cat)
            ->field('cat_id')
            ->select();
        /*
        $catParent = $this->Dao->select('cat_parent')
                               ->from(TABLE_PRODUCT_CATEGORY)
                               ->where("cat_id=$cat")
                               ->getOne();
        $slev      = $this->Dao->select('cat_id')
                               ->from(TABLE_PRODUCT_CATEGORY)
                               ->where("cat_parent=$catParent")
                               ->aw("cat_id <> $cat")
                               ->exec();
        */
        $sIn       = array();
        foreach ($slev as $s) {
            $sIn[] = $s['cat_id'];
        }
        $sIn   = implode(',', $sIn);
        $plist = Db::name('products_info')->alias('info')
        ->join('weshop_product_onsale onsale', 'onsale.product_id = info.product_id')
        ->where('info.is_delete', '<>', 1)
        ->where('info.product_online', '<>', 0)
        ->where('info.product_cat', 'IN', $sIn)
        ->where('info.product_id', '<>', $notId)
        ->order('RAND()')
        ->limit($limit)
        ->field('info.*, onsale.sale_prices, onsale.discount')
        ->select();
        /*
        $plist = $this->Db->query("
            SELECT po.*,
            ps.sale_prices,
            ps.discount " . 
            "FROM `products_info` po " . 
            "LEFT JOIN `product_onsale` ps ON po.product_id = ps.product_id " . 
            "WHERE po.is_delete <> 1 
            AND po.product_online <> 0 
            AND po.product_id <> $notId 
            AND po.product_cat IN ($sIn) 
            ORDER BY RAND() 
            LIMIT $limit;");
        */
        foreach ($plist as &$p) {
            $p['images'] = $this->getProductImages($p['product_id']);
        }
        return $plist;
    }

    /**
     * 增加商品阅读数
     * @param type $pid 商品id
     * @return boolean
     */
    public function upReadi($pid) {
        if (!is_numeric($pid)) {
            return false;
        }
        // readi
        return Db::name('products_info')
            ->where('product_id', $pid)
            ->update(['product_readi' => ['exp', 'product_readi + 1']]);
    }
}
