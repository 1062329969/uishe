{{csrf_field()}}
    <div class="layui-form-item layui-input-block">
        <button type="button" class="layui-btn close" id="addData">添加</button>
    </div>
<div class="layui-row layui-col-space10 " id="index_option_div" >
    <input type="hidden" id="child_json" value="{{ json_encode(array_column($all_categorys->toArray(), 'name', 'id')) }}">
    @foreach($all_option as $key => $item)
    <div class="layui-form-item index_div" style="position: relative; border: 1px #ccc solid;">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">栏目分类</label>
            <div class="layui-input-inline">
                <select name="category_id" lay-filter="category_select" lay-verify="required">
                    <option value=""></option>
                    @foreach($categorys as $category)
                        <option value="{{ $category->id }}" @if(isset($item['category_id'])&&$item['category_id']==$category->id)selected @endif >{{ $category->name }}</option>
                        @if(isset($category->allChilds)&&!$category->allChilds->isEmpty())
                            @foreach($category->allChilds as $child)
                                <option value="{{ $child->id }}" @if(isset($item['category_id'])&&$item['category_id']==$child->id)selected @endif >&nbsp;&nbsp;&nbsp;┗━━{{ $child->name }}</option>
                                @if(isset($child->allChilds)&&!$child->allChilds->isEmpty())
                                    @foreach($child->allChilds as $third)
                                        <option value="{{ $third->id }}" @if(isset($item['category_id'])&&$item['category_id']==$third->id)selected @endif >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;┗━━{{ $third->name }}</option>
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
            <div class="layui-input-block child_cat_div" @if(!isset($item['child']) || empty($item['child'])) style="display: none" @endif>
                @if(isset($item['child']))
                    @foreach($item['child'] as $child_item)
                        <span class="child_span">
                            <span>{{ $child_item['category_alias'] }}</span>
                            <input type="text" name="child" value="{{ $child_item['category_id'] }}" style="display: none">
                            <input type="text" name="child_name" value="{{ $child_item['category_alias'] }}" style="display: none">
                            <input class="child_input" type="button" value="x" onclick="delData(this)">
                        </span>
                    @endforeach
                @endif
            </div>
            <!-- 标签的star -->
            <div class="layui-input-block">
                <div class="layui-input-inline">
                    <select type="text" lay-filter="child_select" autocomplete="off" class="layui-select" lay-search>
                        @foreach($categorys as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @if(isset($category->allChilds)&&!$category->allChilds->isEmpty())
                                @foreach($category->allChilds as $child)
                                    <option value="{{ $child->id }}">{{ $child->name }}</option>
                                    @if(isset($child->allChilds)&&!$child->allChilds->isEmpty())
                                        @foreach($child->allChilds as $third)
                                            <option value="{{ $third->id }}">{{ $third->name }}</option>
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
            <div class="layui-row layui-col-space10 layui-input-block content_show" @if(!isset($item['content']) || empty($item['content'])) style="display: none" @endif>
                @foreach($item['content'] as $content)
                    <div class="layui-col-md3 content_itme" style="position: relative">
                        <img src="{{ $content['thumb'] }}" width="100%">
                        <p>{{ $content['title'] }}</p>
                        <input type="hidden" value="{{ $content['id'] }}" >
                        <button type="button" class="layui-btn close" onclick="delData(this)">X</button>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">栏目热门设置</label>
            <div class="layui-input-block serach_div" @if(!isset($item['search']) || empty($item['search'])) style="display: none" @endif>
                @foreach($item['search'] as $search_item)
                    <button type="button" class="layui-btn" onclick="$(this).remove()">{{ $search_item }}</button>
                @endforeach
            </div>
            <div class="layui-input-block">
                <input type="text" value="" lay-verify="required|numeric"  class="layui-input serach_input" onkeydown="setSerachData(event, this)">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">排序</label>
            <div class="layui-input-inline">
                <input type="number" name="op_sort" value="{{ $key }}" lay-verify="required|numeric"  class="layui-input" >
            </div>
        </div>
        <button type="button" class="layui-btn close" onclick="delData(this)">X</button>
    </div>
    @endforeach
</div>
<div class="layui-form-item">
    <div class="layui-input-block" style="text-align: center;">
        <button type="button" class="layui-btn" lay-filter="formDemo" onclick="saveData()">确 认</button>
        <a  class="layui-btn" href="{{route('admin.weboption')}}" >返 回</a>
    </div>
</div>

<div id="tmp_div" style="display: none">
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
            <div class="layui-input-block child_cat_div" @if(!isset($news->tags)) style="display: none" @endif>
                @if(isset($news->tags))
                    @foreach($news->tags as $tag_item)
                        <span class="child_span">
                            <span>{{ $tag_item['name'] }}</span>
                            <input type="text" name="child" value="{{ $tag_item['id'] }}" style="display: none">
                            <input type="text" name="child_name" value="{{ $tag_item['name'] }}" style="display: none">
                            <input class="child_input" type="button" value="x" onclick="deltags(this)">
                        </span>
                    @endforeach
                @endif
            </div>
            <!-- 标签的star -->
            <div class="layui-input-block">
                <div class="layui-input-inline">
                    <select type="text" lay-filter="child_select" autocomplete="off" class="layui-select" lay-search>
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
            <div class="layui-row layui-col-space10 layui-input-block content_show" style="display:none;"></div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">栏目热门设置</label>
            <div class="layui-input-block serach_div" style="display: none"></div>
            <div class="layui-input-block">
                <input type="text" value="" lay-verify="required|numeric"  class="layui-input serach_input" onkeydown="setSerachData(event, this)">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">排序</label>
            <div class="layui-input-inline">
                <input type="number" name="op_sort" value="" lay-verify="required|numeric"  class="layui-input" >
            </div>
        </div>
        <button type="button" class="layui-btn close" onclick="delData(this)">X</button>
    </div>
</div>
