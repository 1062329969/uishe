<style type="text/css">
    .links {border: 1px #e4e4e4 solid; width: 30%; margin: 1%;}
    .layui-form-label {width: 80px;}
    .close,.add{ position: absolute; top: 1px; right: 1px; }
</style>

<script>
    var element;
    var layer
    layui.use(['element','form', 'layer'],function () {
        layer = layui.layer;
        var form = layui.form
    })
    $('.layui-card-header').find('h2').html('友情链接配置')
    setTimeout(function () {
    },1000)

    add_links()

    function del_links(_self) {
        $(_self).parent().remove()
        if($('.links').length == 1){

        }
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