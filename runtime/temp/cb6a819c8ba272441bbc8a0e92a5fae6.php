<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:53:"/var/www/html/application/admin/view/index/index.html";i:1494034605;s:58:"/var/www/html/application/admin/view/index/index_navs.html";i:1494035117;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="renderer" content="webkit">
    <title><?php echo $settings['shopname']; ?> - 管理后台</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="format-detection" content="telephone=no"/>
    <link href="<?php echo $docroot; ?>public/static/css/bootstrap/bootstrap.css" type="text/css" rel="Stylesheet"/>
    <link href="<?php echo $docroot; ?>public/static/css/wshop_admin_index.css?v=12132131" type="text/css" rel="Stylesheet"/>
    <link href="<?php echo $docroot; ?>public/static/css/font-awesome.min.css" type="text/css" rel="Stylesheet"/>
    <script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/lib/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/admin/wdmin.js?v=46454411"></script>
</head>
<body class="wdmin-main" style="overflow:hidden;">
<!-- 管理控制台主页面 -->
<nav class="navbar navbar-default" id="navtop">
    <div class="container-lg">
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <div class="pull-left" style="line-height: 43px;color: #fff;padding-left: 10px;"><?php echo $settings['shopname']; ?> - 管理后台
                (<?php echo $today; ?>)
            </div>
            <ul class="nav navbar-nav navbar-right">
                <!-- @see http://v3.bootcss.com/components/ -->
                <li class="topRightNavItem">
                    <a href="<?php echo $docroot; ?>weshop/Index/index">商城首页</a>
                </li>
                <li class="topRightNavItem">
                    <a href="https://mp.weixin.qq.com/" target="_blank">微信公众平台</a>
                </li>
                
                <li class="topRightNavItem">
                    <a href="../index/logout/"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>退出</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div id="wdmin-wrap">
    <div id="leftNav"><?php if($Auth['stat']): ?>
<a href="javascript:;" class="navItem" id="navitem2" rel='subnav2'>
    <span class="glyphicon glyphicon-th-large" aria-hidden="true"></span><i class="label">报表中心</i>
</a>
<div class='subnavs clearfix' id='subnav2'>
    <a class='cap-nav-item' href='javascript:;' data-page="overview" data-nav="home">微店总览</a>
</div>
<?php endif; if($Auth['orde']): ?>
<a href="javascript:;" class="navItem" id="navitem4" rel='subnav4'>
    <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span><i class='label'>订单管理</i>
</a>
<div class='subnavs clearfix' id='subnav4'>
    <a class='cap-nav-item' href='javascript:;' data-page="orders_manage" data-nav="orders">订单管理</a>
    <a class='cap-nav-item' href='javascript:;' data-page="orders_refund" data-nav="orders">退款审核</a>
    
    <a class='cap-nav-item' href='javascript:;' data-page="orders_history_address" data-nav="orders">收货地址</a>
    
    
</div>
<?php endif; if($Auth['prod']): ?>
<a href="javascript:;" class="navItem" id="navitem5" rel='subnav5'>
    <span class="glyphicon glyphicon-folder-open" aria-hidden="true" style="font-size: 12px"></span><i
        class='label'>商品管理</i>
</a>
<div class='subnavs clearfix' id='subnav5'>
    <a id='__pdlist' class='cap-nav-item' href='javascript:;' data-page="list_products" data-nav="products">商品管理 <b
            class="icount">(0)</b></a>
    
    <a id='__catlist' class='cap-nav-item' href='javascript:;' data-page="alter_products_category"
       data-nav="products">分类管理 <b class="icount">(0)</b></a>
    <a id='__specmanage' class='cap-nav-item' href='javascript:;' data-page="alter_product_specs"
       data-nav="products">规格管理 <b class="icount">(0)</b></a>
    <a class='cap-nav-item' href='javascript:;' data-page="alter_product_brand" data-nav="products">品牌管理 <b
            class="icount">(0)</b></a>
    <a class='cap-nav-item' href='javascript:;' data-page="deleted_products" data-nav="products">回收站 <b
            class="icount">(0)</b></a>
</div>
<?php endif; if($Auth['user']): ?>
<a href="javascript:;" class="navItem" id="navitem9" rel='subnav9'>
    <span class="glyphicon glyphicon-user" aria-hidden="true"></span><i class='label'>用户管理</i>
</a>
<div class='subnavs clearfix' id='subnav9'>
    <a id='__uslist' class='cap-nav-item' href='javascript:;' data-page="list_customers"
       data-nav="customers">用户列表</a>
    <a class='cap-nav-item' href='javascript:;' data-page="user_level" data-nav="customers">用户分组</a>
    
    
    
</div>
<?php endif; if($Auth['gmes']): ?>
<a href="javascript:;" class="navItem" id="navitem6" rel='subnav6'>
    <span class="glyphicon glyphicon-send" aria-hidden="true"></span><i class='label'>消息群发</i>
</a>
<div class='subnavs clearfix' id='subnav6'>
    <a class='cap-nav-item' href='javascript:;' data-page="gmess_list" data-nav="gmess">素材管理</a>
</div>
<?php endif; if($Auth['sett']): ?>
<a href="javascript:;" class="navItem" id="navitem7" rel='subnav7'>
    <span class="glyphicon glyphicon-cog" aria-hidden="true" style="font-size: 16px"></span><i
        class='label'>店铺设置</i>
</a>
<div class='subnavs clearfix' id='subnav7'>
    <a class='cap-nav-item' href='javascript:;' data-page="settings_base" data-nav="settings">基础设置</a>
    <a class='cap-nav-item' href='javascript:;' data-page="settings_autoresponse" data-nav="setttings">自动回复</a>
    <a class='cap-nav-item' href='javascript:;' data-page="settings_menu" data-nav="settings">自定菜单</a>
    <a class='cap-nav-item' href='javascript:;' data-page="settings_banners" data-nav="settings">广告设置</a>
    <a class='cap-nav-item' href='javascript:;' data-page="settings_section" data-nav="settings">首页板块</a>
    <a class='cap-nav-item' href='javascript:;' data-page="settings_navigation" data-nav="settings">首页导航</a>
    <a class='cap-nav-item' href='javascript:;' data-page="settings_expfee" data-nav="settings">运费模板</a>
    <a class='cap-nav-item' href='javascript:;' data-page="settings_expcompany" data-nav="settings">快递设置</a>
    <a class='cap-nav-item' href='javascript:;' data-page="settings_auth" data-nav="settings">管理权限</a>
</div>
<?php endif; ?>

<br/>
<br/>
<br/></div>
    <div id="rightWrapper">
        <div id="main-mid">
            <div id="iframe_loading"><img src="<?php echo $docroot; ?>public/static/images/icon/iconfont-loading-x64-green.png"/></div>
            <div id="__subnav__"></div>
            <iframe id="right_iframe" src="" width="100%" frameborder="0"></iframe>
        </div>
    </div>
</div>
</body>
</html>