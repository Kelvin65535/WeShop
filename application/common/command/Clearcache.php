<?php
/**
 * Created by PhpStorm.
 * User: kelvin
 * Date: 2017/9/21
 * Time: 下午11:42
 */

namespace app\common\command;

use think\Cache;
use think\console\Command;
use think\console\Input;
use think\console\Output;

/**
 * Class Clearcache 控制台辅助类：用于清除redis缓存
 * @package app\common\command
 */
class Clearcache extends Command
{
    protected function configure()
    {
        $this->setName('clearcache')
             ->setDescription('clear the redis cache');
    }

    protected function execute(Input $input, Output $output)
    {
        Cache::clear();
        $output->writeln("清除redis缓存成功");
    }
}