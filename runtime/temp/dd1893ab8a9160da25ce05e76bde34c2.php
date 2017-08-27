<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:79:"/var/www/html/application/admin/view/mainpage/settings/settings_navigation.html";i:1496402087;s:57:"/var/www/html/application/admin/view/mainpage/header.html";i:1485246950;s:57:"/var/www/html/application/admin/view/mainpage/footer.html";i:1485248131;}*/ ?>
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
<i id="scriptTag"><?php echo $docroot; ?>public/static/script/admin/settings/setting_navigation.js</i>
<div id="list">
    <div id="DataTables_Table_0_filter" class="dataTables_filter clearfix">
        <div class="button-set" style="margin-top: 13px;margin-right: 13px;">
            <a class="button gray" href="javascript:;" onclick='location.reload()'>刷新</a>
            <a class="button blue" id='add_cate_product' href="/admin/Mainpage/alter_navigation/">添加导航</a>
        </div>
    </div>
    <table class="dTable">
        <thead>
        <tr>
            <th style='width:300px'>导航名称</th>
            <th>类型</th>
            <th>内容</th>
            <th>排序</th>
            <th class="center">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($nav) || $nav instanceof \think\Collection): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$navitem): $mod = ($i % 2 );++$i;?>
        <tr>
            <td><?php echo $navitem['nav_name']; ?></td>
            <td><?php if($navitem['nav_type'] == 1): ?>产品分类<?php else: ?>跳转网页<?php endif; ?></td>
            <td><?php echo $navitem['nav_content']; ?></td>
            <td><?php echo $navitem['sort']; ?></td>
            <td class="center">
                <a class="lsBtn" href="/admin/Mainpage/alter_navigation/id/<?php echo $navitem['id']; ?>">编辑</a>
                <a class="lsBtn del navigation_del" data-id="<?php echo $navitem['id']; ?>" href="javascript:;">删除</a>
            </td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
</div>
<div class="fix_bottom" style="position: fixed">
</div>
</body>
</html>

<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/jquery-2.1.1.min.js"></script>
<script data-main="<?php echo $docroot; ?>public/static/script/wdmin-frame.js" src="<?php echo $docroot; ?>public/static/script/lib/require.min.js"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/lib/bootstrap/js/bootstrap.min.js"></script>
