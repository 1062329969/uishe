{{csrf_field()}}
<div class="layui-form-item">
    <label for="" class="layui-form-label">分类</label>
    <div class="layui-input-inline">
        <select name="category_id" lay-verify="required">
            <option value=""></option>
            @foreach($categorys as $category)
                <option value="{{ $category->id }}" @if(isset($news->category_id)&&$news->category_id==$category->id)selected @endif >{{ $category->name }}</option>
                @if(isset($category->allChilds)&&!$category->allChilds->isEmpty())
                    @foreach($category->allChilds as $child)
                        <option value="{{ $child->id }}" @if(isset($news->category_id)&&$news->category_id==$child->id)selected @endif >&nbsp;&nbsp;&nbsp;┗━━{{ $child->name }}</option>
                        @if(isset($child->allChilds)&&!$child->allChilds->isEmpty())
                            @foreach($child->allChilds as $third)
                                <option value="{{ $third->id }}" @if(isset($news->category_id)&&$news->category_id==$third->id)selected @endif >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;┗━━{{ $third->name }}</option>
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @endforeach
        </select>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">标签</label>
    <!-- 标签的star -->
    <div class="layui-input-block" id="tags_select_div" @if(!isset($news->tags)) style="display: none" @endif>
        @if(isset($news->tags))
        @foreach($news->tags as $tag_item)
            <span class="tags_span">
                <span>{{ $tag_item['name'] }}</span>
                <input type="text" name="tags[]" value="{{ $tag_item['id'] }}" style="display: none">
                <input type="text" name="tags_name[]" value="{{ $tag_item['name'] }}" style="display: none">
                <input class="tags_input" type="button" value="x" onclick="deltags(this)">
            </span>
        @endforeach
        @endif
    </div>
    <!-- 标签的star -->
    <div class="layui-input-block">
    <div class="layui-input-inline">
        <input type="text" name="tags_input" id="tags_input" class="layui-input" style="position:absolute;z-index:2;width:80%;" value="" onkeyup="search()" autocomplete="off">
        <select type="text" id="tags_select" lay-filter="tags_select" autocomplete="off" placeholder="移交单位全称" class="layui-select" lay-search>
        {{--<select lay-filter="tags_select" id="tags" lay-verify="" lay-search>--}}
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
            @endforeach
        </select>
    </div>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">标题</label>
    <div class="layui-input-block">
        <input type="text" name="title" value="{{$news->title??old('title')}}" lay-verify="required" placeholder="请输入标题" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">点击(浏览)量</label>
    <div class="layui-input-inline">
        <input type="number" name="views" value="{{$news->views??mt_rand(100,500)}}" lay-verify="required|numeric"  class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">缩略图</label>
    <div class="layui-input-block">
        <div class="layui-upload">
            <button type="button" class="layui-btn" id="uploadPic"><i class="layui-icon">&#xe67c;</i>图片上传</button>
            <div class="layui-upload-list" >
                <ul id="layui-upload-box" class="layui-clear">
                    @if(isset($news->cover_img))
                        <li><img src="{{ $news->cover_img }}" /><p>上传成功</p><span onclick="$('#cover_img').val(''); $(this).parent().remove()">X</span></li>
                    @endif
                </ul>
                <input type="hidden" name="cover_img" id="cover_img" value="{{ $news->cover_img??'' }}">
            </div>
        </div>
    </div>
</div>

@include('UEditor::head');
<div class="layui-form-item">
    <label for="" class="layui-form-label">内容</label>
    <div class="layui-input-block">
        <script id="container" name="content" type="text/plain">
            {!! $news->content??old('content') !!}
        </script>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">介绍</label>
    <div class="layui-input-inline">
        <textarea name="introduction" placeholder="请输入介绍，如果默认为空，则会显示下载设置价格等">{{ $news->introduction ?? old('introduction') }}</textarea>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">下载类型</label>
    <div class="layui-input-block">
        <input type="radio" name="down_type" value="close" title="关闭">
        <input type="radio" name="down_type" value="every" title="所有用户免费下载">
        <input type="radio" name="down_type" value="login" title="登录后免费下载">
        <input type="radio" name="down_type" value="integral" title="积分下载" checked>
        <input type="radio" name="down_type" value="vip" title="VIP下载">
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">免费下载群体</label>
    <div class="layui-input-block">
        <input type="radio" name="down_level" value="0" title="自动">
        <input type="radio" name="down_level" value="1" title="所有VIP免费" checked>
        <input type="radio" name="down_level" value="2" title="钻石终身会员及以上免费">
        <input type="radio" name="down_level" value="3" title="荣誉终身会员免费">
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">商品售价</label>
    <div class="layui-input-inline">
        <input type="text" name="down_price" value="{{$news->down_price??old('down_price', 0)}}" lay-verify="required" placeholder="请输入标题" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">是否允许评论</label>
    <div class="layui-input-inline">
        <input type="checkbox" name="comment_status"
                lay-skin="switch"
                lay-text="{{ \App\Models\News::Comment_Status_On }}|{{ \App\Models\News::Comment_Status_Off }}"
                @if( isset($news->comment_status) and $news->comment_status == \App\Models\News::Comment_Status_On )
                    checked
                @endif
        >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">是否推荐</label>
    <div class="layui-input-inline">
        <input type="checkbox" name="recommend"
                lay-skin="switch"
                lay-text="{{ \App\Models\News::Recommend_ON }}|{{ \App\Models\News::Recommend_Off }}"
                @if( isset($news->recommend) and $news->recommend == \App\Models\News::Recommend_ON )
                    checked
                @endif
        >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">下载链接</label>
    <div class="layui-input-block">
        <input type="text" name="down_url" value="{{$news->down_url??old('down_url')}}" lay-verify="required" placeholder="请输入下载链接" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="button" class="layui-btn" lay-submit="" lay-filter="formDemo" onclick="$('form').submit()">确 认</button>
        <a  class="layui-btn" href="{{route('admin.news')}}" >返 回</a>
    </div>
</div>