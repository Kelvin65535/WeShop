<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:76:"/var/www/html/application/admin/view/mainpage/settings/alter_navigation.html";i:1496421071;s:57:"/var/www/html/application/admin/view/mainpage/header.html";i:1485246950;s:57:"/var/www/html/application/admin/view/mainpage/footer.html";i:1485248131;}*/ ?>
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
<link href="<?php echo $docroot; ?>public/static/css/bootstrap/bootstrap.css" type="text/css" rel="Stylesheet"/>
<link href="<?php echo $docroot; ?>public/static/css/jquery.datetimepicker.css" type="text/css" rel="Stylesheet"/>
<i id="scriptTag"><?php echo $docroot; ?>public/static/script/admin/settings/setting_alter_navigation.js</i>

<form style="padding:15px 20px;padding-bottom: 70px;" id="settingFrom">

    <div class="fv2Field clearfix">
        <div class="fv2Left">
            <span>导航名称</span>
        </div>
        <div class="fv2Right">
            <input type="text" class="form-control" id="nav_name" value="<?php echo $sec['nav_name']; ?>" placeholder="请输入导航名称"
                   autofocus/>
            <div class='fv2Tip'>导航名称，显示在导航底部</div>
        </div>
    </div>
    <!-- 导航类型 -->
    <div class="fv2Field clearfix">
        <div class="fv2Left">
            <span>导航内容</span>
        </div>
        <div class="fv2Right">
            <div style="width:300px;margin-bottom: 20px;" id="radios">
                <input type="radio" id="rad1" name="actype" value=""
                       <?php if(($sec['nav_type'] == 1) OR ($sec['nav_type'] == '')): ?>checked<?php endif; ?>/>
                <label style="margin-right:5px;" onclick="$('#rad1').click();">产品分类</label>
                <input type="radio" id="rad2" name="actype" value=""
                       <?php if(($sec['nav_type'] == 0) AND ($sec['nav_type'] != '')): ?>checked<?php endif; ?>/>
                <label style="margin-right:5px;" onclick="$('#rad2').click();">跳转网页</label>
                <div id="act1" class="acts" style="<?php if(($sec['nav_type'] == 0) and ($sec['nav_type'] != '')): ?>display:none;<?php endif; ?>">
                    <p class="Thead">用户点击会跳转到指定分类商品结果页面</p>
                    <div style="width:400px;margin-bottom: 20px;">
                        <select id="pd-cat-select" style="color:#666" class="form-control">
                            <?php if(!(empty($categorys) || ($categorys instanceof \think\Collection && $categorys->isEmpty()))): if(is_array($categorys) || $categorys instanceof \think\Collection): $i = 0; $__LIST__ = $categorys;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cat1): $mod = ($i % 2 );++$i;?>

                            <option value="<?php echo $cat1['dataId']; ?>" <?php if($sec['nav_content'] == $cat1['dataId']): ?>selected<?php endif; ?>><?php echo $cat1['name']; ?></option>
                            <?php if(isset($cat1['children'])): if(is_array($cat1['children']) || $cat1['children'] instanceof \think\Collection): $i = 0; $__LIST__ = $cat1['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cat2): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $cat2['dataId']; ?>" <?php if($sec['nav_content'] == $cat2['dataId']): ?>selected<?php endif; ?>> -- <?php echo $cat2['name']; ?></option>
                            <?php if(isset($cat2['children'])): if(is_array($cat2['children']) || $cat2['children'] instanceof \think\Collection): $i = 0; $__LIST__ = $cat2['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cat3): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $cat3['dataId']; ?>" <?php if($sec['nav_content'] == $cat3['dataId']): ?>selected<?php endif; ?>> ---- <?php echo $cat3['name']; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; endif; ?>
                        </select>
                    </div>
                </div>
                <!-- 连接跳转 -->
                <div id="act2" class="acts" style="<?php if(($sec['nav_type'] == 1) OR ($sec['nav_type'] == '')): ?>display:none;<?php endif; ?>">
                    <p class="Thead">用户点击该子菜单会跳到以下链接</p>
                    <div style="width:400px;margin-bottom: 20px;">
                        <div class="gs-text">
                            <input type="text" value="<?php echo $sec['nav_content']; ?>" id="menu-url-ed"
                                   placeholder="输入网址需完整加上'http://'或'https://'"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 排序 -->
    <div class="fv2Field clearfix">
        <div class="fv2Left">
            <span>排序</span>
        </div>
        <div class="fv2Right">
            <input class="form-control" type="text" name="sort" id='sort' onclick="this.select()" value="<?php echo $sec['sort']; ?>"/>
            <div class='fv2Tip'>数字越小排序越前</div>
        </div>
    </div>

    <div class="fv2Field clearfix">
        <div class="fv2Left">
            <span>导航图标</span>
        </div>
        <div class="fv2Right">
            <div class="clearfix">
                <div class="alter-cat-img">
                    <input type="hidden" value="<?php echo $sec['nav_ico']; ?>" id="nav_ico"/>
                    <div id="loading" style="transition-duration: .2s;"></div>
                    <img id="catimage"
                         src="<?php echo $sec['nav_ico']; ?>"/>
                    <?php if($sec['nav_ico'] == ''): ?>
                    <div style='line-height: 100px;color:#777;' class='align-center' id="cat_none_pic">无图片</div>
                    <?php endif; ?>
                    <div class="align-center top10">
                        <a class="btn btn-success" id="upload_banner" href="javascript:;">更换图片</a>
                    </div>
                </div>
            </div>
            <div class='fv2Tip'>首页导航对应的图标 建议尺寸80&times;80</div>
        </div>
    </div>
</form>

<div class="fix_bottom" style="position: fixed">
    <a class="btn btn-success" id='saveBtn' data-id='<?php echo $sec['id']; ?>' href="javascript:;"><?php if($sec['id'] > 0): ?>保存<?php else: ?>添加<?php endif; ?></a>
    <a onclick="history.go(-1);" class="btn btn-default">返回</a>
</div>


</body>
</html>

<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/jquery-2.1.1.min.js"></script>
<script data-main="<?php echo $docroot; ?>public/static/script/wdmin-frame.js" src="<?php echo $docroot; ?>public/static/script/lib/require.min.js"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/lib/bootstrap/js/bootstrap.min.js"></script>
