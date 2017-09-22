<?php
/**
 * Created by PhpStorm.
 * User: kelvi
 * Date: 2017/9/22
 * Time: 14:27
 */

namespace app\weshop\controller;


use app\weshop\model\User;
use think\Controller;
use think\Db;
use think\Request;

class Search extends Controller
{
    public $settings;

    /**
     * 记录用户的搜索记录并重定向到搜索结果展示页面
     */
    public function rd() {
        $user = new User();
        $search = new \app\weshop\model\Search();


        $href = urldecode(input('get.href'));
        $searchkey = urldecode(input('get.searchkey'));

        $openid = $user->getOpenId();
        $search->record($openid, $searchkey);
        $this->redirect($href);
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