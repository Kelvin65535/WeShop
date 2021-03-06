<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:53:"/var/www/html/application/admin/view/index/login.html";i:1485323732;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $settings['shopname']; ?> - 管理后台</title>
    <meta charset="utf-8"/>
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"/>
    <meta name="format-detection" content="telephone=no">
    <link href="<?php echo $docroot; ?>public/static/css/wshop_admin_login.css" type="text/css" rel="stylesheet"/>
    <link href="<?php echo $docroot; ?>public/static/css/bootstrap/bootstrap.css" type="text/css" rel="stylesheet"/>
</head>
<body class="loginBody">
<div id="login" class="clearfix" style="margin-top: 100px">
    <div class="login-form" id="login-frame">
        <div id="loading" style="display:none;"></div>

        <div class="text-left">

            <label>账号</label>

            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></div>
                <input type="text" class="form-control" id="pd-form-username" placeholder="请输入账号" autocomplete="off" />
            </div>

            <label>密码</label>

            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></div>
                <input type="password" name="password" id="pd-form-password" class="form-control" placeholder="请输入密码" autocomplete="off" />
            </div>

        </div>

        <div class='login-item'>
            <a class="btn btn-success" href="javascript:;" id="login-btn">登录</a>
        </div>
        <div id="copyrights"><?php echo $settings['copyright']; ?></div>
    </div>
</div>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/spin.min.js?v=2.1.1"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/admin/login.js?t=154654151"></script>
</body>
</html>
