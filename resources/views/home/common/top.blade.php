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

                @auth('users')
                <?php $auth_user = Auth::user(); ?>
                <div class="face-img pr fr">
                    <a rel="nofollow" href="{{ route('user') }}">
                        <img src="{{ $auth_user->avatar_url }}" class="block"> </a>
                    <div class="user-menu-arr pa css3-background-size none" style="background-image: url('https://ss.51miz.com/images/VIP_btn@2x.png'); display: none;"></div>
                    <div class="user-menu-out-box pa oh none" style="display: none;">
                        <div class="user-menu-box oh">
                            <div class="user-menu-info">
                                <div class="user-menu-face fl">
                                    <img src="{{ $auth_user->avatar_url }}">
                                </div>
                                <div class="user-menu-idbox fr">
                                    <span class="block user-menu-name fn14">{{ $auth_user->avatar_url }}</span>
                                    <span class="block user-menu-id fn12">ID:10576527</span>
                                </div>
                            </div>
                            @if($auth_user->user_type == 0)<!--vip type start-->
                            <div class="user-menu-getvip oh" style="height:auto;">
                                <div style="margin-top:10px;margin-bottom:10px;">
                                    <a rel="nofollow" onclick="payFrom(2)" href="https://www.51miz.com/index.php?m=pay&amp;viptype=1" target="_blank" style="background:#33DA8D" class="block fn14 css3-background-size center">
                                        开通VIP
                                    </a>
                                </div>
                            </div><!--vip type end-->
                            @endif
                            <div class="user-menu-list">
                                <a rel="nofollow" target="_blank" href="{{ route('user') }}" class="block fn14">
                                    个人主页
                                </a>
                                <a rel="nofollow" target="_blank" href="https://www.51miz.com/index.php?m=Home&amp;a=myaction&amp;action=download" class="block fn14">
                                    我的下载
                                </a>
                                <a rel="nofollow" target="_blank" href="https://www.51miz.com/index.php?m=Home&amp;a=vipFav" class="block fn14">
                                    我的收藏
                                </a>
                            </div>
                            <!--<div class="user-menu-exit">-->
                            <a rel="nofollow" href="https://www.51miz.com/index.php?m=login&amp;a=logout&amp;backurl=http%3A%2F%2Fwww.51miz.com%2Findex.php%3Fm%3Dhome%26a%3Dmyvip" class="block user-menu-exit fn14">
                                退出
                            </a>
                            <!--</div>-->
                        </div>
                    </div>
                </div>
                @else
                <div class="top-item-login fr">
                    <a href="{{ route('login') }}" class="login-button iblock fl on center fn14">请登录</a>
                    <a href="{{ route('reg') }}" class="register-button iblock fl center fn14">注册</a>
                </div>
                @endauth

        </div>
    </div>
</div>