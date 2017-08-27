<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:83:"/var/www/html/application/admin/view/mainpage/products/ajax_alter_product_spec.html";i:1494338628;s:57:"/var/www/html/application/admin/view/mainpage/header.html";i:1485246950;s:57:"/var/www/html/application/admin/view/mainpage/footer.html";i:1485248131;}*/ ?>
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


<div style="width:500px;padding:15px;">
    <input type="hidden" id="spec_alter_id" value="<?php if(!$add): ?><?php echo $spec['id']; endif; ?>" />
    <div class="gs-label">规格名称</div>
    <div class="gs-text">
        <input type="text" value="<?php if(!$add): ?><?php echo $spec['spec_name']; endif; ?>" id="pd-spec-name" autofocus/>
    </div>
    <div class="gs-label">规格备注</div>
    <div class="gs-text">
        <input type="text" value="<?php if(!$add): ?><?php echo $spec['spec_remark']; endif; ?>" id="pd-spec-remark" autofocus/>
    </div>
    <div class="gs-label">规格值 <a href="javascript:;" id="specdet-add" style="color:#44b549;">+添加</a></div>

    <div id="spes-warpp">
        <?php if($add): ?>
        <div class="clearfix spes-items">
            <div style="float:left;width:55%;">
                <div class="gs-text">
                    <input type="text" value="" class="spec-det" data-id="" placeholder="规格名称" autofocus/>
                </div>
            </div>
            <div style="float:left;width:24%;margin-left:3%;">
                <div class="gs-text">
                    <input type="text" value="0" class="spec-det-sort" placeholder="顺序" autofocus/>
                </div>
            </div>
            <div style="float:left;width:15%;margin-left:2.5%;">
                <a class="wd-btn delete spec-edit-del" href="javascript:;">删除该行</a>
            </div>
        </div>
        <?php else: if(is_array($spec['dets']) || $spec['dets'] instanceof \think\Collection): if( count($spec['dets'])==0 ) : echo "" ;else: foreach($spec['dets'] as $key=>$det): ?>
        <div class="clearfix spes-items">
            <div style="float:left;width:65%;">
                <div class="gs-text">
                    <input type="text" value="<?php echo $det['det_name']; ?>" data-id="<?php echo $det['id']; ?>" class="spec-det" placeholder="规格名称" autofocus/>
                </div>
            </div>
            <div style="float:left;width:14%;margin-left:3%;">
                <div class="gs-text">
                    <input type="text" value="<?php echo $det['det_sort']; ?>" class="spec-det-sort" placeholder="顺序" autofocus/>
                </div>
            </div>
            <div style="float:left;width:15%;margin-left:2.5%;">
                <a class="wd-btn delete spec-edit-del" href="javascript:;">删除该行</a>
            </div>
        </div>
        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
    </div>

    <div class="center top20">
        <a class="wd-btn primary" id='add_spec_btn_save' style="width:150px" href="javascript:;">保存</a>
    </div>
</div>
</body>
</html>

<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/jquery-2.1.1.min.js"></script>
<script data-main="<?php echo $docroot; ?>public/static/script/wdmin-frame.js" src="<?php echo $docroot; ?>public/static/script/lib/require.min.js"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/lib/bootstrap/js/bootstrap.min.js"></script>
