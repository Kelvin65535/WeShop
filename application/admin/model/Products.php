<?php
/**
 * Created by PhpStorm.
 * User: kelvi
 * Date: 2017/5/3
 * Time: 5:05
 */

namespace app\admin\model;

use think\Db;
use think\Model;

class Products extends Model
{
    /**
     * 获取商品列表
     * @param type $cat
     * @param type $page
     * @param type $limit
     * @param type $orderby
     * @param mixed $searchKey 搜索商品关键字
     * @return type
     */
    public function getList($cat = false, $page = 0, $limit = 10, $orderby = 'info.product_id DESC', $searchKey = false) {
        //若搜索关键字存在则按照关键字搜索
        if ($searchKey) {
            $searchKey = urldecode($searchKey);
            $pds = Db::name("products_info") ->alias("info")
                ->join("weshop_product_onsale onsale", "info.product_id = onsale.product_id", "LEFT")
                ->where("info.product_name", "like", "%$searchKey%")
                ->where("info.is_delete", 0)
                ->order($orderby)
                ->limit($page, $limit)
                ->select();
            /*
            $pds       = $this->Dao->select()
                ->from(TABLE_PRODUCTS)
                ->alias('pds')
                ->leftJoin(TABLE_PRODUCT_ONSALE)
                ->alias('pdos')
                ->on('pds.product_id = pdos.product_id')
                ->where("pds.product_name LIKE '%$searchKey%'")
                ->aw('pds.is_delete = 0')
                ->orderBy($orderby)
                ->limit($page, $limit)
                ->exec();
            */
            return $pds;
        }
        //若商品id不存在则获取全部商品
        if ($cat === false) {
            $pds = Db::name("products_info") ->alias("info")
                ->join("weshop_product_onsale onsale", "info.product_id = onsale.product_id", "LEFT")
                ->where("info.is_delete", 0)
                ->order($orderby)
                ->limit($page, $limit)
                ->select();
            /*
            $pds = $this->Dao->select()
                ->from(TABLE_PRODUCTS)
                ->alias('pds')
                ->leftJoin(TABLE_PRODUCT_ONSALE)
                ->alias('pdos')
                ->on('pds.product_id = pdos.product_id')
                ->where('pds.is_delete = 0')
                ->orderBy($orderby)
                ->limit($page, $limit)
                ->exec();
            */
        } else if (is_array($cat)) {
            //商品id为数组，则获取id序列内所有的商品
            $pds = Db::name("products_info") ->alias("info")
                ->join("weshop_product_onsale onsale", "info.product_id = onsale.product_id", "LEFT")
                ->where("info.product_cat", "in", implode(',', $cat))
                ->where("info.is_delete", 0)
                ->order($orderby)
                ->limit($page, $limit)
                ->select();
            /*
            $pds = $this->Dao->select()
                ->from(TABLE_PRODUCTS)
                ->alias('pds')
                ->leftJoin(TABLE_PRODUCT_ONSALE)
                ->alias('pdos')
                ->on('pds.product_id = pdos.product_id')
                ->where('pds.product_cat IN (' . implode(',', $cat) . ')')
                ->aw('pds.is_delete = 0')
                ->orderBy($orderby)
                ->limit($page, $limit)
                ->exec();
            */
        } else {
            //商品id为单个id
            $pds = Db::name("products_info") ->alias("info")
                ->join("weshop_product_onsale onsale", "info.product_id = onsale.product_id", "LEFT")
                ->where("info.product_cat", $cat)
                ->where("info.is_delete", 0)
                ->order($orderby)
                ->limit($page, $limit)
                ->select();
            /*
            $pds = $this->Dao->select()
                ->from(TABLE_PRODUCTS)
                ->alias('pds')
                ->leftJoin(TABLE_PRODUCT_ONSALE)
                ->alias('pdos')
                ->on('pds.product_id = pdos.product_id')
                ->where("pds.product_cat = $cat")
                ->aw('pds.is_delete = 0')
                ->orderBy($orderby)
                ->limit($page, $limit)
                ->exec();
            */
        }

        return $pds;
    }

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
     * 修改商品信息
     * @param type $dataS
     * @return boolean
     */
    public function modifyProduct($product_id, $product_infos, $product_prices, $product_images, $entDiscount, $spec) {

        $ret = false;


        //启动事务，修改商品信息
        $id = Db::transaction(function() use (&$product_id, &$product_prices, &$product_infos, &$product_images, &$spec){
            //商品id
            $id = intval($product_id);
            if ($id == 0) {
                // 下面执行新建商品的操作
                $id = 'NULL';
            }

            // 检查商品首图是否需要上传，并且加入上传列表
            foreach ($product_infos as &$d) {
                if ($d['name'] == 'catimg' && $d['value'] != '') {
                    //if (preg_match("/$this->imageUploadMark/is", $d['value'])) {
                        //将$product_images[-1]设置为商品首图
                        $product_images[-1] = $d['value'];
                        $d['value']                  = str_replace('product_hpic2__', '', $d['value']);
                    //}
                }
            }

            //打包sql语句，将$product_infos中（[0]=>key, [1]=>value）格式转化为 [key]=>value 的形式
            $product_info_array = array();
            foreach ($product_infos as &$info) {
                $product_info_array[$info['name']] = $info['value'];
            }

            //插入商品信息到数据库
            if ($id == 'NULL') {
                //新建商品
                $id = Db::name('products_info')
                    ->insertGetId($product_info_array);
                //写入价格表
                Db::name('product_onsale')
                    ->insert([
                        'product_id' => $id,
                        'discount' => 1,
                        'sale_prices' => $product_prices
                    ]);
            } else {
                //修改商品
                Db::name('products_info')
                    ->where('product_id', $id)
                    ->update($product_info_array);
                //写入价格表
                Db::name('product_onsale')
                    ->where('product_id', $id)
                    ->update([
                        'product_id' => $id,
                        'discount' => 1,
                        'sale_prices' => $product_prices
                    ]);
            }

            //TODO 静态化商品描述html
            //$this->writeHTML($id, $product_info_array['product_desc']);

            //处理商品规格
            if (isset($spec) && is_array($spec)) {
                foreach ($spec AS $index => $spec_item) {
                    //商品规格id
                    $detids = explode('-', $spec_item['sid']);
                    if ($detids[0] == 0) {
                        // 不允许空规格
                        continue;
                    }
                    if (empty($spec_item['market_price'])) {
                        $spec_item['market_price'] = 0;
                    }
                    if (empty($spec_item['instock'])) {
                        $spec_item['instock'] = 0;
                    }
                    if (empty($spec_item['price'])) {
                        $spec_item['price'] = 0;
                    }
                    if ($spec_item['id'] == 0) {
                        // 新增规格项
                        Db::name("product_spec")
                            ->insert([
                                'product_id' => $id,
                                'spec_det_id1' => $detids[0],
                                'spec_det_id2' => $detids[1],
                                'sale_price' => $spec_item['price'],
                                'market_price' => $spec_item['market_price'],
                                'instock' => $spec_item['instock']
                            ]);
                    } else if ($spec_item['id'] > 0) {
                        // 大于0 修改
                        Db::name("product_spec")
                            ->where('id', $spec_item['id'])
                            ->update([
                                'product_id' => $id,
                                'spec_det_id1' => $detids[0],
                                'spec_det_id2' => $detids[1],
                                'sale_price' => $spec_item['price'],
                                'market_price' => $spec_item['market_price'],
                                'instock' => $spec_item['instock']
                            ]);
                    } else {
                        // 小于0 删除
                        Db::name("product_spec")
                            ->where('id', abs($spec_item['id']))
                            ->delete();
                    }
                }
            } else {

            }

            //上传商品图片
            foreach ($product_images as $sort => $image) {
                // 如果是新上传的数据
                //$image   = str_replace($this->imageUploadMark, '', $image);
                //$_path   = $config->productPicRoot . $image;
                //$_tmpath = $config->productPicRootTmp . $image;
                // 移动图片文件

                if ($sort != -1) {
                    Db::name("product_images")
                        ->insert([
                            'product_id' => $id,
                            'image_path' => $image,
                            'image_type' => 0,
                            'image_sort' => $sort
                        ]);
                } else {
                    //新上传了商品首图
                    /*
                    Db::name('products_info')
                        ->where('product_id', $id)
                        ->update(['catimg' => $product_images[-1]]);
                    */
                }
            }

            return $id;

        });

        return $id;
    }

    /**
     * 静态化商品描述html
     * @param type $id
     * @param type $content
     */
    private function writeHTML($id, $content) {
        $dir = ROOT_PATH . 'public/html/products/';
        !is_dir($dir) && mkdir($dir, 0755);
        file_put_contents($dir . $id . '.html', $content);
        unset($dir);
    }

    /**
     * 获取产品系列列表
     */
    public function getSerials($start = false) {
        if ($start) {
            return Db::name('product_serials')
                ->where('sort', '>=', $start)
                ->where('deleted', 0)
                ->order('sort asc')
                ->select();
        } else {
            return Db::name('product_serials')
                ->where('deleted', 0)
                ->order('sort asc')
                ->select();
        }
    }

    /**
     * 获取系列信息
     * @param type $id
     * @return boolean
     */
    public function getSerialInfo($id) {
        if (!is_numeric($id)) {
            return false;
        }
        return Db::name('product_serials')
            ->where('id', $id)
            ->find();
    }

    /**
     * 获取所有产品分类列表，递归
     * @param int $catParent 父级分类
     * @param boolean $cache 缓存开关
     * @return type
     */
    public function getAllCats($catParent = 0, $cache = false) {
        if (input('?session.supplier_id')){
            $supplier_id = input('session.supplier_id');
            $supplierAquery = " AND `product_supplier` = $supplier_id";
        } else {
            $supplierAquery = " ";
        }

        $list = Db::name('product_category')
            ->where('cat_parent', $catParent)
            ->order('cat_order desc')
            ->field(['cat_name'=>'name', 'cat_id'])
            ->select();
        foreach ($list as &$l) {
            $l['dataId']   = intval($l['cat_id']);
            $l['children'] = $this->getAllCats(intval($l['cat_id']));
            // 商品数量
            $l['pdcount'] = Db::name('products_info')
                ->where("product_cat = $l[cat_id] $supplierAquery")
                ->where('is_delete', '<>', 1)
                ->count();
            $l['open']        = 'true';
            $l['hasChildren'] = count($l['children']) > 0;
            $l['name'] .= ' (' . $l['pdcount'] . ')';
            unset($l['cat_id']);
        }
        return $list;
    }

    /**
     * 根据商品分类ID获取商品分类信息
     * @param int $catid 要获取的商品分类id
     * @return <string>
     */
    public function getCatInfo($catid) {
        $catid = intval($catid);
        $res = Db::name('product_category')
            ->where('cat_id', $catid)
            ->limit(1)
            ->select();
        return $res ? $res[0] : null;
    }

    /**
     * 获取所有品牌列表
     * @return type
     */
    public function getAllBrands($del = 0) {
        //$SQL = "SELECT * FROM `product_brand` WHERE `deleted` = $del ORDER BY `sort` ASC;";
        //$Lst = $this->Db->query($SQL, false);
        $list = Db::name('product_brand')
            ->where('deleted', $del)
            ->order('sort asc')
            ->select();
        foreach ($list as &$l) {
            $l['dataId']      = intval($l['id']);
            $l['open']        = 'true';
            $l['name']        = $l['brand_name'];
            $l['hasChildren'] = false;
        }
        return $list;
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
     * 獲取已刪除商品
     * @param type $limit
     */
    public function getDeletedProducts($limit = 100) {
        //$SQL = "SELECT po.*,ps.sale_prices,psl.serial_name FROM `products_info` po " . "LEFT JOIN `product_onsale` ps ON po.product_id = ps.product_id " . "LEFT JOIN `product_serials` psl ON psl.id = po.product_serial WHERE `is_delete` = 1 LIMIT $limit;";
        //return $this->Db->query($SQL, $cache);
        return Db::name('products_info') ->alias('info')
            ->join('weshop_product_onsale onsale', 'info.product_id = onsale.product_id', 'LEFT')
            ->join('weshop_product_serials serial', 'serial.id = info.product_serial', 'LEFT')
            ->where('is_delete', 1)
            ->limit($limit)
            ->field('info.*, onsale.sale_prices, serial.serial_name')
            ->select();
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
     *
     * @param type $catId
     * @param type $level
     * @return type
     */
    public function getCatIdUtilLevel($catId, $level) {
        $info = $this->getCatInfo($catId);
        if ($info['cat_level'] == $level) {
            return $catId;
        } else {
            return $this->getCatIdUtilLevel($info['cat_parent'], $level);
        }
    }
}