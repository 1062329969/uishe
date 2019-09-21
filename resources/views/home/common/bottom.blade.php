<link rel="stylesheet" type="text/css" href="{{ asset('css/bottom.v3.2.css') }}" />
<div class="bottom">
    <div class="bottom-content oh">
        <div class="aboutus fl">
            <div class="aboutus-link">
                <a href="{{ url('/special') }} https://www.51miz.com/aboutus/" class="fn14" target="_blank" rel="nofollow">精选专题</a>
                <span>|</span>
                <a href="{{ url('/business') }} https://www.51miz.com/shengming/" class="fn14" target="_blank" rel="nofollow">商业合作</a>
                <span>|</span>
                <a href="{{ url('/copyright') }} https://www.51miz.com/agreement/" class="fn14" target="_blank" rel="nofollow">版权声明</a>
            </div>
            <div class="cpright fn14">版权所有 Copyright © 2019 UI社 .AllRights Reserved  ·  <a href="http://www.miitbeian.gov.cn" target="_blank" rel="nofollow">{{ $site['copyright'] }}</a>
            </div>
            <div class="cpright fn14">UI社交流群：385330021 （注明来自UI社） 客服QQ: 1829020117 （注明来自UI社）</div>
        </div>
    </div>
    <div style="display: none" id="cnzz"><script src="https://s19.cnzz.com/z_stat.php?id=4875190&web_id=4875190" language="JavaScript"></script></div>
</div>
</body>
<script type="text/javascript" src="{{ asset('js/global.V3.32.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.bxslider.min.v1.3.js') }}"></script>
<script>
    /*轮播banner*/
    $('#banner .banner-box').bxSlider({
        mode: 'fade',
        displaySlideQty: 1,
        moveSlideQty: 1,
        autoHover: true,
        captions: true,
        auto: true,
        controls: true,
        speed: 500,
        pause: 3000
    }); /*$(".banner").hover(function(){$(".banner-switch").show();},function(){$(".banner-switch").hide();});*/
    $("#banner").hover(function() {
        $(this).find(".bx-controls-direction").show();
    }, function() {
        $(this).find(".bx-controls-direction").hide();
    });
    $('.subject-box').bxSlider({
        mode: 'horizontal',
        displaySlideQty: 1,
        moveSlideQty: 1,
        autoHover: true,
        captions: true,
        auto: true,
        controls: true,
        speed: 500,
        pause: 3000
    });
    $(".subject-father").hover(function() {
        $(this).find(".bx-controls-direction").show();
    }, function() {
        $(this).find(".bx-controls-direction").hide();
    });
    $(".search-category-box").hover(function() {
        $(".search-category-select-box").show();
    }, function() {
        $(".search-category-select-box").hide();
    });
    $(".plate-subject,.subject-block").hover(function() {
        $(this).find(".subject-title").stop().animate({
            "bottom": "-22px"
        }, "fast");
        $(this).find(".subject-mask-title").stop().animate({
            "opacity": 1
        }, "fast");
        $(this).find(".subject-mask").stop().animate({
            "opacity": 0.5
        });
    }, function() {
        $(this).find(".subject-title").stop().animate({
            "bottom": "20px"
        }, "fast");
        $(this).find(".subject-mask,.subject-mask-title").stop().animate({
            "opacity": 0
        });
    });
    $(".search-category-select-option").click(function() {
        $(".search-category-select-option").removeClass("on");
        $(".search-category-show-area-text").text($(this).text());
        $(this).addClass("on");
        $(".search-category-select-box").hide();
        $(".search-input").attr("data-plate-path", $(this).attr("data-plate-path"));
        if ($(".search-input").val() == $(".search-input").attr("defaultValue")) {
            $(".search-input").attr("defaultValue", "搜索" + $(this).attr("data-plate-name"));
            $(".search-input").val("搜索" + $(this).attr("data-plate-name"));
        }
    });
    $(".top-search-parent .top-search-class").mouseover(function() {
        $(this).addClass("activer").siblings(".top-search-class").removeClass("activer");
        $(".top-search-child").eq($(this).index() - 1).show().siblings(".top-search-child").hide();
    });
    $(".seo-keyword li").hover(function() {
        $(this).css({
            "color": "#6A70E9",
            "border-color": "#6A70E9"
        })
    }, function() {
        $(this).css({
            "color": "#888",
            "border-color": "#eee"
        })
    }); /*banner下面的主题切换*/
    $(".subject-switch-box").click(function() {
        var width = parseInt($(".subject-box-all").width());
        var left = parseInt($(".subject-box-all").css("left"));
        var yu = width - 1200 - Math.abs(left);
        if ($(this).hasClass("subject-switch-box-left")) {
            if (left < 0) {
                if (Math.abs(left) > 1200) {
                    $(".subject-box-all").animate({
                        "left": (left + 1200) + "px"
                    });
                } else {
                    $(".subject-box-all").animate({
                        "left": "0px"
                    });
                }
            } else {
                $(".subject-box-all").animate({
                    "left": -(width - 1200) + "px"
                });
            }
        } else if ($(this).hasClass("subject-switch-box-right")) {
            if (yu > 0) {
                if (yu < 1200) {
                    $(".subject-box-all").animate({
                        "left": (left - yu) + "px"
                    });
                } else if (yu >= 1200) {
                    $(".subject-box-all").animate({
                        "left": (left - 1200) + "px"
                    });
                }
            } else {
                $(".subject-box-all").animate({
                    "left": "0px"
                });
            }
        }
    });
</script>
<script>
    $().ready(function() {
        $("#banner").find(".bx-controls-direction").append('<span class="main-prev"></span><span class="main-next"></span>');
        $("#banner").find(".main-prev").click(function() {
            $("#banner").find(".bx-prev").click();
        });
        $("#banner").find(".main-next").click(function() {
            $("#banner").find(".bx-next").click();
        });
    })
</script>