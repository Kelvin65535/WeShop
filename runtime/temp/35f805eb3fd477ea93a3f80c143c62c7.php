<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:54:"/var/www/html/application/weshop/view/index/index.html";i:1496420248;s:54:"/var/www/html/application/weshop/view/global/meta.html";i:1485837662;s:59:"/var/www/html/application/weshop/view/global/copyright.html";i:1485890619;s:60:"/var/www/html/application/weshop/view/global/nav_bottom.html";i:1486984813;}*/ ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" id="viewport" name="viewport">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
    <title>嘉永·商城</title>
    <link href="<?php echo $docroot; ?>public/static/css/weshop_public_bottom.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $docroot; ?>public/static/css/weshop_index.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $docroot; ?>public/static/css/swiper-3.4.1.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<!--页面主体-->
<div id="wrap">
    <!--头部-->
    <header>
        <!--顶部图片、活动分类和搜索框 -->

        <!--顶部图片-->
        <!--展示图片左右滑动区域-->
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide red">
                    <img src="<?php echo $docroot; ?>public/static/images/weshop/index_header.jpg" width="100%" height="100%">
                </div>
                <div class="swiper-slide blue">
                    <img src="<?php echo $docroot; ?>public/static/images/weshop/index_header.jpg" width="100%" height="100%">
                </div>
                <div class="swiper-slide yellow">
                    <img src="<?php echo $docroot; ?>public/static/images/weshop/index_header.jpg" width="100%" height="100%">
                </div>
            </div>
            <!-- 如果需要分页器 -->
            <div class="swiper-pagination"></div>

        </div>

        <!--活动分类-->
        <div id="search_active">
            <!--四个活动分类页-->
            <?php if(is_array($navigation) || $navigation instanceof \think\Collection): $i = 0; $__LIST__ = $navigation;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($i % 2 );++$i;?>
            <div class="search_active" id="search_active_first">
                <div class="search_active_img">
                    <img src="<?php echo $nav['nav_ico']; ?>" width="100%" height="100%">
                </div>
                <div class="search_active_text">
                    <a href="<?php echo $nav['nav_content']; ?>" style="text-decoration:none;"><?php echo $nav['nav_name']; ?></a>
                </div>
            </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>

        <!--搜索框-->
        <div id="search_wrap">
            <!--放大镜图片-->
            <div id="search_mrror">
                <img id="search_img" src="<?php echo $docroot; ?>public/static/images/weshop/search_mrror.svg" width="100%" height="100%">
            </div>
            <!--搜索输入框-->
            <div id="search_input_wrap">
                <form>
                    <!--获取键盘效果一定要form表单-->
                    <input id="search_input" type="search" placeholder="请输入商品关键词" >
                </form>
            </div>
        </div>
    </header>
    <!--推荐内容-->
    <div id="recommend">
        <!--四个推荐商品模板-->
        <!--循环渲染-->
        <!--外部循环，显示每个商品模块集合-->
        <?php if(is_array($section) || $section instanceof \think\Collection): $i = 0; $__LIST__ = $section;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sec): $mod = ($i % 2 );++$i;?>
        <div class="recommend">
            <!--模块标题-->
            <p><?php echo $sec['name']; ?></p>
            <!--模版内部循环，解包显示模块内的商品信息-->
            <?php if(is_array($sec['product']) || $sec['product'] instanceof \think\Collection): $i = 0; $__LIST__ = $sec['product'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
            <div class="recommend_wrap" id="recommend_temp">
                <a href="http://yejiayong.cn/weshop/Product/detail/id/<?php echo $item['id']; ?>" class="recommend_img" id="recommend_temp_img">
                    <img src="<?php echo $docroot; ?>public/static/images/weshop/index_header.jpg" width="100%" height="100%">
                </a>

                <div class="recommend_name" id="recommend_temp_name">
                    <?php echo $item['name']; ?>
                </div>

                <div class="recommend_price" id="recommend_temp_price">
                    <?php echo $item['price']; ?>
                </div>

            </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>


    </div>
    <!--广告部分-->
    <div id="ad">
        <img src="<?php echo $docroot; ?>public/static/images/weshop/index_header.jpg" width="100%" height="100%">
    </div>

    <div class="copyright_wrap">
    <div class="copyright"><?php echo $settings['copyright']; ?></div>
</div>

</div>





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

<script language="JavaScript" src="<?php echo $docroot; ?>public/static/script/swiper.min.js"></script>
<script type="text/javascript">
    $(function () {

/*//        推荐模块渲染
        $.get('http://yejiayong.cn/weshop/Index/getRecommendProduct',function (response) {
            var data = $.parseJSON(response);
            var product,i;


            for (i=0;i<4;i++){
                //生成推荐模块
                var $temp = $("#recommend_temp").clone(true);//克隆模板
                $temp.find("#recommend_temp_img").attr("id","recommend_temp_img"+i);
                $temp.find("#recommend_temp_name").attr("id","recommend_temp_name"+i);
                $temp.find("#recommend_temp_price").attr("id","recommend_temp_price"+i);
                $temp.find("#recommend_temp_img"+ i +" img").attr("src",data[i].url);
                $temp.find("#recommend_temp_name"+ i +"").text(data[i].name);
                $temp.find("#recommend_temp_price"+ i +"").text("￥"+data[i].price);

                $temp.appendTo("#recommend");

            }

        });*/

        //让新生成的数据块显示
        $(".recommend_wrap").attr("class","recommend_wrap_show");

    });

    window.onload = function () {
        $(".loading").fadeOut(1000);
        $("body").fadeIn(1000);

        //Swiper对象创建
        var mySwiper = new Swiper ('.swiper-container', {
            loop: true,
            roundLengths:true,
            autoplay : 2000,
            pagination : '.swiper-pagination'
        });
    }
</script>

</body>
</html>
