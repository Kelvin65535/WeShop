<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:62:"/var/www/html/application/weshop/view/product/ajaxcatlist.html";i:1496839275;}*/ ?>
<!-- subcat -->
<div class="subcat_wrapp clearfix">
    
    <div class="clearfix">
        <?php if(is_array($subcat) || $subcat instanceof \think\Collection): $i = 0; $__LIST__ = $subcat;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sc): $mod = ($i % 2 );++$i;if(isset($sc['child'])): ?>
        <div class="subcat_caption"><span><?php echo $sc['cat_name']; ?></span></div>
        <div class="clearfix">
            <?php if(is_array($sc['child']) || $sc['child'] instanceof \think\Collection): $i = 0; $__LIST__ = $sc['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($i % 2 );++$i;?>
            <a class="subcat_item" style="padding-bottom: 0"
               href="<?php echo $docroot; ?>weshop/Product/view_list/cat/<?php echo $child['cat_id']; ?>">
                <img src='<?php echo $child['cat_image']; ?>' />
                <span class="Elipsis block font12"><?php echo $child['cat_name']; ?></span>
            </a>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <?php else: ?>
        <a class="subcat_item" style="padding-bottom: 0"
           href="<?php echo $docroot; ?>weshop/Product/view_list/cat/<?php echo $sc['cat_id']; ?>">
            <img src='<?php echo $sc['cat_image']; ?>' />
            <span class="Elipsis block font12"><?php echo $sc['cat_name']; ?></span>
        </a>
        <?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
<!-- subcat -->