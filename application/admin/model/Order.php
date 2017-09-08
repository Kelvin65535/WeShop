<?php
/**
 * Created by PhpStorm.
 * User: kelvi
 * Date: 2017/5/4
 * Time: 6:34
 */

namespace app\admin\model;

use app\wechat\model\Messager;
use app\wechat\model\Messagetemplate;
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

    /**
     * 通过序列号获取订单信息
     * @param int $orderId
     * @return array
     */
    public function getOrderInfoBySerialNumber($serial_number) {
        return Db::name('orders')
            ->where('serial_number', $serial_number)
            ->find();
    }

    /**
     * 更新订单信息
     * @param string|array $data 需要更新的数据
     * @param string|array $where 查询条件，支持字符串／数组
     * @return int 是否更新成功，成功返回更新的条数，失败返回0
     */
    public function updateOrder($data, $where) {
        return Db::name('orders')
            ->where($where)
            ->update($data);
    }

    /**
     * 获取订单信息
     * @param int $orderId
     * @return array
     */
    public function getOrderInfo($orderId) {
        return Db::name('orders')
            ->where('order_id', $orderId)
            ->find();
    }

    /**
     * 获取订单列表，单表
     */
    public function GetOrderDetails($id) {
        if ($id > 0) {
            return Db::name('orders_detail')
                ->where('order_id', $id)
                ->select();
        } else {
            return false;
        }
    }

    /**
     * 减除库存
     * @param int $orderId 需要减除库存的订单id
     * @return bool 成功为true
     */
    public function cutInstock($orderId, $command = '-') {
        $orderDetail = $this->GetOrderDetails($orderId);
        foreach ($orderDetail as $dt) {
            if ($dt['product_spec_id'] > 0) {
                // 更新规格库存表
                Db::name('product_spec')
                    ->where('id', $dt['product_spec_id'])
                    ->where('product_id', $dt['product_id'])
                    ->update(['instock' => "instock $command {$dt["product_count"]}"]);
            } else {
                // 更新原始库存表
                Db::name('products_info')
                    ->where('product_id', $dt['product_id'])
                    ->update(['instock' => "instock $command {$dt["product_count"]}"]);
            }
        }
        return true;
    }

    /**
     * 用户订单付款通知 微信模板信息
     * @param int $orderId 需要发送的订单id
     * @param string $openid 需要发送的用户openid
     */
    public function userNewOrderNotify($orderId, $openid) {
        $tpl = MessageTemplate::getTpl('pay_success');
        if ($tpl && !empty($tpl['tpl_id'])) {
            // 获取订单信息
            $orderProducts = Db::name('orders_detail')->alias('detail')
                ->join('weshop_products_info info', 'info.product_id = detail.product_id', 'LEFT')
                ->where('detail.order_id', $orderId)
                ->field('info.product_name as name, product_count as count')
                ->select();
            $orderInfos    = array();
            $orderInfo     = $this->getOrderInfo($orderId);
            foreach ($orderProducts as $oi) {
                $orderInfos[] = $oi['name'] . '(' . $oi['count'] . ')';
            }
            $shopName = config('config.shopname');
            return Messager::sendTemplateMessage($tpl['tpl_id'], $openid, array(
                $tpl['first_key'] => '感谢您在' . $shopName . '购物',
                $tpl['serial_key'] => $orderInfo['serial_number'],
                $tpl['product_name_key'] => implode('、', $orderInfos),
                $tpl['product_count_key'] => $orderInfo['product_count'] . '件',
                $tpl['order_amount_key'] => '¥' . sprintf('%.2f', $orderInfo['order_amount']),
                $tpl['remark_key'] => '点击详情 随时查看订单状态'
            ), config('config.docroot') . "weshop/Order/expressDetail/order_id=$orderId");
        }
    }
}