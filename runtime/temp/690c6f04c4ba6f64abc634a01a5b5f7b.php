<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:59:"/var/www/html/application/weshop/view/product/category.html";i:1496840794;s:60:"/var/www/html/application/weshop/view/global/nav_bottom.html";i:1486984813;}*/ ?>
<!DOCTYPE HTML>
<html>
<head>
    <title>产品分类</title>
    <meta http-equiv=Content-Type content="text/html;charset=utf-8" />
    <title><?php echo $title; ?> - <?php echo $settings['shopname']; ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="<?php echo $docroot; ?>public/static/css/wshop/wshop_view_category.css" type="text/css" rel="Stylesheet" />

    <link href="<?php echo $docroot; ?>public/static/css/weshop_public_bottom.css" rel="stylesheet" type="text/css">
</head>

<body style="overflow:hidden;padding:0;-webkit-overflow-scrolling: none;">

<input type="hidden" value="<?php echo $searchkey; ?>" id="searchkey" />
<input type="hidden" value="<?php echo $cat; ?>" id="cat" />
<input type="hidden" value="<?php echo $serial_id; ?>" id="serial_id" />
<input type="hidden" value="<?php echo $orderby; ?>" id="orderby" />


<div class="clearfix" id="viewCat">
    <div id="viewCatLeft">
        <div class="viewCatTopItem Elipsis" data-catid="-1">一周新品</div>
        <div class="viewCatTopItem Elipsis" data-catid="-2">一周热搜</div>
        
        <?php if(is_array($topcat) || $topcat instanceof \think\Collection): $i = 0; $__LIST__ = $topcat;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tc): $mod = ($i % 2 );++$i;?>
        <div class="viewCatTopItem Elipsis" data-catid="<?php echo $tc['cat_id']; ?>"><?php echo $tc['cat_name']; ?></div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    <div id="viewCatRight"></div>
</div>
<script data-main="<?php echo $docroot; ?>public/static/script/weshop/shop_vcategory.js" src="http://apps.bdimg.com/libs/require.js/2.1.9/require.min.js"></script>


<script language='javascript' src='http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js'> </script>
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




</body>
</html>