<?php if (!defined('THINK_PATH')) exit(); /*a:8:{s:64:"/var/www/html/application/admin/view/mainpage/orders/manage.html";i:1493822560;s:60:"/var/www/html/application/admin/view/mainpage/header_v2.html";i:1485246805;s:80:"/var/www/html/application/admin/view/mainpage/modal/orders/modal_order_view.html";i:1493821642;s:82:"/var/www/html/application/admin/view/mainpage/modal/orders/modal_order_delete.html";i:1493931620;s:82:"/var/www/html/application/admin/view/mainpage/modal/orders/modal_order_modify.html";i:1493820757;s:83:"/var/www/html/application/admin/view/mainpage/modal/orders/modal_export_orders.html";i:1493937477;s:88:"/var/www/html/application/admin/view/mainpage/modal/orders/modal_order_view_express.html";i:1493820757;s:60:"/var/www/html/application/admin/view/mainpage/footer_v2.html";i:1493822582;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="renderer" content="webkit">
    <title>iWshop</title>
    <link href="<?php echo $docroot; ?>favicon.ico" rel="Shortcut Icon" />
    <script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/lib/angularjs/angular.min.js"></script>
    <link rel="Stylesheet" type="text/css" href="<?php echo $docroot; ?>public/static/css/wshop_admin_style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $docroot; ?>public/static/css/bootstrap/bootstrap.css"/>
    <link rel="Stylesheet" type="text/css" href="<?php echo $docroot; ?>public/static/css/jquery.datetimepicker.css" />
    <link rel="Stylesheet" type="text/css" href="<?php echo $docroot; ?>public/static/script/lib/umeditor/themes/default/css/umeditor.min.css"/>
    <link rel="Stylesheet" type="text/css" href="<?php echo $docroot; ?>public/static/css/font-awesome.min.css" />
</head>
<body>
<?php $script_name = 'orders_manage_controller'; ?>

<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/lib/jquery.PrintArea.js"></script>

<div class="pd15" ng-controller="orderController" ng-app="ngApp">

    
<!-- modal-查看订单 -->
<div class="modal fade" id="modal_order_view">
    <div class="modal-dialog" style="width: 650px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">查看订单</h4>
            </div>
            <div class="modal-body clearfix" id="modal_content_order_view">

                <div class="row">
                    <div class="col-xs-6">
                        <p>
                            <b>订单状态：</b><span class="orderstatus {{orderInfo.status}}">{{orderInfo.statusX}}</span>
                        </p>
                    </div>
                    <div class="col-xs-6">
                        <p>
                            <b>订单金额：</b><span class="text-danger">&yen;{{orderInfo.order_amount}}</span>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <p>
                            <b>下单时间：</b><span class="font12">{{orderInfo.order_time}}</span>
                        </p>
                    </div>
                    <div class="col-xs-6">
                        <p ng-show="orderInfo.receive_time != null">
                            <b>收货时间：</b><span class="font12">{{orderInfo.receive_time}}</span>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <p>
                            <b>订单编号：</b><span class="font12">{{orderInfo.serial_number}}</span>
                        </p>
                    </div>
                    <div class="col-xs-6">
                        <p ng-show="orderInfo.wepay_serial != null">
                            <b>支付编号：</b><span class="font12">{{orderInfo.wepay_serial}}</span>
                        </p>
                    </div>
                </div>

                <table class="table table-bordered table-hover table-responsive table-fixed" style="margin: 10px 0;">
                    <tr>
                        <th width="280px">商品名称</th>
                        <th class="text-center">商品规格</th>
                        <th class="text-center">下单单价</th>
                        <th class="text-center">下单数量</th>
                        <th class="text-center">总计</th>
                    </tr>
                    <tr ng-repeat="product in orderInfo.products">
                        <td class="breakTd">{{product.product_name}}</td>
                        <td class="text-center breakTd">{{product.det_name1}}{{product.det_name2}}</td>
                        <td class="text-center text-danger">&yen;{{product.product_discount_price}}</td>
                        <td class="text-center">{{product.product_count}}件</td>
                        <td class="text-center text-danger">&yen;{{(product.product_discount_price *
                            product.product_count).toFixed(2)}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">配送费用</td>
                        <td class="text-center text-danger">&yen;{{orderInfo.order_expfee}}</td>
                    </tr>
                </table>

                <div class="row">
                    <div class="col-xs-6">
                        <p><b>姓名</b>：{{orderInfo.address.user_name}}</p>
                    </div>
                    <div class="col-xs-6">
                        <p ng-show="orderInfo.send_time != null">
                            <b>快递公司</b>：{{orderInfo.expressName}}
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <p><b>电话</b>：{{orderInfo.address.tel_number}}</p>
                    </div>
                    <div class="col-xs-6" ng-show="orderInfo.send_time != null">
                        <p><b>快递单号</b>：{{orderInfo.express_code}}</p>
                    </div>
                </div>

                <p><b>地址</b>：<span>{{orderInfo.address.address}}</span></p>

                <p><b>邮编</b>：{{orderInfo.address.postal_code}}</p>

                <p><b>配送</b>：{{orderInfo.exptime}}</p>

                <p><b>备注</b>：{{orderInfo.leword}}</p>

                <div ng-show="orderInfo.status == 'payed'" class="row"
                     style="margin-top: 15px;border-top: 1px solid #dedede;padding-top: 15px;">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <label>快递单号</label>
                            <input type="text" placeholder="请输入快递单号" ng-model="express_code" class="form-control"
                                   value="{{express_code}}"/>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>快递公司</label>
                            <select class="form-control" ng-model="express_company"
                                    ng-options="comp.code as comp.name for comp in express_companys"></select>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>配送人员</label>
                            <select class="form-control" ng-model="express_staff"
                                    ng-options="comp.openid as comp.name for comp in express_staffs">
                                <option value="">无配送人员</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" ng-click="printArea()">
                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span>打印
                </button>
                <button ng-show="!isExprecord && orderInfo.status == 'unpay'" type="button" ng-click="orderPayed()"
                        class="btn btn-success">
                    <span class="glyphicon glyphicon-yen" aria-hidden="true"></span>已支付
                </button>
                <button ng-show="!isExprecord && orderInfo.status == 'delivering'" id="order-confirm-btn" type="button"
                        class="btn btn-success">确认收货
                </button>
                <button ng-show="!isExprecord && orderInfo.status == 'payed'" ng-click="orderExpress()" type="button"
                        class="btn btn-success">
                    <span class="glyphicon glyphicon-send" aria-hidden="true"></span>确认发货
                </button>
                <button ng-show="!isExprecord && orderInfo.status == 'payed'" type="button" class="btn btn-default"
                        ng-click="generateExpressCode()">生成单号
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>

    <!-- modal-删除订单 -->
<div class="modal fade" id="modal_order_delete">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">删除订单</h4>
            </div>
            <div class="modal-body">
                删除该订单将不可恢复
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-danger">删除</button>
            </div>
        </div>
    </div>
</div>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>

</body>
</html>
    <div class="modal fade" id="modal_export_orders">
    <div class="modal-dialog" style="width: 450px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">导出订单</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>开始时间</label>
                            <input type="text" placeholder="点击选择时间" class="form-control" id="stime" value="" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>结束时间</label>
                            <input type="text" placeholder="点击选择时间" class="form-control" id="etime" value="" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>订单类型</label>
                            <select class="form-control" id="otype">
                                <option value="">全部</option>
                                <option value="0">未支付</option>
                                <option value="1">进行中</option>
                                <option value="8">配送中</option>
                                <option value="2">已完成</option>
                                <option value="3">取消中</option>
                                <option value="4">已取消</option>
                                <option value="5">已关闭</option>
                                <option value="6">待评价</option>
                                <option value="7">已退款</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-success" id="confirm-export">导出</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>

</body>
</html>

    
    <div class="pheader clearfix">
        <div class="pull-left">
            <div id="SummaryBoard" style="width:350px">
                <div class="row">
                    <div class="col-xs-9">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <button type="button" style="line-height: 20px;"
                                        class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false"><span
                                        id="search-type-label">订单号</span><span class="caret"
                                                                               style="margin-left: 5px;"></span>
                                </button>
                                <ul class="dropdown-menu small" id="search-type">
                                    <li><a href="#" data-type="0">订单号</a></li>
                                </ul>
                            </div>
                            <input type="text" style="height: 32px;" class="form-control search-field"
                                   placeholder="请输入搜索内容"
                                   aria-describedby="sizing-addon3" id="search-key"/>

                            <div class="input-group-btn">
                                <button type="button" id="search-button" class="btn btn-default"><span
                                        class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-3" style="padding-left: 0">
                        <div class="input-group-btn">
                            <button type="button" style="line-height: 20px;" class="btn btn-default dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span
                                    id="order-status-label">全部 ({{statdata.all}})</span><span class="caret"
                                                                                              style="margin-left: 5px;"></span>
                            </button>
                            <ul class="dropdown-menu small" id="order-status">
                                <li><a href="#" data-type="all">全部 ({{statdata.all}})</a></li>
                                <li><a href="#" data-type="payed">已支付 ({{statdata.payed}})</a></li>
                                <li><a href="#" data-type="delivering">快递在途 ({{statdata.delivering}})</a></li>
                                <li><a href="#" data-type="unpay">未支付 ({{statdata.unpay}})</a></li>
                                <li><a href="#" data-type="canceled">已取消 ({{statdata.canceled}})</a></li>
                                <li><a href="#" data-type="received">已完成 ({{statdata.received}})</a></li>
                                <li><a href="#" data-type="closed">已关闭 ({{statdata.closed}})</a></li>
                                <li><a href="#" data-type="refunded">已退款 ({{statdata.refunded}})</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <div class="button-set">
                <button type="button" class="btn btn-success" data-toggle="modal"
                        data-target="#modal_export_orders">
                    <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>导出
                </button>
                <button type="button" class="btn btn-default" id="list-reload" ng-click="fnGetList()">
                    <span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>刷新
                </button>
            </div>
        </div>
    </div>
    <div class="panel panel-default" style="margin-bottom: 60px;">
        <table class="table table-hover table-bordered table-fixed">
            <thead>
            <tr>
                <th width="50px"></th>
                <th width="130px">订单编号</th>
                <th>姓名</th>
                <th>电话</th>
                <th>地区</th>
                <th>订单金额</th>
                <th>运费</th>
                <th>商品数量</th>
                <th>下单时间</th>
                <th width="50px">状态</th>
                <th width="92px">操作</th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="order in orderList" ng-if="listcount > 0">
                <td class="text-center">
                    <img class="img-rounded" width="35px" height="35px"
                         ng-src="{{order.data.catimg}}@1e_1c_0o_0l_35h_35w_90q.src"/>
                </td>
                <td class="breakTd" ng-bind="order.serial_number"></td>
                <td ng-bind="order.address.user_name"></td>
                <td ng-bind="order.address.tel_number"></td>
                <td class="breakTd" ng-bind="::order.address.province + order.address.city"></td>
                <td class="text-danger" ng-bind="::'&yen;'+order.order_amount"></td>
                <td class="text-danger" ng-bind="::'&yen;'+order.order_expfee"></td>
                <td ng-bind="::order.product_count + '件'"></td>
                <td ng-bind="::order.order_time"></td>
                <td class="orderstatus {{order.status}}" ng-bind="::order.statusX"></td>
                <td>
                    <a class="text-success" data-toggle="modal" data-target="#modal_order_view"
                       data-id="{{order.order_id}}" href="#">查看</a>
                    <a class="text-muted" data-toggle="modal" data-target="#modal_order_viewexpress"
                       data-com="{{order.express_com}}" data-code="{{order.express_code}}"
                       data-id="{{order.order_id}}" href="#">物流</a>
                    <a class="text-danger" data-toggle="modal" data-target="#modal_order_delete"
                       data-order_id="{{order.order_id}}" href="#">删除</a>
                </td>
            </tr>
            <tr ng-if="listcount == 0">
                <td colspan="11" class="EmptyTd">暂无数据</td>
            </tr>
            </tbody>
        </table>
    </div>
    

</div>

<div class="navbar-fixed-bottom bottombar">
    <div id="pager-bottom">
        <ul class="pagination-sm pagination"></ul>
    </div>
</div>

<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/admin/orders/<?php echo $script_name; ?>.js"></script>

</body>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/lib/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/lib/lobibox/lobibox.js"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/lib/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/lib/jqPaginator.min.js"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/admin/service/order_service.js"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/admin/service/user_service.js"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/admin/service/util_service.js"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/admin/service/feedback_service.js"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/admin/service/company_service.js"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/admin/service/gmess_service.js"></script>
<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/admin/service/rebate_service.js"></script>
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
</html>