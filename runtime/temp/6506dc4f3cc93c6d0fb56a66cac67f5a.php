<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:57:"/var/www/html/application/weshop/view/product/detail.html";i:1488185538;s:54:"/var/www/html/application/weshop/view/global/meta.html";i:1485837662;}*/ ?>
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
    <title>商品详情</title>
    <link href="<?php echo $docroot; ?>public/static/css/weshop_detail.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $docroot; ?>public/static/css/swiper-3.4.1.min.css" rel="stylesheet" type="text/css">
    <style>
        .loading{
            position: fixed;
            width: 100%;
            height: 100%;
            background-color: white;
        }
    </style>
</head>
<body>

    <!--loading效果-->
    <div class="loading">
        页面加载中....
    </div>
    <!--选择尺寸套餐展示区域-->
    <div class="select_tab_wrap">
        <div class="select_tab">
        </div>

        <div class="select_main">
            <div class="select_cancel">
                <img src="<?php echo $docroot; ?>public/static/images/weshop/cha.svg" width="100%" height="100%">
            </div>
            <div class="select_header">
                <div class="select_header_img">
                    <img src="#" width="100%" height="100%">
                </div>

                <div class="select_header_data">
                    <div class="select_header_data_price">
                        ￥<?php echo $product['sale_prices']; ?>
                    </div>

                    <div class="select_header_data_repertory">
                        库存量：
                    </div>

                </div>
            </div>

            <div class="select_mainselect">
                <div class="select_mainselect_wrap">
                    <div class="select_mainselect_text">尺寸 ：</div>
                    <!--
                    <div class="select_div">
                        M
                    </div>

                    <div class="select_div">
                        L
                    </div>
                    -->
                    <?php if(is_array($product['specs']) || $product['specs'] instanceof \think\Collection): $i = 0; $__LIST__ = $product['specs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <div class="select_div">
                            <?php echo $item['name1']; ?>
                        </div>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>

                <div class="select_mainselect_wrap">
                    <div class="select_mainselect_text">套餐：</div>
                    <!--
                    <div class="select_div">
                       套餐一
                    </div>
                    <div class="select_div">
                        套餐二
                    </div>
                    <div class="select_div">
                        套餐三
                    </div>
                    -->
                    <?php if(is_array($product['specs']) || $product['specs'] instanceof \think\Collection): $i = 0; $__LIST__ = $product['specs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                    <div class="select_div">
                        <?php echo $item['name2']; ?>
                    </div>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>

            <div class="select_num">
                <p>购买数量 ：</p>
                <div class="select_num_wrap">
                    <div class="select_num_minus">
                        -
                    </div>

                    <div class="select_num_num" data-num="1">
                        1
                    </div>

                    <div class="select_num_add">
                        +
                    </div>

                </div>
            </div>

            <div class="select_footer_wrap">
                <div class="select_footer_addcart">
                    加入购物车
                </div>

                <div class="select_footer_buynow">
                    立即购买
                </div>
            </div>
        </div>
    </div>
    <!--页面返回按钮-->
    <div class="page_back">
        <img src="<?php echo $docroot; ?>public/static/images/weshop/pageback.svg" width="100%" height="100%">
    </div>

    <!--展示图片左右滑动区域-->
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <!--
            <div class="swiper-slide red">Slide 1</div>
            <div class="swiper-slide blue">Slide 2</div>
            <div class="swiper-slide yellow">Slide 3</div>
            -->
            <!-- 循环加载图片 -->
            <?php if(is_array($product['images']) || $product['images'] instanceof \think\Collection): $i = 0; $__LIST__ = $product['images'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                <div class="swiper-slide">
                    <img src="<?php echo $item['image_path']; ?>" width="100%" height="100%">
                </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    <!-- 如果需要分页器 -->
        <div class="swiper-pagination"></div>

    </div>

    <!--商品主要信息展示区-->
    <div class="information_wrap">
        <div class="information_name">
            <p><?php echo $product['product_name']; ?></p>
        </div>

        <div class="information_price">
            <p>￥<?php echo $product['sale_prices']; ?></p>
        </div>

        <div class="information_extra">
            <span id="information_extra_express">快递:￥<?php echo $product['product_expfee']; ?></span>
            <span id="information_extra_month">月销量:<?php echo $product['product_weight']; ?></span>
            <span id="information_extra_repertory">库存量:<?php echo $product['market_price']; ?></span>
        </div>
    </div>

    <!--选择尺寸套餐类型-->
    <div class="select_wrap">
        <div class="select_text">
            选择 尺寸 套餐 类型
        </div>

        <div class="select_img">
            <img src="<?php echo $docroot; ?>public/static/images/weshop/icon_bar-right.png" width="100%" height="100%">
        </div>
    </div>

    <!--宝贝评价-->
    <div class="product_evaluate_wrap">
        <div class="product_evaluate_header">
            宝贝评价（1111）
        </div>

        <div class="last_product_evaluate">
            <div class="evaluate_header">
                <img src="<?php echo $docroot; ?>public/static/images/weshop/face_normal.svg" width="100%">
                <p>叶嘉永</p>
            </div>

            <div class="evaluate_text">
                商品是正品哦！
            </div>

            <div class="evaluate_information">
                <span>套餐类型:官方标配;</span>
                <span>颜色分类:白色比卡丘</span>
            </div>
        </div>

        <div class="product_evaluate_button_wrap">
            <div class="product_evaluate_button">
                查看全部评价
            </div>
        </div>

    </div>

    <!--宝贝详情-->
    <div class="product_introduce_wrap">
        <div class="product_introduce_header">
            <div class="product_introduce_select introduce_select" id="product_select_introduce">
                商品详情
            </div>

            <div class="product_introduce_select" id="product_select_data">
                产品参数
            </div>
        </div>

        <div class="product">
            <!-- 商品介绍 -->
            <p>这里是商品介绍</p>
            <?php echo $product['product_desc']; ?>
        </div>

        <div class="product_data">
            <!--产品参数-->
            <p>这里是产品参数</p>
        </div>

    </div>

    <footer>
        <div class="footer footer_cart">
            <div class="flex_wrap">
                <div class="cart_collect_wrap">
                    <img id="footer_cart_wrap" src="<?php echo $docroot; ?>public/static/images/weshop/cart.svg" width="100%">
                    <p>购物车</p>
                </div>
            </div>
        </div>

        <div class="footer footer_collect">
            <div class="flex_wrap">
                <div class="cart_collect_wrap">
                    <img src="<?php echo $docroot; ?>public/static/images/weshop/love.svg" width="100%">
                    <p>收藏</p>
                </div>
            </div>
        </div>

        <div class="footer footer_right" id="footer_joincart">
            加入购物车
        </div>

        <div class="footer footer_right" id="footer_buy">
            立即购买
        </div>

    </footer>
<!--    <p>您选择的商品ID号为：<?php echo $product['product_id']; ?></p>
    <p>商品名称：<?php echo $product['product_name']; ?></p>
    <p>商品简称：<?php echo $product['product_subname']; ?></p>
    <p>============商品信息==============</p>
    <p>商品简介：<?php echo $product['product_subtitle']; ?></p>
    <p>商品大小：<?php echo $product['product_cat']; ?></p>
    <p>商品重量：<?php echo $product['product_weight']; ?></p>
    <p>商品产地：<?php echo $product['product_origin']; ?></p>
    <p>商品储存条件：<?php echo $product['product_storage']; ?></p>
    <p>商品单位：<?php echo $product['product_unit']; ?></p>
    <p>商品快递费用：<?php echo $product['product_expfee']; ?></p>
    <p>商品分类搜索索引：<?php echo $product['product_indexes']; ?></p>
    <p>============商品价格==============</p>
    <p>商品市场价：<?php echo $product['market_price']; ?></p>
    <p>商品零售价：<?php echo $product['sale_prices']; ?></p>
    <p>商品折扣比例：<?php echo $product['product_prom_discount']; ?> 折</p>
    <p>商品秒杀价 = 零售价 * 折扣比例：<?php echo ($product['sale_prices'] * ($product['product_prom_discount'] / 100)); ?></p>
    <p>============商家信息==============</p>
    <p>TODO</p>
    <p>============商品图片==============</p>
    <?php if(is_array($product['images']) || $product['images'] instanceof \think\Collection): $i = 0; $__LIST__ = $product['images'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
        <p>第 <?php echo $item['image_id']; ?> 张图片路径：</p>
        <p><?php echo $item['image_path']; ?></p>
        <p>图片如下：</p>
        <img src="<?php echo $item['image_path']; ?>">
    <?php endforeach; endif; else: echo "" ;endif; ?>
    <p>============商品详情==============</p>
    <?php echo $product['product_desc']; ?>-->
    <script language='javascript' src='http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js'> </script>
    <script language="JavaScript" src="<?php echo $docroot; ?>public/static/script/swiper.min.js"></script>
    <script type="text/javascript">
        $(function () {
            //****************************
            //TODO 测试加入收藏功能，用完记得删除
            //****************************
            var pid = 1;//TODO 用于测试商品收藏功能的全局变量，商品ID
            $(".cart_collect_wrap").bind('touchend',function () {
                //向服务器提交信息

                $.post("http://yejiayong.cn/weshop/Product/ajaxAlterProductLike", {
                    //商品ID
                    //要增加收藏，该处填写的id为正数商品ID号；移除收藏，该处填写的id为负数商品ID号
                    id: pid
                },function (data) {
                    //返回的json里面包括code和msg两个条目
                    //code表示返回码，当成功时返回0，失败时返回-1
                    var code = data.code;
                    //alert("已加入购物车");
                    if (code == 0){
                        if (pid > 0){
                            alert("添加收藏成功");
                            pid = -1 * pid;
                        }else {
                            alert("取消收藏成功");
                            pid = -1 * pid;
                        }
                    }else{
                        alert("加入失败");
                    }
                    $(".select_tab_wrap").hide().fadeOut(200);
                });
            });
            //****************************
            //TODO 测试加入购物车按钮功能，用完记得删除
            //****************************
            var test = [
                {
                    cart_id : 1,
                    product_id : "product1",
                    spec_id : "spec1",
                    count : 5
                },
                {
                    product_id : "product1",
                    spec_id : "spec1",
                    count : 5
                },
                {
                    cart_id : 5,
                    product_id : "product1",
                    spec_id : "spec1",
                    count : 5
                }
            ];
            $.post("http://yejiayong.cn/weshop/Index/test2",{test : test},function () {
                alert(test.name);
            });
            $(".select_footer_addcart").bind('touchend',function () {
                //向服务器提交信息
                $.post("http://yejiayong.cn/weshop/Cart/set", {
                    //产品ID
                    product_id: 1,
                    //产品规格ID
                    spec_id: 1,
                    //要添加到购物车的数量
                    count: 1
                },function (data) {
                    //返回的json里面包括code和msg两个条目
                    //code表示返回码，当成功时返回0，失败时返回-1
                    var code = data.code;
                    //msg表示返回信息，成功返回"success"，失败返回"failed"
                    var msg = data.msg;
                    //alert("已加入购物车");
                    if (code == 0){
                        alert("已加入购物车");
                    }else{
                        alert("加入失败");
                    }
                    $(".select_tab_wrap").hide().fadeOut(200);
                });
            });
//            页面返回按钮
            $(".page_back").bind('touchstart',function () {
               self.location = "http://yejiayong.cn/weshop/index/index.html";
            });
//            点击商品详情时切换为详情数据
            $("#product_select_introduce").bind('touchstart',function () {
               $(".product_data").css("display","none");
               $("#product_select_data").removeClass("introduce_select");
               $("#product_select_introduce").addClass("introduce_select");
               $(".product").css("display","block");
            });
//            点击产品参数时切换为参数数据
            $("#product_select_data").bind('touchstart',function () {
               $("#product_select_introduce") .removeClass("introduce_select");
               $(".product").css("display","none");
               $("#product_select_data").addClass("introduce_select");
               $(".product_data").css("display","block");
            });

//            点击选择产品参数
            $(".select_div").bind('touchstart',function () {
               $(this).parent().children().removeClass("select_div_choose");
               $(this).addClass("select_div_choose");
            });
//            点击增减产品数量
            $(".select_num_minus").bind('touchstart',function () {
                var now_num = $(".select_num_num").attr("data-num");
                if (now_num == 1){
//                    如果为1时则不能减
                    return false;
                }else {
                    now_num --;
                    $(".select_num_num").attr("data-num",now_num);
                    $(".select_num_num").text(now_num);
//                    如果为1时则减号取消可用样式
                    if (now_num  == 1){
                        $(".select_num_minus").removeClass("select_num_minus_reduce");
                    }
                }
            });

            $(".select_num_add").bind('touchstart',function () {
                var now_num = $(".select_num_num").attr("data-num");
                now_num ++;
                $(".select_num_num").attr("data-num",now_num);
                $(".select_num_num").text(now_num);
                $(".select_num_minus").addClass("select_num_minus_reduce");
            });

//            控制选择尺寸div显示与否
            $(".select_wrap").bind('click',function () {
                $(".select_tab_wrap").css("display","block");
            });

            $(".select_cancel").bind('touchend',function () {
                $(".select_tab_wrap").fadeOut(200);
                $(".select_tab_wrap").css("display","none");
            })
        });

        window.onload = function () {
            $(".loading").fadeOut(1000);
            $("body").fadeIn(1000);

            //Swiper对象创建
            var mySwiper = new Swiper ('.swiper-container', {
                loop: false,
                roundLengths:true,
                pagination : '.swiper-pagination',
                paginationType : 'fraction'
            })
        }
    </script>
</body>
</html>