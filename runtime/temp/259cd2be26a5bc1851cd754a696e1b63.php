<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"/var/www/html/application/admin/view/fancypage/ajax_pd_blocks.html";i:1496393441;}*/ ?>
<?php if(is_array($products) || $products instanceof \think\Collection): $i = 0; $__LIST__ = $products;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pd): $mod = ($i % 2 );++$i;?>
<div class="pdBlock" data-id="<?php echo $pd['product_id']; ?>">
    <a class="sel"></a>
    <p class="title Elipsis"><?php echo $pd['product_name']; ?></p>
    <img height="100" width="100" src="<?php echo $pd['catimg']; ?>" />
    <p class="prices Elipsis"><?php echo $pd['sale_prices']; ?></p>
</div>
<?php endforeach; endif; else: echo "" ;endif; ?>