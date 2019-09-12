{{csrf_field()}}
<div class="layui-row layui-col-space10 links_div" >

    @foreach($all_option as $key => $item)
        <div style="position: relative">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">轮播图</label>
                <div class="layui-input-block">
                    <div class="layui-upload">
                        <button type="button" class="layui-btn uploadPic {{ $key }}"><i class="layui-icon  {{ $key }}">&#xe67c;</i>图片上传</button>
                        <div class="layui-upload-list" >
                            <ul class="layui-upload-box layui-clear">
                                @if(isset($item['op_parameter']))
                                    <li><img src="{{ $item['op_parameter'] }}" /><p>上传成功</p><span onclick="del_img(this)">X</span></li>
                                @endif
                            </ul>
                            <input type="hidden" name="op_parameter[]" value="{{ $item['op_parameter']??'' }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">链接</label>
                <div class="layui-input-block">
                    <input type="text" name="op_value[]" value="{{ $item['op_value'] }}" lay-verify="required|numeric"  class="layui-input" >
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">排序</label>
                <div class="layui-input-inline">
                    <input type="number" name="op_sort[]" value="{{ $item['op_sort'] }}" lay-verify="required|numeric"  class="layui-input" >
                </div>
            </div>
            <button type="button" class="layui-btn close" onclick="delData(this)">X</button>
            <hr class="layui-bg-black">
        </div>

    @endforeach
</div>
<div class="layui-form-item">
    <div class="layui-input-block" style="text-align: center;">
        <button type="submit" class="layui-btn" lay-filter="formDemo" >确 认</button>
        <a  class="layui-btn" href="{{route('admin.weboption')}}" >返 回</a>
    </div>
</div>

