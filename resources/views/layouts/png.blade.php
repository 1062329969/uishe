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
                            <a style="color: #fff; display: inline-block;text-align: center;margin-top: 5px;" class="png_tags_on" href="{{ url('/tag/'.$new_tag)}}">{{ $new_tag }}</a>
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
        <div class="relative-search-box pr">
            <div class="relative_model"
                 style="/*max-width: 960px;*/height:28px;/*overflow:  hidden;*/display: inline-block;">
                <div class="relative-search fn14 fl">相关推荐：</div>
                @foreach($recommend_tag as $rt)
                    <a target="_blank" class="block fn14 fl rel-a" href="{{ url('/tag/'.$rt) }}">{{ $rt }}</a>
                @endforeach
            </div>
        </div>

        <div class="relative-search-box pr">

            <div class="comment-box">
                <div class="comment-edit-box d-flex">
                    <a id="commentsedit"></a>
                    <div class="user-img">
                        <a href="javascript:void(0);" target="_blank" rel="noopener">
                            <img class="show_loginbox" src="//g.csdnimg.cn/static/user-img/anonymous-User-img.png">
                        </a>
                    </div>
                    <form id="commentform">
                        <input type="hidden" id="comment_replyId">
                        <textarea class="comment-content" name="comment_content" id="comment_content"
                                  placeholder="想对作者说点什么"></textarea>
                        <div class="opt-box"> <!-- d-flex -->
                            <div id="ubbtools" class="add_code">
                                <a href="#insertcode" code="code" target="_self"><i
                                            class="icon iconfont icon-daima"></i></a>
                            </div>
                            <input type="hidden" id="comment_replyId" name="comment_replyId">
                            <input type="hidden" id="article_id" name="article_id" value="78222983">
                            <input type="hidden" id="comment_userId" name="comment_userId" value="">
                            <input type="hidden" id="commentId" name="commentId" value="">
                            <div style="display: none;" class="csdn-tracking-statistics tracking-click"
                                 data-report-click="{&quot;mod&quot;:&quot;popu_384&quot;,&quot;dest&quot;:&quot;&quot;}">
                                <a href="#" target="_blank" class="comment_area_btn" rel="noopener">发表评论</a></div>
                            <div class="dropdown" id="myDrap">
                                <a class="dropdown-face d-flex align-items-center" data-toggle="dropdown" role="button"
                                   aria-haspopup="true" aria-expanded="false">
                                    <div class="txt-selected text-truncate">添加代码片</div>
                                    <svg class="icon d-block" aria-hidden="true">
                                        <use xlink:href="#csdnc-triangledown"></use>
                                    </svg>
                                </a>
                                <ul class="dropdown-menu" id="commentCode" aria-labelledby="drop4">
                                    <li><a data-code="html">HTML/XML</a></li>
                                    <li><a data-code="objc">objective-c</a></li>
                                    <li><a data-code="ruby">Ruby</a></li>
                                    <li><a data-code="php">PHP</a></li>
                                    <li><a data-code="csharp">C</a></li>
                                    <li><a data-code="cpp">C++</a></li>
                                    <li><a data-code="javascript">JavaScript</a></li>
                                    <li><a data-code="python">Python</a></li>
                                    <li><a data-code="java">Java</a></li>
                                    <li><a data-code="css">CSS</a></li>
                                    <li><a data-code="sql">SQL</a></li>
                                    <li><a data-code="plain">其它</a></li>
                                </ul>
                            </div>
                            <div class="right-box">
                                <span id="tip_comment" class="tip">还能输入<em>1000</em>个字符</span>
                                <input type="button" class="btn btn-sm btn-cancel d-none" value="取消回复">
                                <input type="submit" class="btn btn-sm btn-red btn-comment" value="发表评论">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="comment-list-container">
                    <a id="comments"></a>
                    <div class="comment-list-box" style="max-height: 275px;">
                        <ul class="comment-list">
                            <li class="comment-line-box d-flex" data-commentid="8868875" data-replyname="u011046042"><a
                                        target="_blank" href="https://me.csdn.net/u011046042"><img
                                            src="https://profile.csdnimg.cn/0/3/D/3_u011046042" username="u011046042"
                                            alt="u011046042" class="avatar"></a>
                                <div class="right-box ">
                                    <div class="new-info-box clearfix"><a target="_blank"
                                                                          href="https://me.csdn.net/u011046042"><span
                                                    class="name ">道亦无名</span></a><span class="date"
                                                                                       title="2018-12-13 13:29:25">1年前</span><span
                                                class="floor-num">#4楼</span><span class="new-comment">赞一个</span><span
                                                class="new-opt-box"><a class="btn btn-link-blue btn-report"
                                                                       data-type="report">举报</a><a
                                                    class="btn btn-link-blue btn-reply" data-type="reply">回复</a></span>
                                    </div>
                                    <div class="comment-like " data-commentid="8868875">
                                        <svg t="1569296798904" class="icon " viewBox="0 0 1024 1024" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg" p-id="5522" width="200" height="200">
                                            <path d="M726.016 906.666667h-348.586667a118.016 118.016 0 0 1-116.992-107.904l-29.013333-362.666667A117.589333 117.589333 0 0 1 348.458667 309.333333H384c126.549333 0 160-104.661333 160-160 0-51.413333 39.296-88.704 93.397333-88.704 36.906667 0 71.68 18.389333 92.928 49.194667 26.88 39.04 43.178667 111.658667 12.714667 199.509333h95.530667a117.418667 117.418667 0 0 1 115.797333 136.106667l-49.28 308.522667a180.608 180.608 0 0 1-179.072 152.704zM348.458667 373.333333l-4.48 0.170667a53.461333 53.461333 0 0 0-48.768 57.472l29.013333 362.666667c2.218667 27.52 25.6 49.024 53.205333 49.024h348.544a116.949333 116.949333 0 0 0 115.925334-98.816l49.322666-308.736a53.418667 53.418667 0 0 0-52.650666-61.781334h-144.085334a32 32 0 0 1-28.458666-46.634666c45.909333-89.130667 28.885333-155.434667 11.562666-180.522667a48.981333 48.981333 0 0 0-40.192-21.504c-6.912 0-29.397333 1.792-29.397333 24.704 0 111.317333-76.928 224-224 224h-35.541333zM170.624 906.666667a32.042667 32.042667 0 0 1-31.872-29.44l-42.666667-533.333334a32.042667 32.042667 0 0 1 29.354667-34.474666c17.066667-1.408 33.024 11.733333 34.432 29.354666l42.666667 533.333334a32.042667 32.042667 0 0 1-31.914667 34.56z"
                                                  p-id="5523"></path>
                                        </svg>
                                        <span></span></div>
                                </div>
                            </li>
                        </ul>
                        <ul class="comment-list">
                            <li class="comment-line-box d-flex" data-commentid="8325230" data-replyname="Cindy62"><a
                                        target="_blank" href="https://me.csdn.net/Cindy62"><img
                                            src="https://profile.csdnimg.cn/8/7/9/3_cindy62" username="Cindy62"
                                            alt="Cindy62" class="avatar"></a>
                                <div class="right-box ">
                                    <div class="new-info-box clearfix"><a target="_blank"
                                                                          href="https://me.csdn.net/Cindy62"><span
                                                    class="name ">Cindy62</span></a><span class="date"
                                                                                          title="2018-08-10 23:25:04">1年前</span><span
                                                class="floor-num">#3楼</span><span class="new-comment">很赞，朋友推荐的摹客设计系统，很方便。</span><span
                                                class="new-opt-box"><a class="btn btn-link-blue btn-report"
                                                                       data-type="report">举报</a><a
                                                    class="btn btn-link-blue btn-reply" data-type="reply">回复</a></span>
                                    </div>
                                    <div class="comment-like " data-commentid="8325230">
                                        <svg t="1569296798904" class="icon " viewBox="0 0 1024 1024" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg" p-id="5522" width="200" height="200">
                                            <path d="M726.016 906.666667h-348.586667a118.016 118.016 0 0 1-116.992-107.904l-29.013333-362.666667A117.589333 117.589333 0 0 1 348.458667 309.333333H384c126.549333 0 160-104.661333 160-160 0-51.413333 39.296-88.704 93.397333-88.704 36.906667 0 71.68 18.389333 92.928 49.194667 26.88 39.04 43.178667 111.658667 12.714667 199.509333h95.530667a117.418667 117.418667 0 0 1 115.797333 136.106667l-49.28 308.522667a180.608 180.608 0 0 1-179.072 152.704zM348.458667 373.333333l-4.48 0.170667a53.461333 53.461333 0 0 0-48.768 57.472l29.013333 362.666667c2.218667 27.52 25.6 49.024 53.205333 49.024h348.544a116.949333 116.949333 0 0 0 115.925334-98.816l49.322666-308.736a53.418667 53.418667 0 0 0-52.650666-61.781334h-144.085334a32 32 0 0 1-28.458666-46.634666c45.909333-89.130667 28.885333-155.434667 11.562666-180.522667a48.981333 48.981333 0 0 0-40.192-21.504c-6.912 0-29.397333 1.792-29.397333 24.704 0 111.317333-76.928 224-224 224h-35.541333zM170.624 906.666667a32.042667 32.042667 0 0 1-31.872-29.44l-42.666667-533.333334a32.042667 32.042667 0 0 1 29.354667-34.474666c17.066667-1.408 33.024 11.733333 34.432 29.354666l42.666667 533.333334a32.042667 32.042667 0 0 1-31.914667 34.56z"
                                                  p-id="5523"></path>
                                        </svg>
                                        <span></span></div>
                                </div>
                            </li>
                        </ul>
                        <ul class="comment-list">
                            <li class="comment-line-box d-flex" data-commentid="8252108" data-replyname="jongde1"><a
                                        target="_blank" href="https://me.csdn.net/jongde1"><img
                                            src="https://profile.csdnimg.cn/A/1/F/3_jongde1" username="jongde1"
                                            alt="jongde1" class="avatar"></a>
                                <div class="right-box ">
                                    <div class="new-info-box clearfix"><a target="_blank"
                                                                          href="https://me.csdn.net/jongde1"><span
                                                    class="name ">Mockplus</span></a><span class="date"
                                                                                           title="2018-07-25 11:36:10">1年前</span><span
                                                class="floor-num">#2楼</span><span class="new-comment">摹客设计系统上线，为大家准备了双重好礼！邀请各位设计师今夏一起High！

福利一：免费领取摹客（Mockplus+摹客设计系统）专业版等福利。

领取步骤：注册账号，领取礼包必并创建设计库后即可获得一个兑奖码，参与抽奖。Airpods、Wacom手绘板、小米手环，创可贴，Eagle VIP会员等你拿！

福利二：参加设计大赛，晒设计规范，赢iPad Pro和Wecom手绘板！

点击此链接即可参加活动：https://ds.mockplus.cn/?hmsr=thomas</span><span class="new-opt-box"><a class="btn btn-link-blue btn-report"
                                                                                   data-type="report">举报</a><a
                                                    class="btn btn-link-blue btn-reply" data-type="reply">回复</a></span>
                                    </div>
                                    <div class="comment-like " data-commentid="8252108">
                                        <svg t="1569296798904" class="icon " viewBox="0 0 1024 1024" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg" p-id="5522" width="200" height="200">
                                            <path d="M726.016 906.666667h-348.586667a118.016 118.016 0 0 1-116.992-107.904l-29.013333-362.666667A117.589333 117.589333 0 0 1 348.458667 309.333333H384c126.549333 0 160-104.661333 160-160 0-51.413333 39.296-88.704 93.397333-88.704 36.906667 0 71.68 18.389333 92.928 49.194667 26.88 39.04 43.178667 111.658667 12.714667 199.509333h95.530667a117.418667 117.418667 0 0 1 115.797333 136.106667l-49.28 308.522667a180.608 180.608 0 0 1-179.072 152.704zM348.458667 373.333333l-4.48 0.170667a53.461333 53.461333 0 0 0-48.768 57.472l29.013333 362.666667c2.218667 27.52 25.6 49.024 53.205333 49.024h348.544a116.949333 116.949333 0 0 0 115.925334-98.816l49.322666-308.736a53.418667 53.418667 0 0 0-52.650666-61.781334h-144.085334a32 32 0 0 1-28.458666-46.634666c45.909333-89.130667 28.885333-155.434667 11.562666-180.522667a48.981333 48.981333 0 0 0-40.192-21.504c-6.912 0-29.397333 1.792-29.397333 24.704 0 111.317333-76.928 224-224 224h-35.541333zM170.624 906.666667a32.042667 32.042667 0 0 1-31.872-29.44l-42.666667-533.333334a32.042667 32.042667 0 0 1 29.354667-34.474666c17.066667-1.408 33.024 11.733333 34.432 29.354666l42.666667 533.333334a32.042667 32.042667 0 0 1-31.914667 34.56z"
                                                  p-id="5523"></path>
                                        </svg>
                                        <span></span></div>
                                </div>
                            </li>
                        </ul>
                        <ul class="comment-list">
                            <li class="comment-line-box d-flex" data-commentid="8145220" data-replyname="jongde1"><a
                                        target="_blank" href="https://me.csdn.net/jongde1"><img
                                            src="https://profile.csdnimg.cn/A/1/F/3_jongde1" username="jongde1"
                                            alt="jongde1" class="avatar"></a>
                                <div class="right-box ">
                                    <div class="new-info-box clearfix"><a target="_blank"
                                                                          href="https://me.csdn.net/jongde1"><span
                                                    class="name ">Mockplus</span></a><span class="date"
                                                                                           title="2018-06-26 18:03:49">1年前</span><span
                                                class="floor-num">#1楼</span><span class="new-comment">非常赞！最近国内原型设计工具大佬-Mockplus推出了国内首家设计系统-摹客设计系统。可轻松对Logo、标准色、字体、标准字、图标、组件、图片、度量及阴影九大资源进行设计和管理规范。设计好的规范可以直接应用到Mockplus和Sketch中，此外，还可以分享给其它小伙伴或导出为图片及PDF发给开发。可以轻松管理设计资源，让设计和开发沟通更简单！具体地址：https://ds.mockplus.cn/。有兴趣的可以看看。</span><span
                                                class="new-opt-box"><a class="btn btn-link-blue btn-report"
                                                                       data-type="report">举报</a><a
                                                    class="btn btn-link-blue btn-reply" data-type="reply">回复</a></span>
                                    </div>
                                    <div class="comment-like " data-commentid="8145220">
                                        <svg t="1569296798904" class="icon " viewBox="0 0 1024 1024" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg" p-id="5522" width="200" height="200">
                                            <path d="M726.016 906.666667h-348.586667a118.016 118.016 0 0 1-116.992-107.904l-29.013333-362.666667A117.589333 117.589333 0 0 1 348.458667 309.333333H384c126.549333 0 160-104.661333 160-160 0-51.413333 39.296-88.704 93.397333-88.704 36.906667 0 71.68 18.389333 92.928 49.194667 26.88 39.04 43.178667 111.658667 12.714667 199.509333h95.530667a117.418667 117.418667 0 0 1 115.797333 136.106667l-49.28 308.522667a180.608 180.608 0 0 1-179.072 152.704zM348.458667 373.333333l-4.48 0.170667a53.461333 53.461333 0 0 0-48.768 57.472l29.013333 362.666667c2.218667 27.52 25.6 49.024 53.205333 49.024h348.544a116.949333 116.949333 0 0 0 115.925334-98.816l49.322666-308.736a53.418667 53.418667 0 0 0-52.650666-61.781334h-144.085334a32 32 0 0 1-28.458666-46.634666c45.909333-89.130667 28.885333-155.434667 11.562666-180.522667a48.981333 48.981333 0 0 0-40.192-21.504c-6.912 0-29.397333 1.792-29.397333 24.704 0 111.317333-76.928 224-224 224h-35.541333zM170.624 906.666667a32.042667 32.042667 0 0 1-31.872-29.44l-42.666667-533.333334a32.042667 32.042667 0 0 1 29.354667-34.474666c17.066667-1.408 33.024 11.733333 34.432 29.354666l42.666667 533.333334a32.042667 32.042667 0 0 1-31.914667 34.56z"
                                                  p-id="5523"></path>
                                        </svg>
                                        <span></span></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div id="commentPage" class="pagination-box d-none" style="display: block;">
                        <div id="Paging_01426385864191193" class="ui-paging-container">
                            <ul>
                                <li class="js-page-first js-page-action ui-pager ui-pager-disabled"></li>
                                <li class="js-page-prev js-page-action ui-pager ui-pager-disabled">上一页</li>
                                <li data-page="1" class="ui-pager focus">1</li>
                                <li class="js-page-next js-page-action ui-pager ui-pager-disabled">下一页</li>
                                <li class="js-page-last js-page-action ui-pager ui-pager-disabled"></li>
                            </ul>
                        </div>
                    </div>
                    <div class="opt-box text-center">
                        <div class="btn btn-sm btn-link-blue" id="btnMoreComment"><span>登录 查看 4 条热评</span>
                            <svg class="icon open" aria-hidden="true">
                                <use xlink:href="#csdnc-chevrondown"></use>
                            </svg>
                        </div>
                    </div>
                </div>
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