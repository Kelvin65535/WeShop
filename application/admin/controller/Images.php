<?php
/**
 * Created by PhpStorm.
 * User: kelvi
 * Date: 2017/5/9
 * Time: 16:16
 */

namespace app\admin\controller;


use think\Controller;
use think\File;
use think\Request;

/**
 * Class Images 图片处理控制器
 * @package app\admin
 */
class Images extends Controller
{
    /**
     * 图片上传器
     * TODO 目前只支持上传到本地，增加云支持
     * @return \think\response\Json
     */
    public function ImageUpload(){
        // 获取表单上传文件
        $files = request()->file();
        //dump($files);
        if (!$files){
            $return = [
                'ret_code' => -1,
                'ret_msg' => "文件读取失败"
            ];
            return json($return);
        }
        foreach ($files as $file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                $finalUrl = request()->domain() . '/public/uploads/' . $info->getSaveName();
                $return = [
                    'ret_code' => 0,
                    'ret_msg' => $finalUrl
                ];
                return json($return);
            } else {
                $return = [
                    'ret_code' => -1,
                    'ret_msg' => "上传文件失败"
                ];
                return json($return);
            }
        }
    }
}