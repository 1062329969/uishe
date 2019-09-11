{{csrf_field()}}
<div class="layui-row layui-col-space10 links_div" >

    @foreach($all_option as $item)
    <div class="layui-col-md4 links">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">友情名称</label>
            <div class="layui-input-inline">
                <input type="text" name="op_value[]" value="{{ $item['op_value'] }}" lay-verify="required|numeric"  class="layui-input" >
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">友情链接</label>
            <div class="layui-input-inline">
                <input type="text" name="op_parameter[]" value="{{ $item['op_parameter'] }}" lay-verify="required|numeric"  class="layui-input" >
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">排序</label>
            <div class="layui-input-inline">
                <input type="number" name="op_sort[]" value="{{ $item['op_sort'] }}" lay-verify="required|numeric"  class="layui-input" >
            </div>
        </div>

        <button type="button" class="layui-btn close" onclick="del_links(this)">X</button>
    </div>
    @endforeach
</div>
<div class="layui-form-item">
    <div class="layui-input-block" style="text-align: center;">
        <button type="submit" class="layui-btn" lay-filter="formDemo" >确 认</button>
        <a  class="layui-btn" href="{{route('admin.weboption')}}" >返 回</a>
    </div>
</div>
<button type="button" class="layui-btn add" onclick="add_links(this)">添加</button>

