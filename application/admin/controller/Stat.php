<?php
/**
 * Created by PhpStorm.
 * User: kelvi
 * Date: 2017/5/9
 * Time: 15:33
 */

namespace app\admin\controller;


use think\Controller;
use think\Db;

/**
 * Class Stat 数据统计控制器
 * @package app\admin\controller
 */
class Stat extends Controller
{
    /**
     * 获取销售统计占比数据
     * 屏蔽代理功能
     * TODO 未经测试
     */
    public function getSalePercent() {
        //$com        = isset($Query->com);
        //$QueryMonth = isset($_GET['date']) ? $_GET['date'] : date("Y-m");
        if (input('?get.date')){
            $QueryMonth = input('get.date');
        } else {
            $QueryMonth = date("Y-m");
        }
/*
            $Sd = $this->Db->query("select pc.cat_name AS name,SUM(od.product_count) AS `count` from orders_detail od LEFT JOIN products_info pi on pi.product_id = od.product_id LEFT JOIN product_category pc on pc.cat_id = pi.product_cat
            LEFT JOIN orders ods ON ods.order_id = od.order_id
            WHERE DATE_FORMAT(ods.order_time,'%Y-%m') = '$QueryMonth'
            GROUP BY pi.product_cat");
*/
        $Sd = Db::name('orders_detail')->alias('detail')
            ->join('weshop_products_info info', 'info.product_id = detail.product_id', 'LEFT')
            ->join('weshop_product_category category', 'category.cat_id = info.product_cat', 'LEFT')
            ->join('weshop_orders orders', 'orders.order_id = detail.order_id', 'LEFT')
            ->where("DATE_FORMAT(orders.order_time,'%Y-%m') = '$QueryMonth'")
            ->field('category.cat_name as name, SUM(detail.product_count) as count')
            ->group('info.product_cat')
            ->select();
        $r = array();
        $c = count($Sd);
        foreach ($Sd as $index => $s) {
            if ($s['name'] != '' && !empty($s['name'])) {
                $t = array(
                    $s['name'],
                    intval($s['count'])
                );
                $r[] = $t;
            }
        }
        //$this->echoJson($r);
        return json($r);
    }
}