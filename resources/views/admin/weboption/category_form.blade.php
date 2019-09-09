{{csrf_field()}}
<div class="tpl_div none">
    <div class="layui-form-item">
        <label for="" class="layui-form-label">类型</label>
        <div class="layui-input-block">
            <input type="radio" name="type" value="category" title="分类" checked>
            <input type="radio" name="type" value="customize" title="自定义">
        </div>
    </div>

    <div class="layui-form-item">
        <label for="" class="layui-form-label">分类</label>
        <div class="layui-input-inline">
            <select name="category_id" lay-verify="required">
                @foreach($categorys as $category)
                    <option value="{{ $category->id }}" @if(isset($option->ids)&&$option->ids==$category->id)selected @endif >{{ $category->name }}</option>
                    @if(isset($category->allChilds)&&!$category->allChilds->isEmpty())
                        @foreach($category->allChilds as $child)
                            <option value="{{ $child->id }}" @if(isset($option->ids)&&$option->ids==$child->id)selected @endif >&nbsp;&nbsp;&nbsp;┗━━{{ $child->name }}</option>
                            @if(isset($child->allChilds)&&!$child->allChilds->isEmpty())
                                @foreach($child->allChilds as $third)
                                    <option value="{{ $third->id }}" @if(isset($option->ids)&&$option->ids==$third->id)selected @endif >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;┗━━{{ $third->name }}</option>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label for="" class="layui-form-label">名称</label>
        <div class="layui-input-block">
            <input type="text" name="title" value="" lay-verify="required" placeholder="请输入名称" class="layui-input" >
        </div>
        <label for="" class="layui-form-label">URL</label>
        <div class="layui-input-block">
            <input type="text" name="url" value="" lay-verify="required" placeholder="请输入链接" class="layui-input" >
        </div>
    </div>


    <div class="layui-form-item">
        <label for="" class="layui-form-label">开启关闭</label>
        <div class="layui-input-inline">
            <input type="checkbox" name="comment_status"
                   lay-skin="switch"
                   lay-text="{{ \App\Models\WebOption::OP_STATUS_ENABLE }}|{{ \App\Models\WebOption::OP_STATUS_DISABLED }}"
                   @if( isset($option->op_status) and $option->op_status == \App\Models\WebOption::OP_STATUS_ENABLE )
                   checked
                    @endif
            >
        </div>
    </div>


    <div class="layui-form-item">
        <label for="" class="layui-form-label">排序</label>
        <div class="layui-input-inline">
            <input type="number" name="op_sort" value="0" lay-verify="required|numeric"  class="layui-input" >
        </div>
    </div>
</div>
<div id="data_div">

</div>


<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="button" class="layui-btn" lay-submit="" lay-filter="formDemo" onclick="$('form').submit()">确 认</button>
        <a  class="layui-btn" href="{{route('admin.news')}}" >返 回</a>
    </div>
</div>