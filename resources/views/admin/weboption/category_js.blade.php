<script>
    $('.layui-card-header').find('h2').html('首页菜单配置')

    var element;
    var layer
    var all_categorys = JSON.parse($('#all_categorys').val())
    layui.use(['element','form', 'layer'],function () {
        layer = layui.layer;
        var form = layui.form
        form.on('radio(type)', function(data){
            var type = data.value
            console.log(type)
            if(type == 'category'){
                $('div[name=customize_div]').addClass('layui-hide')
                $('div[name=category_div]').removeClass('layui-hide')
            }
            if(type == 'customize'){
                $('div[name=customize_div]').removeClass('layui-hide')
                $('div[name=category_div]').addClass('layui-hide')
            }
        });
    })

    setTimeout(function () {
        console.log(layer)
    },1000)

    function setData() {
        var type = $('input[name="type"]:checked').val()
        if(type == 'category'){

            var title = all_categorys[$('select').val()]
            var data = encodeURI(title)
            var label = '别名'
            var op_type = 'category'
            var op_ids = $('select').val()

        } else {
            var title = $('input[name="title"]').val()
            var data = $('input[name="url"]').val()
            var label = 'URL'
            var op_type = 'url'
            var op_ids = 0
        }

        var op_sort = $('input[name="op_sort"]').val()

        var data_div = $('.category_tpl').clone();

        data_div.removeClass('category_tpl').removeClass('layui-hide')
        data_div.find('h2').html(title)
        data_div.find('input[name="data"]').html(title)
        data_div.find('input[name=op_value]').val(title)
        data_div.find('input[name="data"]').attr('placeholder', data)
        data_div.find('input[name="sort"]').val(op_sort)
        data_div.find('.layui-colla-content').addClass('layui-show')
        data_div.attr('name', 'layui-colla-item-data')
        data_div.find('input[name=op_type]').val(op_type)
        data_div.find('input[name=op_ids]').val(op_ids)
        data_div.find('label').html(label)

        $('.layui-colla-content').removeClass('layui-show')

        $('#data_div').prepend(data_div);
        layui.use(['element'],function () {
            var element = layui.element;
            element.init();
        })
        addData()
    }

    function addData() {
        console.log($('div[name=layui-colla-item-data]').length)
        if($('div[name=layui-colla-item-data]').length > 0){
            $('#data_div').removeClass('layui-hide');
        }else{
            $('#data_div').addClass('layui-hide');
        }
    }

    function delData(_self) {
        $(_self).parents('div.layui-colla-item').remove()
        addData()
    }

    function saveData() {
        var data = {}
        $('div[name=layui-colla-item-data]').each(function (m,n) {
            var data_tmp = {}
            data_tmp.op_ids = $(n).find('input[name=op_ids]').val()
            data_tmp.op_value = $(n).find('input[name=op_value]').val()
            data_tmp.op_status = '{{ \App\Models\WebOption::OP_STATUS_ENABLE }}'
            data_tmp.op_sort = $(n).find('input[name="sort"]').val()
            data_tmp.op_parameter = $(n).find('input[name="data"]').attr('placeholder')
            data_tmp.op_json = $(n).find('input[name="op_type"]').val()
            data_tmp.op_type = '{{ $op_type }}'

            data[m] = data_tmp
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