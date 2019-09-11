{{csrf_field()}}
<div class="layui-row layui-col-space10">
    <input type="hidden" id="all_categorys" value="{{ json_encode($all_categorys, JSON_UNESCAPED_UNICODE) }}">
    <div class="tpl_div layui-col-md4">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">类型</label>
            <div class="layui-input-block">
                <input type="radio" lay-filter="type" name="type" value="category" title="分类" checked>
                <input type="radio" lay-filter="type" name="type" value="customize" title="自定义">
            </div>
        </div>

        <div class="layui-form-item" name="category_div">
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

        <div class="layui-form-item layui-hide" name="customize_div">
            <label for="" class="layui-form-label">名称</label>
            <div class="layui-input-block">
                <input type="text" name="title" value="" lay-verify="required" placeholder="请输入名称" class="layui-input" >
            </div>
        </div>
        <div class="layui-form-item layui-hide" name="customize_div">
            <label for="" class="layui-form-label">URL</label>
            <div class="layui-input-block">
                <input type="text" name="url" value="" lay-verify="required" placeholder="请输入链接" class="layui-input" >
            </div>
        </div>

        <div class="layui-form-item">
            <label for="" class="layui-form-label">排序</label>
            <div class="layui-input-inline">
                <input type="number" name="op_sort" value="0" lay-verify="required|numeric"  class="layui-input" >
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button type="button" class="layui-btn" lay-filter="formDemo" onclick="setData()">确 认</button>
                <a  class="layui-btn" href="{{route('admin.weboption')}}" >返 回</a>
            </div>
        </div>
    </div>


    <div id="data_div" class="layui-collapse layui-col-md4 @if(!$all_option->toArray()) layui-hide @endif" lay-accordion>
        <div class="layui-colla-item layui-hide category_tpl">
            <h2 class="layui-colla-title"></h2>
            <div class="layui-colla-content">
                <div class="layui-form-item">
                    <label class="layui-form-label" style="width: auto" name="tmp_label"></label>
                    <div class="layui-input-inline">
                        <input type="text" name="data" value="" class="layui-input" >
                        <span style="color: red;font-size: 10px">注：此字段为地址栏显示，可自行修改,默认为URL编码数据</span>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label" style="width: auto">排序</label>
                    <div class="layui-input-inline">
                        <input type="number" name="sort" value="" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <button type="button" class="layui-btn layui-btn-danger"  onclick="delData(this)">删除</button>
                </div>
            </div>
            <input type="hidden" name="op_type">
            <input type="hidden" name="op_value">
            <input type="hidden" name="op_ids">
        </div>

        @foreach($all_option as $index => $item)
        <div class="layui-colla-item" name="layui-colla-item-data">
            <h2 class="layui-colla-title">{{ $item['op_value'] }}</h2>
            <div class="layui-colla-content layui-show">
                <div class="layui-form-item">
                    <label class="layui-form-label" style="width: auto" name="tmp_label">
                        @if($item['op_json'] == 'url')
                            URL
                        @else
                            别名
                        @endif
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" name="data" value="" class="layui-input" placeholder="{{ $item['op_parameter'] }}">
                        <span style="color: red;font-size: 10px">注：此字段为地址栏显示，可自行修改,默认为URL编码数据</span>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label" style="width: auto">排序</label>
                    <div class="layui-input-inline">
                        <input type="number" name="sort" value="{{ $item['op_sort'] }}" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <button type="button" class="layui-btn layui-btn-danger" onclick="delData(this)">删除</button>
                </div>
            </div>
            <input type="hidden" name="op_type" value="{{ $item['op_type'] }}">
            <input type="hidden" name="op_value" value="{{ $item['op_value'] }}">
            <input type="hidden" name="op_ids" value="{{ $item['op_ids'] }}">
        </div>
        @endforeach


        <div class="layui-form-item"></div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button type="button" class="layui-btn" onclick="saveData()">确 认</button>
            </div>
        </div>
    </div>

</div>
