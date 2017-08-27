<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:83:"/var/www/html/application/admin/view/mainpage/products/alter_products_category.html";i:1496407380;s:57:"/var/www/html/application/admin/view/mainpage/header.html";i:1485246950;s:57:"/var/www/html/application/admin/view/mainpage/footer.html";i:1485248131;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="renderer" content="webkit">
    <title>iWshop</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <link href="<?php echo $docroot; ?>public/favicon.ico" rel="Shortcut Icon" />
    <link href="<?php echo $docroot; ?>public/static/css/wshop_admin_style.css" type="text/css" rel="Stylesheet" />
    <link href="<?php echo $docroot; ?>public/static/script/lib/zTree_v3/css/zTreeStyle/zTreeStyle.css" type="text/css" rel="Stylesheet" />
    <link href="<?php echo $docroot; ?>public/static/script/lib/umeditor/themes/default/css/umeditor.min.css" type="text/css" rel="Stylesheet" />
    <link href="<?php echo $docroot; ?>public/static/script/lib/fancyBox/source/jquery.fancybox.css" type="text/css" rel="Stylesheet" />
    <link rel="Stylesheet" type="text/css" href="<?php echo $docroot; ?>public/static/css/font-awesome.min.css" />
</head>
<body>
<link rel="stylesheet" type="text/css" href="<?php echo $docroot; ?>public/static/css/bootstrap/bootstrap.css"/>
<i id="scriptTag">page_alter_products_categroy</i>
<div class="clearfix">
    <div id="categroys">
        <div id="_ztree" class="ztree"></div>
        <div class="fix_bottom fixed" style="display: none;width:229px;">
            <a id="add_category_btn" onclick="javascript:;" class="btn btn-success fancybox.ajax" data-fancybox-type="ajax"
               href="<?php echo $docroot; ?>admin/Mainpage/ajax_add_category/"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>添加分类</a>
        </div>
    </div>
    <div id="cate_settings">
        <div id="iframe_loading" style="top:0;"></div>
        <iframe id="iframe_altercat" src="" width="100%" frameborder="0"></iframe>
    </div>
</div>
</body>
</html>

<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/jquery-2.1.1.min.js"></script>
<script data-main="<?php echo $docroot; ?>public/static/script/wdmin-frame.js" src="<?php echo $docroot; ?>public/static/script/lib/require.min.js"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/lib/bootstrap/js/bootstrap.min.js"></script>
