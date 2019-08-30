<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>UI社_PPT模板,海报,图片设计素材下载_优质原创设计资源站</title>
    <meta name="renderer" content="webkit" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="keywords" content="PPT模板，海报，图片素材，ps素材，背景，ae模板，免扣素材，矢量素材" />
    <meta name="description" content="UI社专注于优质实用的设计资源下载。包括PPT模板、海报、视频、音频、摄图图片、PNG素材、插画等。除了作品创意与美感，我们更注重作品的实用性。深入研究每一类作品的使用人群，使用场景、受众、需要作品传递的信息，从文案、设计风格、创意、构图上真正符合用户的真实场景需求，做到下载即用。" />
    <link href="{{ asset('css/global.V3.6.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/index/index.v4.15.css') }}" rel="stylesheet" type="text/css">
</head>

<body>

<link href="{{ asset('css/top.v4.11.css') }}" rel="stylesheet" type="text/css">
<div class="top top-fixed" id="top">
    <div class="top-content" id="top-content">
        <a class="logo ibloc fl" href="{{ url('/') }}" style="line-height: 80px;">
            <img src="{{ asset('images/logo.png') }}" class="iblock" alt="UI社" title="UI社"/>
        </a>
        <ul class="header-plate-box ul-nav fl pa">
            <li>
                <a href="{{ url('/') }}" class="top-nav">首页</a>
            </li>
            @foreach(\App\Models\WebOption::getIndexMenu() as $k => $v)
                <li>
                    <a href="{{ url('/'.$v['op_parameter']) }}" class="top-nav on">{{ $v['op_value'] }}</a>
                </li>
            @endforeach
            <li>
                <a href="{{ url('/huiyuan') }}" class="top-nav">免费获得VIP会员</a>
            </li>
        </ul>
        <!--search-box start-->
        <form class="tsearch-box fl pr none">
            <input type="text" class="tsearch-input fl color-888 fn13" />
            <input class="tsearch-submit pa css3-background-size" style="background-image: url('{{ asset('images/search.png') }}'); border: 0;" value="" type="submit" />
        </form>
        <!--search-box end-->
        <div class="top-item-right fr">
            <div class="top-item-login fr">
                @auth
                <a href="{{ url('/') }}">Home</a>
                @else
                    <a href="{{ route('login') }}" class="login-button iblock fl on center fn14">请登录</a>
                    <a href="{{ route('reg') }}" class="register-button iblock fl center fn14">注册</a>
                @endauth
            </div>

            {{--<a rel="nofollow" href="https://www.51miz.com/index.php?m=pay&viptype=1" target="_blank" class="vip-pro fr iblock css3-background-size"></a>--}}
        </div>
    </div>
</div>