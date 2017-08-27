<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:53:"/var/www/html/application/weshop/view/order/cart.html";i:1496331144;}*/ ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv=Content-Type content="text/html;charset=utf-8" />
    <title><?php echo $title; ?> - <?php echo $settings['shopname']; ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="format-detection" content="telephone=no" />
    <link href="<?php echo $docroot; ?>public/static/css/wshop_cart.css" type="text/css" rel="Stylesheet" />
    <link href="<?php echo $docroot; ?>public/static/css/base/base_animate.css" type="text/css" rel="Stylesheet" />
    <script type="text/javascript" src="http://cdn.iwshop.org/scripts/crypto-md5.js"></script>
    <link href="<?php echo $docroot; ?>public/static/script/lib/mobiscroll/css/mobiscroll.custom-2.17.1.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo $docroot; ?>public/static/css/weui/weui.min.css" type="text/css" rel="Stylesheet"/>
    <link href="<?php echo $docroot; ?>public/static/css/weshop_public_bottom.css" rel="stylesheet" type="text/css">
</head>
<body>

<div id="addrPick"></div>
<input type="hidden" id="payOn" value="<?php if(!\think\Config::get('order_nopayment')): ?>1<?php else: ?>0<?php endif; ?>" />
<input type="hidden" id="addrOn" value="<?php if(\think\Config::get('wechatVerifyed')): ?>1<?php else: ?>0<?php endif; ?>" />
<input type="hidden" id="paycallorderurl" value="<?php echo $docroot; ?>?/Order/ajaxCreateOrder" />

<div class="weui_cells_title">收货信息</div>

<!-- 收货地址 -->
<div id="express-bar"></div>
<div id="express_address" href="javascript:;">
    <div id="wrp-btn">点击选择收货地址</div>
    <div class="express-person-info clearfix">
        <div class="express-person-name">
            <span id="express-name"></span><span id="express-person-phone"></span>
        </div>
    </div>
    <div class="express-address-info">
        <span id="express-address"></span>
    </div>
</div>

<div class="weui_cells_title">订单信息</div>

<div id="orderDetailsWrapper" data-minheight="68px"></div>

<div id="extra-field" style="display: none;">
    <div class="weui_cells_title">配送时间</div>

    <section class="orderopt" id="exptime-selector">
        <span class="label">配送时间</span>
        <span class="value" id="input-exptime-label">随时可以</span>
        <input type="text" id="input-exptime"/>
        <input type="hidden" id="exptime" value="随时可以"/>
    </section>

    <div class="weui_cells_title">订单备注</div>

    <div class="weui_cells weui_cells_form">
        <div class="weui_cell">
            <div class="weui_cell_bd weui_cell_primary">
                <textarea class="weui_textarea" placeholder="请输入订单备注" id="input-remark" rows="3"></textarea>
                <div class="weui_textarea_counter"><span>0</span>/200</div>
            </div>
        </div>
    </div>

    <?php if(isset($userInfo['balance'])): ?>
    <div class="weui_cells_title">使用余额</div>

    <div class="weui_cells weui_cells_form">
        <div class="weui_cell weui_cell_switch">
            <div class="weui_cell_hd weui_cell_primary" style="font-size: 12px">使用余额 &yen;<?php echo $userInfo['balance']; ?> 抵扣</div>
            <div class="weui_cell_ft">
                <input class="weui_switch" type="checkbox" id="cart-balance-check" />
                <input type="hidden" value="<?php echo $userInfo['balance']; ?>" id="cart-balance-pay" />
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- 订单总额 -->
<div id="orderSummay" class='hidden'>
    <div>
        运费 : <b class="prices font13" id="order_expfee">&yen;0.00</b>
    </div>
    <div id="envsDisTip">
        红包 : <b class="prices font13" id="envs_amount">&yen;0.00</b>
    </div>
    <div id="reciTip">
        税费 : <b class="prices font13" id="reciTip_amount">&yen;0.00</b>
    </div>
    <div id="balanceTip">
        余额 : <b class="prices font13" id="balanceTip_amount">&yen;0.00</b>
    </div>
    <div>
        总价 : <b class="prices font13" id="order_amount_sig">&yen;0.00</b>
    </div>
    <div>
        总计 : <b class="prices" id="order_amount">&yen;0.00</b>
    </div>
</div>

<!-- 模板开始，可以使用script（type设置为text/html）来存放模板片段，并且用id标示 -->
<script id="t:cart_list" type="text/html">
    
    <%for(var i=0;i < list.length;i++){%>

    <% var carts = list[i].cart_datas; %>

    <%for(var j=0;j < carts.length;j++){%>

    <section class="cartListWrap clearfix" id="cartsec<%=carts[j].product_id%>">
        <input type="hidden" value="<%=carts[j].envstr%>" id="pd-envs-<%=carts[j].product_id%>"
               data-pid="<%=carts[j].product_id%>" class="pd-envstr" />
        <img alt="<%=carts[j].product_name%>" width="100" height="100" src="<%=carts[j].catimg%>" />
        <div class="cartListDesc">
            <p class="title">
                <%=carts[j].product_name%>
            </p>
            <p class="count">
                <span class="spec Elipsis"><%=carts[j].specname%></span>
                <span class="dprice prices"
                      data-expfee="{$product_list[i].product_expfee}"
                      data-price="<%=carts[j].sale_price%>"
                      data-weight="<%=carts[j].product_weight%>"
                      data-count="<%=carts[j].count%>">&yen; <%=carts[j].sale_price%>
						</span>
            </p>
            <dl class="pd-dsc clearfix">
                <dt class="productCount clearfix" data-pid="<%=carts[j].product_id%>" data-spid="<%=carts[j].spec_id%>">
                    <a class="btn productCountMinus" href='javascript:;'></a>
                    <span class="productCountNum">
							<input type='tel'
                                   data-prom-limit="0"
                                   value="<%=carts[j].count%>"
                                   class="dcount productCountNumi" />
						</span>
                    <a class="btn productCountPlus" href='javascript:;'></a>
                </dt>
            </dl>
            <a class="cartDelbtn" data-pdid="<%=carts[j].product_id%>" data-spid="<%=carts[j].spec_id%>"></a>
        </div>
    </section>

    <%}%>

    <%}%>
    
</script>
<!-- 模板结束 -->

<!-- 微信支付按钮 -->
<div class="button green" style='display: none' id="wechat-payment-btn"><b></b>微信安全支付</div>
<!-- 微信JSSDK -->
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<!-- 微信JSSDK -->
<script type="text/javascript">
    window.onload = function (){
        wx.config({
            debug: true,
            appId: '<?php echo $signPackage['appId']; ?>',
            timestamp: <?php echo $signPackage['timestamp']; ?>,
            nonceStr: '<?php echo $signPackage['nonceStr']; ?>',
            signature: '<?php echo $signPackage['signature']; ?>',
            jsApiList: ['chooseWXPay','openAddress']
        });
        addrsignPackage = '{}';
    }

</script>
<script data-main="<?php echo $docroot; ?>public/static/script/weshop/shop_cart.js" src="http://apps.bdimg.com/libs/require.js/2.1.9/require.min.js"></script>

<!--页面尾部-->
<script language='javascript' src='http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js'> </script>


</body>
</html>
