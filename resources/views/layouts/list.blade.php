<link href="{{ asset('css/search/search.v4.7.bak.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/video_category.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/video/video-list.v3.0.css') }}" rel="stylesheet" type="text/css">
@include('home.common.top')

<div class="main"> <!--search-banner start-->

    <link href="{{ asset('css/search/search-banner.v3.0.css') }}" rel="stylesheet" type="text/css">
    <div class="search-banner">
        <form class="search-box pr" action="{{ url('/getNewsList') }}?category={{ $category }}">
            <input type="text" class="search-input banner-search fl color-888" placeholder="搜索" value="" name="search">
            <a href="javascript:;" class="search-submit pa" onclick="$(this).parent().submit()"></a>
        </form>
        <div class="clear"></div>
    </div>

    <div class="category-box-outer oh pr">
        <div class="category-box oh">
            @if( isset($category_list) && !$category_list->isEmpty())
            <div class="category oh">
                <a href="javascript:;" class="child-category-title fn14 fl on" rel="nofollow">全部分类</a>
                <div class="oh fl" classid="1" style="width: 90%;">
                    @foreach($category_list as $category_item)
                    <a href="{{ url('/getNewsList') }}?category={{ $category_item }}&tag={{ $tag }}" @if($category_item == $category) class="on" @endif style="display: inline-block; margin-bottom: 5px" class="child-category fn14">{{ $category_item }}</a>
                    @endforeach
                </div>
            </div>
            @endif

            @if($tag_list)
            <div class="category oh" classid="2">
                <a href="javascript:;" class="child-category-title fn14 fl on" rel="nofollow">全部标签</a>
                <div class="oh fl" style="width: 90%;">
                    @foreach($tag_list as $tag_item)
                        <a href="{{ url('/getNewsList') }}?category={{ $category }}&tag={{ $tag_item }}" @if($tag_item == $tag) class="on" @endif style="display: inline-block;margin-bottom: 5px;" class="child-category fn14">{{ $tag_item }}</a>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="category oh noborder" classid="3">
                <div class="child-category-box category-height oh fl">
                    <a href="javascript:;" class="child-category-title fn14 on" rel="nofollow">排序</a>
                    <a href="{{ url('/getNewsList') }}?category={{ $category }}&tag={{ $tag }}&orderby=created_at" class="child-category fn14">发布时间</a>
                    <a href="{{ url('/getNewsList') }}?category={{ $category }}&tag={{ $tag }}&orderby=comment_count" class="child-category fn14">评论最多</a>
                    <a href="{{ url('/getNewsList') }}?category={{ $category }}&tag={{ $tag }}&orderby=views" class="child-category fn14">浏览数量</a>
                    <a href="{{ url('/getNewsList') }}?category={{ $category }}&tag={{ $tag }}&orderby=like" class="child-category fn14">点赞最多</a>
                </div>
            </div>
        </div>
    </div>
    <div class="main-content oh pr"> <!-- 面包屑开始 --><!-- 面包屑结束 --><!--已经选择的属性 satrt-->
        <div class="selected-block-box" style="margin-top:0px;"></div> <!--已经选择的属性 end--> <!--相关搜索和搜索结果汇总合并-->
         <!-- sucai标签添加 --><!-- sucai标签结束 --> <!-- 映射词 开始--><!-- 映射词 结束 -->
        <div class="relative_model oh"></div>
        <!--Piclist start-->
        <!--搜索内容-->
        @if(!$data->isEmpty())
        <div class="picList" style="z-index:20">
            @foreach($data as $item)
            <div class="video-Box picBox fl pr">
                <a href="{{ url('/' . $item['id'] . '.html') }}" rel="nofollow" style="height:158px" target="_blank" class="pr picBox-a" data-id="{{ $item['id'] }}" >
                    <img class="lazyload" src="{{ $item['cover_img'] }}" alt="{{ $item['title'] }}" title="{{ $item['title'] }}" >{{-- style="z-index: 20; position: absolute; top: 50%; margin-top: -78.75px; display: block;" --}}
                </a>
                <div class="pic-item-title center fn14 color-444">
                    <div class="color-444 fn14 seo-h3">
                        <a class="title-content" href="{{ url('/' . $item['id'] . '.html') }}" target="_blank" style="display: block;background: none;color:#444;">
                            {{ $item['title'] }}
                        </a>
                    </div>
                </div>
                <div class="pic-item-action center pa none" style="display: none;">
                    <div class="pic-item-action-button">
                        <a class="pic-item-action-button-fav fav fl css3-background-size block fav-{{ $item['id'] }}" onclick="fav({{ $item['id'] }});" href="javascript:;" rel="nofollow" style="background-image: url('{{ asset('images/icon-star.png') }}');"></a>
                        <a class="pic-item-action-button-download fr color-fff block" target="_blank" href="{{ url('/'.$item['id'].'.html') }}" rel="nofollow" data-id="{{ $item['id'] }}" data-plate-id="5">立即下载</a>
                    </div>
                </div>
                <input type="hidden" class="tools tools-v-108875" data-id="v-108875">
            </div>
            @endforeach
        </div><!--无结果时的相关推荐-->
        <div id="pageNum">
            <div>
                {{ $data->links() }}
            </div>
        </div>
        @else
        <div>
            <div style="margin: auto;margin: 50px;text-align: center">
                <div class="advice" style="margin-top: 20px;width: 100%;font-size: 20px;text-align: center;color: #888;height: 20px;line-height: 20px;">
                    暂无相关内容，请切换分类或更短的搜索词，
                </div>
            </div>
        </div>
        @endif
        <div class="hotSearch pr oh" style="width: 100%;line-height: 28px;margin: 10px auto 20px;z-index: 9;   ">
            <div class="hotSearch_model oh">
                <div class="hotSearch-search fn14 fl">热门推荐：</div>
                @foreach($recommend_tag as $rt)
                    <a style="margin-bottom: 7px" target="_blank" class="block fn14 fl rel-a" href="{{ url('/tag/'.$rt) }}">{{ $rt }}</a>
                @endforeach
            </div>
        </div>
    </div>
</div>

@include('home.common.bottom')

<style>
    .suggest-bottom {display: inline-block;height: 50px;width: 160px;margin: 40px auto 60px;line-height: 50px;border-radius: 50px;background-color: #7371ef;color: #fff;}
    .suggest-bottom:hover {transition: all 0.2s;box-shadow: 0 4px 20px rgba(115, 113, 239, 0.5);}
    .main-content, .search-sorting-box-cus, .search-sorting-box-cus-hide {
        width: 1182px;
        z-index: 0;
        margin: auto;
    }

    .search-sorting-line-item-box {
        width: 95%;
        float: left;
        height: 50px
    }
    .all-search-tags {
        padding: 8px 8px;
        height: 48px;
        margin-bottom: 10px;
        box-sizing: content-box;
    }

    .all-search-tags span a {
        display: none;
        position: absolute;
        width: 16px;
        height: 16px;
        top: -4px;
        right: 0px;
        background: url("//ss.51miz.com//images/search/remove.png") no-repeat center;
        background-size: 16px 16px;
    }

    .all-search-tags span {
        position: relative;
        cursor: pointer;
        margin: 0px 10px 20px 0px;
        width: 120px;
        text-align: center;
        display: inline-block;
        box-shadow: 0 1px 6px rgba(32, 33, 36, 0.28);
        background-color: white;
        border-radius: 24px;
        box-sizing: border-box;
        color: #444;
        font-size: 16px;
        font-weight: bold;
        height: 48px;
        line-height: 48px;
    }

    .all-search-tags span.on, .all-search-tags span:hover {
        background-color: #e8f0fe;
        border-color: #D2E3FC;
    }

    .all-search-tags span.on a {
        display: block;
    }
</style>

</body>
</html>