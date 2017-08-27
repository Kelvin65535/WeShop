<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"/var/www/html/application/admin/view/mainpage/settings/settings_menu_data.html";i:1496414840;}*/ ?>
<?php if(is_array($menu['button']) || $menu['button'] instanceof \think\Collection): $i = 0; $__LIST__ = $menu['button'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$m): $mod = ($i % 2 );++$i;?>
<div class="tMenus">
    <div class="menutop" data-name="<?php if(isset($m['name'])): ?><?php echo $m['name']; endif; ?>" data-type="<?php if(isset($m['type'])): ?><?php echo $m['type']; endif; ?>" data-url="<?php if(isset($m['url'])): ?><?php echo $m['url']; endif; ?>" data-key="<?php if(isset($m['key'])): ?><?php echo $m['key']; endif; ?>">
        <span class="n"><?php echo $m['name']; ?></span>
        <a class="del" href="javascript:;"></a>
        <a class="sadd" href="javascript:;"></a>
    </div>
    <ul class="menusubs">
        <?php if(!(empty($m['sub_button']) || ($m['sub_button'] instanceof \think\Collection && $m['sub_button']->isEmpty()))): if(is_array($m['sub_button']) || $m['sub_button'] instanceof \think\Collection): $i = 0; $__LIST__ = $m['sub_button'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$s): $mod = ($i % 2 );++$i;?>
        <li data-type="<?php if(isset($s['type'])): ?><?php echo $s['type']; endif; ?>" data-name="<?php if(isset($s['name'])): ?><?php echo $s['name']; endif; ?>" data-url="<?php if(isset($s['url'])): ?><?php echo $s['url']; endif; ?>" data-key="<?php if(isset($s['key'])): ?><?php echo $s['key']; endif; ?>">
            <span class="n"><?php echo $s['name']; ?></span>
            <a class="del" href="javascript:;"></a>
        </li>
        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
    </ul>
</div>
<?php endforeach; endif; else: echo "" ;endif; ?>