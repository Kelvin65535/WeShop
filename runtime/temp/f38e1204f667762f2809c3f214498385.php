<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:58:"/var/www/html/application/weshop/view/usercenter/home.html";i:1488186269;s:54:"/var/www/html/application/weshop/view/global/meta.html";i:1485837662;s:60:"/var/www/html/application/weshop/view/global/nav_bottom.html";i:1486984813;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" id="viewport" name="viewport">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
    <title>嘉永商城-个人中心</title>
    <link href="<?php echo $docroot; ?>public/static/css/weshop_home.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $docroot; ?>public/static/css/weshop_public_bottom.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div id="wrap">
        <header>
            <!--头部-->
            <div id="header_background">
                <!--用户头像-->
                <div id="head_img">
                    <img src="<?php echo $userinfo['client_head']; ?>/132" width="100%" height="100%">
                </div>

                <!--用户昵称-->
                <div id="head_name">
                    <?php echo $userinfo['client_name']; ?>
                </div>
            </div>
        </header>

        <!--用户数据栏-->
        <div id="user_data">
            <!--用户积分数-->
            <div id="user_score" class="user_wrap">
                <div class="data_wrap">
                    <div class="data_box">
                        <div id="score_num" class="user_data">
                            <!--积分数   -->
                            0
                        </div>

                        <div id="score_text" class="user_text">
                            积分
                        </div>
                    </div>
                </div>
            </div>

            <!--用户优惠券数量-->
            <div id="user_discount" class="user_wrap">
                <div class="data_wrap">
                    <div class="data_box">
                        <div id="discount_num"  class="user_data">
                            <!--优惠券数-->
                            0
                        </div>

                        <div id="discount_text" class="user_text">
                            优惠券
                        </div>
                    </div>
                </div>
            </div>

            <!--用户收藏数-->
            <div id="user_collect" class="user_wrap">
                <div class="data_wrap">
                    <div class="data_box">
                        <div id="collect_num" class="user_data">
                            <!--收藏数-->
                            0
                        </div>

                        <div id="collect_text" class="user_text">
                            收藏
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--订单状态显示-->
        <div id="order_wrap">
            <!--我的订单-->
            <div id="myorder_wrap">
                <div id="myorder_text">
                    我的订单
                </div>

                <div id="myorder_checkall">
                    查看全部已购宝贝
                    <div id="myorder_checkall_img">
                        <img src="<?php echo $docroot; ?>public/static/images/weshop/icon_bar-right.png" width="100%" height="100%">
                    </div>
                </div>

            </div>

            <div id="state_wrap">
                <!--订单查询的五种操作-->
                <!--待付款-->
                <div class="state_wrap">
                    <div class="state_flex">
                        <div class="state_box">
                            <div id="state_prepay_img" class="state_img">
                                <img src="<?php echo $docroot; ?>public/static/images/weshop/prepay.svg" width="100%" height="100%">
                            </div>

                            <div id="state_prepay_text" class="state_text">
                                待付款
                            </div>
                        </div>
                    </div>
                </div>

                <!--待发货-->
                <div class="state_wrap">
                    <div class="state_flex">
                        <div class="state_box">
                            <div id="state_presend_img" class="state_img">
                                <img src="<?php echo $docroot; ?>public/static/images/weshop/presend.svg" width="100%" height="100%">
                            </div>

                            <div id="state_presend_text" class="state_text">
                                待发货
                            </div>
                        </div>
                    </div>
                </div>

                <!--待收货-->
                <div class="state_wrap">
                    <div class="state_flex">
                        <div class="state_box">
                            <div id="state_preget_img" class="state_img">
                                <img src="<?php echo $docroot; ?>public/static/images/weshop/preget.svg" width="100%" height="100%">
                            </div>

                            <div id="state_preget_text" class="state_text">
                                待收货
                            </div>
                        </div>
                    </div>
                </div>

                <!--待评价-->
                <div class="state_wrap">
                    <div class="state_flex">
                        <div class="state_box">
                            <div id="state_preevaluate_img" class="state_img">
                                <img src="<?php echo $docroot; ?>public/static/images/weshop/preevaluate.svg" width="100%" height="100%">
                            </div>

                            <div id="state_preevaluate_text" class="state_text">
                                待评价
                            </div>
                        </div>
                    </div>
                </div>

                <!--退款/售后-->
                <div class="state_wrap">
                    <div class="state_flex">
                        <div class="state_box">
                            <div id="state_back_img" class="state_img">
                                <img src="<?php echo $docroot; ?>public/static/images/weshop/back.svg" width="100%" height="100%">
                            </div>

                            <div id="state_back_text" class="state_text">
                                售后
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!--我的收藏-->
        <div id="collect_wrap">
            <div id="collect_first_img">
                <img src="<?php echo $docroot; ?>public/static/images/weshop/collect.svg" width="100%" height="100%">
            </div>

            <div id="collect_first_text">
                我的收藏
            </div>

            <div id="collect_second_text">
                我喜欢，我收藏
            </div>

            <div id="collect_second_img">
                <img src="<?php echo $docroot; ?>public/static/images/icon_bar-right.png" width="100%" height="100%">
            </div>
        </div>
    </div>

    <script language='javascript' src='http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js'> </script>
    <!--页面尾部-->
    <!--页面尾部-->
<footer>
    <!--尾部通用代码-->
    <!--首页-->
    <div class="nav_wrap" id="nav_index">
        <div class="nav_flex">
            <div class="nav_box">
                <div id="nav_index_img" class="nav_img">
                    <img src="<?php echo $docroot; ?>public/static/images/weshop/index<?php if($controller == 'Index'): ?>_in<?php endif; ?>.svg" width="100%" height="100%">
                </div>

                <div id="nav_index_text" class="nav_text">
                    首页
                </div>
            </div>
        </div>
    </div>

    <!--分类-->
    <div class="nav_wrap" id="nav_select">
        <div class="nav_flex">
            <div class="nav_box">
                <div id="nav_select_img" class="nav_img">
                    <img src="<?php echo $docroot; ?>public/static/images/weshop/select<?php if($controller == 'Product'): ?>_in<?php endif; ?>.svg" width="100%" height="100%">
                </div>

                <div id="nav_select_text" class="nav_text">
                    分类
                </div>
            </div>
        </div>
    </div>

    <!--购物车-->
    <div class="nav_wrap" id="nav_cart">
        <div class="nav_flex">
            <div class="nav_box">
                <div id="nav_cart_img" class="nav_img">
                    <img src="<?php echo $docroot; ?>public/static/images/weshop/cart<?php if($controller == 'Order'): ?>_in<?php endif; ?>.svg" width="100%" height="100%">
                </div>

                <div id="nav_cart_text" class="nav_text">
                    购物车
                </div>
            </div>
        </div>
    </div>

    <!--我的-->
    <div class="nav_wrap" id="nav_my">
        <div class="nav_flex">
            <div class="nav_box">
                <div id="nav_my_img" class="nav_img">
                    <img src="<?php echo $docroot; ?>public/static/images/weshop/my<?php if($controller == 'Usercenter'): ?>_in<?php endif; ?>.svg" width="100%" height="100%">
                </div>

                <div id="nav_my_text" class="nav_text">
                    我的
                </div>
            </div>
        </div>
    </div>
</footer>

<script type="text/javascript">
    $(function () {
        var file = ['index','select','cart','my'],
            path = ['../index/index.html','../product/category.html','../order/cart.html','../usercenter/home.html'];

        $("#nav_"+file[0]+"").bind('touchend',function () {
            self.location=path[0]
        });
        $("#nav_"+file[1]+"").bind('touchend',function () {
            self.location=path[1]
        });
        $("#nav_"+file[2]+"").bind('touchend',function () {
            self.location=path[2]
        });
        $("#nav_"+file[3]+"").bind('touchend',function () {
            self.location=path[3]
        });
    })
</script>


    <script>
        $(function () {
            //我的订单
            $("#myorder_wrap").bind('touchend',function () {
                self.location = "http://yejiayong.cn/weshop/Order/orderList";
            });
            //我的收藏
            $("#collect_wrap").bind('touchend', function () {
                self.location = "http://yejiayong.cn/weshop/Usercenter/productLikes";
            })
        })

    </script>
</body>
</html>