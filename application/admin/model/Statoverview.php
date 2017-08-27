<?php
/**
 * Created by PhpStorm.
 * User: kelvi
 * Date: 2017/5/5
 * Time: 22:00
 */


namespace app\admin\model;

use think\Db;
use think\Model;

/**
 * Class Statoverview 统计数据模型
 * @package app\admin\model
 */
class Statoverview extends Model
{
    /**
     * 获取微信商城的整体统计数据
     * @return array
     */
    public function getOverViewDatas() {
        $data = array();

        $QueryMonth    = date('Y-m');
        $QueryDay      = date('Y-m-d');
        $QueryYesterday = date('Y-m-d', strtotime('-1 day'));

        // TODO 新增粉丝
        $data['newfans'] = (int)0;
        //$data['newfans'] = (int)$this->Db->getOne("SELECT SUM(dv) AS `sc` FROM `wechat_subscribe_record` WHERE DATE_FORMAT(`date`,'%Y-%m-%d') = '$QueryDay' AND `dv` > 0 GROUP BY DATE_FORMAT(`date`,'%Y-%m-%d');");
        // TODO 取消关注粉丝
        //$data['runfans'] = abs((int)$this->Db->getOne("SELECT SUM(dv) AS `sc` FROM `wechat_subscribe_record` WHERE DATE_FORMAT(`date`,'%Y-%m-%d') = '$QueryDay' AND `dv` < 0 GROUP BY DATE_FORMAT(`date`,'%Y-%m-%d');"));
        $data['runfans'] = 0;
        // TODO 总粉丝
        //$data['allfans'] = (int)$this->Db->getOne("SELECT SUM(dv) AS `sc` FROM `wechat_subscribe_record`;");
        $data['allfans'] = 0;
        // 新增会员
        //$data['newuser'] = (int)$this->Db->getOne("SELECT COUNT(*) AS `sc` FROM `clients` WHERE DATE_FORMAT(`client_joindate`,'%Y-%m-%d') = '$QueryDay' AND `deleted` = 0;");
        $data['newuser'] = (int)Db::name('clients')
            ->whereTime('client_joindate', '>=', $QueryDay)
            ->where('deleted', 0)
            ->count();
        // TODO 新增代理
        //$data['newcoms'] = (int)$this->Db->getOne("SELECT COUNT(*) AS `sc` FROM `companys` WHERE DATE_FORMAT(`join_date`,'%Y-%m-%d') = '$QueryDay' AND `deleted` = 0 AND `verifed` = 1;");
        $data['newcoms'] = 0;
        // 总会员
        //$data['alluser'] = (int)$this->Db->getOne("SELECT COUNT(*) AS `sc` FROM `clients` WHERE `deleted` = 0;");
        $data['alluser'] = (int)Db::name('clients')
            ->where('deleted', 0)
            ->count();
        // TODO 总代理
        //$data['allcoms'] = (int)$this->Db->getOne("SELECT COUNT(*) AS `sc` FROM `companys` WHERE `deleted` = 0 AND `verifed` = 1;");
        $data['allcoms'] = (int)0;
        // 今日成交
        //$data['saletoday'] = (float)$this->Db->getOne("SELECT SUM(order_amount) AS `sc` FROM `orders`
        // WHERE DATE_FORMAT(`order_time`,'%Y-%m-%d') = '$QueryDay' AND `wepay_serial` <> '' AND `status` <> 'refunded';");
        $data['saletoday'] = (float)Db::name('orders')
            ->where('wepay_serial', '<>', '')
            ->where('status', '<>', 'refunded')
            ->whereTime('order_time', 'today')
            ->sum('order_amount');
        // 昨日成交
        //$data['saleyestoday'] = (float)$this->Db->getOne("SELECT SUM(order_amount) AS `sc` FROM `orders` WHERE
        // DATE_FORMAT(`order_time`,'%Y-%m-%d') = '$QueryYesterday' AND `wepay_serial` <> '' AND `status` <> 'refunded';");
        $data['saleyestoday'] = (float)Db::name('orders')
            ->where('wepay_serial', '<>', '')
            ->where('status', '<>', 'refunded')
            ->whereTime('order_time', 'yesterday')
            ->sum('order_amount');
        // 本月成交
        //$data['salemonth'] = (float)$this->Db->getOne("SELECT SUM(order_amount) AS `sc` FROM `orders`
        // WHERE DATE_FORMAT(`order_time`,'%Y-%m') = '$QueryMonth' AND `wepay_serial` <> '' AND `status` <> 'refunded';");
        $data['salemonth'] = (float)Db::name('orders')
            ->whereTime('order_time', 'month')
            ->where('wepay_serial', '<>', '')
            ->where('status', '<>', 'refunded')
            ->sum('order_amount');
        // 总成交
        //$data['saletotal'] = (float)$this->Db->getOne("SELECT SUM(order_amount) AS `sc` FROM `orders`
        // WHERE `wepay_serial` <> '' AND `status` <> 'refunded';");
        $data['saletotal'] = (float)Db::name('orders')
            ->where('wepay_serial', '<>', '')
            ->where('status', '<>', 'refunded')
            ->sum('order_amount');
        // TODO 代理今日成交
        //$data['pxsaletoday'] = (float)$this->Db->getOne("SELECT SUM(order_amount) AS `sc` FROM `orders` WHERE DATE_FORMAT(`order_time`,'%Y-%m-%d') = '$QueryDay' AND `wepay_serial` <> '' AND `company_id` <> 0 AND `status` <> 'refunded';");
        $data['pxsaletoday'] = (float)0;
        // TODO 代理昨日成交
        //$data['pxsaleyestoday'] = (float)$this->Db->getOne("SELECT SUM(order_amount) AS `sc` FROM `orders` WHERE DATE_FORMAT(`order_time`,'%Y-%m-%d') = '$QueryYesterday' AND `wepay_serial` <> '' AND `company_id` <> 0 AND `status` <> 'refunded';");
        $data['pxsaleyestoday'] = (float)0;
        // TODO 代理本月成交
        //$data['pxsalemonth'] = (float)$this->Db->getOne("SELECT SUM(order_amount) AS `sc` FROM `orders` WHERE DATE_FORMAT(`order_time`,'%Y-%m') = '$QueryMonth' AND `wepay_serial` <> '' AND `company_id` <> 0 AND `status` <> 'refunded';");
        $data['pxsalemonth'] = (float)0;
        // TODO 代理总成交
        //$data['pxsaletotal'] = (float)$this->Db->getOne("SELECT SUM(order_amount) AS `sc` FROM `orders` WHERE `wepay_serial` <> '' AND `company_id` <> 0 AND `status` <> 'refunded';");
        $data['pxsaletotal'] = (float)0;
        // 今日新增订单
        //$data['neworder'] = (int)$this->Db->getOne("SELECT COUNT(*) AS `sc` FROM `orders` WHERE DATE_FORMAT(`order_time`,'%Y-%m-%d') = '$QueryDay';");
        $data['neworder'] = (int)Db::name('orders')
            ->whereTime('order_time', 'today')
            ->count();
        // 本月新增订单
        //$data['neworder_month'] = (int)$this->Db->getOne("SELECT COUNT(*) AS `sc` FROM `orders` WHERE DATE_FORMAT(`order_time`,'%Y-%m') = '$QueryMonth';");
        $data['neworder_month'] = (int)Db::name('orders')
            ->whereTime('order_time', 'month')
            ->count();
        // 本月已付款或已收货或快递中
        //$data['valorder_month'] = (int)$this->Db->getOne("SELECT COUNT(*) AS `sc` FROM `orders` WHERE
        // DATE_FORMAT(`order_time`,'%Y-%m') = '$QueryMonth' AND `status` <> 'canceled' AND `status` <> 'closed';");
        $data['valorder_month'] = (int)Db::name('orders')
            ->where('status', '<>', 'canceled')
            ->where('status', '<>', 'closed')
            ->whereTime('order_time', 'month')
            ->count();
        // 昨日新增订单
        //$data['neworderyes'] = (int)$this->Db->getOne("SELECT COUNT(*) AS `sc` FROM `orders`
        // WHERE DATE_FORMAT(`order_time`,'%Y-%m-%d') = '$QueryYesterday';");
        $data['neworderyes'] = (int)Db::name('orders')
            ->whereTime('order_time', 'yesterday')
            ->count();
        // 今日新增订单 已付款
        //$data['neworderpayed'] = (int)$this->Db->getOne("SELECT COUNT(*) AS `sc` FROM `orders` WHERE
        // DATE_FORMAT(`order_time`,'%Y-%m-%d') = '$QueryDay' AND `wepay_serial` <> '';");
        $data['neworderpayed'] = (int)Db::name('orders')
            ->where('wepay_serial', '<>', '')
            ->whereTime('order_time', 'today')
            ->count();
        // 昨日新增订单 已付款
        //$data['neworderpayedyes'] = (int)$this->Db->getOne("SELECT COUNT(*) AS `sc` FROM `orders`
        // WHERE DATE_FORMAT(`order_time`,'%Y-%m-%d') = '$QueryYesterday' AND `wepay_serial` <> '';");
        $data['neworderpayedyes'] = (int)Db::name('orders')
            ->where('wepay_serial', '<>', '')
            ->whereTime('order_time', 'yesterday')
            ->count();
        // 订单已付款
        //$data['orderpayed'] = (int)$this->Db->getOne("SELECT COUNT(*) AS `sc` FROM `orders` WHERE `status` = 'payed';");
        $data['orderpayed'] = (int)Db::name('orders')
            ->where('status', 'payed')
            ->count();
        // 订单已发货
        //$data['orderexped'] = (int)$this->Db->getOne("SELECT COUNT(*) AS `sc` FROM `orders` WHERE `status` = 'delivering';");
        $data['orderexped'] = (int)Db::name('orders')
            ->where('status', 'delivering')
            ->count();
        // 订单退货申请
        //$data['ordercanceled'] = (int)$this->Db->getOne("SELECT COUNT(*) AS `sc` FROM `orders` WHERE `status` = 'canceled' AND `wepay_serial` <> '';");
        $data['ordercanceled'] = (int)Db::name('orders')
            ->where('status', 'canceled')
            ->where('wepay_serial', '<>', '')
            ->count();
        // 本月订单
        //$data['ordermonth'] = (int)$this->Db->getOne("SELECT COUNT(*) AS `sc` FROM `orders` WHERE DATE_FORMAT(`order_time`,'%Y-%m') = '$QueryMonth';");
        $data['ordermonth'] = (int)Db::name('orders')
            ->whereTime('order_time', 'month')
            ->count();
        // 商品分类总数
        //$data['catotal'] = (int)$this->Db->getOne("SELECT COUNT(*) AS `sc` FROM `product_category`;");
        $data['catotal'] = (int)Db::name('product_category')
            ->count();
        // 商品总数
        //$data['pdtotal'] = (int)$this->Db->getOne("SELECT COUNT(*) AS `sc` FROM `products_info` WHERE `is_delete` = 0;");
        $data['pdtotal'] = (int)Db::name('products_info')
            ->where('is_delete', 0)
            ->count();
        // 平均商品浏览
        //$data['pdtotalavg'] = (int)$this->Db->getOne("SELECT AVG(`product_readi`) AS `sc` FROM `products_info` WHERE `is_delete` = 0;");
        $data['pdtotalavg'] = (int)Db::name('products_info')
            ->where('is_delete', 0)
            ->avg('product_readi');
        // 商品平均价格
        //$data['pdpriceavg'] = sprintf('%.2f', $this->Db->getOne("SELECT AVG(`sale_prices`) AS `sc` FROM `product_onsale` `pos`
        // LEFT JOIN `products_info` `pi` ON `pi`.product_id = `pos`.product_id WHERE pi.`is_delete` = 0"));
        $data['pdpriceavg'] = sprintf('%.2f', (int)Db::name('product_onsale')->alias('onsale')
            ->join('weshop_products_info info', 'info.product_id = onsale.product_id', 'LEFT')
            ->where('is_delete', 0)
            ->avg('sale_prices')
        );

        return $data;
    }
}