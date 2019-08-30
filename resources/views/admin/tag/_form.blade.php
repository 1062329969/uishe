{{csrf_field()}}
<div class="layui-form-item">
    <label for="" class="layui-form-label">名称</label>
    <div class="layui-input-inline">
        <input type="text" name="name" value="{{ $tag->name ?? old('name') }}" lay-verify="required" placeholder="请输入名称" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">URL别名</label>
    <div class="layui-input-inline">
        <input type="text" name="alias" value="<?php echo isset($tag->alias) && !empty($tag->alias) ? urldecode($tag->alias) : old('alias'); ?>" lay-verify="required" placeholder="请输入URL别名" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">排序</label>
    <div class="layui-input-inline">
        <input type="text" name="sort" value="{{ $tag->sort ?? 0 }}" lay-verify="required|number" placeholder="请输入数字" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label class="layui-form-label">开关</label>
    <div class="layui-input-block">
        <input type="checkbox" name="recommend" lay-skin="switch" lay-text="ON|OFF" {{ isset($tag->recommend) && $tag->recommend == 'on' ? 'checked' : '' }}>
    </div>
</div>

<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a class="layui-btn" href="{{route('admin.tag')}}" >返 回</a>
    </div>
</div>