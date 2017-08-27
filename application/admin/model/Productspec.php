<?php
/**
 * Created by PhpStorm.
 * User: Kelvin
 * Date: 2017/5/3
 * Time: 5:50
 */

namespace app\admin\model;

use think\Db;
use think\Model;

/**
 * 商品规格处理模块
 * Class Productspec
 * @package app\admin\model
 */
class Productspec extends Model
{
    /**
     * 获取规格列表
     */
    public function getSpecList() {
        //$ret = $this->Db->query("SELECT * FROM `wshop_spec`;");
        $ret = Db::name('spec')
            ->select();
        foreach ($ret as &$spec) {
            $spec['dets'] = Db::name('spec_det')
                ->where('spec_id', $spec['id'])
                ->select();
            //$this->Db->query("SELECT * FROM `wshop_spec_det` WHERE `spec_id` = $spec[id];");
        }
        return $ret;
    }

    /**
     * 获取单个规格信息
     * @param int $id 要获取的规格ID
     * @return boolean
     */
    public function getSpecData($id) {
        if (!empty($id) && is_numeric($id)) {
            $ret = Db::name('spec')
                ->where('id', $id)
                ->find();
            if ($ret) {
                $ret['dets'] = Db::name('spec_det')
                    ->where('spec_id', $id)
                    ->select();
                return $ret;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function addSpec($id, $spec_name, $spec_remark, $dets) {

    }

    /**
     * 修改规格信息
     * @param type $id
     * @param type $spec_name
     * @param type $spec_remark
     * @param type $dets
     * @return boolean
     */
    public function alterSpec($id, $spec_name, $spec_remark, $dets, $append = false) {
        if ($id == '') {
            $id = '';
        } else {
            $id = intval($id);
            if ($id <= 0) {
                return false;
            }
        }

        if ($id == '') {
            // 查找数据库内是否有相同的规格记录
            $id = intval(Db::name('spec')
                ->where('spec_name', $spec_name)
                ->where('spec_remark', $spec_remark)
                ->field('id')
                ->find());
            // 如果找不到记录则新建一条规则记录
            if (!$id) {
                $id = Db::name('spec')
                    ->insertGetId(['spec_name' => $spec_name, 'spec_remark' => $spec_remark]);
            }
        } else {
            Db::name('spec')
                ->where('id', $id)
                ->update(['spec_name' => $spec_name, 'spec_remark' => $spec_remark]);
        }

        if ($id !== false) {
            $ids = array();
            if (!$append) {
                // 非追加模式，删除原有的详细规则记录并新建
                Db::name('spec_det')
                    ->where('spec_id', $id)
                    ->delete();
            }
            if ($dets != '') {
                foreach ($dets as &$det) {
                    if (empty($det['id'])) {
                        $det['id'] = 'NULL';
                    }
                    if ($det['name'] != '') {
                        array_push($ids, Db::name('spec_det')
                            ->insertGetId(['id' => $det['id'], 'spec_id' => $id, 'det_name' => $det['name'], 'det_sort' => $det['sort']]));
                    }
                }
            }
            return $id . '-' . implode('-', $ids);
        } else {
            return false;
        }
    }
}