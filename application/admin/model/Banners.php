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
//            $banner = $this->Dao->select()
//                ->from(TABLE_BANNERS)
//                ->where("banner_position=$position")
//                ->aw("(`exp` > CURRENT_DATE() OR (`exp` IS NULL OR `exp` = '' OR `exp` = '0000-00-00 00:00:00'))")
//                ->orderby('sort')
//                ->desc()
//                ->limit($limit)
//                ->exec();
        } else {
            $banner = Db::name('banners')
                ->order('sort desc')
                ->limit($limit)
                ->select();
//            $banner = $this->Dao->select()
//                ->from(TABLE_BANNERS)
//                ->orderby('sort')
//                ->desc()
//                ->limit($limit)
//                ->exec();
        }
        foreach ($banner as &$b) {
            switch ($b['reltype']) {
                case 0:
                    $b['link'] = '?/vProduct/view_list/cat=' . $b['relid'];
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
}