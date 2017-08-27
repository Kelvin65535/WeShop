<?php

namespace app\admin\controller;

use app\admin\model\ProductCategory;
use app\admin\model\Products;
use think\Controller;
use think\Db;
use think\Debug;
use think\Request;

class Product extends Controller
{
    /**
     * ajax获取商品分类列表树
     */
    public function ajaxGetCategroys()
    {
        // 加载模型
        $product_model = new Products();
        // 获取分类列表
        $cats = $product_model->getAllCats();

        //TODO 当前商品若为空时将显示“未分类”提示，应将“未分类”的显示修改为空
        /*
        $cats[] = array(
            'name' => '未分类',
            'dataId' => 0,
            'hasChildren' => false,
            'children' => array(),
            'open' => 'true'
        );
        */
        echo json_encode($cats, JSON_UNESCAPED_UNICODE);
    }

    /**
     * ajax添加商品分类
     */
    public function ajaxAddCategroy() {
        $catname = trim(input("post.catname"));
        $pid     = intval(input('post.pid'));
        $res = Db::name("product_category")
            ->insert([
                'cat_name' => $catname,
                'cat_parent' => $pid
            ]);
        return json($res);
    }

    /**
     * ajax获取产品列表
     */
    public function ajax_product_list() {
        //从post变量中获取数据
        if (input("?post.page")){
            $page = input("post.page");
        } else {
            $page = 0;
        }
        if (input("?post.page_size")){
            $page_size = input("post.page_size");
        } else {
            $page_size = 15;
        }
        $offset      = $page * $page_size;
        if (input("?post.product_cat")){
            $product_cat = input("post.product_cat");
        } else {
            $product_cat = 5;
        }
        //sql中order字符串
        if (input("?post.order")){
            $order = input("post.order");
        } else {
            $order = 'info.product_id DESC';
        }
        //商品名称的搜索关键字
        if (input("?post.key")){
            $key = input("post.key");
        } else {
            $key = '';
        }

        if ($product_cat > 0) {
            if (!empty($key)){
                $pds = Db::name("products_info") -> alias("info")
                    ->join("weshop_product_serials serials", "serials.id = info.product_serial", "LEFT")
                    ->join("weshop_product_category category", "category.cat_id = info.product_cat", "LEFT")
                    ->where("is_delete", "<>", 1)
                    ->where("product_name", "like", "%%$key%%")
                    ->where("product_cat", $product_cat)
                    ->order($order)
                    ->limit($offset, $page_size)
                    ->field("info.catimg, info.product_id, info.product_name, info.product_online, info.product_code, info.product_unit, info.product_readi, info.sell_price as sale_prices, serials.serial_name, category.cat_parent")
                    ->select();
            } else {
                $pds = Db::name("products_info") -> alias("info")
                    ->join("weshop_product_serials serials", "serials.id = info.product_serial", "LEFT")
                    ->join("weshop_product_category category", "category.cat_id = info.product_cat", "LEFT")
                    ->where("is_delete", "<>", 1)
                    ->where("product_cat", $product_cat)
                    ->order($order)
                    ->limit($offset, $page_size)
                    ->field("info.catimg, info.product_id, info.product_name, info.product_online, info.product_code, info.product_unit, info.product_readi, info.sell_price as sale_prices, serials.serial_name, category.cat_parent")
                    ->select();
            }
            /*
            $pds = $this->Dao->select("po.catimg,po.product_id,po.product_name,po.product_online,po.product_code,po.product_unit,po.product_readi,po.sell_price AS sale_prices,psl.serial_name,pca.cat_parent")
                ->from(TABLE_PRODUCTS)
                ->alias('po')
                ->leftJoin(TABLE_PRODUCT_SERIALS)
                ->alias('psl')
                ->on('psl.id = po.product_serial')
                ->leftJoin(TABLE_PRODUCT_CATEGORY)
                ->alias('pca')
                ->on('pca.cat_id = po.product_cat')
                ->where('`is_delete` <> 1')
                ->aw(!empty($key) ? "`product_name` LIKE '%%$key%%'" : '')
                ->aw("`product_cat` = $product_cat")
                ->aw($supplierquery)
                ->orderBy($order)
                ->limit("$offset, $page_size")
                ->exec();
            */
            // 算总数
            /*
            $tcount = $this->Dao->select("")
                ->count()
                ->from(TABLE_PRODUCTS)
                ->alias('po')
                ->where('`is_delete` <> 1')
                ->aw(!empty($key) ? "`product_name` LIKE '%%$key%%'" : '')
                ->aw("`product_cat` = $product_cat")
                ->aw($supplierquery)
                ->getOne();
            */
            if (!empty($key)) {
                $tcount = Db::name("products_info")
                    ->where('product_cat', $product_cat)
                    ->where("is_delete", "<>", 1)
                    ->where('product_name', 'like', "%%$key%%")
                    ->count();
            } else {
                $tcount = Db::name("products_info")
                    ->where('product_cat', $product_cat)
                    ->where("is_delete", "<>", 1)
                    ->count();
            }
            foreach ($pds as &$pd) {
                $pd['catimg'] = $this->productImageConv($pd['catimg']);
            }
            return json(['ret_code' => 0, 'ret_msg' => [
                'data' => $pds,
                'count' => intval($tcount)
            ]]);
        } else {
            return json(['ret_code' => -1]);
        }
    }

    /**
     * TODO 商品图片转换
     * @param type $catimg
     * @param type $x
     * @param type $y
     */
    private function productImageConv($catimg, $x = 50, $y = 50) {
        /*
        global $config;
        if (empty($catimg)) {
            $catimg = 'static/images/icon/iconfont-pic.png';
        } else {
            $catimg = $config->oss ? $catimg : "static/Thumbnail/?w=$x&h=$y&p=" . $config->productPicLink . $catimg;
        }
        */
        return $catimg;
    }
}
