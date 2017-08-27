
/**
 * @description Hope You Do Good But Not Evil
 * @copyright   Copyright 2014-2015 <ycchen@iwshop.cn>
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chenyong Cai <ycchen@iwshop.cn>
 * @package     Wshop
 * @link        http://www.iwshop.cn
 */

requirejs(['jquery', 'datatables', 'fancyBox', 'util'], function ($, dataTable, util) {
    $(function () {
        //var dT = $('.dTable').dataTable(DataTableConfig).api();

        // 添加
        $('#specdet-add').unbind('click').click(function () {
            var n = $('.spes-items').eq(0).clone();
            n.find('input').val('');
            n.find('.spec-det').attr('data-id', '');
            //为删除按钮重新绑定点击事件
            n.find('.spec-edit-del').bind("click", function () {
                $(this).parents('.spes-items').remove();
                $.fancybox.reposition();
                specRelist();
            })
            $('#spes-warpp').append(n);
            $.fancybox.reposition();
            specRelist();
        });
        // 删除
        $('.spec-edit-del').unbind('click').click(function () {
            $(this).parents('.spes-items').remove();
            $.fancybox.reposition();
            specRelist();
        });
        // 保存
        $('#add_spec_btn_save').click(function () {
            if ($('#pd-spec-name').val() !== "") {
                var dets = [];
                $('.spes-items').each(function () {
                    dets.push({
                        id: $(this).find('.spec-det').attr('data-id'),
                        name: $(this).find('.spec-det').val(),
                        sort: $(this).find('.spec-det-sort').val()
                    });
                });
                $.post('/admin/ProductSpec/ajaxAlterSpec/', {
                    id: $('#spec_alter_id').val(),
                    dets: dets,
                    spec_name: $('#pd-spec-name').val(),
                    spec_remark: $('#pd-spec-remark').val()
                }, function (res) {
                    if (res !== '0') {
                        parent.window.location.reload();
                        parent.$.fancybox.close();
                    } else {
                        parent.alert('提交失败！');
                    }
                    parent.$.fancybox.close();
                });
            }
        });

        fnFancyBox('.spec-edit-btn', function () {
            // 添加
            $('#specdet-add').unbind('click').click(function () {
                alert('点击了添加按钮');
                var n = $('.spes-items').eq(0).clone();
                n.find('input').val('');
                n.find('.spec-det').attr('data-id', '');
                $('#spes-warpp').append(n);
                $.fancybox.reposition();
                specRelist();
            });
            // 删除
            $('.spec-edit-del').unbind('click').click(function () {
                $(this).parents('.spes-items').remove();
                $.fancybox.reposition();
                specRelist();
            });
            // 保存
            $('#add_spec_btn_save').click(function () {
                if ($('#pd-spec-name').val() !== "") {
                    var dets = [];
                    $('.spes-items').each(function () {
                        dets.push({
                            id: $(this).find('.spec-det').attr('data-id'),
                            name: $(this).find('.spec-det').val(),
                            sort: $(this).find('.spec-det-sort').val()
                        });
                    });
                    $.post('/admin/ProductSpec/ajaxAlterSpec/', {
                        id: $('#spec_alter_id').val(),
                        dets: dets,
                        spec_name: $('#pd-spec-name').val(),
                        spec_remark: $('#pd-spec-remark').val()
                    }, function (res) {
                        if (res !== '0') {
                            if (location.href.indexOf('nocache') >= 0) {
                                location.reload();
                            } else {
                                location.href = location.href + '/nocache=1';
                            }
                        } else {
                            alert('提交失败！');
                        }
                        $.fancybox.close();
                    });
                }
            });
        });

        function specRelist() {
            $('.spec-det-sort').each(function (i, node) {
                $(node).val(i);
            });
        }

        /**
         * 删除规格
         * @returns {undefined}
         */
        function fnDeleteSpec() {
            $('.spec-del-btn').on('click', function () {
                //var dT = $('.dTable').dataTable(DataTableConfig).api();
                var nParent = $(this).parent();
                if (confirm('你确认要删除吗')) {
                    $.post('/admin/ProductSpec/ajaxAlterSpec/', {
                        id: nParent.attr('data-id') * (-1)
                    }, function (r) {
                        if (r > 0) {
                            location.reload();
                            dT.row(nParent.parent()).remove().draw();
                        } else {
                            alert('删除失败');
                        }
                    });
                }
            });
        }
        fnDeleteSpec();
    });
});