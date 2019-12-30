'use strict';

(function ($) {
    window.datatables= {}; //创建一个自己的对象相当于C#中的命名空间
    /**
     *
     * @param that // 绑定dom操作元素
     * @param url // post请求数据链接
     * @param columns // 列显示
     * @param param 传值参数
     * @param type = 默认POST请求
     * @param dataType = json形式
     */
    var dt = function (that,url,columns=[],param={},type="POST",dataType="json")
    {
        $(that).DataTable({
            language: {
                "sProcessing": "处理中...",
                "sLengthMenu": "显示 _MENU_ 项结果",
                "sZeroRecords": "没有匹配结果",
                "sInfo": "显示 _START_ 到 _END_，共 _TOTAL_ 项",
                "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
                "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
                "sInfoPostFix": "",
                "sSearch": "从当前数据中检索:",
                "sUrl": "",
                "sEmptyTable": "表中数据为空",
                "sLoadingRecords": "载入中...",
                "sInfoThousands": ",",
                "oPaginate": {
                    "sFirst": "首页",
                    "sPrevious": "上页",
                    "sNext": "下页",
                    "sLast": "末页"
                },
                "oAria": {
                    "sSortAscending": ": 以升序排列此列",
                    "sSortDescending": ": 以降序排列此列"
                }
            },
            autoWidth: false,  //禁用自动调整列宽
            lengthChange:false,//是否允许用户改变表格每页显示的记录数
            paging: true,//是否开启本地分页
            pagingType:"full_numbers",//分页按钮显示选项
            searching:false,//是否允许 DataTables 开启本地搜索
            info:false,//是否显示左下角信息
            ordering:false,
            serverSide: true, // 服务端处理分页
            processing:true,// 数据量大则显示进度
            ajax: function (data, callback, settings) {
                param.size = data.length;//页面显示记录条数，在页面显示每页显示多少项的时候
                param.start = data.start;//开始的记录序号
                param.page = (data.start / data.length)+1;//当前页码
                param.order = data.order[0];
                $.ajax({
                    url:url,
                    type:type,
                    cache: false,  //禁用缓存
                    data: param,  //传入组装的参数
                    dataType: dataType,
                    success: function (result) {
                        setTimeout(function () {
                            //封装返回数据
                            var returnData = {};
                            returnData.draw = data.draw;//这里直接自行返回了draw计数器,应该由后台返回
                            returnData.recordsTotal = result.data.total;//返回数据全部记录
                            returnData.recordsFiltered = result.data.total;//后台不实现过滤功能，每次查询均视作全部结果
                            returnData.data = result.data.data;//返回的数据列表
                            callback(returnData);
                        }, 200);
                    }
                })
            },
            columns: columns
        });
    };
    datatables.dt = dt;  //将函数注册到命名空间上
})(jQuery);