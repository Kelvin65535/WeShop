

var scriptTag = document.getElementById('scriptTag');

var shoproot = location.pathname.substr(0, location.pathname.lastIndexOf('/') + 1);

window.UMEDITOR_HOME_URL = '/public/static/script/lib/umeditor/';

var shopdomain = location.hostname;

// datatable 配置项
var DataTableConfig = {
    "bPaginate": false,
    "bLengthChange": false,
    "iDisplayLength": 6000,
    "bFilter": true,
    "bInfo": false,
    "bAutoWidth": false,
    "fnInitComplete": function () {
        dataTableLis();
        $('.dataTables_filter').addClass('clearfix');
        $('.search-w-box input').attr('placeholder', '输入搜索内容');
    }
};

if (scriptTag) {
    require.config({
        packages: [
            {
                name: 'echarts',
                location: './Echarts/src',
                main: 'echarts'
            },
            {
                name: 'zrender',
                location: './zrender/src', // zrender与echarts在同一级目录
                main: 'zrender'
            }
        ],
        paths: {
            jquery: 'lib/jquery-2.1.1.min',
            lobibox: 'lib/lobibox/lobibox',
            bootstrap: 'lib/bootstrap/js/bootstrap',
            util: 'admin/util',
            Spinner: 'lib/spin.min',
            highcharts: 'lib/highcharts/highcharts',
            fancyBox: 'lib/fancyBox/source/jquery.fancybox.pack',
            datatables: 'lib/DataTables/media/js/jquery.dataTables.min',
            jqPaginator: 'lib/jqPaginator.min',
            provinceCity: 'lib/provinceCity',
            jUploader: 'lib/jUploader.min',
            ztree: 'lib/zTree_v3/js/jquery.ztree.core-3.5.min',
            ztree_loader: 'lib/ztree_loader',
            ueditor: 'lib/umeditor/umeditor',
            ueditor_config: 'lib/umeditor/umeditor.config',
            pagination: 'lib/jquery.pagination.min',
            datetimepicker: 'lib/jquery.datetimepicker.full.min',
            baiduTemplate: 'lib/baiduTemplate',
            'jquery-mousewheel': 'lib/fancyBox/lib/jquery.mousewheel-3.0.6.pack',
            page_orders_all: 'Wdmin/orders/orders_all',
            page_orders_toexpress: 'Wdmin/orders/orders_toexpress',
            page_orders_expressing: 'Wdmin/orders/orders_expressing',
            page_orders_toreturn: 'Wdmin/orders/orders_toreturn',
            page_home: 'Wdmin/stat_center/home',
            page_list_products: 'admin/products/list_products',
            page_alter_products_categroy: 'admin/products/alter_products_categroy',
            page_alter_categroy: 'admin/products/alter_categroy',
            page_iframe_list_products: 'admin/products/iframe_list_products',
            page_iframe_alter_product: 'admin/products/iframe_alter_product',
            page_list_customers: 'admin/customers/list_customers',
            page_deleted_products: 'admin/products/deleted_products',
            page_alter_product_specs: 'admin/products/alter_product_specs',
            page_list_customer_orders: 'Wdmin/customers/list_customer_orders',
            page_list_companys: 'Wdmin/company/list_companys',
            page_company_requests: 'Wdmin/company/company_requests',
            page_alter_product_serials: 'admin/products/alter_product_serials',
            page_alter_products_brand: 'admin/products/page_alter_products_brand',
            page_orders_comment: 'Wdmin/orders/orders_comment',
            // services
            user: 'admin/service/user_service',
            product: 'admin/service/product_service',
            setting: 'admin/service/setting_service',
            order: 'admin/service/order_service',
            supplier: 'Wdmin/service/supplier_service',
            companyLevel: 'Wdmin/service/company_level_service'
        },
        shim: {
            'pagination': {
                deps: ['jquery']
            },
            'page_home': {
                deps: ['jquery', 'highcharts']
            },
            'page_orders_all': {
                deps: ['jquery', 'datatables']
            },
            'page_orders_toexpress': {
                deps: ['jquery', 'datatables']
            },
            'page_orders_expressing': {
                deps: ['jquery', 'datatables']
            },
            'page_orders_toreturn': {
                deps: ['jquery', 'datatables']
            },
            'page_list_products': {
                deps: ['jquery', 'datatables']
            },
            'page_alter_products_categroy': {
                deps: ['jquery', 'datatables', 'ztree']
            },
            'page_alter_categroy': {
                deps: ['jquery', 'datatables', 'ztree', 'jUploader']
            },
            'page_iframe_list_products': {
                deps: ['jquery', 'datatables', 'ztree']
            },
            'page_iframe_alter_product': {
                deps: ['jquery', 'datatables', 'ztree', 'ueditor', 'jUploader']
            },
            'page_alter_products_brand': {
                deps: ['jquery']
            },
            'page_list_customers': {
                deps: ['jquery', 'datatables', 'ztree', 'ueditor']
            },
            'page_deleted_products': {
                deps: ['jquery', 'datatables', 'ztree', 'ueditor']
            },
            'page_alter_product_specs': {
                deps: ['jquery']
            },
            'page_list_customer_orders': {
                deps: ['jquery']
            },
            'page_list_companys': {
                deps: ['jquery']
            },
            'page_company_requests': {
                deps: ['jquery']
            },
            'page_alter_product_serials': {
                deps: ['jquery']
            },
            'page_orders_comment': {
                deps: ['jquery', 'datatables']
            },
            'fancyBox': {
                deps: ['jquery']
            },
            'jUploader': {
                deps: ['jquery']
            },
            'datetimepicker': {
                deps: ['jquery'],
                exports: 'datetimepicker'
            },
            'datatables': {
                deps: ['jquery'],
                exports: 'datatable'
            },
            'provinceCity': {
                deps: ['jquery'],
                exports: 'provinceCity'
            },
            'supplier': {
                deps: ['jquery'],
                exports: 'supplier'
            },
            'highcharts': {
                deps: ['jquery'],
                exports: 'highcharts'
            },
            'ztree_loader': {
                deps: ['ztree', 'jquery'],
                exports: 'ztree_loader'
            },
            'ueditor': {
                deps: ['jquery', 'ueditor_config']
            },
            'ztree': {
                deps: ['jquery']
            },
            'bootstrap': {
                deps: ['jquery']
            },
            'jquery': {
                exports: '$'
            },
            'lobibox': {
                exports: 'lobibox',
                deps: ['jquery']
            },
            'util': {
                exports: 'util',
                deps: ['jquery', 'lobibox']
            }
        },
        //urlArgs: "bust=1.5.3",
        urlArgs: "bust=" + (new Date()).getMonth().toString() + (new Date()).getDay().toString() + (new Date()).getHours().toString() + 5,
        xhtml: true
    });

    require([scriptTag.innerHTML]);
}