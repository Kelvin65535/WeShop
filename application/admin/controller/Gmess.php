<?php
/**
 * Created by PhpStorm.
 * User: kelvin
 * Date: 2017/9/20
 * Time: 下午9:36
 */

namespace app\admin\controller;

use app\common\model\Util;
use app\wechat\model\Wechatsdk;
use think\Log;
use think\Request;
use think\Controller;
use think\Db;

class Gmess extends Controller
{

    /**
     * @var array 商铺整体设置
     */
    public $settings;

    /**
     * 获取素材列表
     * @example /?/wGmess/getGmessList/
     */
    public function getGmessList($key = '', $page = 0) {
        $gmess_model = new \app\admin\model\Gmess();
        $list    = $gmess_model->getGmessList($page, 20, $key);
        $count   = $gmess_model->getGmessCount();
        return json(['list' => $list, 'count' => $count]);
    }

    /**
     * 编辑素材
     * @param type $Query
     * @example /?/wGmess/gmess_edit/
     */
    public function gmess_edit($id = 0) {
        if (isset($id) && is_numeric($id) && $id > 0) {
            $id              = intval($id);
            $gmess_model = new \app\admin\model\Gmess();
            $gmess = $gmess_model->getGmess($id);
            $this->assign('g', $gmess);
        } else {
            $id = 0;
            $this->assign('g', false);
        }
        $this->assign('ed', $id > 0);
        return $this->fetch('mainpage/gmess/gmess_edit');
    }

    /**
     * 创建群发页面
     * @param type $Query
     * @example /?/wGmess/alterGmessPage/
     */
    public function alterGmessPage() {

        $gmess_model = new \app\admin\model\Gmess();

        // 是否为更新
        if (input('?post.msgid')) {
            $msgId = input('post.msgid/d');
        } else {
            $msgId = 0;
        }

        // 首图
        if (input('?post.catimg')) {
            $catimg = input('post.catimg');
        } else {
            $catimg = false;
        }

        // 描述
        if (input('?post.desc')) {
            $digest = input('post.desc');
        } else {
            $digest = false;
        }

        // 内容
        if (input('?post.content')) {
            $content = input('post.content');
        } else {
            $content = false;
        }

        // 标题
        if (input('?post.title')) {
            $title = input('post.title');
        } else {
            $title = false;
        }

        // 显示封面图片
        if (input('?post.show_cover_pic')) {
            $show_cover_pic = input('post.show_cover_pic/d');
        } else {
            $show_cover_pic = 1;
        }

        // 原文地址
        if (input('?post.content_source_url')) {
            $content_source_url = input('post.content_source_url');
        } else {
            $content_source_url = false;
        }

        // 上传数据到微信服务器
        $tData = $this->uploadData($catimg, $title, $content, $digest, $show_cover_pic);
        $ret = false;

        if ($tData) {
            // 微信素材缩略图id
            $thumbMediaId = $tData[0];
            // 微信素材id
            $mediaId = $tData[1];
            // 编辑素材内容
            $rst = $gmess_model->alterGmess($msgId, $title, $content, $digest, $catimg, $thumbMediaId, $content_source_url, $mediaId);
            if ($rst) {
                if ($msgId == 0) {
                    $ret['status'] = 1;
                    $ret['url']    = Util::getROOT() . "admin/Gmess/view/id/" . $rst;
                    $ret['msgid']  = $rst;
                } else {
                    $ret['status'] = 1;
                }
            } else {
                $ret['status'] = 0;
            }
        }

        return json($ret);

    }

    /**
     * 上传数据到腾讯
     * @param $catimg
     * @param $title
     * @param $content
     * @param $digest
     * @param $show_cover_pic
     * @example /?/wGmess/uploadData/
     */
    private function uploadData($catimg, $title, $content, $digest, $show_cover_pic) {
        // 上传数据到腾讯
        $file     = file_get_contents($catimg);
//        $filename = APP_PATH . '/tmp/' . time() . '.jpg';
        $filename = $catimg;
//        file_put_contents($filename, $file);

        $docroot = config('config.docroot');
        $filename=substr_replace($catimg,"",strpos($docroot,"ab"),strlen($docroot));
        Log::info("$filename");

        if (is_file($filename)) {
            // 上传首图
            $reSult = Wechatsdk::upLoadMedia($filename, 'image');
            // 删除临时文件
            unset($filename);
            if ($reSult && is_array($reSult)) {
                // 媒体编号
                $thumbMediaId = $reSult['media_id'];
                // 上传图文素材到腾讯服务器
                $ret = WechatSdk::upLoadGmess($thumbMediaId, $title, $content, $digest, $show_cover_pic);
                if ($ret && is_array($ret) && isset($ret['media_id'])) {
                    $mediaId = $ret['media_id'];
                    return [$thumbMediaId, $mediaId];
                } else {
                    Log::error("上传微信素材失败" . json_encode($ret));
                    return false;
                }
            } else {
                Log::error("上传微信素材失败" . json_encode($reSult));
                return false;
            }
        } else {
            Log::error("上传微信素材失败, 图片不存在, 或者/tmp/不可写");
            return false;
        }
    }

    /**
     * 发送图文群发
     * @example /?/wGmess/sendGemss/
     */
    public function sendGemss() {
        if (input('?post.mediaid')) {
            $mediaId = input('post.mediaid/s');
        } else {
            $mediaId = '';
        }
        if (!empty($mediaId)) {
            // 请求微信服务器 发送群发
            $result = Wechatsdk::sendGmessAll($mediaId, true);
            $result = json_decode($result);
            dump($result, true);
            if ($result) {
                if ($result->errcode == 0) {
                    // 发送成功
                    Log::info("消息群发成功 $mediaId");
                    return json(['ret_code' => 0]);
                    // @todo 记录发送任务的ID
                } else {
                    Log::error("消息群发失败" . json_encode($result));
                    return json(['ret_code' => -1, 'ret_msg' => "发送失败, 系统错误"]);
                }
            } else {
                return json(['ret_code' => -1, 'ret_msg' => "发送失败, 系统错误"]);
            }
        } else {
            return json(['ret_code' => -1, 'ret_msg' => "发送失败, mediaId有误"]);
        }
    }

    /**
     * 【云搜索】
     * 获取微信服务器上的公众号素材分类列表
     * @see https://www.showapi.com/api/lookPoint/582
     */
    public function getCloudCategorys() {
        $apiId = config('config.showAPIAppID');
        $apiKey = config('config.showAPISecret');
        $validateData = ['showapi_appid' => $apiId, 'showapi_sign' => $apiKey];
        $ret = json_decode(\curl\Curl::post("http://route.showapi.com/582-1", $validateData));
        $cats = $ret->showapi_res_body->typeList;
        foreach ($cats as $cat) {
            $cat->id = intval($cat->id);
        }
        $cats = array_reverse($cats);
        return json($cats);
    }

    /**
     * 【云搜索】
     * 获取微信服务器上的公众号素材列表
     * @param type $id
     * @param type $keyword
     * @param type $page
     * @see https://www.showapi.com/api/lookPoint/582
     */
    public function getCloudList() {
        if (input('?post.id')) {
            $id = input('post.id');
        } else {
            $id = 0;
        }
        if (input('?post.key')) {
            $keyword = input('post.key');
        } else {
            $keyword = false;
        }
        if (input('?post.page')) {
            $page = intval(input('post.page/d')) + 1;
        } else {
            $page = 0;
        }
        if (!empty($keyword)) {
            $keyword = urlencode($keyword);
        }
        $apiId = config('config.showAPIAppID');
        $apiKey = config('config.showAPISecret');
        $validateData = ['showapi_appid' => $apiId, 'showapi_sign' => $apiKey];
        $ret = json_decode(\curl\Curl::post("http://route.showapi.com/582-2?typeId=$id&key=$keyword&page=$page", $validateData));
        return json($ret->showapi_res_body->pagebean);
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