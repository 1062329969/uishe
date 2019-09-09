@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('zixun.news.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">详情</a>
                    @endcan
                </div>
            </script>
        </div>
    </div>
@endsection

@section('script')
    @can('zixun.news')
        <script>
            layui.use(['layer','table','form'],function () {
                var layer = layui.layer;
                var form = layui.form;
                var table = layui.table;
                //用户表格初始化
                var dataTable = table.render({
                    elem: '#dataTable'
                    ,height: 'auto'
                    ,url: "{{ route('admin.weboption.data') }}" //数据接口
                    ,cols: [[ //表头
                        {field: 'name', title: 'ID', sort: true}
                        ,{fixed: 'right',align:'center', toolbar: '#options'}
                    ]]
                });

                //监听工具条
                table.on('tool(dataTable)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        ,layEvent = obj.event; //获得 lay-event 对应的值
                    if(layEvent === 'edit'){
                        location.href = '/admin/weboption/'+data.value+'/edit';
                    }
                });

            })
        </script>
    @endcan
@endsection