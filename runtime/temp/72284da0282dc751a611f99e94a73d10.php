<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:77:"/var/www/html/application/admin/view/mainpage/users/iframe_list_customer.html";i:1493974330;s:60:"/var/www/html/application/admin/view/mainpage/header_v2.html";i:1485246805;s:79:"/var/www/html/application/admin/view/mainpage/modal/user/modal_modify_user.html";i:1493982010;s:79:"/var/www/html/application/admin/view/mainpage/modal/user/modal_export_user.html";i:1493974157;s:80:"/var/www/html/application/admin/view/mainpage/modal/orders/modal_order_view.html";i:1493821642;s:60:"/var/www/html/application/admin/view/mainpage/footer_v2.html";i:1493822582;}*/ ?>
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
<?php $script_name = 'user_list_controller'; ?>
<style type="text/css">
    td {
        padding: 0px 6px !important;
    }
</style>
<div class="pd15" ng-controller="userListController" ng-app="ngApp">

    <input type="hidden" id="groudId" value="<?php echo $gid; ?>"/>

    <!-- modal-用户编辑 -->
<div class="modal fade" id="modal_modify_user">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">编辑用户</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>用户姓名</label>
                            <input type="text" placeholder="请输入用户姓名" ng-model="user.client_name" class="form-control" />
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>用户电话</label>
                            <div class="form-group">
                                <label class="sr-only" for="usr-client-phone"></label>
                                <div class="input-group">
                                    <div class="input-group-addon" style="padding-right: 8px"><span
                                            class="glyphicon glyphicon-earphone" aria-hidden="true"></span></div>
                                    <input type="text" placeholder="请输入用户电话" id="usr-client-phone"
                                           ng-model="user.client_phone" class="form-control"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label>性别</label>
                            <select class="form-control" ng-model="user.client_sex" ng-options="sex.id as sex.name for sex in sexs" ></select>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label>积分</label>
                            <input type="text" onclick="this.select()" placeholder="请输入" ng-model="user.client_credit" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>省份</label>
                            <input type="text" placeholder="请输入省份" ng-model="user.client_province" class="form-control" name="supp_stime" value="" />
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>城市</label>
                            <input type="text" placeholder="请输入城市" ng-model="user.client_city" class="form-control" name="supp_sprice" value="" />
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>分组</label>
                            <select class="form-control" ng-model="user.client_level" ng-options="level.id as level.level_name for level in userLevel" >
                                <option value="">请选择分组</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>用户备注</label>
                            <textarea class="form-control" ng-model="user.client_remark" rows="3" name="supp_desc" placeholder="请填写用户备注"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" ng-click="modifyUser($event)">保存</button>
            </div>
        </div>
    </div>
</div>
    <!-- 用户导出的modal -->
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>

</body>
</html>
    
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


    

    <div class="pheader clearfix">
        <div class="pull-left">
            <div id="SummaryBoard" style="width:350px">
                <div class="row">
                    <div class="col-xs-9">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <button type="button" style="line-height: 20px;" class="btn btn-default dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span
                                        id="search-type-label">电话</span><span class="caret"
                                                                              style="margin-left: 5px;"></span>
                                </button>
                                <ul class="dropdown-menu small" id="search-type">
                                    <li><a href="#" data-type="0">电话</a></li>
                                    <li><a href="#" data-type="1">姓名</a></li>
                                </ul>
                            </div>
                            <input type="text" style="height: 32px;border-left: none;" class="form-control search-field"
                                   placeholder="请输入搜索内容" aria-describedby="sizing-addon3" id="search-key"/>
                            <div class="input-group-btn">
                                <button type="button" id="search-button" class="btn btn-default"><span
                                        class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                            </div>
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
                <!--<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal_modify_user">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>添加用户
                </button>-->
                <button type="button" class="btn btn-gray" id="list-reload" onclick="location.reload()">刷新</button>
            </div>
        </div>
    </div>

    <table class="table table-hover table-bordered" style="margin-bottom: 50px;">
        <thead>
        <tr>
            <th width="50px">编号</th>
            <th width="60px" class="text-center">头像</th>
            <th width="150px">姓名</th>
            <th width="150px">省市</th>
            <th>电话</th>
            <th class="text-center" width="50px">性别</th>
            <th>积分</th>
            <th>余额</th>
            <th>分组</th>
            <th>注册日期</th>
            <th width="65px" class="text-center">操作</th>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="usr in userlist" class="usrlist">
            <td ng-bind="::usr.client_id"></td>
            <td style="width: 53px;" class="text-center"><img class="ccl-head" ng-src="{{usr.client_head}}"/></td>
            <td ng-bind="::usr.client_name"></td>
            <td>{{usr.client_province}}{{usr.client_city}}</td>
            <td ng-class="{'text-muted': usr.client_phone == '未录入'}" ng-bind="::usr.client_phone"></td>
            <td class="text-center">{{sexStr[usr.client_sex]}}</td>
            <td ng-bind="usr.client_credit"></td>
            <td class="text-danger" ng-bind="'&yen;' + usr.client_money"></td>
            <td>{{userLevelStr[usr.client_level]}}</td>
            <td>{{usr.client_joindate}}</td>
            <td>
                <a class="text-success" data-toggle="modal" data-target="#modal_modify_user" data-id="{{usr.client_id}}"
                   href="#">编辑</a>
                <a class="text-danger" data-id="{{usr.client_id}}" ng-click="deleteUser($event)" href="#">删除</a>
            </td>
        </tr>
        </tbody>
    </table>
</div>



</div>

<script type="text/javascript" src="<?php echo $docroot; ?>public/static/script/admin/user/<?php echo $script_name; ?>.js"></script>

<div class="navbar-fixed-bottom bottombar">
    <div id="pager-bottom">
        <ul class="pagination-sm pagination"></ul>
    </div>
</div>

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
