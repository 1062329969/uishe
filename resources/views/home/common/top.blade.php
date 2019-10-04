<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $site['title'] }}</title>
    <meta name="renderer" content="webkit" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="keywords" content="{{ $site['keywords'] }}" />
    <meta name="description" content="{{ $site['description'] }}" />
    <script src="{{ asset('js/jquery.min.js') }}"></script>
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
                <a href="{{ url('/') }}" class="top-nav {{ Request::getPathInfo()=='/' ? 'on' : '' }}">首页</a>
            </li>
            @foreach($menus as $k => $v)
                @if($v['op_json'] == 'url')
                    <li>
                        <a href="{{ $v['op_parameter'] }}"target="_blank" class="top-nav">{{ $v['op_value'] }}</a>
                    </li>
                @else
                    <li>
                        <a href="{{ url('/'.$v['op_parameter']) }}" class="top-nav {{ Request::getPathInfo() == '/'.$v['op_parameter'] ? 'on' : '' }}">{{ $v['op_value'] }}</a>
                    </li>
                @endif

            @endforeach
            <li>
                <a href="{{ url('/huiyuan') }}" class="top-nav">免费获得VIP会员</a>
            </li>
        </ul>
        <!--search-box start-->
        <form class="tsearch-box fl pr none" action="{{ url('/getNewsList') }}">
            <input type="text" class="tsearch-input fl color-888 fn13" name="search"/>
            <input class="tsearch-submit pa css3-background-size" style="background-image: url('{{ asset('images/search.png') }}'); border: 0;" value="" type="submit" />
        </form>
        <!--search-box end-->
        <div class="top-item-right fr">
            <div class="top-item-login fr">
                @auth('users')
                <a href="{{ url('/') }}">Home</a>
                @else
                    <a href="{{ route('login') }}" class="login-button iblock fl on center fn14">请登录</a>
                    <a href="{{ route('reg') }}" class="register-button iblock fl center fn14">注册</a>
                @endauth
            </div>
        </div>
    </div>
</div>