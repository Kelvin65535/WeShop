<?php

namespace app\weshop\model;

use think\Db;
use think\Model;

/**
 * Class Cart 购物车模型
 * @package app\weshop\model
 */
class Cart extends Model
{
    public function getCartData($openid, $uid){
        //加载模型
        $product_spec_model = new Productspec();
        $user_model = new User();

        //从数据库获取用户数据
        $datas = Db::name('client_cart')
            ->where("openid", $openid)
            ->field("id, product_id, spec_id, count")
            ->select();

        //返回值数组
        $return = [
            'total' => 0,
            'supps' => []
        ];

        if (sizeof($datas) > 0){
            //用户所在组的折扣
            $discount = $user_model->getDiscount($uid);

            //商品数量
            $product_count = 0;

            //供应商列表
            $suppliers = [];

            //解包$datas，通过其中的product_id获取商品详细信息
            foreach ($datas as $data) {
                //商品信息
                $product_info = Db::name('products_info')
                    ->where("product_id", $data['product_id'])
                    ->where("is_delete", 0)
                    ->where("product_online", 1)
                    ->field("product_name, product_supplier, product_weight, catimg")
                    ->find();

                if ($product_info){
                    //商品规格信息
                    $product_spec                     = $product_spec_model->getProductSpecInfo($data['product_id'], $data['spec_id']);
                    //该商品在购物车的id
                    $product_info['cart_id']            = intval($data['id']);
                    //商品ID
                    $product_info['product_id']       = intval($data['product_id']);
                    //规格ID
                    $product_info['spec_id']          = intval($data['spec_id']);
                    //该商品在购物车的数量
                    $product_info['count']            = intval($data['count']);
                    //供应商
                    $product_info['product_supplier'] = intval($product_info['product_supplier']);
                    //售价
                    $product_info['sale_price']       = floatval($product_spec['sale_price'] * $discount);
                    //市场价
                    $product_info['market_price']     = floatval($product_spec['market_price']);
                    //库存
                    $product_info['instock']          = intval($product_spec['instock']);
                    //TODO 商品图片
                    //$product_info['catimg']           = Util::packProductImgURI($product_info['catimg']) . '@1e_1c_0o_0l_200h_200w_90q.src';
                    //TODO 商品红包字符串序列 红包id,红包id (获取商品关联红包)
                    //$product_info['envstr']         = $this->Envs->getPdEnvsJoinStr($product_info['product_id']);
                    //规格名称
                    $product_info['specname']       = $product_spec['specname'];
                    $product_info['product_weight'] = floatval($product_info['product_weight']);
                    //供应商ID
                    $supplier_id                    = intval($product_info['product_supplier']);
                    //TODO 若供应商信息存在
                    /*
                    if ($supplier_id > 0) {
                        $supplier = $this->Supplier->get($supplier_id);
                        if (!$supplier) {
                            $supplier = ['supp_name' => null, 'supp_phone' => null];
                        }
                    } else {
                    */
                        // 供应商信息不存在
                        $supplier = ['supp_name' => config('config.shopname'), 'supp_phone' => null];
                    //}
                    if (!array_key_exists('supp' . $supplier_id, $suppliers)) {
                        $suppliers['supp' . $supplier_id] = [
                            'supp_id' => $supplier_id,
                            'supp_name' => $supplier['supp_name'],
                            'supp_phone' => $supplier['supp_phone'],
                            'cart_datas' => []
                        ];
                    }
                    //购物车数据
                    array_push($suppliers['supp' . $supplier_id]['cart_datas'], $product_info);
                    //增加商品总数
                    $product_count += $data['count'];
                }else{
                    //商品信息不存在
                    continue;
                }
            }
            //加入购物车
            $return = [
                'total' => $product_count,
                'supps' => array_values($suppliers)
            ];
        }

        return $return;
    }


    /**
     * 获取购物车数据,简单结构
     * @param string $openid 用户openid
     * @return array 返回简单的购物车信息
     * 返回值说明如下：
     * array(1) {
     * 每条购物车项目为数组中的一个项
        [0] => array(3) {
            ["pid"] => string(1) "1"    商品ID
            ["spid"] => string(1) "1"   商品规格ID
            ["count"] => string(1) "1"  数量
        }
    }
     */
    public function getCartDataSimple($openid) {
        $datas = Db::name('client_cart')->alias("cart")
            ->join("weshop_products_info info", "info.product_id = cart.product_id", "LEFT")
            ->where("openid", $openid)
            ->where("info.product_online", 1)
            ->field("info.product_id as pid, spec_id as spid, count")
            ->select();

        return $datas;
    }

}
