<?php
/**
 * Created by PhpStorm.
 * User: kelvi
 * Date: 2017/5/5
 * Time: 19:29
 */

namespace app\admin\controller;


use app\wechat\model\Wechatsdk;
use think\Controller;
use think\Db;

use think\Log;

class Settings extends Controller
{
    /**
     * 更新系统设置项
     * 直接replace
     */
    public function updateSettings() {
        $data = input('post.data/a');
        $result = 0;
        $time = date("Y-m-d H:i:s");    //修改设置时间
        if (is_array($data) && count($data) > 0) {
            foreach ($data as &$d) {
                $d['value'] = trim(str_replace("'", '"', $d['value']));
                $result += Db::name('settings')
                    ->where('key', $d['name'])
                    ->update(['value' => $d['value'], 'last_mod' => $time]);
            }
            if ($result) {
                return json(['ret_code' => 1]);
            } else {
                return json(['ret_code' => 0]);
            }

        } else {
            return json(['ret_code' => 0]);
        }
    }

    /**
     * 编辑首页导航
     */
    public function alterNavigation() {
        //从post变量中获取数据
        //导航id
        if (input("?post.id"))
            $id = input("post.id");
        else
            $id = false;
        //导航名称
        if (input("?post.nav_name"))
            $name = input("post.nav_name");
        else
            $name = false;
        //导航内容
        if (input("?post.nav_content"))
            $content = input("post.nav_content");
        else
            $content = false;
        //导航图标
        if (input("?post.nav_ico"))
            $ico = input("post.nav_ico");
        else
            $ico = false;
        //导航类型
        if (input("?post.nav_type"))
            $type = input("post.nav_type");
        else
            $type = false;
        //排序
        if (input("?post.sort"))
            $sort = input("post.sort");
        else
            $sort = false;

        if (!$sort || !is_numeric($sort)) {
            $sort = 0;
        }
        //if ($type == -1) {
        //    $type = 1;
        //}

        if ($id > 0) {
            if ($type == -1){
                $res = Db::name("settings_nav")
                    ->where("id", $id)
                    ->update(
                        ['nav_name' => $name, 'nav_ico' => $ico, 'nav_content' => $content, 'sort' => $sort]
                    );
                return json($res);
            }
            $res = Db::name("settings_nav")
                ->where("id", $id)
                ->update(
                    ['nav_name' => $name, 'nav_ico' => $ico, 'nav_type' => $type, 'nav_content' => $content, 'sort' => $sort]
                );
            return json($res);
        } else {
            $res = Db::name("settings_nav")
                ->insert([
                    'nav_name' => $name, 'nav_ico' => $ico, 'nav_type' => $type, 'nav_content' => $content, 'sort' => $sort
                ]);
            return json($res);
        }
    }
    /**
     * 删除首页导航
     */
    public function ajaxDeleteNavigation() {
        //要删除的导航id
        $id = input("post.id");
        $res = Db::name("settings_nav")
            ->where('id', $id)
            ->delete();
        return json($res);
    }

    /**
     * 获取微信自定义菜单
     */
    public function ajaxGetWechatMenu() {
        $menu = Wechatsdk::getMenu();
        $this->assign('menu', $menu);
        $this->assign('docroot', config('config.docroot'));
        //由于return会将fetch生成的模板代码通过json转义后返回，因此会加入下划线转义
        //使用echo方法保持fetch模板代码的原样输出
        echo $this->fetch('mainpage/settings/settings_menu_data');
    }
}