{{csrf_field()}}

<div class="layui-form-item">
    <div class="">
        <input type="text" name="content" value="{{ $reply_comments->content ?? old('content') }}" lay-verify="required" placeholder="请输入名称" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <div class="">
        <input type="hidden" name="reply_id" value="{{$reply_comments->id}}">
        <input type="hidden" name="comments_id" value="{{$comments->id}}">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.comments')}}" >返 回</a>
    </div>
</div>