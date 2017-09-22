<?php
namespace app\index\controller;

use app\weshop\model\Products;
use think\Db;

class Index
{
    public function index()
    {
        //echo 'Hello world';
        //return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';


        // 测试页面
        $m = new Products();
        $product_model_admin = new \app\admin\model\Products();

        $pdLoaded = 0;
        $limit    = 10;
        // 获取所有系列
        $serials      = $product_model_admin->getSerials(false);

        dump($serials);

        $serialsCount = count($serials) - 1;
//
//            if (isset($Query->searchKey) && $Query->searchKey != '') {
//                $Query->searchKey = urldecode($Query->searchKey);
//            }

        foreach ($serials as $index => &$seri) {
            //$seri['s'] = $index == 0 && $page != 0;
            // 商品列表

            $where = [];
//            if ($in) {$where['in'] = $in; }
//            if ($searchkey) {$where['po.`product_name`'] = ['LIKE', "%%$searchkey%%"]; }

            $seri['pd'] = Db::name('products_info info')
                ->join('weshop_product_onsale onsale', 'info.product_id = onsale.product_id', 'LEFT')
                ->join('weshop_product_serials serial', 'serial.id = info.product_serial', 'LEFT')
                ->where('is_delete', '<>', 1)
                ->where('product_online', 1)
//                ->where(intval($cat) > 0 ? $children : '')
                ->where($where)
//                ->order($orderby)
//                ->limit($pdlists2, 1000)
                ->select();

            // 商品计数
            $seri['pdCount'] = count($seri['pd']);
            $pdLoaded += $seri['pdCount'];
            $limit -= $seri['pdCount'];
            if ($limit <= 0 || $index == $serialsCount) {
                cookie('pdlist-serial', $seri['sort']);
//                if ($seri['sort'] == $pdlists1) {
//                    cookie('pdlist-start', $pdlists2 + $seri['pdCount']);
//                } else {
//                    cookie('pdlist-start', $seri['pdCount']);
//                }
                $serials = array_slice($serials, 0, $index + 1);
                break;
            }
            $pdlists2 = 0;
        }
        dump($serials);
    }
}
