@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group ">
                @can('admin.orders.destroy')
                    <button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">删 除</button>
                @endcan
                <button class="layui-btn layui-btn-sm" id="searchBtn">搜 索</button>
            </div>
            <div class="layui-form">
                <div class="layui-input-inline">
                    <select name="order_type" lay-verify="required" id="order_type">
                        <option value="">请选择分类</option>
                        @foreach($order_type as $key =>$value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="layui-input-inline">
                    <input type="text" name="order_no" id="order_no" placeholder="请输入文章标题" class="layui-input">
                </div>
            </div>
        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('admin.orders.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan
                    @can('admin.orders.destroy')
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
                    @endcan
                </div>
            </script>
            <script type="text/html" id="thumb">
                <a href="@{{d.thumb}}" target="_blank" title="点击查看"><img src="@{{d.thumb}}" alt="" width="28"
                                                                         height="28"></a>
            </script>
            <script type="text/html" id="tags">
                @{{#  layui.each(d.tags, function(index, item){ }}
                <button type="button" class="layui-btn layui-btn-sm">@{{ item.name }}</button>
                @{{# }); }}
            </script>
            <script type="text/html" id="category">
                @{{#  layui.each(d.category, function(index, item){ }}
                <button type="button" class="layui-btn layui-btn-sm">@{{ item.name }}</button>
                @{{# }); }}
            </script>
        </div>
    </div>
@endsection

@section('script')
    @can('system.orders')
        <script>
            layui.use(['layer', 'table', 'form'], function () {
                var layer = layui.layer;
                var form = layui.form;
                var table = layui.table;
                //用户表格初始化
                var dataTable = table.render({
                    elem: '#dataTable'
                    , height: 500
                    , url: "{{ route('admin.orders.data') }}" //数据接口
                    , page: true //开启分页
                    , cols: [[ //表头
                        {checkbox: true, fixed: true}
                        , {field: 'id', title: 'ID', sort: true, width: 80}
                        , {field: 'order_no', title: '订单号'}
                        , {field: 'actual_price', title: '订单价格'}
                        , {field: 'order_type', title: '订单类型'}
                        , {field: 'pay_type', title: '支付方式'}
                        , {field: 'pay_user_id', title: '用户ID'}
                        , {field: 'pay_at', title: '支付时间'}
                        , {field: 'created_at', title: '创建时间'}
                        , {field: 'updated_at', title: '更新时间'}
                        , {fixed: 'right', width: 220, align: 'center', toolbar: '#options'}
                    ]]
                });

                //监听工具条
                table.on('tool(dataTable)', function (obj) { //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        , layEvent = obj.event; //获得 lay-event 对应的值
                    if (layEvent === 'del') {
                        layer.confirm('确认删除吗？', function (index) {
                            $.post("{{ route('admin.orders.destroy') }}", {
                                _method: 'delete',
                                ids: [data.id]
                            }, function (result) {
                                if (result.code == 0) {
                                    obj.del(); //删除对应行（tr）的DOM结构
                                }
                                layer.close(index);
                                layer.msg(result.msg)
                            });
                        });
                    } else if (layEvent === 'edit') {
                        location.href = '/admin/orders/' + data.id + '/edit';
                    }
                });

                @can('admin.orders.edit')
                //监听是否显示
                form.on('switch(isShow)', function (obj) {
                    var index = layer.load();
                    var url = $(obj.elem).attr('url')
                    var data = {
                        "is_show": obj.elem.checked == true ? 1 : 0,
                        "_method": "put"
                    }
                    $.post(url, data, function (res) {
                        layer.close(index)
                        layer.msg(res.msg)
                    }, 'json');
                });
                @endcan

                //按钮批量删除
                $("#listDelete").click(function () {
                    var ids = []
                    var hasCheck = table.checkStatus('dataTable')
                    var hasCheckData = hasCheck.data
                    if (hasCheckData.length > 0) {
                        $.each(hasCheckData, function (index, element) {
                            ids.push(element.id)
                        })
                    }
                    if (ids.length > 0) {
                        layer.confirm('确认删除吗？', function (index) {
                            $.post("{{ route('admin.orders.destroy') }}", {
                                _method: 'delete',
                                ids: ids
                            }, function (result) {
                                if (result.code == 0) {
                                    dataTable.reload()
                                }
                                layer.close(index);
                                layer.msg(result.msg)
                            });
                        })
                    } else {
                        layer.msg('请选择删除项')
                    }
                })

                //搜索
                $("#searchBtn").click(function () {
                    var catId = $("#order_type").val()
                    var order_no = $("#order_no").val();
                    dataTable.reload({
                        where: {order_type: catId, order_no: order_no},
                        page: {curr: 1}
                    })
                })
            })
        </script>
    @endcan
@endsection