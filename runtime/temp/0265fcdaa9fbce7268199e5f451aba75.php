<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:80:"/var/www/html/application/admin/view/mainpage/products/iframe_list_products.html";i:1496906558;s:57:"/var/www/html/application/admin/view/mainpage/header.html";i:1485246950;s:57:"/var/www/html/application/admin/view/mainpage/footer.html";i:1485248131;}*/ ?>
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
<link href="<?php echo $docroot; ?>public/static/css/base/base_pagination.css" type="text/css" rel="Stylesheet" />
<i id="scriptTag">page_iframe_list_products</i>
<input type="hidden" id="cat" value="<?php echo $cat; ?>" />
<input type="hidden" id="listype" value="0" />
<div id="list" style="margin-bottom: 50px;">
    <div id="DataTables_Table_0_filter" class="dataTables_filter clearfix">
        <div class="search-w-box"><input type="text" class="searchbox" placeholder="输入搜索内容" /></div>
        <div class="button-set" style="margin-top: 13px;margin-right: 13px;">
            <a class="btn btn-success" href="<?php echo $docroot; ?>admin/Mainpage/iframe_alter_product/mod/add/cat/<?php echo $cat; ?>">添加商品</a>
            <a class="btn btn-primary" href="javascript:;" id='refresh_static'>刷新缓存</a>
            <a class="btn btn-default" href="javascript:;" onclick='location.reload()'>刷新</a>
        </div>
    </div>
    <table class="dTable">
        <thead>
        <tr>
            <th class="hidden"> </th>
            <th> </th>
            <th style="width:320px">产品名称</th>
            <th>编号</th>
            <th>单位</th>
            <th>价格</th>
            <th>浏览</th>
            <th style='width:145px;'>操作</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<!-- 模板开始，可以使用script（type设置为text/html）来存放模板片段，并且用id标示 -->
<script id="t:pd_list" type="text/html">
    
    <%for(var i=0;i<list.length;i++){%>
    <tr class="defTr font12">
        <td class="hidden"><%=list[i].product_id%></td>
        <td>
            <img class="pdlist-image" height="50" width="50" src="<%=list[i].catimg%>" />
        </td>
        <td><%=list[i].product_name.substring(0,30)%></td>
        <td><%=list[i].product_code%></td>
        <td><%=list[i].product_unit%></td>
        <td class="prices font12">&yen;<%=list[i].sale_prices%></td>
        <td><%=list[i].product_readi%></td>
        <th>
            <a class="pd-altbtn" href="/admin/Mainpage/iframe_alter_product/mod/edit/pid/<%=list[i].product_id%>" data-product-id="<%=list[i].product_id%>">编辑</a>&nbsp;
            <a class="pd-altbtn pd-switchonline <%if(list[i].product_online == 1){%>tip<%}%>" href="javascript:;" data-product-id="<%=list[i].product_id%>" data-product-online="<%=list[i].product_online%>"><%if(list[i].product_online == 1){%>下架<%}else{%>上架<%}%></a>&nbsp;
            <a class="pd-altbtn pd-del-btn del" href="javascript:;" data-product-id="<%=list[i].product_id%>">删除</a>
        </th>
    </tr>
    <%}%>
    
</script>
<!-- 模板结束 -->

<div class="fix_bottom textRight fixed">
    <div id="pager-bottom"><ul style="margin-top: 7px" class="pagination-sm pagination"></ul></div>
</div>

</body>
</html>

<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/jquery-2.1.1.min.js"></script>
<script data-main="<?php echo $docroot; ?>public/static/script/wdmin-frame.js" src="<?php echo $docroot; ?>public/static/script/lib/require.min.js"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/lib/bootstrap/js/bootstrap.min.js"></script>
