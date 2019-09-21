@include('home.common.top')
<div class="main" id="main">
    <!--Banner 区域 ///start///-->
    <div class="banner pr css3-background-size " id="banner">
        <!--<div id="fireworksBox" style="position:absolute;width:1920px;height:400px;top:0;left:0;"></div>-->
        <div class="banner-box oh">
            @foreach($index_banner as $k => $v)
                <a href="{{ $v['op_value'] }}" target="_blank" title="{{ $v['op_json']['title'] }}" style="background-image:url('{{ $v['op_parameter'] }}');" class="css3-background-size"></a>
            @endforeach
        </div>
        <!--Search Box ///start///-->
        <div class="search-content pa" id="search-content">
            <!--Search框-->
            <div class="search-input-box fl pr">
                <input type="text" placeholder="搜索作品" class="mastHasPlate search-input fn16 banner-search" data-search-top="55" data-search-width="640" data-plate-path="sucai" />
                <!--Search-Element搜索按钮-->
                <a href="javascript:;" data-plate-path="sucai" rel="nofollow" class="search-submit search-button-box search-submit-element search-submit-sucai iblock fl pa center">搜素材</a>
            </div>
        </div>
        <!--Search Box ///end///-->
        <!--Search框下面的搜索词-->
        <div class="banner-search-word-box pa oh center color-fff fn14">  <a target="_blank" data-id="251" href="//www.51miz.com/so-sucai/239141.html" class="color-fff iblock">处暑</a>   <span>·</span>   <a target="_blank" data-id="258" href="//www.51miz.com/so-sucai/1953799.html" class="color-fff iblock">建国70周年</a>   <span>·</span>
            <a target="_blank" data-id="252" href="//www.51miz.com/so-ppt/85216.html" class="color-fff iblock">党课PPT</a>
            <span>·</span>
            <a target="_blank" data-id="253" href="//www.51miz.com/so-sucai/96403.html" class="color-fff iblock">中秋节</a>
            <span>·</span>
            <a target="_blank" data-id="254" href="//www.51miz.com/so-sucai/86094.html" class="color-fff iblock">开学季</a>
            <span>·</span>   <a target="_blank" data-id="255" href="//www.51miz.com/so-sucai/86725.html" class="color-fff iblock">秋天</a>
            <span>·</span>
            <a target="_blank" data-id="256" href="//www.51miz.com/so-sucai/86496.html" class="color-fff iblock">招生</a>
            <span>·</span>
            <a target="_blank" data-id="257" href="//www.51miz.com/so-sucai/84942.html" class="color-fff iblock">小清新</a>
        </div>
    </div>
    <!--Banner 区域 ///end///-->
    @foreach($index_option as $k => $v)
    <div class="png-box plate-box pr">
        <div class="plate-title">
            <h2 style="font-size: 28px;font-weight: normal">
                <span class="plate-title-dotted iblock">•</span>
                <a href="{{ url('/'.$v['category_alias']) }}" target="_blank">PPT模板精选</a>
                <span class="plate-title-dotted iblock">•</span>
            </h2>
        </div>
        @if(isset($v['search']))
        <div class="plate-keyword-box" >
            @foreach($v['child'] as $c_k => $c_v)
                <a target="_blank" href="{{ url('/'.$v['category_alias'].'/'.$c_v['category_alias']) }}">{{ $c_v['category_alias'] }}</a>
            @endforeach
        </div>
        @endif
        <div class="plate-subject-box oh">
            @foreach($v['content'] as $con_k => $con_v)
                <a target="_blank" href="{{ url('/'.$con_v['id']) }}" alt="{{ $con_v['title'] }}" title="{{ $con_v['title'] }}">
                    <div class="plate-subject pr ">
                        <img src="{{ $con_v['thumb'] }}" width="280px" />
                        <div class="jianbian-mask"></div>
                        <div class="subject-title pa">{{ $con_v['title'] }}</div>
                        <div class="subject-mask-box">
                            <div class="subject-mask"></div>
                            <div class="subject-mask-title">{{ $con_v['title'] }}</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        @if(isset($v['search']))
        <ul class="seo-keyword oh">
            @foreach($v['search'] as $s_k => $s_v)
            <a target="_blank" href="{{ url('/?s='.$s_v) }}">
                <li>{{ $s_v }}</li>
            </a>
            @endforeach
        </ul>
        @endif
    </div>
    @endforeach
    <!--why start-->
    <div class="why-mould oh">
        <div class="why-mould-title center">为什么选择觅知？</div>
        <div class="why-content oh">
            <div class="why fl">
                <div class="icon-bg01 css3-background-size"></div>
                <div class="why-title fn16">海量下载</div>
                <div class="why-detail fn14">一次充值 海量下载</div>
            </div>
            <div class="why fl">
                <div class="icon-bg02 css3-background-size"></div>
                <div class="why-title fn16">价格低廉</div>
                <div class="why-detail fn14">单个下载低至0.01元</div>
            </div>
            <div class="why fl">
                <div class="icon-bg03 css3-background-size"></div>
                <div class="why-title fn16">海量精品</div>
                <div class="why-detail fn14">1000万在线作品</div>
            </div>
            <div class="why fl last-item">
                <div class="icon-bg04 css3-background-size"></div>
                <div class="why-title fn16">实时更新</div>
                <div class="why-detail fn14">每日更新500+作品</div>
            </div>
        </div>
    </div>
    <!--why end-->
    <!--experience-->
    <div class="mould foot-banner oh">
        <div class="experience-box"> <a href="https://www.51miz.com/index.php?m=pay&a=vip" target="_blank" class="block experience fn18">立即体验</a>
        </div>
    </div>
    <!-- 分享活动结束关闭分享活动入口 -->
    <!---->
    <!--<div class="reply-main">-->
    <!--<div class="close-reply" onclick="closereply()"></div>-->
    <!--<a target="_blank" href="https://www.51miz.com/?m=ReplyAct" onclick="ReplyAct(3)">-->
    <!--<div class="reply css3-background-size">-->
    <!--</div>-->
    <!--</a>-->
    <!--</div>-->
    <!---->
    <!-- 右侧 -->
    <!--<a class="page-right-animation css3-background-size" href="https://www.51miz.com/?m=Bargain&from=right" target="_blank">-->
    <!--<span class="close" title="关闭">-->
    <!--<span class="close-image-animation css3-background-size"></span>-->
    <!--</span>-->
    <!--</a>-->
    <!--fixed-button start-->
    <!--style="width:49px;height:114px;background-color:transparent;box-shadow:none;" makemoney makemoney_back css3-background-size fr clear-->
    <div class="new-fixed-button">
        <!---->
        <!--<!–<a href="https://www.51miz.com/?m=Info&a=makemoney" class="block makemoney pa css3-background-size" target="_blank"></a>–>-->
        <!--<a href="https://www.51miz.com/?m=Info&a=makemoney" class="block makemoney pa css3-background-size" target="_blank"></a>-->
        <!---->
        <!--<a href="https://vs.rainbowred.com/visitor/pc/chat.html?companyId=12020&routeEntranceId=178&metaData=8flLdz6K78QlS0GbaeMXmJxXFMxtKgBsYJZdUq+wDt1XTW404QkiBkO5y7cjxOshCAhXppu5HD4faxPhW3dh5GAaSBqD6DEekKXu9ca7gnHgGvsyiAucabk9taUCdCDh7TdCC1gPx3CZz3sWhMmSdIr4qrSnnqounRM5ynbcz0++Kz6cAd6wbJxtNmTxWvjBKQAs1WamZezQlyPnd6P/aI1Jb8IF4QmSbELC6E7XjRZKxC/JjczLY2SQIkE2IU2G"
        class="block fn12 center clear turn_purple" target="_blank">在线<br>客服</a>-->
    <!--<a style="cursor: pointer" onclick="popup('suggest@isclose:1;')" class="block fn12 center clear turn_purple" target="_blank">您的<br>需求</a>-->
        <!--<!–<a href="https://jq.qq.com/?_wv=1027&k=5mcrkGY" class="block fn12 center clear turn_purple" target="_blank">官方QQ群</a>–>-->
        <!---->
        <!--<!–<a href="https://www.51miz.com/?m=Bargain&from=right" class="block fn12 center clear turn_purple" target="_blank">0元终身VIP</a>–>-->
        <!---->
        <!--<a href="javascript:void(0)" class="gotop-button center fn12 oh pr fr none ">返回顶部 <div class="gotop-icon css3-background-size iblock pa"></div></a>--> <a href="javascript:;" class="show-qr-code"><span class="bottom-border css3-background-size weixin-act"><br></span></a>  <a href="https://www.51miz.com/?m=Info&a=makemoney" target="_blank"><span class="bottom-border">我要<br>赚钱</span></a>  <a href="https://vs.rainbowred.com/visitor/pc/chat.html?companyId=12020&routeEntranceId=178&metaData=8flLdz6K78QlS0GbaeMXmJxXFMxtKgBsYJZdUq+wDt1XTW404QkiBkO5y7cjxOshCAhXppu5HD4faxPhW3dh5GAaSBqD6DEekKXu9ca7gnHgGvsyiAucabk9taUCdCDh7TdCC1gPx3CZz3sWhMmSdIr4qrSnnqounRM5ynbcz0++Kz6cAd6wbJxtNmTxWvjBKQAs1WamZezQlyPnd6P/aI1Jb8IF4QmSbELC6E7XjRZKxC/JjczLY2SQIkE2IU2G"
                                                                                                                                                                                                                                                                                                                                                                                                                    target="_blank"><span class="bottom-border">在线<br>客服</span></a>  <a href="javascript:;" onclick="popup('suggest@isclose:1;')"><span class="bottom-border">您的<br>需求</span></a>  <a class="to-top-button" style="display: none" href="javascript:;"><span><i class="to-top"></i><br>顶部</span></a>
    </div>
    <div class="weixin-login none">
        <div class="qr-code css3-background-size"></div>
        <div class="qr-title">
            <div class="qr-title-main">微信扫码关注</div>
            <div class="qr-title-detail">领见面礼和素材包</div>
        </div>
    </div>
    <!--back to top end-->
</div>

@include('home.common.bottom')
</html>