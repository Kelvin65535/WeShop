<?php

namespace app\weshop\controller;

use app\weshop\model\User;
use think\Controller;
use think\Db;
use think\Request;
use think\sae\Log;
use think\Session;

/**
 * Class Cart 购物车资源控制器
 * @package app\weshop\controller
 */
class Cart extends Controller
{
    //用户的微信openID
    private $openID = null;

    /**
     * 获取用户的购物车数据
     */
    public function get(){

        $return = [];

        if (!empty($this->openID)) {
            //获取用户的uid
            $user = new User();
            $cart = new \app\weshop\model\Cart();
            $uid = $user->getUidByOpenId($this->openID);
            if ($uid > 0) {
                $return = $cart->getCartData($this->openID, $uid);
            }
        }

        return json(['ret_code' => 0, 'ret_msg' => $return]);

    }

    /**
     * 添加购物车数据
     * @param int $product_id
     * @param int $spec_id
     * @param int $count
     * @param int $fixed [default:false]
     */
    public function set() {
        //从post中获取以下信息
        //商品ID号
        $product_id = input('post.product_id');
        //商品规格ID
        $spec_id    = input('post.spec_id');
        //数量
        $count      = input('post.count');
        // 是否设置定值数量
        //$fixed = $this->post('fixed', false);
        $fixed      = input('post.fixed', false);
        if (!empty($this->openID) && $product_id > 0 && $spec_id >= 0 && $count > 0) {
            // 查找数据
            $cart_data = Db::name("client_cart")
                ->where('product_id', $product_id)
                ->where('spec_id', $spec_id)
                ->where('openid', $this->openID)
                ->select();
            try {
                if ($cart_data) {
                    // 定值Set
                    $set = $fixed ? ['count' => $count] : ['count' => ['exp', 'count + '.$count]];
                    Db::name("client_cart")
                        ->where("product_id", $product_id)
                        ->where("spec_id", $spec_id)
                        ->where("openid", $this->openID)
                        ->update($set);
                } else {
                    //数据库中没有数据，新建一行
                    $data = ['product_id' => $product_id, 'spec_id' => $spec_id, 'openid' => $this->openID];
                    Db::name("client_cart")
                        ->insert($data);
                }
                return json(['code' => 0, 'msg' => 'success']);
            } catch (\Exception $ex) {
                \think\Log::write($ex->getMessage());
                return json(['code' => -1, 'msg' => 'failed']);
            }
        } else {
            return json(['code' => -1, 'msg' => 'failed']);
        }
    }

    /**
     * 从购物车中删除
     * @param int $product_id
     * @param int $spec_id
     * @param int $count
     * @param int $all [default:false]
     */
    public function del() {
        //从post中获取以下信息
        //商品ID号
        $product_id = input('post.product_id');
        //商品规格ID
        $spec_id    = input('post.spec_id');
        //数量
        $count      = input('post.count');
        // 是否删除整个商品，而不是减少数量
        $all        = input('post.all', false);
        $cart_data = Db::name('client_cart')
            ->where('product_id', $product_id)
            ->where('spec_id', $spec_id)
            ->where('openid', $this->openID)
            ->select();
        if ($cart_data) {
            try {
                // 如果是普通删除
                if ($cart_data['count'] > $count && !$all) {
                    $set = ['count' => ['exp', 'count - '.$count]];
                    Db::name("client_cart")
                        ->where("product_id", $product_id)
                        ->where("spec_id", $spec_id)
                        ->where("openid", $this->openID)
                        ->update($set);
                } else {
                    // 溢出，全删
                    Db::name("client_cart")
                        ->where("product_id", $product_id)
                        ->where("spec_id", $spec_id)
                        ->where("openid", $this->openID)
                        ->delete();
                }
                return json(['code' => 0, 'msg' => 'success']);
            } catch (\Exception $ex) {
                \think\Log::write($ex->getMessage());
                return json(['code' => -1, 'msg' => 'failed']);
            }
        } else {
            return json(['code' => -1, 'msg' => 'failed']);
        }
    }

    /**
     * 清空购物车
     */
    public function clear() {
        try {
            Db::name("client_cart")
                ->where('openid', $this->openID)
                ->delete();
            return json(['code' => 0, 'msg' => 'success']);
        } catch (\Exception $ex) {
            \think\Log::write($ex->getMessage());
            return json(['code' => -1, 'msg' => 'failed']);
        }
    }

    /**
     * 获取购物车总数量
     */
    public function count() {
        if (!empty($this->openID)) {
            $count = Db::name("client_cart")
                    ->where("openid", $this->openID)
                    ->sum("count");
            echo $count;
        } else {
            echo 0;
        }
    }

    /**
     * 初始化函数
     */
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        //获取用户的openID
        $this->openID = Session::get('openid');
    }
}
