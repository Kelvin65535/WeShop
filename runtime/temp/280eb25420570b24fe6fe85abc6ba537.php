<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"/var/www/html/application/weshop/view/product/ajax_newproduct.html";i:1496839344;}*/ ?>
<div style="margin: 0 10px;">
    
</div>
<?php if(is_array($products) || $products instanceof \think\Collection): $i = 0; $__LIST__ = $products;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pd): $mod = ($i % 2 );++$i;?>
<section class="productListWrap hoz" onclick="location = '<?php echo $docroot; ?>weshop/Product/detail/id/<?php echo $pd['product_id']; ?>';">
    <a class="productList<?php if($stype != 'hoz'): ?> clearfix<?php endif; ?>">
        <img width="200" src="<?php echo $pd['catimg']; ?>" />
        <section>
            <title class="title"><?php echo $pd['product_name']; ?></title>
            <span class='prices'>&yen;<?php echo $pd['sale_prices']; ?></span>
        </section>
    </a>
</section>
<?php endforeach; endif; else: echo "" ;endif; ?>