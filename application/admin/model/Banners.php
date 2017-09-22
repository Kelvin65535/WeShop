<?php
/**
 * Created by PhpStorm.
 * User: kelvin
 * Date: 2017/9/11
 * Time: 下午11:56
 */

namespace app\admin\model;

use think\Db;
use think\Model;

class Banners extends Model
{
    /**
     * 获取所有首页banner
     * @return type
     */
    public function getBanners($position = -1, $limit = 1000) {
        if ($position >= 0) {
            $banner = Db::name('banners')
                ->where('banner_position', $position)
                ->where('exp', 'exp', '> CURRENT_DATE()')
                ->whereOr('exp', null)
                ->whereOr('exp', '=', '')
                ->whereOr('exp', '=', '0000-00-00 00:00:00')
                ->order('sort desc')
                ->limit($limit)
                ->select();
        } else {
            $banner = Db::name('banners')
                ->order('sort desc')
                ->limit($limit)
                ->select();
        }
        foreach ($banner as &$b) {
            switch ($b['reltype']) {
                case 0:
                    $b['link'] = '/weshop/vProduct/view_list/cat=' . $b['relid'];
                    break;
                case 1:
                    if (strpos(',', $b['relid']) !== -1) {
                        $b['link'] = '?/vProduct/view_list/in=' . $b['relid'] . '&showwxpaytitle=1';
                    } else {
                        $b['link'] = '?/vProduct/view/id=' . $b['relid'];
                    }
                    break;
                case 2:
                    $b['link'] = '?/Gmess/view/id=' . $b['relid'];
                    break;
                case 3:
                    $b['link'] = $b['banner_href'];
            }
        }
        return $banner;
    }

    /**
     * 根据指定的id获取banner
     * @param type $id
     * @return
     */
    public function getOne($id) {
        return Db::name("banners")
            ->where('id', $id)
            ->find();
    }

    /**
     * banner 增删改
     * @param type $id
     * @param type $bname
     *
     * @param tinyiny $bposition = 0 首页顶部位置
     * @param tinyiny $bposition = 1 首页底部位置
     *
     * @param tinyint $reltype = 0 链接到某分类
     * @param tinyint $reltype = 1 链接到商品池
     * @param tinyint $reltype = 2 链接到图文
     *
     * @param type $bimg
     * @param type $relid
     */
    public function modiBanner($id, $bname = '', $bimg = '', $bposition = 0, $reltype = 0, $relid = false, $sort = 0, $href = '#', $exp = '') {
        $id = intval($id);
        if ($exp == '') {
            $exp = 'NULL';
        }
        if ($id > 0) {
            $oldData = $this->getOne($id);
            // 编辑
            $r = Db::name('banners')
                ->where('id', $id)
                ->update([
                    'banner_name' => $bname,
                    'banner_href' => $href,
                    'banner_image' => $bimg,
                    'banner_position' => $bposition,
                    'relid' => $relid,
                    'reltype' => $reltype,
                    'sort' => $sort,
                    'exp' => $exp
                ]);
            if ($r) {
                if ($oldData['banner_image'] != $bimg) {
                    // 删除旧图片
                    //@unlink(dirname(__FILE__) . "/../uploads/banner/" . $oldData['banner_image']);
                }
            }
            return $r;
        } else if ($id < 0) {
            // 删除
            $id = abs($id);
            return Db::name('banners')
                ->where('id', $id)
                ->delete();
        } else if ($id == 0 || !$id) {
            // 添加
            return Db::name('banners')
                ->insert([
                    'banner_name' => $bname,
                    'banner_href' => $href,
                    'banner_image' => $bimg,
                    'banner_position' => $bposition,
                    'relid' => $relid,
                    'reltype' => $reltype,
                    'sort' => $sort,
                    'exp' => $exp
                ]);
        } else {
            return false;
        }
    }
}