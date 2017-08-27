<?php

namespace app\weshop\model;

use think\Db;
use think\Model;

/**
 * 商品规格处理模块
 * Class Productspec
 * @package app\weshop\model
 */
class Productspec extends Model
{
    /**
     * 获取商品规格、价格、库存信息
     * @param $product_id int 商品ID号
     * @param $spec_id int 商品对应的规格项ID号
     * @return array 该商品规格号对应的规格信息
     * 返回值定义如下：
     * specname；商品规格名称
     * sale_price：售价
     * market_price：市场价
     * instock：库存量
     */
    public function getProductSpecInfo($product_id, $spec_id){
        //返回值
        $return = [
            //商品规格名称
            'specname' => '',
            //售价
            'sale_price' => 0.00,
            //市场价
            'market_price' => 0.00,
            //库存量
            'instock' => 0
        ];

        if ($spec_id > 0 && $product_id > 0){
            /*
             * 根据商品的规格ID号获取该商品对应的规格信息项
             * $spec数组定义如下：
             * id：该商品对应规格项的ID号
             * product_id：该信息项对应的商品ID号
             * spec_det_id1：主规格的ID号
             * spec_det_id2：子规格的ID号
             * sale_price：售价
             * market_price：市场价
             * instock：库存
             */
            $spec = Db::name("product_spec")
                ->where("id", $spec_id)
                ->find();
            /*
             * 获取商品主规格的详细信息
             * 返回数组定义如下：
             * id：该详细信息的ID号
             * spec_id：规格项的ID号
             * det_name：详细信息名称
             * det_sort：该信息在规格项中的排序
             */
            $spec_main_detail = Db::name("spec_det")
                ->where("id", $spec['spec_det_id1'])
                ->find();
            /*
             * 获取商品子规格的详细信息
             * 返回值定义同上
             */
            $spec_sub_detail = Db::name("spec_det")
                ->where("id", $spec['spec_det_id2'])
                ->find();
            /*
             * 获取规格信息项的名称
             */
            $spec_name = Db::name("spec")
                ->where("id", $spec_main_detail['spec_id'])
                ->find();

            //若该规格项包含商品子信息
            if ($spec_sub_detail != null){
                $return['specname']     = $spec_name['spec_name'] . '(' . $spec_main_detail['det_name'] . $spec_sub_detail['det_name'] . ')';
                $return['sale_price']   = floatval($spec['sale_price']);
                $return['market_price'] = floatval($spec['market_price']);
                $return['instock']      = intval($spec['instock']);
            }else{
                $return['specname']     = $spec_name['spec_name'] . '(' . $spec_main_detail['det_name'] . ')';
                $return['sale_price']   = floatval($spec['sale_price']);
                $return['market_price'] = floatval($spec['market_price']);
                $return['instock']      = intval($spec['instock']);
            }
        }else{
            // 如果spec_id为0，那就是没有设置规格，返回普通商品信息
            $productInfo = Db::name("products_info")
                ->where("product_id", $product_id)
                ->find();
            $return['specname']     = config("config.default_spec_name");
            $return['sale_price']   = $productInfo['sell_price'];
            $return['market_price'] = $productInfo['market_price'];
            $return['instock']      = $productInfo['product_instocks'];
        }
        return $return;
    }
}
