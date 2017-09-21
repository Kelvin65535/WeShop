<?php

namespace app\admin\controller;

use app\admin\model\Banners;
use app\admin\model\Products;
use app\wechat\model\Wechatsdk;
use think\Controller;
use think\Cookie;
use think\Db;
use think\Request;

class Mainajax extends Controller
{
    /**
     * 获取商品详细信息
     * @param type $Query
     */
    public function ajaxGetProductInfo($Query) {
        $id            = intval($Query->id);
        $res           = $this->Db->getOneRow("SELECT * FROM `products_info` pi LEFT JOIN product_onsale po ON po.product_id = pi.product_id WHERE pi.`product_id` = $id;");
        $res['images'] = $this->Db->query("SELECT * FROM `product_images` WHERE `product_id` = " . $res['product_id']);

        echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 获取商品分类统计数据
     */
    public function ajaxGetProductStatnums() {
        // multi-supplier
        //TODO 删除代理部分
        $ret = array();
        //已删除商品数量
        //$ret['pdcount2'] = intval($this->Db->getOne("SELECT COUNT(*) FROM products_info WHERE `is_delete` = 1 $supplierAquery;"));
        $ret['pdcount2'] = intval(Db::name("products_info")
                                    ->where('is_delete', 1)
                                    ->count());
        //未删除商品数量
        //$ret['pdcount']  = intval($this->Db->getOne("SELECT COUNT(*) FROM products_info WHERE `is_delete` = 0 $supplierAquery;"));
        $ret['pdcount'] = intval(Db::name("products_info")
                                    ->where("is_delete", 0)
                                    ->count());
        //商品分类数量
        //$ret['cacount']  = intval($this->Db->getOne("SELECT COUNT(*) FROM product_category $supplierWquery;"));
        $ret['cacount'] = intval(Db::name("product_category") -> count());
        //$ret['spcount']  = intval($this->Db->getOne("SELECT COUNT(*) FROM wshop_spec $supplierWquery;"));
        //商品规格数量
        $ret['spcount'] = intval(Db::name("spec") -> count());
        //商品序列数量
        $ret['secount'] = intval(Db::name("product_serials") -> count());
        //$ret['secount']  = intval($this->Db->getOne("SELECT COUNT(*) FROM product_serials $supplierWquery;"));
        //$ret['brcount'] = intval($this->Db->getOne("SELECT COUNT(*) FROM product_brand $supplierWquery;"));
        //商品品牌数量
        $ret['brcount'] = intval(Db::name("product_brand") -> count());
        return json($ret);
    }

    /**
     * ------------------
     * 微信公众号菜单设置
     * ------------------
     */
    /**
     * 获取自定义菜单设置项
     */
    public function getMenu() {
        $r = Db::name("menu")
            ->where("id", input("post.id"))
            ->select();
        return json($r);
    }

    /**
     * 设置自定义菜单设置项
     */
    public function bindMenu() {
        $data = ['relid' => input("post.relid"),
                'reltype' => input("post.reltype"),
                'relcontent' => strip_tags(input("post.relcontent"))];
        $result = Db::name("menu")
            ->insert($data);
        return json($result);
    }


    /**
     * 设置微信自定义菜单
     */
    public function ajaxSetWechatMenu() {
        $rst = WechatSdk::setMenu(input('post.menu'));
        return json($rst);
    }

    /**
     * 设置广告列表
     */
    public function settings_banners() {
        $banners_model = new Banners();
        $arrPos  = array(
            '首页顶部',
            '首页尾部',
            '个人中心',
            '搜索板块',
            '全站顶部',
            '首页中间广告展示'
        );
        $arrType = array(
            '产品分类',
            '产品列表',
            '图文消息',
            '超链接'
        );
        $banner  = $banners_model->getBanners();
        foreach ($banner as &$ba) {
            $ba['pos']  = $arrPos[$ba['banner_position']];
            $ba['type'] = $arrType[$ba['reltype']];
        }
        $this->assign('banners', $banner);
        $this->show(self::TPL . 'settings/banners.tpl');
    }

    /**
     * ------------------
     * 后台商品设置
     * ------------------
     */

    /**
     * 更新商品信息
     */
    public function updateProduct() {
        $product_model = new Products();
        if (input("?post.product_serial")) {
            Cookie::set('lastSerial', input("post.product_serial"));
        }

        //获取插入所需参数
        //商品id
        if (input("?post.product_id")) {
            $product_id = input("post.product_id");
        } else {
            $product_id = false;
        }
        //商品详细信息
        if (input("?post.product_infos")) {
            $product_infos = input("post.product_infos/a");
        } else {
            $product_infos = false;
        }
        //商品价格
        if (input("?post.product_prices")) {
            $product_prices = input("post.product_prices");
        } else {
            $product_prices = false;
        }
        //商品图片列表
        if (input("?post.product_images")) {
            $product_images = input("post.product_images/a");
        } else {
            $product_images = false;
        }
        //商品折扣
        if (input("?post.entDiscount")) {
            $entDiscount = input("post.entDiscount/a");
        } else {
            $entDiscount = false;
        }
        //商品规格列表
        if (input("?post.product_infos")) {
            $spec = input("post.spec/a");
        } else {
            $spec = false;
        }

        // 默认供货价
        if (!input("?post.supply_price") || empty(input("post.supply_price"))) {
            $_POST['supply_price'] = 0.00;
        }

        try {
            $id = $product_model->modifyProduct($product_id, $product_infos, $product_prices, $product_images, $entDiscount, $spec);
            if ($id > 0) {
                return json([
                    'ret_code' => 0,
                    'ret_msg' => $id]
                );
            } else {
                return json([
                        'ret_code' => -1,
                        'ret_msg' => "未知错误"]
                );
            }
        } catch (\Exception $ex) {
            return json([
                    'ret_code' => -1,
                    'ret_msg' => $ex->getMessage()]
            );
        }
    }

}
