<div class="top top-fixed" id="top">
    <div class="top-content" id="top-content">
        <a class="logo ibloc fl" href="{{ url('/') }}" style="line-height: 80px;">
            <img src="{{ asset('images/logo.png') }}" class="iblock" alt="UI社" title="UI社"/>
        </a>
        <ul class="header-plate-box ul-nav fl pa">
            <li>
                <a href="https://www.51miz.com/ppt/" class="top-nav">首页</a>
            </li>
            @foreach(\App\WebOption::getIndexMenu() as $k => $v)
                <li>
                    <a href="{{ url('/'.$v['op_parameter']) }}" class="top-nav">{{ $v['op_value'] }}</a>
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


            <a rel="nofollow" href="https://www.51miz.com/index.php?m=pay&viptype=1" target="_blank" class="vip-pro fr iblock css3-background-size"></a>
        </div>
    </div>
</div>