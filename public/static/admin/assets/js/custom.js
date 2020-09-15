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
    var dt = function (that,url,columns=[],limit=20,param={},type= "POST")
    {
        // layui插件
        layui.use(['table','laydate','form'],function(){
            var table = layui.table
                ,laydate = layui.laydate
                ,form = layui.form;
            //第一个实例
            table.render({
                elem: that
                ,url: url
                ,method:type
                ,toolbar: '#toolbarDemo' //操作1:启用自定义模板表格头部工具栏
                ,page: true //开启分页
                ,limit: limit
                ,loading:true
                ,cols: [columns]
                ,parseData: function(res){
                    return {
                        "code": res.data.code,
                        "msg": res.data.msg,
                        "count": res.data.count,
                        "data": res.data.list
                    };
                }
            });
            //监听事件
            table.on('toolbar(kplphptable)', function(obj){
                var where = {}
                switch(obj.event){
                    case 'refresh':
                        table.reload('kplphptable', {
                            url: url
                            ,page:true
                            ,where: where
                        });
                        break;
                    case 'add':
                        add();
                        break;
                    case 'delete':
                        var checkStatus = table.checkStatus('kplphptable')
                            , data = checkStatus.data
                            , length = checkStatus.data.length;
                        var delList = [];
                        data.forEach(function(n,i){
                            delList.push(n.id);
                        });
                        if (delList.length!=0) {
                            layer.confirm('你确定删除这'+length+'行吗？', function (index) {
                                del(delList)
                                table.reload('kplphptable', {});
                                layer.close(index);
                            })
                        } else {
                            layer.msg('请选择需要删除的行');return;
                        }
                        break;
                }
            });
            table.on('tool(kplphptable)', function(obj){
                var data = obj.data;
                if(obj.event === 'detail'){
                    see(data.id);
                } else if(obj.event === 'del'){
                    layer.confirm('真的删除行么', function(index){
                        obj.del();
                        layer.close(index);
                        del(data.id);
                    });
                } else if(obj.event === 'edit'){
                    edit(data.id);
                }else if(obj.event === 'see'){
                    see(data.id);
                }
            });
            form.on('submit(formSearch)', function (data) {
                table.reload('kplphptable', {
                    url: url
                    ,page:true
                    ,where: data.field
                });
                return false;
            });


            $('.times').each(function(){
                laydate.render({
                    elem: this
                    ,position: 'fixed'
                    ,trigger: 'click'
                    ,format: 'yyyy-MM-dd'
                });
            })

        });



    };
    datatables.dt = dt;  //将函数注册到命名空间上
})(jQuery);