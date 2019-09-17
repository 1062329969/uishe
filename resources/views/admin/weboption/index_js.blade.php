<style>
    .close,.add{ position: absolute; top: 1px; right: 1px; }

    .child_span{background-color: #009688;display: inline-block;padding-left: 10px;color: #fff;}
    .child_input{background-color: #009688;border: 0;padding: 7px;color: #fff;}
</style>

<script>
    $('.layui-card-header').find('h2').html('首页内容配置')
    var news_list
    var element;
    var layer
    layui.use(['element','form', 'layer', 'upload'],function () {
        layer = layui.layer;
        var form = layui.form

        form.on('select(category_select)', function(data){
            console.log(data.value); //得到被选中的值
            $.ajax({
                url:'{{ url('admin/news/getbycategory')}}/'+data.value,
                type:'GET',
                async:true,
                success:function (data) {
                    news_list = data.msg
                    console.log(data)

                    var _html = '';
                    for (x in news_list){
                        _html += "<option value="+news_list[x]['id']+">"+news_list[x]['title']+"</option>"
                    }
                    $("#content_select").html(_html);
                    form.render('select');
                }
            })
        })

        form.on('select(child_select)', function(data){
//            console.log(data.elem); //得到select原始DOM对象
//            console.log(data.value); //得到被选中的值
//            console.log(data.othis); //得到美化后的DOM对象
            setChild(data.elem, data.value);
            $(data.elem).next().removeClass('layui-form-selected');
        });

        form.on('select(category_select)', function(data){
            console.log(data.value); //得到被选中的值
            var elem = data.elem
            $.ajax({
                url:'{{ url('admin/news/getbycategory')}}/'+data.value,
                type:'GET',
                async:true,
                success:function (data) {
                    news_list = data.msg
                    console.log(data)

                    var _html = '';
                    for (x in news_list){
                        _html += "<option value="+news_list[x]['id']+">"+news_list[x]['title']+"</option>"
                    }

                    $(elem).parents('.index_div').find('.module_content').find('.content_select').html(_html);
                    form.render('select');
                }
            })
        })

        form.on('select(content_select)', function (data) {
            console.log(data.value); //得到被选中的值
            var elem = data.elem
            console.log(news_list[data.value])
        })
    })

    setTimeout(function () {
    },1000)

    function setChild(elem, datas) {

        var tag_obj = JSON.parse($('#child_json').val())
        var child = tag_obj[datas]
        var child_input_name = 'child'
        var bool = true;
        $(elem).parents('.layui-input-block').prev().find('.child_span').each(function (m, n) {
            console.log($(n).find('span').html())
            console.log(child)
            console.log($(n).find('span').html() == child)
            if($(n).find('span').html() == child){
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
        <span class="child_span">
            <span>`+child+`</span>
            <input type="text" name="`+child_input_name+`[]" value="`+datas+`" style="display: none">
            <input type="text" name="child_name[]" value="`+child+`" style="display: none">
            <input class="child_input" type="button" value="x" onclick="delchild(this)">
        </span>
        `
        $(elem).parents('.layui-input-block').prev().show()
        $(elem).parents('.layui-input-block').prev().append(_html)
    }

    function delData(_self) {
        $(_self).parent().remove()
    }
    
    function addData() {
        var _html = $('.index_div').clone()
        $('#banner_div').prepend(_html)
    }

</script>