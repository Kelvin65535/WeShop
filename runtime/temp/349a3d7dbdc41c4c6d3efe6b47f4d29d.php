<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:73:"/var/www/html/application/admin/view/mainpage/settings/settings_base.html";i:1493771900;s:57:"/var/www/html/application/admin/view/mainpage/header.html";i:1485246950;s:76:"/var/www/html/application/admin/view/mainpage/settings/tab_setting_base.html";i:1493772213;s:57:"/var/www/html/application/admin/view/mainpage/footer.html";i:1485248131;}*/ ?>
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
<i id="scriptTag"><?php echo $docroot; ?>public/static/script/admin/settings/setting_base.js</i>
<link rel="stylesheet" type="text/css" href="<?php echo $docroot; ?>public/static/css/bootstrap/bootstrap.css"/>
<style type="text/css">
    .tab-pane {
        padding: 15px 0;
    }

    .nav-tabs a {
        color: #000;
        padding: 7px 20px !important;
    }
</style>
<form style="padding:15px 20px;padding-bottom: 70px;" id="settingFrom">

    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist" id="setting_tab">
            <li role="presentation" class="active">
                <a href="#base" aria-controls="base" role="tab" data-toggle="tab">基础设置</a>
            </li>
            <li role="presentation">
                <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">会员设置</a>
            </li>
            <li role="presentation">
                <a href="#reci" aria-controls="reci" role="tab" data-toggle="tab">发票设置</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="base">
                <div class="fv2Field clearfix">
    <div class="fv2Left">
        <span>店铺名称</span>
    </div>
    <div class="fv2Right">
        <input type="text" class="form-control" name="shopname" value="<?php echo $settings['shopname']; ?>" autofocus/>

        <div class='fv2Tip'>微店铺名称，显示在网页标题结尾</div>
    </div>
</div>

<div class="fv2Field clearfix">
    <div class="fv2Left">
        <span>版权标识</span>
    </div>
    <div class="fv2Right">
        <input type="text" class="form-control" name="copyright" value="<?php echo $settings['copyright']; ?>"/>

        <div class='fv2Tip'>版权标识，显示在页面底部</div>
    </div>
</div>

<div class="fv2Field clearfix">
    <input type="hidden" value="<?php echo $settings['welcomegmess']; ?>" name="welcomegmess" id="welcomegmess"/>

    <div class="fv2Left">
        <span>关注自动消息</span>
    </div>
    <div class="fv2Right">
        <a id="sGmess" href="?/WdminPage/ajax_gmess_list/" class="btn btn-success fancybox.ajax"
           data-fancybox-type="ajax" style="margin:0;width:100%;" data-id="">选择素材</a>

        <div id="GmessItem" class="clearfix">
            <?php if($gm): ?>
            <div class="gmBlock" data-id="<?php echo $gm['id']; ?>">
                <a class="sel hov"></a>

                <p class="title Elipsis"><?php echo $gm['title']; ?></p>
                <img src="<?php echo $gm['catimg']; ?>"/>

                <p class="desc Elipsis"><?php echo $gm['desc']; ?></p>
            </div>
            <?php endif; ?>
        </div>
        <div class='fv2Tip' id="gmessTip">请点击选择图文素材</div>
    </div>
</div>

<div class="fv2Field clearfix">
    <div class="fv2Left">
        <span>确认收货天数</span>
    </div>
    <div class="fv2Right">
        <input type="text" class="form-control" name="order_confirm_day" value="<?php echo $settings['order_confirm_day']; ?>"/>

        <div class='fv2Tip'>发货状态订单自动确认收货 天数</div>
    </div>
</div>

<div class="fv2Field clearfix">
    <div class="fv2Left">
        <span>订单自动回收</span>
    </div>
    <div class="fv2Right">
        <input type="text" class="form-control" name="order_cancel_day" value="<?php echo $settings['order_cancel_day']; ?>"/>

        <div class='fv2Tip'>未支付状态订单自动回收 天数</div>
    </div>
</div>

<div class="fv2Field clearfix">
    <div class="fv2Left">
        <span>公众号图标</span>
    </div>
    <div class="fv2Right">
        <div class="clearfix">
            <div class="alter-cat-img">
                <input type="hidden" value="<?php echo $settings['admin_setting_icon']; ?>" id="icon" name="admin_setting_icon"/>

                <div id="icon-loading" style="transition-duration: .2s;"></div>
                <img id="iconimage" src="<?php echo $settings['admin_setting_icon']; ?>"/>
                <?php if($settings['admin_setting_icon'] == ''): ?>
                <div style='line-height: 100px;color:#777;' class='align-center' id="icon_none_pic">无图片</div>
                <?php endif; ?>
                <div class="align-center top10">
                    <a class="btn btn-success" id="upload_icon" href="javascript:;">更换图片</a>
                </div>
            </div>
        </div>
        <div class='fv2Tip'>设置公众号小图标 jpg或png</div>
    </div>
</div>

<div class="fv2Field clearfix">
    <div class="fv2Left">
        <span>公众号二维码</span>
    </div>
    <div class="fv2Right">
        <div class="clearfix">
            <div class="alter-cat-img">
                <input type="hidden" value="<?php echo $settings['admin_setting_qrcode']; ?>" id="qrcode" name='admin_setting_qrcode'/>

                <div id="qrcode-loading" style="transition-duration: .2s;"></div>
                <img id="qrcodeimage" src="<?php echo $settings['admin_setting_qrcode']; ?>"/>
                <?php if($settings['admin_setting_qrcode'] == ''): ?>
                <div style='line-height: 100px;color:#777;' class='align-center' id="qrcode_none_pic">无图片</div>
                <?php endif; ?>
                <div class="align-center top10">
                    <a class="btn btn-success" id="upload_qrcode" href="javascript:;">更换图片</a>
                </div>
            </div>
        </div>
        <div class='fv2Tip'>设置公众号二维码 jpg或png</div>
    </div>
</div>
            </div>
        </div>

    </div>

</form>


<div class="fix_bottom" style="position: fixed; height: 58px;">
    <a class="btn btn-success" id='saveBtn' style="width:150px" href="javascript:;">
        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>保存设置
    </a>
</div>

</body>
</html>

<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/jquery-2.1.1.min.js"></script>
<script data-main="<?php echo $docroot; ?>public/static/script/wdmin-frame.js" src="<?php echo $docroot; ?>public/static/script/lib/require.min.js"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/lib/bootstrap/js/bootstrap.min.js"></script>
