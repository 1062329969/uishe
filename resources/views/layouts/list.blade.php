<link href="{{ asset('css/search/search.v4.7.bak.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/video_category.css') }}" rel="stylesheet" type="text/css">

@include('common.top')

<div class="main"> <!--search-banner start-->

    <link href="{{ asset('css/search/search-banner.v3.0.css') }}" rel="stylesheet" type="text/css">
    <div class="search-banner">
        <div class="search-box pr">
            <input type="text" class="search-input banner-search fl color-888" placeholder="搜索视频" value="">
            <a href="javascript:;" class="search-submit pa"></a>
        </div>
        <div class="clear"></div>
    </div>

    <div class="category-box-outer oh pr"> <div class="category-box oh"> <div class="category oh"> <div class="child-category-box category-height oh fl" classid="1"> <a href="https://www.51miz.com/shipin/0-0-0-default-1.html" class="child-category-title fn14 on" rel="nofollow">全部分类</a><a href="https://www.51miz.com/shipin/ae/" class="child-category fn14">AE模板</a><a href="https://www.51miz.com/shipin/huiying/" class="child-category fn14">会声会影</a><a href="https://www.51miz.com/shipin/beijing/" class="child-category fn14">视频背景</a><a href="https://www.51miz.com/shipin/duanpian/" class="child-category fn14">实拍短片</a></div> </div> <div class="category oh" classid="2"> <div class="child-category-box category-height oh fl"> <a href="https://www.51miz.com/shipin/0-0-0-default-1.html" class="child-category-title fn14 on" rel="nofollow">全部用途</a><a href="https://www.51miz.com/shipin/0-1-0-default-1.html" class="child-category fn14">开场片头</a><a href="https://www.51miz.com/shipin/0-12-0-default-1.html" class="child-category fn14">节日</a><a href="https://www.51miz.com/shipin/0-11-0-default-1.html" class="child-category fn14">年会</a><a href="https://www.51miz.com/shipin/0-10-0-default-1.html" class="child-category fn14">颁奖典礼</a><a href="https://www.51miz.com/shipin/0-9-0-default-1.html" class="child-category fn14">党建</a><a href="https://www.51miz.com/shipin/0-8-0-default-1.html" class="child-category fn14">生日</a><a href="https://www.51miz.com/shipin/0-7-0-default-1.html" class="child-category fn14">图文展示</a><a href="https://www.51miz.com/shipin/0-6-0-default-1.html" class="child-category fn14">企业宣传</a><a href="https://www.51miz.com/shipin/0-5-0-default-1.html" class="child-category fn14">LOGO演绎</a><a href="https://www.51miz.com/shipin/0-4-0-default-1.html" class="child-category fn14">相册动画</a><a href="https://www.51miz.com/shipin/0-3-0-default-1.html" class="child-category fn14">婚礼婚庆</a><a href="https://www.51miz.com/shipin/0-2-0-default-1.html" class="child-category fn14">倒计时</a><a href="https://www.51miz.com/shipin/0-13-0-default-1.html" class="child-category fn14">栏目包装</a></div> </div> <div class="category oh noborder" classid="3"> <div class="child-category-box category-height oh fl"> <a href="https://www.51miz.com/shipin/0-0-0-default-1.html" class="child-category-title fn14 on" rel="nofollow">全部效果</a><a href="https://www.51miz.com/shipin/0-0-1-default-1.html" class="child-category fn14">粒子特效</a><a href="https://www.51miz.com/shipin/0-0-13-default-1.html" class="child-category fn14">烟雾</a><a href="https://www.51miz.com/shipin/0-0-12-default-1.html" class="child-category fn14">花瓣飘落</a><a href="https://www.51miz.com/shipin/0-0-11-default-1.html" class="child-category fn14">波纹</a><a href="https://www.51miz.com/shipin/0-0-10-default-1.html" class="child-category fn14">爆炸</a><a href="https://www.51miz.com/shipin/0-0-9-default-1.html" class="child-category fn14">火焰</a><a href="https://www.51miz.com/shipin/0-0-8-default-1.html" class="child-category fn14">线条</a><a href="https://www.51miz.com/shipin/0-0-7-default-1.html" class="child-category fn14">转场</a><a href="https://www.51miz.com/shipin/0-0-6-default-1.html" class="child-category fn14">水墨</a><a href="https://www.51miz.com/shipin/0-0-5-default-1.html" class="child-category fn14">漩涡特效</a><a href="https://www.51miz.com/shipin/0-0-4-default-1.html" class="child-category fn14">科技感</a><a href="https://www.51miz.com/shipin/0-0-3-default-1.html" class="child-category fn14">震撼</a><a href="https://www.51miz.com/shipin/0-0-2-default-1.html" class="child-category fn14">图片汇聚</a><a href="https://www.51miz.com/shipin/0-0-14-default-1.html" class="child-category fn14">中国风</a></div> </div> </div> </div>
    <div class="main-content oh pr"> <!-- 面包屑开始 --><!-- 面包屑结束 --><!--已经选择的属性 satrt-->
        <div class="selected-block-box" style="margin-top:0px;"></div> <!--已经选择的属性 end--> <!--相关搜索和搜索结果汇总合并-->
         <!-- sucai标签添加 --><!-- sucai标签结束 --> <!-- 映射词 开始--><!-- 映射词 结束 -->
        <div class="relative_model oh"></div>
        <!--Piclist start-->
        <!--搜索内容-->
        <link href="//ss.51miz.com/css/video/video-list.v3.0.css" rel="stylesheet" type="text/css">
        <div class="picList" style="z-index:20">
            <div class="video-Box picBox fl pr">
                <a href="{{ url('/123214.html') }}" rel="nofollow" style="height:158px" target="_blank" class="pr picBox-a" data-id="108875" data-plate-id="5">
                    <img class="lazyload" src="//img.51miz.com/preview/video/00/00/10/88/V-108875-26D37AA1.jpg!/quality/90/unsharp/true/compress/true/format/webp/fw/280" alt="建国七十70周年国庆节党政党建大气图文展示企业宣传片头AE模板" title="建国七十70周年国庆节党政党建大气图文展示企业宣传片头AE模板" >{{-- style="z-index: 20; position: absolute; top: 50%; margin-top: -78.75px; display: block;" --}}
                </a>
                <div class="pic-item-title center fn14 color-444">
                    <div class="color-444 fn14 seo-h3">
                        <a class="title-content" href="{{ url('/123214.html') }}" target="_blank" style="display: block;background: none;color:#444;">
                            建国七十70周年国庆节党政党建大气图文展示企业宣传片头AE模板
                        </a>
                    </div>
                </div>
                <div class="pic-item-action center pa none" style="display: none;">
                    <div class="pic-item-action-button">
                        <a class="pic-item-action-button-fav fav fl css3-background-size block fav-5-108875" onclick="fav(108875,5);" href="javascript:;" rel="nofollow" style="background-image: url('{{ asset('images/icon-star@2x.png') }}');"></a>
                        <a class="pic-item-action-button-download fr color-fff block" target="_blank" href="https://www.51miz.com/?m=download&amp;id=108875&amp;plate_id=5" rel="nofollow" data-id="108875" data-plate-id="5">立即下载</a>
                    </div>
                </div>
                <input type="hidden" class="tools tools-v-108875" data-id="v-108875"></div>

        </div><!--无结果时的相关推荐-->
        <div class="bottom_no_pleased">
            <div class="fn14" style="margin-top: 20px;text-align: center;">
                搜索结果不满意？换个搜索词试试
                <span class="main_bottom_advice">
                    <a class="bottom_advice" href="https://www.51miz.com/so-shipin/1852684.html">不忘初心牢记使命党课</a>
                </span>或者
                <a class="bottom_suggest" onclick="popup('suggest@isclose:1;')">提交您的需求</a>
            </div>
        </div> <!--分页-->
        <div id="pageNum">
            <div></div>
        </div>
        <div class="hotSearch pr oh" style="width: 100%;line-height: 28px;margin: 10px auto 20px;z-index: 9;   ">
            <div class="hotSearch_model oh">
                <div class="hotSearch-search fn14 fl">热门推荐：</div>
                <a style="margin-bottom: 7px" target="_blank" class="block fn14 fl rel-a" href="https://www.51miz.com/so-shipin/92428.html">片头</a>
                <a style="margin-bottom: 7px" target="_blank" class="block fn14 fl rel-a" href="https://www.51miz.com/so-shipin/85274.html">相册</a>
                <a style="margin-bottom: 7px" target="_blank" class="block fn14 fl rel-a" href="https://www.51miz.com/so-shipin/200297.html">片头片尾</a>
                <a style="margin-bottom: 7px" target="_blank" class="block fn14 fl rel-a" href="https://www.51miz.com/so-shipin/84852.html">中国风</a>
            </div>
        </div>
    </div>
</div>

@include('common.bottom')

<style>
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