<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:79:"/var/www/html/application/admin/view/mainpage/products/alter_product_specs.html";i:1494333611;s:57:"/var/www/html/application/admin/view/mainpage/header.html";i:1485246950;s:57:"/var/www/html/application/admin/view/mainpage/footer.html";i:1485248131;}*/ ?>
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
<i id="scriptTag">page_alter_product_specs</i>
<div class="clearfix" style="margin-bottom:42px;">
    <div id="DataTables_Table_0_filter" class="dataTables_filter clearfix">
        <div class="search-w-box"><input type="text" class="searchbox" placeholder="输入搜索内容" /></div>
        <div class="button-set" style="margin-top: 13px;margin-right: 13px;">
            <a class="button gray" href="javascript:;" onclick='location.reload()'>刷新</a>
            <a class="button spec-edit-btn fancybox.iframe" data-fancybox-type="iframe"  href="<?php echo $docroot; ?>admin/Mainpage/ajax_alter_product_spec/">新增规格</a>
        </div>
    </div>
    <table class="dTable">
        <thead>
        <tr>
            <th>编号</th>
            <th>名称</th>
            <th>规格值</th>
            <th style='padding-right:10px;'>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($specs) || $specs instanceof \think\Collection): if( count($specs)==0 ) : echo "" ;else: foreach($specs as $key=>$s): ?>
        <tr>
            <td>#<?php echo $s['id']; ?></td>
            <td style="font-size:14px;"><?php echo $s['spec_name']; ?> <b class="spec-remark"><?php if($s['spec_remark'] != ''): ?>[<?php echo $s['spec_remark']; ?>]<?php endif; ?></td>
            <td style='white-space: initial;'>
                <?php if(is_array($s['dets']) || $s['dets'] instanceof \think\Collection): if( count($s['dets'])==0 ) : echo "" ;else: foreach($s['dets'] as $key=>$dets): ?>
                <a class="spec-det-item" href="javascript:;" data-id="<?php echo $dets['id']; ?>"><?php echo $dets['det_name']; ?></a>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </td>
            <td data-id="<?php echo $s['id']; ?>" style='padding-right:10px;'>
                <a class="spec-edit-btn fancybox.iframe" data-fancybox-type="iframe" href="<?php echo $docroot; ?>admin/Mainpage/ajax_alter_product_spec/id/<?php echo $s['id']; ?>">修改</a> / <a class="spec-del-btn del" href="javascript:;">删除</a>
            </td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/jquery-2.1.1.min.js"></script>
<script data-main="<?php echo $docroot; ?>public/static/script/wdmin-frame.js" src="<?php echo $docroot; ?>public/static/script/lib/require.min.js"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/lib/bootstrap/js/bootstrap.min.js"></script>
