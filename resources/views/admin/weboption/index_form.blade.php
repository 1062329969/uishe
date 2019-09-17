{{csrf_field()}}
    <div class="layui-form-item layui-input-block">
        <button type="button" class="layui-btn close" onclick="addData()">添加</button>
    </div>
<div class="layui-row layui-col-space10 " id="banner_div" >
    <input type="hidden" id="child_json" value="{{ json_encode(array_column($all_categorys->toArray(), 'name', 'id')) }}">
    @foreach($all_option as $key => $item)
    <div class="layui-form-item index_div" style="position: relative; border: 1px #ccc solid;">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">栏目分类</label>
            <div class="layui-input-inline">
                <select name="category_id" lay-filter="category_select" lay-verify="required">
                    <option value=""></option>
                    @foreach($categorys as $category)
                        <option value="{{ $category->id }}" @if(isset($item->category_id)&&$item->category_id==$category->id)selected @endif >{{ $category->name }}</option>
                        @if(isset($category->allChilds)&&!$category->allChilds->isEmpty())
                            @foreach($category->allChilds as $child)
                                <option value="{{ $child->id }}" @if(isset($item->category_id)&&$item->category_id==$child->id)selected @endif >&nbsp;&nbsp;&nbsp;┗━━{{ $child->name }}</option>
                                @if(isset($child->allChilds)&&!$child->allChilds->isEmpty())
                                    @foreach($child->allChilds as $third)
                                        <option value="{{ $third->id }}" @if(isset($item->category_id)&&$item->category_id==$third->id)selected @endif >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;┗━━{{ $third->name }}</option>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">栏目子级分类</label>
            <div class="layui-input-block" @if(!isset($news->tags)) style="display: none" @endif>
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
                    <select type="text" id="child_select" lay-filter="child_select" autocomplete="off" class="layui-select" lay-search>
                        {{--<select lay-filter="child_select" id="tags" lay-verify="" lay-search>--}}
                        @foreach($categorys as $category)
                            <option value="{{ $category->id }}" @if(isset($news->category_id)&&$news->category_id==$category->id)selected @endif >{{ $category->name }}</option>
                            @if(isset($category->allChilds)&&!$category->allChilds->isEmpty())
                                @foreach($category->allChilds as $child)
                                    <option value="{{ $child->id }}" @if(isset($news->category_id)&&$news->category_id==$child->id)selected @endif >{{ $child->name }}</option>
                                    @if(isset($child->allChilds)&&!$child->allChilds->isEmpty())
                                        @foreach($child->allChilds as $third)
                                            <option value="{{ $third->id }}" @if(isset($news->category_id)&&$news->category_id==$third->id)selected @endif >{{ $third->name }}</option>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="layui-form-item module_content">
            <label for="" class="layui-form-label">栏目内容</label>
            <div class="layui-input-block">
                <select type="text" lay-filter="content_select" class="content_select" autocomplete="off" class="layui-select" lay-search></select>
            </div>
            <div class="layui-input-block" style="display:none;">

            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">栏目搜索设置</label>
            <div class="layui-input-block">
                <input type="text" name="op_value[]" value="" lay-verify="required|numeric"  class="layui-input" >
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">排序</label>
            <div class="layui-input-inline">
                <input type="number" name="op_sort[]" value="" lay-verify="required|numeric"  class="layui-input" >
            </div>
        </div>
        <button type="button" class="layui-btn close" onclick="delData(this)">X</button>
    </div>
    @endforeach
</div>
<div class="layui-form-item">
    <div class="layui-input-block" style="text-align: center;">
        <button type="button" class="layui-btn" lay-filter="formDemo" >确 认</button>
        <a  class="layui-btn" href="{{route('admin.weboption')}}" >返 回</a>
    </div>
</div>

<div id="tmp_div" style="display: none">
    <div class="layui-form-item index_div" style="position: relative; border: 1px #ccc solid;">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">栏目分类</label>
            <div class="layui-input-inline">
                <select name="category_id" lay-verify="required">
                    <option value=""></option>
                    @foreach($categorys as $category)
                        <option value="{{ $category->id }}" @if(isset($item->category_id)&&$item->category_id==$category->id)selected @endif >{{ $category->name }}</option>
                        @if(isset($category->allChilds)&&!$category->allChilds->isEmpty())
                            @foreach($category->allChilds as $child)
                                <option value="{{ $child->id }}" @if(isset($item->category_id)&&$item->category_id==$child->id)selected @endif >&nbsp;&nbsp;&nbsp;┗━━{{ $child->name }}</option>
                                @if(isset($child->allChilds)&&!$child->allChilds->isEmpty())
                                    @foreach($child->allChilds as $third)
                                        <option value="{{ $third->id }}" @if(isset($item->category_id)&&$item->category_id==$third->id)selected @endif >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;┗━━{{ $third->name }}</option>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">栏目子级分类</label>

            <div class="layui-input-block" id="child_select_div" @if(!isset($news->tags)) style="display: none" @endif>
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
                    <select type="text" id="child_select" lay-filter="child_select" autocomplete="off" placeholder="移交单位全称" class="layui-select" lay-search>
                        {{--<select lay-filter="child_select" id="tags" lay-verify="" lay-search>--}}
                        @foreach($categorys as $category)
                            <option value="{{ $category->id }}" @if(isset($news->category_id)&&$news->category_id==$category->id)selected @endif >{{ $category->name }}</option>
                            @if(isset($category->allChilds)&&!$category->allChilds->isEmpty())
                                @foreach($category->allChilds as $child)
                                    <option value="{{ $child->id }}" @if(isset($news->category_id)&&$news->category_id==$child->id)selected @endif >{{ $child->name }}</option>
                                    @if(isset($child->allChilds)&&!$child->allChilds->isEmpty())
                                        @foreach($child->allChilds as $third)
                                            <option value="{{ $third->id }}" @if(isset($news->category_id)&&$news->category_id==$third->id)selected @endif >{{ $third->name }}</option>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">栏目内容</label>
            <div class="layui-input-block">
                <input type="text" name="op_value[]" value="" lay-verify="required|numeric"  class="layui-input" >
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">栏目搜索设置</label>
            <div class="layui-input-block">
                <input type="text" name="op_value[]" value="" lay-verify="required|numeric"  class="layui-input" >
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">排序</label>
            <div class="layui-input-inline">
                <input type="number" name="op_sort[]" value="" lay-verify="required|numeric"  class="layui-input" >
            </div>
        </div>
        <button type="button" class="layui-btn close" onclick="delData(this)">X</button>
    </div>
</div>
