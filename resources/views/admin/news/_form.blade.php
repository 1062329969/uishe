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
    <div class="layui-input-block" id="tags_select_div" style="display: none"></div>
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
    <label for="" class="layui-form-label">关键词</label>
    <div class="layui-input-block">
        <input type="text" name="keywords" value="{{$news->keywords??old('keywords')}}" lay-verify="required" placeholder="请输入关键词" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">描述</label>
    <div class="layui-input-block">
        <textarea name="description" placeholder="请输入描述" class="layui-textarea">{{$news->description??old('description')}}</textarea>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">点击量</label>
    <div class="layui-input-block">
        <input type="number" name="click" value="{{$news->click??mt_rand(100,500)}}" lay-verify="required|numeric"  class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">缩略图</label>
    <div class="layui-input-block">
        <div class="layui-upload">
            <button type="button" class="layui-btn" id="uploadPic"><i class="layui-icon">&#xe67c;</i>图片上传</button>
            <div class="layui-upload-list" >
                <ul id="layui-upload-box" class="layui-clear">
                    @if(isset($news->thumb))
                        <li><img src="{{ $news->thumb }}" /><p>上传成功</p></li>
                    @endif
                </ul>
                <input type="hidden" name="thumb" id="thumb" value="{{ $news->thumb??'' }}">
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
    <div class="layui-input-block">
        <button type="button" class="layui-btn" lay-submit="" lay-filter="formDemo" onclick="$('form').submit()">确 认</button>
        <a  class="layui-btn" href="{{route('admin.news')}}" >返 回</a>
    </div>
</div>