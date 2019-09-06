<style>
    #layui-upload-box li{
        width: 120px;
        height: 100px;
        float: left;
        position: relative;
        overflow: hidden;
        margin-right: 10px;
        border:1px solid #ddd;
    }
    #layui-upload-box li img{
        width: 100%;
    }
    #layui-upload-box li p{
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
    #layui-upload-box li span {
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
    #layui-upload-box li i{
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
    layui.use(['upload','form'],function () {
        var form = layui.form
        form.on('select(tags_select)', function(data){
//            console.log(data.elem); //得到select原始DOM对象
//            console.log(data.value); //得到被选中的值
//            console.log(data.othis); //得到美化后的DOM对象
            setTags('layui', data.value);
            $("#tags_select").next().removeClass('layui-form-selected');
            $('#tags_input').val('')
        });
        window.search = function () {
            var value = $("#tags_input").val();
            form.render();
            $("#tags_select").next().addClass('layui-form-selected');
            var dl = $("#tags_select").next().find("dl").children();
            var j = -1;
            for (var i = 0; i < dl.length; i++) {
                if (dl[i].innerHTML.indexOf(value) <= -1) {
                    dl[i].style.display = "none";
                    j++;
                }
                if (j == dl.length-1) {
                    $("#tags_select").next().removeClass('layui-form-selected');
                }
            }

        }
        $("#tags_input").bind({
            keydown:function(e){
                if(e.keyCode == 13){
                    if($.trim($(this).val())){
                        setTags('blur', $(this).val());
                    }
                }
            },
            blur:function () {
                return false;
            }
        });

        var upload = layui.upload

        //普通图片上传
        var uploadInst = upload.render({
            elem: '#uploadPic'
            ,url: '{{ route("uploadImg") }}'
            ,multiple: false
            ,data:{"_token":"{{ csrf_token() }}"}
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                /*obj.preview(function(index, file, result){
                 $('#layui-upload-box').append('<li><img src="'+result+'" /><p>待上传</p></li>')
                 });*/
                obj.preview(function(index, file, result){
                    $('#layui-upload-box').html('<li><img src="'+result+'" /><p>上传中</p><span onclick="$(\'#cover_img\').val(\'\'); $(this).parent().remove()">X</span></li>')
                });

            }
            ,done: function(res){
                //如果上传失败
                if(res.code == 0){
                    $("#cover_img").val(res.url);
                    $('#layui-upload-box li p').text('上传成功');
                    return layer.msg(res.msg);
                }
                return layer.msg(res.msg);
            }
        });
    })

    function setTags(from, datas) {

        if(from == 'layui'){
            var tag_obj = JSON.parse($('#tags_json').val())
            var tags = tag_obj[datas]
            var tags_input_name = 'tags'
        }else{
            var tags = datas
            var tags_input_name = 'new_tags'
        }
        var bool = true;
        $('.tags_span').each(function (m, n) {
            console.log($(n).find('span').html())
            console.log(tags)
            console.log($(n).find('span').html() == tags)
            if($(n).find('span').html() == tags){
                console.log('---------')
                bool = false
                return false
                console.log('+++++++++')
            }
        })
        if(!bool){
            return false
        }
        var _html = `
        <span class="tags_span">
            <span>`+tags+`</span>
            <input type="text" name="`+tags_input_name+`[]" value="`+datas+`" style="display: none">
            <input type="text" name="tags_name[]" value="`+tags+`" style="display: none">
            <input class="tags_input" type="button" value="x" onclick="deltags(this)">
        </span>
        `
        $('#tags_select_div').show()
        $('#tags_select_div').append(_html)
        $('#tags_input').val('')
    }

    function deltags(_self) {
        $(_self).parent().remove()
        if($('#tags_select_div').find('input').length == 0){
            $('#tags_select_div').hide()
        }
    }

</script>
<!-- 实例化编辑器 -->
<script type="text/javascript">
    var ue = UE.getEditor('container');
    ue.ready(function() {
        ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
    });
</script>