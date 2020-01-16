<link href="{{ asset('css/top_simple.v3.9.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/element/element-view.v3.7.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/element/element-list.v3.5.css') }}" rel="stylesheet" type="text/css">
<script src="{{ asset('js/clipboard.min.js') }}"></script>
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
    .tags_recommend_div a:hover{
        background: #9862f4;
        background: -webkit-linear-gradient(left top, #7371ef , #a55ef6); /* Safari 5.1 - 6.0 */
        background: -o-linear-gradient(bottom right, #7371ef , #a55ef6); /* Opera 11.1 - 12.0 */
        background: -moz-linear-gradient(bottom right, #7371ef , #a55ef6); /* Firefox 3.6 - 15 */
        background: linear-gradient(to bottom right, #7371ef , #a55ef6); /* 标准的语法 */
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
    .comment-box ul{
        padding: 0 10px;
    }
    .comment-box ul li{
        border-bottom: 1px dotted #E6E6E6;
        padding:20px;
        background: #f9f9f9;
        margin-bottom: 20px;
    }
    .cmt{
         position: relative;
         border-radius: 3px;
         -webkit-border-radius: 3px;
         padding: 8px 12px;
         /*background: #F7F7F7;*/
         line-height: 20px;
         font-size: 13px;
         color: #31424e;
     }
    .comment_title{
        background: #f7f7f7;
        height: 50px;
        border-bottom: 1px #ccc solid;
        line-height: 50px;
        padding-left: 20px;
        font-size: 20px;
        color: #7f7f7f;
        font-weight: bold;
    }
    .perMsg{
        overflow: hidden;
    }
    .comment_avater, .txt{
        float: left;
    }
    .txt{
        line-height: 40px;
        margin-left: 20px;
    }
    .cmt{
        line-height: 25px;
        margin-top: 15px;
        font-size: 16px;
        text-indent: 2em;
    }
    .comment_avater img { width: 80px;height: 80px; display: block; border-radius: 80px;}
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
                <div class="download">
                    @if($new['down_url'])
                        <a rel="nofollow" data-id="{{ $new['id'] }}" class="download-png block fn18 fl css3-background-size" style="cursor:hand;background-image: url('https://ss.51miz.com/images/detail_icon@2x.png');" id="download_news">下载</a>
                    @endif
                    <a class="show-fav fav-view button iblock color-fff center fl fav-{{ $new['id'] }}" data-id="{{ $new['id'] }}"  onclick="fav({{ $new['id'] }})" href="javascript:;" rel="nofollow" title="加入收藏">
                        <span class="fav-icon iblock css3-background-size" style="background-image: url('https://ss.51miz.com/images/icon-star@2x.png');"></span>
                    </a>
                </div>
                <script>
                    $('.download-png').click(function () {
                        var data_id = $(this).attr('data-id')
                        $.ajax({
                            type:"get",
                            url:'{{ route('check_download', ['down_type' =>'news', 'id'=> $new['id'] ]) }}',
                            dataType:'json',
                            success:function(data){
                                if(data.code == 500){
                                    if(data.msg == 'Unauthenticated.'){
                                        var msg = '请先登录！'
                                        var fun = function(){
                                            window.location.href='{{ route('login', ['r' => request()->url()]) }}'
                                        }
                                    }else if(data.msg == 'Notfound') {
                                        var msg = '未找到资源'
                                        var fun = function(){}
                                    }else if(data.msg == 'LevelError') {
                                        var msg = '您的会员等级不足，请提升等级'
                                        var fun = function(){
                                            window.location.href='{{ route('user') }}'
                                        }
                                    }else if(data.msg == 'CreditError') {
                                        var msg = '抱歉您的积分余额不足，请及时充值'
                                        var fun = function(){}
                                    }else if(data.msg == 'DownClose') {
                                        var msg = '资源下载暂时关闭，请联系管理员'
                                        var fun = function(){
                                            window.location.href='{{ route('creditlog') }}'
                                        }
                                    }
                                    layer.msg(msg, {icon:5,offset: 't',shade: [0.8, '#393D49'], shadeClose:true}, fun);
                                }
                                if ( data.code == 0 ){
                                    if(data.msg.platfrom == 'local'){
                                        var fun = function(){
                                            window.location.href='{{ route('down_new', ['id'=> $new['id'] ]) }}'
                                        }
                                        layer.msg('前往下载', {icon:5,offset: 't',shade: [0.8, '#393D49'], shadeClose:true}, fun);
                                    }else if (data.msg.platfrom == 'web'){
                                        var fun = function(){
                                            window.location.href = data.msg.url
                                        }
                                        layer.msg('前往下载', {icon:5,offset: 't',shade: [0.8, '#393D49'], shadeClose:true}, fun);
                                    }else if (data.msg.platfrom == 'baidu'){
                                        var pwd = data.msg.pwd

                                        layer.open({
                                            content: '点击确定，复制密码',
                                            offset: 't',
                                            shade: [0.8, '#393D49'],
                                            shadeClose:true,
                                            yes: function(index, layero){
                                                $(".layui-layer-btn0").attr("data-clipboard-text", pwd);
                                                var clipboard = new Clipboard('.layui-layer-btn0');
                                                clipboard.on('success', function(e) {
                                                    layer.msg('复制成功正在前往下载', {icon:1,offset: 't',shade: [0.8, '#393D49'], shadeClose:true}, function () {
                                                        window.open(data.msg.url, '_blank').location;
                                                    });
                                                    clipboard.destroy();  //解决多次弹窗
                                                    e.clearSelection();
                                                });
                                            }
                                        });
                                    }
                                }
                            }
                        })
                    })
                </script>
                <div class="tags fn14">
                    <p style="display: inline;">
                        @foreach(json_decode($new['tag'], true) as $new_tag)
                            <a style="color: #fff; display: inline-block;width: auto;text-align: center;margin-top: 5px;" class="png_tags_on" href="{{ url('/tag/'.$new_tag)}}">{{ $new_tag }}</a>
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
        <div class="relative-search-box pr" style="height: auto;">
            <div class="relative_model"
                 style="display: inline-block;">
                <div class="relative-search fn14 fl">相关推荐：</div>
                @foreach($recommend_tag as $rt)
                    <a target="_blank" class="block fn14 fl rel-a" href="{{ url('/tag/'.$rt) }}">{{ $rt }}</a>
                @endforeach
            </div>
        </div>

        <div class="relative-search-box pr" style="height: auto;border: 1px #ccc solid;">
            <div class="comment_title">社友热评</div>
            <div class="self_comment">
                <form action="http://www.uishe.cn/wp-comments-post.php" class="comment_form" method="post" id="commentform">
                    <div class="single-post-comment__form cf">
                        <textarea class="textarea form-control" data-widearea="enable" id="comment" name="comment" placeholder="你怎么看？"></textarea>
                        <input type="hidden" name="comment_post_ID" value="15172" id="comment_post_ID">
                        <input type="hidden" name="comment_parent" id="comment_parent" value="0">
                        <p style="display: none;">
                            <input type="hidden" id="akismet_comment_nonce" name="akismet_comment_nonce" value="f3797f5d79">
                        </p>
                        <span class="mail-notify-check">
                            <input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked" style="vertical-align:middle;">
                            <label for="comment_mail_notify" style="vertical-align:middle;">有人回复时邮件通知我</label>
                        </span>
                    </div>

                    <div id="comboxinfo" class="comboxinfo cl">
                        <div class="cominfodiv cominfodiv-author ">
                            <p for="author" class="nicheng">
                                <input type="text" name="author" id="author" class="texty" value="" tabindex="1">
                                <span class="required">昵称*</span>
                            </p>
                        </div>
                        <div class="cominfodiv cominfodiv-email">
                            <p for="email">
                                <input type="text" name="email" id="email" class="texty" value="" tabindex="2">
                                <span class="required">邮箱*</span>
                            </p>
                        </div>

                        <button type="submit" class="ladda-button comment-submit-btn">
                            <span class="ladda-label">提交评论</span>
                        </button>
                        <div id="cancel_comment_reply">
                            <a rel="nofollow" id="cancel-comment-reply-link" href="/15172.html#respond" style="display:none;">取消回复</a>
                        </div>
                    </div>
                    <input type="hidden" id="ak_js" name="ak_js" value="1579165621966"></form>
            </div>
            <div class="comment-box" style="padding: 10px;">
                <ul>
                    @foreach($comments_recommend as $comments_item)
                    <li class="sidcomment">
                        <div class="perMsg cl">
                            <a href="{{ url('/'.$comments_item['new_id'].'.html') }}" target="_blank" class="comment_avater" rel="nofollow">
                                <img src="{{ $comments_item['avatar_url'] }}" class="avatar" onerror="this.src = 'http://qzapp.qlogo.cn/qzapp/101214606/6EB1E86CD71993912377E404C7C66F87/100'">
                            </a>
                            <div class="txt">
                                <div class="rows cl">
                                    <a href="{{ url('/'.$comments_item['new_id'].'.html') }}" target="_blank" class="name" rel="nofollow">
                                        <span>{{ $comments_item['user_name'] }}</span>
                                        评论文章：
                                    </a>
                                    <span class="time"> {{ $comments_item['created_at'] }} </span>
                                </div>
                                <div class="artHeadTit">
                                    <a href="{{ url('/'.$comments_item['new_id'].'.html') }}" target="_blank" title="{{ $comments_item['new_title'] }}&nbsp;">
                                        {{ $comments_item['new_title'] }}&nbsp;
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="cmt">
                            {{ $comments_item['content'] }}
                        </div>
                    </li>
                    @endforeach
                </ul>
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