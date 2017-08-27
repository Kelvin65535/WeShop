<?php
/**
 * Created by PhpStorm.
 * User: kelvi
 * Date: 2017/5/4
 * Time: 6:29
 */

namespace app\admin\controller;

use app\admin\model\ProductCategory;
use app\admin\model\Products;
use app\common\model\Util;
use think\Controller;
use think\Db;
use think\Debug;
use think\Loader;
use think\Request;

/**
 * Class Order 订单控制器
 * @package app\admin\controller
 */
class Order extends Controller
{

    /**
     * @var array 订单状态对应列表
     */
    public $orderStatus = array(
        'unpay' => '未支付',
        'payed' => '已支付',
        'canceled' => '已取消',
        'received' => '已完成',
        'delivering' => '快递中',
        'closed' => '已关闭',
        'refunded' => '已退款',
        'reqing' => '代付'
    );

    /**
     * 订单导出
     * @return mixed
     */
    public function order_exports() {
        //获取GET变量
        //订单起始日期
        //$stime = $Q->stime;
        $stime = input('get.stime');
        //订单结束日期
        //$etime = $Q->etime;
        $etime = input('get.etime');
        //订单类型
        //$otype = $Q->otype;
        $otype = input('get.otype');

        if (!empty($stime) && !empty($etime)) {
            //快递列表
            $express = config('express_code.code');
            if (strtotime($stime) > strtotime($etime)) {
                $tmp   = $stime;
                $stime = $etime;
                $etime = $tmp;
            }

            $where = "order_time >= '$stime' AND order_time <= '$etime'";
            //选择订单类型
            if ($otype != '') {
                $where .= " AND status = '$otype'";
            }
            //从数据库获得订单信息
            //$orderList = $this->Dao->select('od.order_id,od.express_code,od.express_com,wepay_serial,od.serial_number,od.order_time,pd.product_id,pd.product_name,ods.product_count,ods.product_discount_price as product_price,od.order_expfee')
            //    ->from(TABLE_ORDERS_DETAILS)
             //   ->alias('ods')
            //    ->leftJoin(TABLE_ORDERS)
            //    ->alias('od')
            //    ->on("od.order_id = ods.order_id")
            //    ->leftJoin(TABLE_PRODUCTS)
            //    ->alias('pd')
            //    ->on("pd.product_id = ods.product_id")
            //    ->where($where)
            //    ->orderby('od.order_id')
            //    ->desc()
            //    ->exec();
            $orderList = Db::name('orders_detail')->alias('detail')
                ->join('weshop_orders orders', 'detail.order_id = orders.order_id', 'LEFT')
                ->join('weshop_products_info info', 'info.product_id = detail.product_id', 'LEFT')
                ->where($where)
                ->order('orders.order_id desc')
                ->field('orders.order_id,orders.express_code,orders.express_com,wepay_serial,orders.serial_number,orders.order_time,info.product_id,info.product_name,detail.product_count,detail.product_discount_price as product_price,detail.order_expfee')
                ->select();
            /**
             * 加工
             */
            foreach ($orderList as $index => $order) {
                // 订单地址
                //$address                      = $this->Db->query("SELECT * FROM `orders_address` WHERE order_id = $order[order_id];");
                $address = Db::name('orders_address')
                    ->where('order_id', $order['order_id'])
                    ->select();
                $orderList[$index]['address'] = $address[0];
                // 订单快递公司
                $orderList[$index]['expname'] = $express[$orderList[$index]['express_com']];
            }

            //导出至Excel文件
            include EXTEND_PATH . 'PHPExcel/Classes/PHPExcel.php';

            include EXTEND_PATH . 'PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

            include APP_PATH . 'PHPExcel/Classes/PHPExcel/Reader/Excel2007.php';

            //Loader::import('PHPExcel.Classes.PHPExcel');
            //Loader::import('PHPExcel.Classes.PHPExcel.Writer.Excel2007');
            //Loader::import('PHPExcel.Classes.PHPExcel.Writer.Excel2007');

            $templateName = ROOT_PATH . 'exports/orders_export/order_exp_sample/sample_1.xlsx';

            $PHPReader = new PHPExcel_Reader_Excel2007();

            if (!$PHPReader->canRead($templateName)) {
                $PHPReader = new \PHPExcel_Reader_Excel5();
                if (!$PHPReader->canRead($templateName)) {
                    echo '无法识别的Excel文件！';
                    return false;
                }
            }

            $PHPExcel = $PHPReader->load($templateName);

            header('Location: ' . $this->genXlsxFileType1($orderList, $PHPExcel, $PHPExcel->getActiveSheet(), 2));
        }
    }

    /**
     * @global array $config
     * @param array $data
     * @param object $PHPExcel
     * @param object $Sheet
     * @param int $offset
     * @param int $expType
     * @return null
     */
    private function genXlsxFileType1($data, $PHPExcel, $Sheet, $offset, $expType = 1) {
        //global $config;
        //Loader::import('PHPExcel.Classes.PHPExcel');
        //Loader::import('PHPExcel.Classes.PHPExcel.Writer.Excel2007');
        //Loader::import('PHPExcel.Classes.PHPExcel.Writer.Excel2007');

        $Sheet->getStyle('A1')
            ->getAlignment()
            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_BOTTOM);

        foreach ($data as $index => $da) {

            $Sheet->setCellValueExplicit("A$offset", $da['wepay_serial'], PHPExcel_Cell_DataType::TYPE_STRING);
            $Sheet->setCellValueExplicit("B$offset", $da['serial_number'], PHPExcel_Cell_DataType::TYPE_STRING);
            $Sheet->setCellValueExplicit("C$offset", $da['order_time'], PHPExcel_Cell_DataType::TYPE_STRING);
            $Sheet->setCellValueExplicit("D$offset", $da['address']['user_name'], PHPExcel_Cell_DataType::TYPE_STRING);
            $Sheet->setCellValueExplicit("E$offset", $da['address']['address'], PHPExcel_Cell_DataType::TYPE_STRING);
            $Sheet->setCellValueExplicit("F$offset", $da['address']['tel_number'], PHPExcel_Cell_DataType::TYPE_STRING);
            $Sheet->setCellValueExplicit("G$offset", $da['product_id'], PHPExcel_Cell_DataType::TYPE_STRING);
            $Sheet->setCellValueExplicit("H$offset", $da['product_name'], PHPExcel_Cell_DataType::TYPE_STRING);
            $Sheet->setCellValueExplicit("I$offset", $da['product_count'], PHPExcel_Cell_DataType::TYPE_STRING);
            $Sheet->setCellValueExplicit("J$offset", $da['product_price'], PHPExcel_Cell_DataType::TYPE_STRING);
            $Sheet->setCellValueExplicit("K$offset", $da['product_price'] * $da['product_count'], \PHPExcel_Cell_DataType::TYPE_STRING);
            $Sheet->setCellValueExplicit("L$offset", $da['order_expfee'], PHPExcel_Cell_DataType::TYPE_STRING);
            $Sheet->setCellValueExplicit("M$offset", $da['address']['postal_code'], \PHPExcel_Cell_DataType::TYPE_STRING);
            $Sheet->setCellValueExplicit("N$offset", $da['expname'], PHPExcel_Cell_DataType::TYPE_STRING);
            $Sheet->setCellValueExplicit("O$offset", $da['express_code'], PHPExcel_Cell_DataType::TYPE_STRING);

            $offset++;
        }
        // 写入文件
        $objWriter = new PHPExcel_Writer_Excel2007($PHPExcel);
        $fileName  = date('Y-md') . '-' . Util::convName($expType) . '-' . uniqid() . '.xlsx';
        $objWriter->save(ROOT_PATH . 'exports/orders_export/export_files/' . $fileName);
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://" . $_SERVER['HTTP_HOST'] . config('config.shoproot') . 'exports/orders_export/export_files/' . $fileName;
    }

    /**
     * 获取订单详情信息
     * 传入GET方法
     * GET变量：
     * id：要获取详情的订单id
     * @return mixed json打包的订单信息
     */
    public function getOrderInfo() {
        // 从GET变量中获取订单id
        $id = input('get.id');
        if ($id > 0) {
            //global $config;
            //$info                = $this->mOrder->GetOrderDetail($id, FALSE);
            $order_model = new \app\admin\model\Order();
            $info = $order_model->GetOrderDetail($id);
            //获取订单状态
            $info['statusX']     = $this->orderStatus[$info['status']];
            //获取快递名称
            //TODO 编辑快递信息
            //$info['expressName'] = mOrder::getExpressCompanyName($info['express_com']);
            $info['expressName'] = '';
            return json(['code' => 0, 'ret_msg' => $info]);
        } else {
            //$this->echoMsg(-1);
            return json(['code' => -1, 'ret_msg' => '获取订单信息错误']);
        }
    }

    /**
     * 获取订单列表
     */
    public function getOrderList() {

        $order_model = new \app\admin\model\Order();

        // 页码
        //$page = $this->pGet('page');
        $page = input('get.page');
        // 订单状态
        //$status = $this->pGet('status', 'all');
        if (input('?get.status')) {
            $status = input('get.status');
        } else {
            $status = 'all';
        }
        // 页数
        //$page_size = $this->pGet('page_size', 25);
        if (input('?get.page_size')) {
            $page_size = input('get.page_size');
        } else {
            $page_size = 25;
        }
        // 用户编号
        //$uid = $this->pGet('uid', 0);
        if (input('?get.uid')) {
            $uid = input('get.uid');
        } else {
            $uid = 0;
        }
        // 搜索字段
        //$serial_number = $this->pGet('serial_number', false);
        if (input('?get.serial_number')) {
            $serial_number = input('get.serial_number');
        } else {
            $serial_number = false;
        }

        //快递代码
        $express = config('express_code.code');

        //构造where查询条件
        $WHERE = ' order_id > 0 ';

        if ($status != 'all') {
            if ($status == 'canceled') {
                // 退货而且已经支付才需要审核，否则直接关闭订单
                $WHERE .= " AND status = '$status' AND wepay_serial <> '' ";
            } else {
                $WHERE .= " AND status = '$status' ";
            }
        }

        if ($uid > 0) {
            $WHERE .= " AND client_id = $uid ";
        }

        if ($serial_number) {
            $WHERE .= "AND `serial_number` LIKE '%$serial_number%' ";
        }

        // 如果商户id为0显示全部 有商户id则显示对应订单
        //$supplier_id = $_SESSION['supplier_id'];
        if (input('?session.supplier_id')){
            $supplier_id = input('?session.supplier_id');
        } else {
            $supplier_id = false;
        }

        if ($supplier_id) {
            $WHERE .= " AND `supplier_id` = $supplier_id ";
        } else {
            $WHERE .= " ";
        }

        $Limit = $page * $page_size . "," . $page_size;
        // 计算总数
        //$count = $this->Db->getOne("SELECT COUNT(order_id) FROM `orders` WHERE $WHERE;");
        $count = Db::name('orders')
            ->where($WHERE)
            ->count('order_id');
        // 订单列表
        //$orderList = $this->Dao->select()->from(TABLE_ORDERS)->where($WHERE)->orderby("order_id")->desc()->limit($Limit)->exec();
        $orderList = Db::name('orders')
            ->where($WHERE)
            ->order('order_id desc')
            ->limit($Limit)
            ->select();
        if ($status == 'canceled') {
            foreach ($orderList as &$od) {
                if ($od['order_amount'] < 1) {
                    $od['refundable'] = $od['order_amount'];
                } else {
                    $od['refundable'] = $order_model->getUnRefunded($od['order_id']);
                }
            }
        }

        /**
         * 加工
         */
        foreach ($orderList as $index => $order) {
            // address
            $orderList[$index]['address']     = Db::name('orders_address')
                ->where('order_id', $order['order_id'])
                ->field('user_name,tel_number,province,city')
                ->find();
                //$this->Db->getOneRow("SELECT user_name,tel_number,province,city FROM `orders_address` WHERE order_id = $order[order_id];");
            $orderList[$index]['order_time']  = Util::dateTimeFormat($orderList[$index]['order_time']);
                //$this->Util->dateTimeFormat($orderList[$index]['order_time']);
            $orderList[$index]['statusX']     = $this->orderStatus[$orderList[$index]['status']];
                //$config->orderStatus[$orderList[$index]['status']];
            //TODO 补充快递信息
            $orderList[$index]['expressName'] = '';
                //$express[$orderList[$index]['express_com']];
            // product info
            $orderList[$index]['data'] = Db::name('orders_detail') ->alias('detail')
                ->join('weshop_products_info info', 'info.product_id = detail.product_id', 'LEFT')
                ->where('detail.order_id', $order['order_id'])
                ->find();
                //$this->Db->getOneRow("SELECT catimg FROM `orders_detail` sd LEFT JOIN " . TABLE_PRODUCTS . " pi on pi.product_id = sd.product_id WHERE `sd`.order_id = " . $order['order_id']);
        }

        $data = array(
            'ret_code' => 0,
            'ret_msg' => array(
                'list' => $orderList,
                'count' => intval($count)
            )
        );

        $data = json_encode($data, JSON_UNESCAPED_UNICODE);

        //$this->echoJsonRaw($data);
        //return json(['code' => 0, 'ret_msg' => $data]);
        return $data;
    }

    /**
     * 获取快递公司列表
     * TODO 修改快递信息
     */
    public function getExpressCompanys() {
        //快递代码
        $express         = config('express_code.code');
        $expressFormated = [];
        $expressEs       = Db::name('settings')
            ->where('key', 'expcompany')
            ->field('value')
            ->find();
            //$this->Dao->select("value")
            //->from('wshop_settings')
            //->where("`key` = 'expcompany'")
            //->getOne();
        $expressEs       = explode(',', $expressEs['value']);
        foreach ($express as $k => &$od) {
            if (!in_array($k, $expressEs)) {
                unset($express[$k]);
            } else {
                $expressFormated[] = [
                    'code' => $k,
                    'name' => $od
                ];
            }
        }
        //$this->echoMsg(0, $expressFormated);
        return json(['code' => 0, 'ret_msg' => $expressFormated]);
    }

    /**
     * 删除订单
     */
    public function deleteOrder() {
        //$orderId = $this->pPost('order_id', false);
        //从POST变量获取要删除的订单ID
        if (input('?post.order_id')){
            $orderId = input('post.order_id');
        } else {
            $orderId = false;
        }

        if ($orderId > 0) {
            //$this->loadModel('mOrder');
            $order_model = new \app\admin\model\Order();
            if ($order_model->deleteOrder($orderId)) {
                return json(['code' => 0]);
            } else {
                return json(['code' => -1, 'ret_msg' => '删除失败']);
            }
        } else {
            //$this->echoMsg(-1, 'params error');
            return json(['code' => -1, 'ret_msg' => '参数错误']);
        }
    }
}