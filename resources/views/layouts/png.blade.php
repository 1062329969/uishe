<link href="{{ asset('css/top_simple.v3.9.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/element/element-view.v3.7.css') }}" rel="stylesheet" type="text/css">
@include('home.common.top')
<style>
    .png_tags{
        padding: 5px 10px;
        border-radius: 10px;
        color: #fff;
        background: -webkit-linear-gradient(left top, #fff , #848484); /* Safari 5.1 - 6.0 */
        background: -o-linear-gradient(bottom right, #fff , #848484); /* Opera 11.1 - 12.0 */
        background: -moz-linear-gradient(bottom right, #fff , #848484); /* Firefox 3.6 - 15 */
        background: linear-gradient(to bottom right, #fff , #848484); /* 标准的语法 */
    }
    .png_tags_on{
        border: 1px solid #a060f5;
        padding: 5px 10px;
        border-radius: 10px;
        color: #fff;
        background: #9862f4;
        background: -webkit-linear-gradient(left top, #7371ef , #a55ef6); /* Safari 5.1 - 6.0 */
        background: -o-linear-gradient(bottom right, #7371ef , #a55ef6); /* Opera 11.1 - 12.0 */
        background: -moz-linear-gradient(bottom right, #7371ef , #a55ef6); /* Firefox 3.6 - 15 */
        background: linear-gradient(to bottom right, #7371ef , #a55ef6); /* 标准的语法 */
    }
    .r_tag{
        display: inline-block;
        text-align: center;
        margin-top: 5px;
        min-width: 66px;
    }
    .tags_recommend_div,.tags_thematic_div{
        padding: 10px;
        min-height: auto;
    }
    .tags_recommend_p,.tags_thematic_p{
        background: #eaeaea;
        height: 50px;
        line-height: 50px;
        padding-left: 20px;
        font-size: 20px;
        font-weight: bold;
        color: #969696;
    }
    .thematic_itme{
        overflow: hidden;
    }
    .thematic_itme a{
        display: block;
        float: left;
        width: 230px;
        height: 115px;
        margin: 6px;
    }
</style>
<div class="main"> <!--location start-->
    <div class="location-box oh">
        <div class="location fn12">
            <span>当前位置：</span>
            <a href="https://www.51miz.com/sucai/">设计素材</a>
            <span>/</span>
            <a href="https://www.51miz.com/yishuzi/">艺术字</a>
            <span>/</span>
            <span>当前作品</span>
        </div>
    </div><!--location end-->
    <div class="main-content"> <!--detail start--> <!--[if lte IE 8]>
        <style>
            .show-box {
            border: 1px solid #d3d3d3;
        }
        </style> <![endif]-->
        <div class="detail-box">
            <div class="show-box fl pr" style="word-wrap: break-word;word-break: normal;">
                {!! $new['content'] !!}
            </div>
            <style>
                .show-box img{
                    width:100%;
                    height: auto;
                }
            </style>
            <div class="info-box fr pr">
                <div class="title-banner">
                    <div class="title-box">
                        <div class="title fl lh28">
                            <div class="copyright_icon center iblock fl iftip" style="*zoom:1;*display:inline;">
                                <span>©</span>
                            </div>
                            &nbsp;
                            <div class="element_tip iftip" style="display:none">
                                <div class="inner"></div>
                                <div style="margin:4px;">UI社独家版权作品</div>
                            </div>
                            <h1 class="iftip" style="display:inline-block;font-size:18px;font-weight:normal;width:400px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;float:left;*zoom: 1;*display: inline;*float:none;">{{ $new['title'] }}</h1>
                        </div>
                    </div>
                </div>
                <div class="otherinfo oh fn14">
                    <div>素材编号ID： {{ $new['id'] }}</div>
                    @foreach(explode(' | ', $new['introduction']) as $introduction)
                    <div>{{ $introduction }}</div>
                    @endforeach
                </div>
                <script src="{{ asset('js/clipboard.min.js') }}"></script>
                <div class="download">
                    <a rel="nofollow" data-id="{{ $new['id'] }}" class="download-png block fn18 fl css3-background-size" style="background-image: url('https://ss.51miz.com/images/detail_icon@2x.png');" id="download_news">下载PNG</a>
                    <a class="show-fav fav-view button iblock color-fff center fl fav-{{ $new['id'] }}" data-id="{{ $new['id'] }}"  onclick="fav({{ $new['id'] }})" href="javascript:;" rel="nofollow" title="加入收藏">
                        <span class="fav-icon iblock css3-background-size" style="background-image: url('https://ss.51miz.com/images/icon-star@2x.png');"></span>
                    </a>
                </div>
                <script>
                    $('.download-png').click(function () {
                        $.ajax({
                            type:"GET",
                            url:'/user/checkvip',
                            dataType:'json',
                            success:function(data){
                                if(data.msg == 'Unauthenticated'){
                                    window.location.href='{{ route('login', ['r' => request()->url()]) }}'
                                }
                            }
                        })
                    })
                </script>
                <div class="tags fn14">
                    <p style="display: inline;">
                        @foreach(json_decode($new['tag'], true) as $new_tag)
                            <a style="color: #fff;" class="png_tags_on" href="{{ url('/tag/'.$new_tag)}}">{{ $new_tag }}</a>
                        @endforeach
                    </p>
                </div>
            </div>

            <div class="info-box fr pr" style="margin-top: 20px;">
                <div class="tags fn14 show-box tags_recommend_div">
                    <p class="tags_recommend_p" >热门搜索</p>
                    <p style="display: inline;">
                        @foreach($tags_recommend as $r_tag)
                            <a style="color: #fff;width: auto;" class="@if(in_array( $r_tag['name'] , json_decode($new['tag'], true))) png_tags_on @else png_tags @endif r_tag" href="{{ url('/tag/'.$r_tag['name'])}}">{{ $r_tag['name'] }}</a>
                        @endforeach
                    </p>
                </div>
            </div>

            <div class="info-box fr pr" style="margin-top: 20px;">
                <div class="tags fn14 show-box tags_thematic_div">
                    <p class="tags_thematic_p" >相关专题</p>
                    <p class="thematic_itme">
                        @foreach($thematic as $thematic_item)
                            <a class="" href="{{ $thematic_item['url']  }}">
                                <img src="{{ $thematic_item['cover_img'] }}">
                            </a>
                        @endforeach
                    </p>
                </div>
            </div>

        </div> <!--detail end--> <!--interest start-->
        <div class="interest oh">
            <div class="interesting fl  fn16">您可能感兴趣</div>
            <!--<a href="/shejisucai/" class="block seemore fn14 fr"> <span class="iblock seemore-icon fr css3-background-size"></span> <span class="iblock fr">查看更多</span> </a>-->
        </div> <!--interest end--><!--[if lte IE 8]>
        <style type="text/css">
            .element-box {
                border: 1px solid #eee;
            }
        </style> <![endif]-->
        <link href="{{ asset('css/element/element-list.v3.5.css') }}" rel="stylesheet" type="text/css">
        <div class="flex-images pr">
            @foreach($news_recommend as $nr)
                <div class="element-box item" style="display: block;">
                    <div class="element-box-detail">
                        <div class="element pr oh">
                            <div class="copyright-icon opacity-5">©</div>
                            <a class="image-box" href="{{ url('/'.$nr['id'].'.html') }}" target="_blank" style="width:100%;height:100%;">
                                <img class="lazyload" src="{{ $nr['cover_img'] }}" data-original="{{ $nr['cover_img'] }}" title="{{ $nr['title'] }}" alt="{{ $nr['title'] }}" style="height: 189px; margin: 0px;">
                            </a>
                            <a onclick="fav({{ $nr['id'] }})" class="pa fav-star fl fav-{{ $nr['id'] }}">
                                <span class="block"></span>
                            </a>
                            <div class="hide-top pa none" style="display: block;">
                                <a onclick="1" target="_blank" href="{{ url('/'.$nr['id'].'.html') }}" rel="nofollow" class="png-download fr">
                                    <span class="download-icon block fl"></span>
                                    <span class="block fl psd">下载</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!--<span class="size fl">1280*640</span>-->
        <div class="relative-search-box pr">
            <div class="relative_model"
                 style="/*max-width: 960px;*/height:28px;/*overflow:  hidden;*/display: inline-block;">
                <div class="relative-search fn14 fl">相关推荐：</div>
                @foreach($recommend_tag as $rt)
                    <a target="_blank" class="block fn14 fl rel-a" href="{{ url('/tag/'.$rt) }}">{{ $rt }}</a>
                @endforeach
            </div>
        </div>
    </div>
    <div style="height:70px;"></div>
</div>
@include('home.common.bottom')
<script>
    $(".bx-prev,.bx-prevs").click(function () {
    var multiImgNum = "0";
    /*var key = parseInt($(".previewPic.show").attr("data-id"));*/
    var key = 0;
    if ($(this).parent().hasClass("big-picture")) {
        key = parseInt($(this).parent().find(".previewPic.show").attr("data-id"));
    }
    if ($(this).parent().hasClass("show-box")) {
        key = parseInt($(this).parent().find(".img-box").find(".previewPic.show").attr("data-id"));
    }
    if (multiImgNum > 0) {
        var showkey = 0;
        if (key == 0) {
            showkey = multiImgNum;
        } else {
            showkey = key - 1;
        }
        if ($(this).parent().hasClass("big-picture")) {
            var width = $(this).parent().find(".previewPic_" + showkey).attr("data-width");
            var height = $(this).parent().find(".previewPic_" + showkey).attr("data-height");
            $(this).parent().css("width", width).css("height", height).css("margin-top", -height / 2).css("margin-left", -width / 2);
            $(this).parent().find(".previewPic").addClass("none").removeClass("show");
            $(this).parent().find(".previewPic_" + showkey).removeClass("none").addClass("show");
        }
        if ($(this).parent().hasClass("show-box")) {
            var widthsmall = $(this).parent().find(".img-box").find(".previewPicSmall_" + showkey).attr("data-width");
            var heightsmall = $(this).parent().find(".img-box").find(".previewPicSmall_" + showkey).attr("data-height");
            $(this).parent().find(".img-box").css("width", widthsmall).css("height", heightsmall).css("margin-top", -heightsmall / 2).css("margin-left", -widthsmall / 2);
            $(this).parent().find(".img-box").find(".previewPic").addClass("none").removeClass("show");
            $(this).parent().find(".img-box").find(".previewPic_" + showkey).removeClass("none").addClass("show");
            var width = $(".bx-nexts").parent().find(".previewPic_" + showkey).attr("data-width");
            var height = $(".bx-nexts").parent().find(".previewPic_" + showkey).attr("data-height");
            $(".bx-nexts").parent().css("width", width).css("height", height).css("margin-top", -height / 2).css("margin-left", -width / 2);
            $(".bx-nexts").parent().find(".previewPic").addClass("none").removeClass("show");
            $(".bx-nexts").parent().find(".previewPic_" + showkey).removeClass("none").addClass("show");
        }
        /*$(".big-picture").css("width",width).css("height",height).css("margin-top",-height/2).css("margin-left",-width/2);$(".img-box").css("width",widthsmall).css("height",heightsmall).css("margin-top",-heightsmall/2).css("margin-left",-widthsmall/2);$(".bx-prevs").css("margin-left",-(width/2 + 66));$(".bx-nexts").css("margin-left",width/2 + 22);$(this).parent().find(".previewPic").addClass("none").removeClass("show");$(this).parent().find(".previewPic_"+showkey).removeClass("none").addClass("show");*/
    }
});
$(".bx-next,.bx-nexts").click(function () {
    var multiImgNum = "0";
    /*var key = parseInt($(".previewPic.show").attr("data-id"));*/
    var key = 0;
    if ($(this).parent().hasClass("big-picture")) {
        key = parseInt($(this).parent().find(".previewPic.show").attr("data-id"));
    }
    if ($(this).parent().hasClass("show-box")) {
        key = parseInt($(this).parent().find(".img-box").find(".previewPic.show").attr("data-id"));
    }
    if (multiImgNum > 0) {
        var showkey = 0;
        if (key == multiImgNum) {
            showkey = 0;
        } else {
            showkey = key + 1;
        }
        if ($(this).parent().hasClass("big-picture")) {
            var width = $(this).parent().find(".previewPic_" + showkey).attr("data-width");
            var height = $(this).parent().find(".previewPic_" + showkey).attr("data-height");
            $(this).parent().css("width", width).css("height", height).css("margin-top", -height / 2).css("margin-left", -width / 2);
            $(this).parent().find(".previewPic").addClass("none").removeClass("show");
            $(this).parent().find(".previewPic_" + showkey).removeClass("none").addClass("show");
        }
        if ($(this).parent().hasClass("show-box")) {
            var widthsmall = $(this).parent().find(".img-box").find(".previewPicSmall_" + showkey).attr("data-width");
            var heightsmall = $(this).parent().find(".img-box").find(".previewPicSmall_" + showkey).attr("data-height");
            $(this).parent().find(".img-box").css("width", widthsmall).css("height", heightsmall).css("margin-top", -heightsmall / 2).css("margin-left", -widthsmall / 2);
            $(this).parent().find(".img-box").find(".previewPic").addClass("none").removeClass("show");
            $(this).parent().find(".img-box").find(".previewPic_" + showkey).removeClass("none").addClass("show");
            var width = $(".bx-nexts").parent().find(".previewPic_" + showkey).attr("data-width");
            var height = $(".bx-nexts").parent().find(".previewPic_" + showkey).attr("data-height");
            $(".bx-nexts").parent().css("width", width).css("height", height).css("margin-top", -height / 2).css("margin-left", -width / 2);
            $(".bx-nexts").parent().find(".previewPic").addClass("none").removeClass("show");
            $(".bx-nexts").parent().find(".previewPic_" + showkey).removeClass("none").addClass("show");
        }
        /*$(".big-picture").css("width",width).css("height",height).css("margin-top",-height/2).css("margin-left",-width/2);$(".img-box").css("width",widthsmall).css("height",heightsmall).css("margin-top",-heightsmall/2).css("margin-left",-widthsmall/2);$(".bx-prevs").css("margin-left",-(width/2 + 66));$(".bx-nexts").css("margin-left",width/2 + 22);$(this).parent().find(".previewPic").addClass("none").removeClass("show");$(this).parent().find(".previewPic_"+showkey).removeClass("none").addClass("show");*/
    }
});

</script>
</body>
</html>