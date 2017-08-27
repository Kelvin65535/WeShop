<?php

namespace app\weshop\controller;

use app\admin\model\Productspec;
use app\admin\model\Setting;
use app\admin\model\Statoverview;
use app\common\model\Util;
use app\weshop\model\Products;
use think\Controller;
use think\Log;
use think\Request;
use think\Db;

class Index extends Controller
{
    /**
     * 商铺的整体设置
     * 定义如下：
     * ->
     */
    public $settings;

    /**
     * 店铺首页
     */
    public function index() {

        $section_arr = array(
            "1" => array(
                "id" => "1",
                "name" => "section 1 : 这是第一个产品合集的标题",
                "product" => array(
                    '1' => array(
                        'id' => 1,
                        'url' => '第一个商品链接',
                        'name' => '第一个商品名字',
                        'price' => 100
                    ),
                    '2' => array(
                        'id' => 2,
                        'url' => '第二个商品链接',
                        'name' => '第二个商品名字',
                        'price' => 200
                    ),
                    '3' => array(
                        'id' => 3,
                        'url' => '第三个商品链接',
                        'name' => '第三个商品名字',
                        'price' => 300
                    ),
                    '4' => array(
                        'id' => 4,
                        'url' => '第四个商品链接',
                        'name' => '第四个商品名字',
                        'price' => 400
                    ),
                ),
            ),
            "2" => array(
                "id" => "2",
                "name" => "section 2 : 这是第二个产品合集的标题",
                "product" => array(
                    '1' => array(
                        'id' => 1,
                        'url' => '第一个商品链接',
                        'name' => '第一个商品名字',
                        'price' => 100
                    ),
                    '2' => array(
                        'id' => 2,
                        'url' => '第二个商品链接',
                        'name' => '第二个商品名字',
                        'price' => 200
                    ),
                    '3' => array(
                        'id' => 3,
                        'url' => '第三个商品链接',
                        'name' => '第三个商品名字',
                        'price' => 300
                    ),
                    '4' => array(
                        'id' => 4,
                        'url' => '第四个商品链接',
                        'name' => '第四个商品名字',
                        'price' => 400
                    ),
                ),
            ),
        );
        //首页导航板块
        $setting_model = new Setting();
        $nav = $setting_model->getNav();
        $this->assign('navigation',$nav);
        $this->assign('section', $section_arr);
        return $this->fetch();
    }


    public function wtf(){
        $product = new Products();
        $product_info = $product->getProductInfoById(1);
        dump($product_info);
        $this->assign('product', $product_info);
        return $this->fetch();
    }

    public function test2(){
        $s = input("post.test/a");
        Log::error(var_dump($s));
        foreach ($s as $item){
            Log::error("分组：");
            if (array_key_exists("cart_id", $item)){
                Log::error($item['cart_id']);
            }
            Log::error($item['product_id']);
            Log::error($item['spec_id']);
            Log::error($item['count']);
        }
        return json([$s]);
    }


    /**
     * TODO test
     */
    public function test(){
        $data = new Productspec();
        dump($data->getSpecData(1));
    }

    /**
     * 初始化函数
     * 新建本控制器实例时调用该方法
     * 加载全局模版变量
     */
    public function _initialize()
    {
        parent::_initialize();
        //获取店铺全局设置
        //从数据库获取店铺配置信息
        $query = Db::name('settings')
            ->select();
        //处理query
        $this->settings = array();
        foreach ($query as $item){
            $this->settings[$item['key']] = $item['value'];
        }
        //加载模版变量
        //全局商铺设置
        $this->assign('settings', $this->settings);
        //商铺根目录
        $this->assign('docroot', config('config.docroot'));
        $request = Request::instance();
        $this->assign('controller', $request->controller());   //控制器名称
    }


}
