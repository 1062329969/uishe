
if($(".next_page").size() > 0)
{
    $(".next_page").hover(function(){
        $(".next_page_back").css("display","block");
    }, function() {
        $(".next_page_back").css("display","none");
    });

    $(window).resize(function(){
        $(".next_page_back").css("display","none");
    });
    $(".next_page_back").css("display","none");
}


/*导航菜单*/
$(".search-menu").hover(function(){
    $(".search-menu-box").show();
},function(){
    $(".search-menu-box").hide();
});

$(".element").live("mouseover",function(){$(this).find(".actionButton").stop().animate({top:'0px'},"fast");});
$(".imgBox").live("mouseleave",function(){$(this).find(".actionButton").stop().animate({top:'-56px'},"fast");});

$(".order").hover(function() {
    $(this).find("span").css("color","#282828");
    $(this).find(".order-arr-icon").css("background-position","-10px -83px");
    $(".order-box").show();
},function(){
    $(this).find("span").css("color","#656565");
    $(this).find(".order-arr-icon").css("background-position","0 -83px");
    $(".order-box").hide();
});

$(".more-category").hover(function(){
    $(this).children("span").css("color","#7371EF");
}, function () {
    $(this).children("span").css("color","#999");
});

/*
判断显示 【更多】 和 【下拉筛选项】按钮
*/

function initMore()
{
    var heightShow = $(".category-box").size() * $(".category-box").height();

    var heightH = 0;

    $(".category").each(function(i,item){
        heightH += $(this).height();
    });

    if(heightH > heightShow)
    {
        $(".show-more-box").show();
    }

    $(".child-category-box").each(function(){

        var cWidthShow = $(this).width();

        var cWidthW = 0;

        $(this).find("a").each(function(){
            cWidthW += $(this).width()+28;
        });

        if(cWidthW > cWidthShow)
        {
            $(this).siblings(".more-category").css("visibility","visible");
        }else{
            $(this).siblings(".more-category").css("visibility","hidden");
        }
    });


}

initMore();

$(window).resize(function(){
    initMore();
});

$(".more-category").click(function(){

    var _thisChild = $(this).siblings(".child-category-box");

    if(_thisChild.hasClass("close"))
    {
        if($(".show-more-icon").hasClass("close"))
        {
            $(".show-more").click();
        }

        _thisChild.removeClass("close").addClass("open").css("height","auto");

    }else if($(".child-category-box").hasClass("open")){

        _thisChild.removeClass("open").addClass("close").css("height","40px");

    }

});

$(".show-more").hover(function () {
    if($(".category-box").hasClass("open")) {
        $(this).children("div").css("background-position","-35px -85px");
    }else{
        $(this).children("div").css("background-position","-10px -85px");
    }
},function () {
    if($(".category-box").hasClass("open")) {
        $(this).children("div").css("background-position","-25px -85px");
    }else{
        $(this).children("div").css("background-position","0 -85px");
    }

});


$(".show-more").click(function () {

    if($(".category-box").hasClass("close"))
    {
        $(".category-box").removeClass("close").addClass("open").css("max-height","none");

        $(".show-more-icon").addClass("open");

    }else if($(".category-box").hasClass("open")){

        $(".category-box").removeClass("open").addClass("close").css("max-height","82px");

        $(".show-more-icon").removeClass("open");

    }

});

$(".sub-adv").click(function() {
    $(".bg-needs").show();
    $(".frame").show();
});

$(".needclose").click(function(){
    $(".bg-needs").hide();
    $(".frame").hide();
});

$(".frame input[defaultValue]").blur(function() {
    var inputval= $.trim($(this).val());
    $(this).removeClass("advborder");
    $(".needcheck").text("");

});

$(".button-adv").click(function(){
    $(".bg-needs").hide();
    $(".submit-success").hide();
});



/*综合搜索-点击分类*/
$(".search-menu-box").find("a").click(function() {
    $(".search-menu-head").text($(this).attr("data-plate-name"));
    $(".search-submit").siblings(".search-input").attr("data-plate-path",$(this).attr("data-plate-path"));
});


