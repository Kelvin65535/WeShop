<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:80:"/var/www/html/application/admin/view/mainpage/products/iframe_alter_product.html";i:1496418512;s:57:"/var/www/html/application/admin/view/mainpage/header.html";i:1485246950;s:57:"/var/www/html/application/admin/view/mainpage/footer.html";i:1485248131;}*/ ?>
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
<link rel="stylesheet" type="text/css" href="<?php echo $docroot; ?>public/static/css/jquery.datetimepicker.css"/>
<?php if($ed): ?><input type="hidden" value="<?php echo $pd['product_id']; ?>" id="pid" /><?php endif; ?>
<input type="hidden" value="<?php echo $mod; ?>" id="mod"/>
<input type="hidden" value="<?php echo \think\Request::instance()->server('HTTP_REFERER'); ?>" id="http_referer"/>
<i id="scriptTag">page_iframe_alter_product</i>
<form id="pd-baseinfo" class='pt58'>
    <div style="padding: 22px;" class="clearfix">

        <input id="pd-catimg" name="catimg" type="hidden" value="<?php echo $pd['catimg']; ?>"/>
        <input id="pd-serial-val" type="hidden" value="<?php echo $pd['product_serial']; ?>"/>
        <input id="pd-form-cat" type="hidden" value="<?php echo $cat; ?>"/>
        <?php if(is_array($pd['images']) || $pd['images'] instanceof \think\Collection): if( count($pd['images'])==0 ) : echo "" ;else: foreach($pd['images'] as $key=>$pdi): ?>
        <input class="pd-images" type="hidden" value="<?php echo $pdi['image_path']; ?>" data-sort="<?php echo $pdi['image_sort']; ?>"/>
        <?php endforeach; endif; else: echo "" ;endif; ?>

        <div class="clearfix">

            <div id="alterProductLeft">

                <!-- 商品名称 -->
                <div class="fv2Field clearfix">
                    <div class="fv2Left">
                        <span>商品名称</span>
                    </div>
                    <div class="fv2Right">
                        <input type="text" class="form-control" name="product_name"
                               value="<?php if($ed): ?><?php echo $pd['product_name']; endif; ?>" id="pd-form-title" autofocus/>
                    </div>
                </div>

                <!-- 商品简称 -->
                <div class="fv2Field clearfix">
                    <div class="fv2Left">
                        <span>商品简称</span>
                    </div>
                    <div class="fv2Right">
                        <input type="text" class="form-control" name="product_subname" id="pd-form-subname"
                               value="<?php if($ed): ?><?php echo $pd['product_subname']; endif; ?>"/>
                    </div>
                </div>

                <!-- 商品编号 -->
                <div class="fv2Field clearfix">
                    <div class="fv2Left">
                        <span>商品编号</span>
                    </div>
                    <div class="fv2Right">
                        <input type="text" class="form-control" name="product_code" onclick="this.select();"
                               value="<?php if($ed): ?><?php echo $pd['product_code']; endif; ?>" id="pd-form-code"/>
                    </div>
                </div>

                <!-- 商品简介 -->
                <div class="fv2Field clearfix">
                    <div class="fv2Left">
                        <span>商品简介</span>
                    </div>
                    <div class="fv2Right">
                        <textarea name="product_subtitle" class="form-control" style="width: 100%"
                                  placeholder="请填写商品简介" rows="4"><?php if($ed > 0): ?><?php echo $pd['product_subtitle']; endif; ?></textarea>
                    </div>
                </div>

                <!-- 商品分类 -->
                <div class="fv2Field clearfix">
                    <div class="fv2Left">
                        <span>商品分类</span>
                    </div>
                    <div class="fv2Right">
                        <select class="form-control" id="pd-catselect" style="color:#000" name="product_cat">
                            <?php if(!(empty($categorys) || ($categorys instanceof \think\Collection && $categorys->isEmpty()))): foreach($categorys as $cat1): ?>
                            <option value="<?php echo $cat1['dataId']; ?>"
                                    <?php if($cat1['hasChildren']): ?>disabled<?php endif; ?>><?php echo $cat1['name']; ?></option>
                            <?php foreach($cat1['children'] as $cat2): ?>
                            <option value="<?php echo $cat2['dataId']; ?>" <?php if($cat2['hasChildren']): ?>disabled<?php endif; ?>>
                            -- <?php echo $cat2['name']; ?></option>
                            <?php foreach($cat2['children'] as $cat3): ?>
                            <option value="<?php echo $cat3['dataId']; ?>" <?php if($cat3['hasChildren']): ?>disabled<?php endif; ?>>
                            ---- <?php echo $cat3['name']; ?></option>
                            <?php foreach($cat3['children'] as $cat4): ?>
                            <option value="<?php echo $cat4['dataId']; ?>" <?php if($cat4['hasChildren']): ?>disabled<?php endif; ?>>
                            ------ <?php echo $cat4['name']; ?></option>
                            <?php endforeach; endforeach; endforeach; endforeach; else: ?>
                            <option value="0">未分类</option>
                            <?php endif; ?>
                        </select>
                        <div class='fv2Tip'>若分类为空，请到商品分类设置中添加至少一个商品分类</div>
                    </div>
                </div>

                <!-- 商品品牌 -->
                <div class="fv2Field clearfix">
                    <div class="fv2Left">
                        <span>商品品牌</span>
                    </div>
                    <div class="fv2Right">
                        <select class="form-control" id="pd-serial" name="product_brand">
                            <option value="0" <?php if($pd['product_brand'] == 0): ?>selected<?php endif; ?>>默认</option>
                            <?php foreach($brands as $ser): ?>
                            <option value="<?php echo $ser['id']; ?>"
                                    <?php if($pd['product_brand'] == $ser['id']): ?>selected<?php endif; ?>><?php echo $ser['brand_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- 商户名称 -->
                <div class="fv2Field clearfix">
                    <div class="fv2Left">
                        <span>商户名称</span>
                    </div>
                    <div class="fv2Right">
                        <select class="form-control" id="pd-suppliers" name="product_supplier">
                            <option value="0" <?php if($pd['suppiler'] == 0): ?>selected<?php endif; ?>>无</option>
                            <?php foreach($suppliers as $supp): ?>
                            <option value="<?php echo $supp['id']; ?>"
                                    <?php if($pd['product_supplier'] == $supp['id']): ?>selected<?php endif; ?>><?php echo $supp['supp_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- 商品价格 -->
                <div class="fv2Field clearfix">
                    <div class="fv2Left">
                        <span>商品价格</span>
                    </div>
                    <div class="fv2Right">
                        <input type="text" name="sell_price" class="form-control" onclick="this.select();"
                               value="<?php if($ed): ?><?php echo $pd['sale_prices']; else: ?>0.00<?php endif; ?>" id="pd-form-prices"/>
                    </div>
                </div>

                <!-- 参考价格 -->
                <div class="fv2Field clearfix">
                    <div class="fv2Left">
                        <span>参考价格</span>
                    </div>
                    <div class="fv2Right">
                        <input type="hidden" id="pd-form-discount" value="<?php if($ed): ?><?php echo $pd['discount']; else: ?>100<?php endif; ?>"/>
                        <input type="text" class="form-control" name="market_price" onclick="this.select();"
                               value="<?php if($ed and $pd['market_price']): ?><?php echo $pd['market_price']; else: ?>0.00<?php endif; ?>"
                               id="market_price"/>
                    </div>
                </div>

                <!-- 商品重量 -->
                <div class="fv2Field clearfix">
                    <div class="fv2Left">
                        <span>商品重量</span>
                    </div>
                    <div class="fv2Right">
                        <input type="text" class="form-control" name="product_weight" id="pd-form-weight"
                               value="<?php if($ed > 0): ?><?php echo $pd['product_weight']; else: ?>0<?php endif; ?>"/>

                        <div class='fv2Tip'>订单运费计算必备参数，0为默认包邮，单位：克</div>
                    </div>
                </div>

                <!-- 商品产地 -->
                <div class="fv2Field clearfix">
                    <div class="fv2Left">
                        <span>商品产地</span>
                    </div>
                    <div class="fv2Right">
                        <input type="text" class="form-control" name="product_origin"
                               value="<?php if($ed > 0): ?><?php echo $pd['product_origin']; endif; ?>"/>

                        <div class='fv2Tip'>产品原产地</div>
                    </div>
                </div>

                <!-- 储存条件 -->
                <div class="fv2Field clearfix">
                    <div class="fv2Left">
                        <span>储存条件</span>
                    </div>
                    <div class="fv2Right">
                        <input type="text" class="form-control" name="product_storage"
                               value="<?php if($ed > 0): ?><?php echo $pd['product_storage']; endif; ?>"/>

                        <div class='fv2Tip'>冰鲜、干燥、避光等</div>
                    </div>
                </div>

                <!-- 商品单位 -->
                <div class="fv2Field clearfix">
                    <div class="fv2Left">
                        <span>商品单位</span>
                    </div>
                    <div class="fv2Right">
                        <input type="text" class="form-control" name="product_unit"
                               value="<?php if($ed > 0): ?><?php echo $pd['product_unit']; endif; ?>"/>

                        <div class='fv2Tip'>99片/包等</div>
                    </div>
                </div>

                <!-- 商品供货价 -->
                <div class="fv2Field clearfix">
                    <div class="fv2Left">
                        <span>商品供货价</span>
                    </div>
                    <div class="fv2Right">
                        <input type="text" class="form-control" name="supply_price"
                               value="<?php if($ed > 0): ?><?php echo $pd['supply_price']; endif; ?>"/>

                        <div class='fv2Tip'>供货价（用于计算产品利润）</div>
                    </div>
                </div>

                <!-- 商品库存 -->
                <div class="fv2Field clearfix">
                    <div class="fv2Left">
                        <span>商品库存</span>
                    </div>
                    <div class="fv2Right">
                        <input type="text" class="form-control" name="product_instocks"
                               value="<?php if($ed > 0): ?><?php echo $pd['product_instocks']; endif; ?>"/>

                        <div class='fv2Tip'>如果该商品没有设置任何规格，则会使用此库存</div>
                    </div>
                </div>

                <!-- 积分奖励 -->
                <div class="fv2Field clearfix">
                    <div class="fv2Left">
                        <span>积分奖励</span>
                    </div>
                    <div class="fv2Right">
                        <input type="text" class="form-control" name="product_credit" id="pd-form-credit"
                               value="<?php if($ed > 0): ?><?php echo $pd['product_credit']; else: ?>0<?php endif; ?>"/>

                        <div class='fv2Tip'>用户购买商品之后，奖励的积分数量</div>
                    </div>
                </div>

                <!-- 商品运费 -->
                <div class="fv2Field clearfix">
                    <div class="fv2Left">
                        <span>固定运费</span>
                    </div>
                    <div class="fv2Right">
                        <input type="text" class="form-control" name="product_expfee" id="pd-expfee"
                               value="<?php if($ed and $pd['product_expfee'] > 0): ?><?php echo $pd['product_expfee']; else: ?>0<?php endif; ?>"/>

                        <div class='fv2Tip'>订单将以这个金额计算运费，默认为0</div>
                    </div>
                </div>

                <div class="fv2Field clearfix">
                    <div class="fv2Left">
                        <span>商品秒杀</span>
                    </div>
                    <div class="fv2Right">
                        <select class="form-control" id="pd-prom" name="product_prom">
                            <option value="0" <?php if($pd['product_prom'] == 0): ?>selected<?php endif; ?>>不参与秒杀</option>
                            <option value="1" <?php if($pd['product_prom'] == 1): ?>selected<?php endif; ?>>参与秒杀</option>
                        </select>

                        <div id="prom_option" class="<?php if($pd['product_prom'] == 0): ?>hidden<?php endif; ?>" style="margin-top: 10px;">
                            <input type="text" class="form-control mt10" name="product_prom_limitdate"
                                   id="pd-form-msexp" value="<?php echo $pd['product_prom_limitdate']; ?>"/>

                            <div class='fv2Tip'>过期时间 例如：(2015-07-30 00:00)</div>
                            <input type="text" class="form-control mt10" name="product_prom_limitdays"
                                   id="pd-form-msdays" value="<?php echo $pd['product_prom_limitdays']; ?>"/>

                            <div class='fv2Tip'>用户秒杀间隔，单位：天</div>
                            <input type="text" class="form-control mt10" name="product_prom_limit" id="pd-form-mscount"
                                   value="<?php echo $pd['product_prom_limit']; ?>"/>

                            <div class='fv2Tip'>用户限购数量，单位：件</div>
                            <input type="text" class="form-control mt10" name="product_prom_discount"
                                   id="pd-form-discount" value="<?php echo $pd['product_prom_discount']; ?>"/>

                            <div class='fv2Tip'>秒杀折扣，必填选项，百分比（1-100）</div>
                        </div>
                    </div>
                </div>

            </div>

            <div id="alterProductRight">
                <div class="t1">商品首图</div>
                <!-- 商品大图 -->
                <a class="pd-image-sec" data-id="0" href="javascript:;"></a>
                <div class="t2">建议使用500&#215;500尺寸图片 <a id="catimgPv" href="">预览</a></div>
            </div>

        </div>

        <div class="fv2Field clearfix" style="max-width:100%;">
            <div class="fv2Left">
                <span>商品规格</span>
            </div>
            <div class="fv2Right">
                <div class="button-set l pt0">
                    <!-- 规格增加按钮 -->
                    <a class="button" id="pd-spec-add" href="javascript:;">添加</a>
                    <!-- 规格管理按钮 -->
                    <a class="button orange"
                       onclick="$('#__specmanage', parent.parent.document).get(0).click();">规格管理</a>
                </div>
                <table class="table table-bordered <?php if(empty($pd['specs']) || ($pd['specs'] instanceof \think\Collection && $pd['specs']->isEmpty())): ?>hidden<?php endif; ?>" id='pd-spec-frame'>
                    <thead>
                    <tr>
                        <th style="width: 180px;">规格</th>
                        <th style="width: 180px;">>规格</th>
                        <th>售价</th>
                        <th>市场价</th>
                        <th>库存</th>
                        <th style="width: 40px;">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="specselect hidden" data-id="#">
                        <td>
                            <select class="spec1">
                                <option value="0">无规格</option>
                                <?php foreach($speclist as $spec): foreach($spec['dets'] as $dets): ?>
                                <option value="<?php echo $dets['id']; ?>" data-spec="<?php echo $spec['id']; ?>"
                                        data-name="<?php echo $spec['spec_name']; ?>"><?php echo $spec['spec_name']; ?>(<?php echo $dets['det_name']; ?>)
                                </option>
                                <?php endforeach; endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select class="spec2">
                                <option value="0">无规格</option>
                                <?php foreach($speclist as $spec): foreach($spec['dets'] as $dets): ?>
                                <option value="<?php echo $dets['id']; ?>" data-spec="<?php echo $spec['id']; ?>"
                                        data-name="<?php echo $spec['spec_name']; ?>"><?php echo $spec['spec_name']; ?>(<?php echo $dets['det_name']; ?>)
                                </option>
                                <?php endforeach; endforeach; ?>
                            </select>
                        </td>
                        <td><input type="text" onclick="this.select();" class="pd-spec-prices"
                                   value=""></td>
                        <td><input type="text" onclick="this.select();" class="pd-spec-market"
                                   value=""></td>
                        <td><input type="text" onclick="this.select();" class="pd-spec-stock" value="">
                        </td>

                        <td><a class="btn-delete-spectr" href="javascript:;">删除</a></td>
                    </tr>
                    <?php if(is_array($pd['specs']) || $pd['specs'] instanceof \think\Collection): $i = 0; $__LIST__ = $pd['specs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$specs): $mod = ($i % 2 );++$i;?>
                    <tr class="specselect" data-id="<?php echo $specs['id']; ?>">
                        <td>
                            <select class="spec1">
                                <option value="0">无规格</option>
                                <?php foreach($speclist as $spec): foreach($spec['dets'] as $dets): ?>
                                <option value="<?php echo $dets['id']; ?>" data-spec="<?php echo $spec['id']; ?>"
                                        data-name="<?php echo $spec['spec_name']; ?>"
                                        <?php if($specs['id1'] == $dets['id']): ?>selected<?php endif; ?>><?php echo $spec['spec_name']; ?>
                                (<?php echo $dets['det_name']; ?>)
                                </option>
                                <?php endforeach; endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select class="spec2">
                                <option value="0">无规格</option>
                                <?php foreach($speclist as $spec): foreach($spec['dets'] as $dets): ?>
                                <option value="<?php echo $dets['id']; ?>" data-spec="<?php echo $spec['id']; ?>"
                                        data-name="<?php echo $spec['spec_name']; ?>"
                                        <?php if($specs['id2'] == $dets['id']): ?>selected<?php endif; ?>><?php echo $spec['spec_name']; ?>
                                (<?php echo $dets['det_name']; ?>)
                                </option>
                                <?php endforeach; endforeach; ?>
                            </select>
                        </td>
                        <td><input type="text" onclick="this.select();" class="pd-spec-prices"
                                   value="<?php echo $specs['sale_price']; ?>"></td>
                        <td><input type="text" onclick="this.select();" class="pd-spec-market"
                                   value="<?php echo $specs['market_price']; ?>"></td>
                        <td><input type="text" onclick="this.select();" class="pd-spec-stock"
                                   value="<?php echo $specs['instock']; ?>"></td>
                        <td><a class="btn-delete-spectr" href="javascript:;">删除</a></td>
                    </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
                <div class='fv2Tip'>请点击添加 新增一项规格</div>
            </div>
        </div>

        <div class="fv2Field clearfix" style="max-width:100%;">
            <div class="fv2Left">
                <span>商品图集</span>
            </div>
            <div class="fv2Right">
                <div id="pd-ilist" class="clearfix">
                    <a class="pd-image-sec ps20" data-id="1" href="javascript:;"></a>
                    <a class="pd-image-sec ps20" data-id="2" href="javascript:;"></a>
                    <a class="pd-image-sec ps20" data-id="3" href="javascript:;"></a>
                    <a class="pd-image-sec ps20" data-id="4" href="javascript:;"></a>
                    <a class="pd-image-sec ps20" data-id="5" href="javascript:;" style="margin-right: 0;"></a>
                </div>
                <div class='fv2Tip'>请进行图片选择</div>
            </div>
        </div>

        <div class="fv2Field clearfix" style="max-width:100%;">
            <div class="fv2Left">
                <span>详细介绍</span>
            </div>
            <div class="fv2Right">
                <script style='width:100%;' id="ueditorp" type="text/plain"
                        name="product_desc"><?php if($ed): ?><?php echo $pd['product_desc']; endif; ?></script>
            </div>
        </div>

    </div>

</form>
<div class="fix_top fixed" style="height: 55px;">
    <div class='button-set' style="margin-top: 13px;margin-right: 13px;">
        <a class='btn btn-success' id="save_product_btn" href="javascript:;">
            <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>保存
        </a>
        <?php if($mod != 'add'): ?>
        <a class="btn btn-danger" data-product-id="<?php echo $pd['product_id']; ?>" style="margin-left: 5px;">
            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除
        </a>
        <?php endif; ?>
        <a onclick="location.href = $('#http_referer').val();" class="btn btn-default" style="margin-left: 5px;">返回</a>
    </div>
</div>
</body>
</html>

<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/jquery-2.1.1.min.js"></script>
<script data-main="<?php echo $docroot; ?>public/static/script/wdmin-frame.js" src="<?php echo $docroot; ?>public/static/script/lib/require.min.js"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/lib/bootstrap/js/bootstrap.min.js"></script>
