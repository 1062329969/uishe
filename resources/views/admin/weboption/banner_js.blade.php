<style>
    .close,.add{ position: absolute; top: 1px; right: 1px; }
</style>

<script>
    $('.layui-card-header').find('h2').html('首页banner配置')

    var element;
    var layer
    layui.use(['element','form', 'layer'],function () {
        layer = layui.layer;
        var form = layui.form
    })

    setTimeout(function () {
    },1000)


    function delData(_self) {
        $(_self).parent().remove()
        addData()
    }

    function add_links() {
        var _html = `
            <div class="layui-col-md4 links">
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">友情名称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="op_value[]" value="" lay-verify="required|numeric"  class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">友情链接</label>
                    <div class="layui-input-inline">
                        <input type="text" name="op_parameter[]" value="" lay-verify="required|numeric"  class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">排序</label>
                    <div class="layui-input-inline">
                        <input type="number" name="op_sort[]" value="" lay-verify="required|numeric"  class="layui-input" >
                    </div>
                </div>

                <button type="button" class="layui-btn close" onclick="del_links(this)">X</button>
            </div>
            `
        $('.links_div').prepend(_html)
    }

</script>