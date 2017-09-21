<?php
/**
 * Created by PhpStorm.
 * User: kelvi
 * Date: 2017/5/3
 * Time: 8:44
 */

namespace app\admin\model;

use app\common\model\Util;
use think\Db;
use think\Model;

/**
 * Class Gmess 素材管理控制器
 * @package app\admin\model
 */
class Gmess extends Model
{
    /**
     * 获取指定id的素材
     * @param type $id
     * @return
     */
    public function getGmess($id) {
        $result =  Db::name('gmess_page')
            ->where('id', $id)
            ->find();
        // 反转义斜杠字符
        if ($result) {
            $result['desc'] = stripslashes($result['desc']);
            $result['content'] = stripslashes($result['content']);
        }
        return $result ? $result : [];
    }

    /**
     * 获取素材列表
     * @global type $config
     * @return type
     */
    public function getGmessList($page = 0, $limit = 20, $keyword = '') {
        //$limit = sprintf("%s, %s", $page * $limit, $limit);
        //$where = '`deleted` = 0';
        if (!empty($keyword)) {
            $keyword = urldecode($keyword);
            $list = Db::name('gmess_page')
                ->where('deleted', '0')
                ->where('title', 'LIKE', "%$keyword%")
                ->whereOr('desc', 'LIKE', "%$keyword%")
                ->order('id desc')
                ->page($page, $limit)
                ->field("id,title,`desc`,catimg,createtime,media_id")
                ->select();
            //$where .= " AND (title LIKE '%$keyword%' OR `desc` LIKE '%$keyword%')";
            //echo $where;
        } else {
            $list = Db::name('gmess_page')
                ->where('deleted', '0')
                ->order('id desc')
                ->page($page, $limit)
                ->field("id,title,`desc`,catimg,create_time,media_id")
                ->select();
        }
        //$this->Db->cache = $cache;
        //$list            = $this->Db->query("SELECT id,title,`desc`,catimg,createtime,media_id FROM `gmess_page` WHERE $where ORDER BY `id` DESC LIMIT $limit;");
        $root            = Util::getROOT();
        foreach ($list as &$l) {
            $l['href'] = $root . "admin/Gmess/view/id/" . $l['id'];
            if (!stristr($l['catimg'], 'http') && !stristr($l['catimg'], 'iwshop')) {
                $l['catimg'] = $root . 'public/uploads/gmess/' . $l['catimg'];
            }
        }
        return $list;
    }

    /**
     * 获取素材总数
     * @return int
     */
    public function getGmessCount() {
        $count = Db::name('gmess_page')
            ->where('deleted', 0)
            ->count();
        return intval($count);
    }

    /**
     * 编辑素材内容
     * @param int $msgId 素材id，若id=0则表示新增id
     * @param type $title
     * @param type $content
     * @param type $desc
     * @param type $thumbMediaId
     * @param string $content_source_url 原文链接
     * @param type $category
     */
    public function alterGmess($msgId, $title, $content, $desc, $catImg, $thumbMediaId = '', $content_source_url, $media_id = 0) {
        $desc    = addslashes($desc);
        $content = addslashes($content);
        if ($msgId > 0) {
            // 修改素材
            return Db::name('gmess_page')
                ->where('id', $msgId)
                ->update(array(
                    'title' => $title,
                    'content' => $content,
                    'desc' => $desc,
                    'catimg' => $catImg,
                    'thumb_media_id' => $thumbMediaId,
                    'create_time' => date("Y-m-d"),
                    'media_id' => $media_id,
                    'content_source_url' => $content_source_url
                ));
//            return $this->Dao->update(TABLE_GMESS)
//                ->set(array(
//                    'title' => $title,
//                    'content' => $content,
//                    'desc' => $desc,
//                    'catimg' => $catImg,
//                    'thumb_media_id' => $thumbMediaId,
//                    'createtime' => date("Y-m-d"),
//                    'media_id' => $media_id,
//                    'content_source_url' => $content_source_url
//                ))->where('id', $msgId)->exec();
        } else {
            // 插入数据
            return Db::name('gmess_page')
                ->insert(array(
                    'title' => $title,
                    'content' => $content,
                    'desc' => $desc,
                    'catimg' => $catImg,
                    'thumb_media_id' => $thumbMediaId,
                    'create_time' => date("Y-m-d"),
                    'media_id' => $media_id,
                    'content_source_url' => $content_source_url
                ));
//            return $this->Dao->insert(TABLE_GMESS, explode(', ', 'title, content, desc, catimg, createtime, media_id, thumb_media_id, content_source_url'))
//                ->values([$title,
//                    $content,
//                    $desc,
//                    $catImg,
//                    date("Y-m-d"),
//                    $media_id,
//                    $thumbMediaId,
//                    $content_source_url])
//                ->exec();
        }
    }
}