<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:77:"/var/www/html/application/admin/view/mainpage/products/ajax_add_category.html";i:1496407538;}*/ ?>
<div style="width: 250px;position: relative;padding:5px;">
    <div class="gs-label">分类名称</div>
    <div class="gs-text">
        <input type="text" name="cat_name" id="cat_name_f" value="" />
    </div>
    <div class="gs-label">上级分类</div>
    <select id="pd-cat-select" style="color:#666">
        <option value="0">顶级分类</option>
        <?php if(is_array($categorys) || $categorys instanceof \think\Collection): $i = 0; $__LIST__ = $categorys;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cat1): $mod = ($i % 2 );++$i;?>
        <option value="<?php echo $cat1['dataId']; ?>"><?php echo $cat1['name']; ?></option>
        <?php if(isset($cat1['children'])): if(is_array($cat1['children']) || $cat1['children'] instanceof \think\Collection): $i = 0; $__LIST__ = $cat1['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cat2): $mod = ($i % 2 );++$i;?>
            <option value="<?php echo $cat2['dataId']; ?>">-- <?php echo $cat2['name']; ?></option>
            <?php if(isset($cat2['children'])): if(is_array($cat2['children']) || $cat2['children'] instanceof \think\Collection): $i = 0; $__LIST__ = $cat2['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cat3): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $cat3['dataId']; ?>">---- <?php echo $cat3['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; ?>
    </select>
    <div class="align-center top10">
        <a class="wd-btn primary" id="add_cate_btn" href="javascript:;" style="margin-left: 0">添加</a>
    </div>
</div>