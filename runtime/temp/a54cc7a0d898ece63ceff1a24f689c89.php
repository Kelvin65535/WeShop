<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:73:"/var/www/html/application/admin/view/mainpage/settings/settings_menu.html";i:1496364863;s:57:"/var/www/html/application/admin/view/mainpage/header.html";i:1485246950;s:57:"/var/www/html/application/admin/view/mainpage/footer.html";i:1485248131;}*/ ?>
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
<i id="scriptTag"><?php echo $docroot; ?>public/static/script/admin/settings/setting_menu.js</i>
<link rel="stylesheet" type="text/css" href="<?php echo $docroot; ?>public/static/css/bootstrap/bootstrap.css"/>
<style type="text/css">
    #add_menu_please {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        right: 0;
        background: #fff;
        text-align: center;
        padding-top: 110px;
        z-index: 999;
    }
    #add_menu_please p{
        padding: 10px 0;
        font-size: 14px;
        color: #666;
    }
    label{
        margin-bottom: -4px;
        font-weight: normal;
    }
</style>
<input type="hidden" value="<?php echo \think\Request::instance()->server('HTTP_REFERER'); ?>" id="http_referer"/>
<div class="padding10" style="margin-bottom: 40px;">
    <p class="Thead" style="margin-left: 10px;">可创建最多<b>3</b>个一级菜单，每个一级菜单下可创建最多<b>5</b>个二级菜单。</p>

    <div id="menuW" class="margin10 clearfix">
        <div class="l">
            <div class="tbar">
                菜单管理
                <a class="add" id="topAdd" href="javascript:;"></a>
            </div>
            <div id="menuX"></div>
        </div>
        <div class="r">
            <div class="tbar">设置动作</div>
            <!-- 设置表单 -->
            <form id="ntright">
                <div id="iframe_loading" style="display:none;"></div>
                <div id="add_menu_please" style="display:none;">
                    <img src="static/images/icon/iconfont-no.png" />
                    <p>请先在左侧添加菜单</p>
                </div>
                <div style="padding:10px 20px;">
                    <p class="Thead">菜单名称</p>
                    <!-- 菜单名称 -->
                    <div style="width:300px;margin-bottom: 20px;">
                        <div class="gs-text">
                            <input type="text" value="" id="menu-name-ed" autofocus/>
                        </div>
                    </div>
                    <div id="nTop">
                        <!-- 非顶级菜单设置项 -->
                        <p class="Thead">菜单动作</p>
                        <!-- 菜单动作 -->
                        <div style="width:300px;margin-bottom: 20px;" id="radios">
                            <input type="radio" id="rad1" name="actype" value="" checked/>
                            <label style="margin-right:5px;" onclick="$('#rad1').click();">发送信息</label>
                            <input type="radio" id="rad2" name="actype" value=""/>
                            <label style="margin-right:5px;" onclick="$('#rad2').click();">跳转网页</label>
                            <input type="radio" id="rad3" name="actype" value=""/>
                            <label onclick="$('#rad3').click();">签到</label>
                        </div>
                        <div id="act1" class="acts">
                            <p class="Thead">消息设置</p>

                            <div style="width:500px;margin-bottom: 10px;">
                                <!-- 消息类型选择 -->
                                <div style="width: 300px;margin-bottom: 10px" class="clearfix">
                                    <select id="reltype">
                                        <option value="0" selected>文字消息</option>
                                        <option value="1">图文消息</option>
                                        <option value="2">商品推荐</option>
                                    </select>
                                    <select id="cattype">
                                        <?php if(is_array($categorys) || $categorys instanceof \think\Collection): $i = 0; $__LIST__ = $categorys;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cat1): $mod = ($i % 2 );++$i;?>
                                        <option value="<?php echo $cat1['dataId']; ?>"
                                                <?php if($cat1['hasChildren']): ?>disabled<?php endif; ?>><?php echo $cat1['name']; ?></option>
                                        <?php if(isset($cat1['children'])): if(is_array($cat1['children']) || $cat1['children'] instanceof \think\Collection): $i = 0; $__LIST__ = $cat1['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cat2): $mod = ($i % 2 );++$i;?>
                                        <option value="<?php echo $cat2['dataId']; ?>" <?php if($cat2['hasChildren']): ?>disabled<?php endif; ?>>
                                        -- <?php echo $cat2['name']; ?></option>
                                        <?php if(isset($cat2['children'])): if(is_array($cat2['children']) || $cat2['children'] instanceof \think\Collection): $i = 0; $__LIST__ = $cat2['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cat3): $mod = ($i % 2 );++$i;?>
                                        <option value="<?php echo $cat3['dataId']; ?>" <?php if($cat3['hasChildren']): ?>disabled<?php endif; ?>>
                                        ---- <?php echo $cat3['name']; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>
                                <!-- 文字消息内容 -->
                                <textarea id="mpdcont" cols='4' class="mpdcont"></textarea>
                                <!-- 图文选择 -->
                                <div id="appmsgItem1" class="js_appmsg_item"
                                     style="width:335px;margin-bottom: 10px;display: none;">
                                    <h4 class="appmsg_title"><a href="javascript:;">标题</a></h4>

                                    <div class="appmsg_info">
                                        <em class="appmsg_date"></em>
                                    </div>
                                    <a data-fancybox-type="ajax" class="fancybox.ajax appmsg_thumb_wrp pd-image-sec"
                                       id="thumbUp"
                                       href="<?php echo $docroot; ?>?/WdminPage/ajax_gmess_list/">
                                        <img class="js_appmsg_thumb appmsg_thumb" src="" id="appmsimg-preview"
                                             style="display: none;">
                                    </a>

                                    <p class="appmsg_desc"></p>
                                </div>
                            </div>
                        </div>
                        <!-- 连接跳转 -->
                        <div id="act2" class="acts">
                            <p class="Thead">订阅者点击该子菜单会跳到以下链接</p>

                            <div style="width:400px;margin-bottom: 20px;">
                                <div class="gs-text">
                                    <input type="text" value="" id="menu-url-ed"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a id="menu_savebtn" style="margin-left:0;margin-bottom: 10px;" href="javascript:;"
                       class="btn btn-default"><span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span> 保存设置</a>
                </div>
            </form>
        </div>
    </div>
</div>
<a id="addmenu1_t" style="display: none" href="#addmenu1"></a>
<div id="addmenu1">
    <div class="in">
        <div class="gs-label">菜单名称</div>
        <div class="gs-text">
            <input type="text" value="" id="menu-name-sm" autofocus/>
        </div>
    </div>
    <div class="center">
        <a id="add_menu_btn" href="javascript:;" class="wd-btn primary">确认</a>
    </div>
</div>
<div class="fix_bottom fixed">
    <a id="menu_pubbtn" href="javascript:;" class="btn btn-success"><span class="glyphicon glyphicon-send" aria-hidden="true"></span> 发布菜单</a>
</div>
</body>
</html>

<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/jquery-2.1.1.min.js"></script>
<script data-main="<?php echo $docroot; ?>public/static/script/wdmin-frame.js" src="<?php echo $docroot; ?>public/static/script/lib/require.min.js"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/lib/bootstrap/js/bootstrap.min.js"></script>
