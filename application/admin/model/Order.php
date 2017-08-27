<?php
/**
 * Created by PhpStorm.
 * User: kelvi
 * Date: 2017/5/4
 * Time: 6:34
 */

namespace app\admin\model;

use think\Db;
use think\Model;

class Order extends Model

{

    /**
     * 获取包括商品列表的订单详情
     * @param <int> $id 订单id
     * @return mixed 成功时返回订单信息数组，失败返回false
     */
    public function GetOrderDetail($id) {
        if ($id > 0) {
            $product_model = new Products();
            //$orderData             = $this->Db->getOneRow("SELECT * FROM `orders` WHERE `order_id` = $id");
            $orderData = Db::name('orders')
                ->where('order_id', $id)
                ->find();
            //$orderData['address']  = $this->Db->getOneRow("SELECT * FROM `orders_address` WHERE order_id = $id;");
            $orderData['address'] = Db::name('orders_address')
                ->where('order_id', $id)
                ->find();
            //$orderData['products'] = $this->Db->query("SELECT detail_id, product_id, product_price_hash_id, product_count, product_discount_price, refunded FROM `orders_detail` where order_id = " . $orderData['order_id']);
            $orderData['products'] = Db::name('orders_detail')
                ->where('order_id', $orderData['order_id'])
                ->field('detail_id, product_id, product_discount_price, product_count, product_spec_id, refunded')
                ->select();
            foreach ($orderData['products'] as &$pds) {
                $pinfo = $product_model->getProductInfoWithSpec($pds['product_id'], $pds['product_spec_id']);
                $pds   = array_merge(array(
                    'phid' => $pds['product_spec_id'],
                    'product_count' => $pds['product_count'],
                    'product_discount_price' => $pds['product_discount_price'],
                    'refunded' => intval($pds['refunded']),
                    'detail_id' => intval($pds['detail_id'])
                ), $pinfo);
            }
            return $orderData;
        } else {
            return false;
        }
    }

    /**
     * 获取订单未退款金额
     * @param type $orderId 订单id
     * @return mixed
     */
    public function getUnRefunded($orderId) {
        if (is_numeric($orderId) && $orderId > 0) {
            //return $this->Dao->select('order_amount - order_refund_amount')
            //    ->from(TABLE_ORDERS)
            //    ->where("order_id = $orderId")
            //    ->getOne();
            return Db::name('orders')
                ->where('order_id', $orderId)
                ->field('order_amount - order_refund_amount')
                ->find();
        } else {
            return false;
        }
    }

    /**
     * 删除订单
     * @param int $orderId 要删除的订单ID
     * @return bool
     */
    public function deleteOrder($orderId) {
        if ($orderId > 0) {
            //$r1 = $this->Dao->delete()
            //    ->from(TABLE_ORDERS)
            //    ->where("order_id = $orderId")
            //    ->exec();
            $r1 = Db::name('orders')
                ->where('order_id', $orderId)
                ->delete();
            //$r2 = $this->Dao->delete()
            //    ->from(TABLE_ORDERS_DETAILS)
            //    ->where("order_id = $orderId")
             //   ->exec();
            $r2 = Db::name('orders_detail')
                ->where('order_id', $orderId)
                ->delete();
            return $r1 && $r2;
        } else {
            return false;
        }
    }
}