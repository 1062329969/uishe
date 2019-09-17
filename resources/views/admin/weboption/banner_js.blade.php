<style>
    .close,.add{ position: absolute; top: 1px; right: 1px; }
     .layui-upload-box li{
         width: 120px;
         height: 100px;
         float: left;
         position: relative;
         overflow: hidden;
         margin-right: 10px;
         border:1px solid #ddd;
     }
    .layui-upload-box li img{
        width: 100%;
    }
    .layui-upload-box li p{
        width: 100%;
        height: 22px;
        font-size: 12px;
        position: absolute;
        left: 0;
        bottom: 0;
        line-height: 22px;
        text-align: center;
        color: #fff;
        background-color: #333;
        opacity: 0.6;
    }
    .layui-upload-box li span {
        width: 20%;
        height: 22px;
        font-size: 12px;
        position: absolute;
        right: 0;
        top: 0;
        line-height: 22px;
        text-align: center;
        color: #fff;
        background-color: #000;
        opacity: 0.6;
    }
    .layui-upload-box li i{
        display: block;
        width: 20px;
        height:20px;
        position: absolute;
        text-align: center;
        top: 2px;
        right:2px;
        z-index:999;
        cursor: pointer;
    }
    .tags_span{background-color: #009688;display: inline-block;padding-left: 10px;color: #fff;}
    .tags_input{background-color: #009688;border: 0;padding: 7px;color: #fff;}
</style>

<script>
    $('.layui-card-header').find('h2').html('首页banner配置')

    var element;
    var layer
    var upload
    var upload_option = {
        elem: '.uploadPic'
        ,url: '{{ route("uploadImg") }}'
        ,multiple: false
        ,data:{"_token":"{{ csrf_token() }}"}
        ,before: function(obj){
            var ul = $(this.item).parent().parent().find('.layui-upload-list').find('ul')
            obj.preview(function(index, file, result){
                ul.html('<li><img src="'+result+'" /><p>上传中</p><span onclick="del_img(this)">X</span></li>')
            });
        }
        ,done: function(res, index, upload){
            //如果上传失败
            if(res.code == 0){
                $(this.item).parent().parent().find('input[name^=op_parameter]').val(res.url);
                $(this.item).parent().parent().find('p').text('上传成功');
                return layer.msg(res.msg);
            }else{
                return layer.msg(res.msg);
            }
        }
    }

    layui.use(['element','form', 'layer', 'upload'],function () {
        layer = layui.layer;
        var form = layui.form

        upload = layui.upload

        //普通图片上传
        var uploadInst = upload.render(upload_option);
    })

    setTimeout(function () {
    },1000)


    function delData(_self) {
        $(_self).parent().remove()
    }
    
    function addData() {
        var _html = `
            <div style="position: relative">
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">轮播图</label>
                    <div class="layui-input-block">
                        <div class="layui-upload">
                            <button type="button" class="layui-btn uploadPic "><i class="layui-icon ">&#xe67c;</i>图片上传</button>
                        </div>
                        <div class="layui-upload-list" >
                            <ul class="layui-upload-box layui-clear">
                            </ul>
                            <input type="hidden" name="op_parameter[]" value="">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">链接</label>
                    <div class="layui-input-block">
                        <input type="text" name="op_value[]" value="" lay-verify="required|numeric"  class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">排序</label>
                    <div class="layui-input-inline">
                        <input type="number" name="op_sort[]" value="0" lay-verify="required|numeric"  class="layui-input" >
                    </div>
                </div>
                <button type="button" class="layui-btn close" onclick="delData(this)">X</button>
                <hr class="layui-bg-black">
            </div>
        `
        $('#banner_div').prepend(_html)
        var uploadInst = upload.render(upload_option)
    }

    function del_img(_self) {
        $(_self).parents('ul').next().val(''); $(_self).parent().remove()
    }

</script>