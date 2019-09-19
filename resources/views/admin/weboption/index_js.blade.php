<style>
    .close,.add{ position: absolute; top: 1px; right: 1px; }

    .child_span{background-color: #009688;display: inline-block;padding-left: 10px;color: #fff;}
    .child_input{background-color: #009688;border: 0;padding: 7px;color: #fff;}
</style>

<script>
    $('.layui-card-header').find('h2').html('首页内容配置')
    content_show_bind_change();
    var news_list
    var element;
    var layer
    var form
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
            var elem = data.elem
            var news_id = data.value
            var bool = true;
            $(elem).parents('.module_content').find('.content_show').find('.content_itme').each(function (m, n) {
                var news_show_id = $(n).find('input').val()
                if(news_id == news_show_id){
                    bool = false
                    return false
                }
            })
            if(!bool){ return false }
            var _news = news_list[data.value];
            console.log(_news);
            var _html = `
                <div class="layui-col-md3 content_itme" style="position: relative">
                    <img src="`+_news.cover_img+`" width="100%">
                    <p>`+_news.title+`</p>
                    <input type="hidden" value="`+_news.id+`" >
                    <button type="button" class="layui-btn close" onclick="delData(this)">X</button>
                </div>
            `
            $(elem).parents('.module_content').find('.content_show').append(_html)
            $('.content_show').show()
        })

        $('#addData').click(function () {
            var _html = $('#tmp_div').find('.index_div').clone(true)
            $('#index_option_div').prepend(_html)
            form.render();
        })
    })
    function content_show_bind_change() {
        $(".content_show").bind('DOMNodeRemoved', function(e) {
            console.log(e);
            var content_itme_length = $(this).find('.content_itme').length
            console.log(content_itme_length)
            if(content_itme_length == 1){
                $(this).hide();
            }
        });
        $(".child_cat_div").bind('DOMNodeRemoved', function(e) {
            console.log(e);
            var content_itme_length = $(this).find('.child_span').length
            console.log(content_itme_length)
            if(content_itme_length == 1){
                $(this).hide();
            }
        });
        $(".serach_div").bind('DOMNodeRemoved', function(e) {
            console.log(e);
            var content_itme_length = $(this).find('button').length
            console.log(content_itme_length)
            if(content_itme_length == 1){
                $(this).hide();
            }
        });
    }

    setTimeout(function () {
    },1000)

    function setChild(elem, datas) {

        var tag_obj = JSON.parse($('#child_json').val())
        var child = tag_obj[datas]
        var child_input_name = 'child'
        var bool = true;
        $(elem).parents('.layui-input-block').prev().find('.child_span').each(function (m, n) {
            if($(n).find('span').html() == child){
                bool = false
                return false
            }
        })
        if(!bool){
            return false
        }
        var _html = `
        <span class="child_span">
            <span>`+child+`</span>
            <input type="text" name="`+child_input_name+`" value="`+datas+`" style="display: none">
            <input type="text" name="child_name" value="`+child+`" style="display: none">
            <input class="child_input" type="button" value="x" onclick="delData(this)">
        </span>
        `
        $(elem).parents('.layui-input-block').prev().show()
        $(elem).parents('.layui-input-block').prev().append(_html)
    }

    function delData(_self) {
        $(_self).parent().remove()
    }
    
    function setSerachData(e, _self) {
        if(e.keyCode == 13){
            var bool = true
            $(_self).parent().prev().find('button').each(function (m,n) {
                if($(n).html() == $(_self).val()){
                    bool = false
                    return false;
                }
            })
            if(!bool){
                return false
            }
            var _html = `<button type="button" class="layui-btn" onclick="$(this).remove()">`+$(_self).val()+`</button>`
            $(_self).parent().prev().append(_html)
            $(_self).parent().prev().show()
            $(_self).val('')
        }
    }

    function saveData() {
        var data = new Array()
        $('#index_option_div .index_div').each(function (m, n) {
            var category_id = $(n).find('select[name=category_id]').val()
            var child = new Array();
            $(n).find('.child_span').each(function (cm, cn) {
                child[cm] = {}
                child[cm].category_id = $(cn).find('input[name=child]').val()
                child[cm].category_alias = $(cn).find('input[name=child_name]').val()
            })

            var content = new Array();
            $(n).find('.content_itme').each(function (cim, cin) {
                content[cim] = {}
                content[cim].id = $(cin).find('input').val()
                content[cim].thumb = $(cin).find('img').attr('src')
                content[cim].title = $(cin).find('p').html()
            })
            var search = new Array()
            $(n).find('.serach_div button').each(function (sm, sn) {
                search[sm] = $(sn).html()
            })
            var sort = $(n).find('input[name=op_sort]').val()

            data[m] = {}
            data[m].category_id = category_id
            data[m].child = child
            data[m].content = content
            data[m].search = search
            data[m].sort = sort
        })

        console.log(data)
        $.ajax({
            url:'{{ route('admin.weboption.update', ['op_type'=>$op_type]) }}',
            type:'PUT',
            data:{op_type:'{{ $op_type }}', data:data},
            async:false,
            success:function (data) {
                console.log(layer)
                if(data.code == 0){
                    layer.msg(data.msg, {
                        icon: 1,
                        time: 1500 //2秒关闭（如果不配置，默认是3秒）
                    }, function(){
                        location.href = '{{route('admin.weboption')}}';
                    });
                }else{
                    layer.msg(data.msg, {
                        icon: 2,
                        time: 1500 //2秒关闭（如果不配置，默认是3秒）
                    })
                }
            }
        })
    }
</script>