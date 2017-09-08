<?php
/**
 * Created by PhpStorm.
 * User: kelvin
 * Date: 2017/9/8
 * Time: 上午11:20
 */

namespace app\wechat\model;

use think\Model;
use think\Log;

/**
 * 模板消息管理类
 */
class Messagetemplate extends Model{

    /**
     * 模板参数配置数据
     * @var array
     */
    static $tplConfig = false;

    /**
     * 获取模板消息参数
     * @param $tplname
     */
    public static function getTpl($tplname) {
        $configFile = config('message_template');

        if ($configFile) {
            if (!self::$tplConfig) {
                self::$tplConfig = $configFile;
            }
            if (isset(self::$tplConfig[$tplname]) && !empty(self::$tplConfig[$tplname]['tpl_id'])) {
                return self::$tplConfig[$tplname];
            } else {
                return false;
            }
        } else {
            Log::error("无法获取模板消息参数，微信发送消息模版配置文件不存在");
            return false;
        }
    }

}