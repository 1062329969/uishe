@include('home.common.top')
<!--banner start-->
<div class="main oh">
    <style>
        .main{width:100%;padding-bottom:50px;}
        .myvip{width:900px;height:270px;margin:auto;}
        .current-vip-title{height:18px;line-height: 18px;color:#3a3a3a;}
        .vip-type{width:220px;height:190px;float:left;margin-right:20px;margin-top:24px;box-shadow:0 0 4px rgba(0,0,0,0.2);border-radius:4px;border:1px solid #ececec\9;padding-bottom: 25px;}
        .vip-title{height:51px;line-height: 51px;text-align: center;color:#3f3f3f;}
        .vip-price{height:41px;line-height: 41px;font-size: 28px;color:red;text-align: center;}
        .vip-price span{color:#292929;}
        .endtime{height:38px;line-height: 38px;text-align: center;}
        .show-right{width:100%;height: 22px;line-height: 22px;text-align: center;cursor:pointer;}
        .vip-type a{width:140px;height:38px;bottom:20px;left:50%;margin-left:-70px;line-height: 38px;color:#fff;background:url('//ss.51miz.com/V3/images/VIP_btn.png') 0 -78px no-repeat;background-size:249px 316px;border-radius: 19px;font-size: 15px;}
        .vip-type a:hover{background-position: 0 -116px;}
        .download-right-box{width:220px;top:0;right:-68px;z-index: 100;}
        .download-right{width:240px;border:1px solid #e7e9ef;border-radius: 4px;margin-top: 30px;padding:12px 0;background-color: #fff;+height:108px;}
        .download-right-arr{width:16px;height:12px;background: url('//ss.51miz.com/V3/images/VIP_btn.png') -183px -13px no-repeat;background-size:249px 316px;top:23px;left:96px;z-index: 9;}
        .right-span{height:30px;line-height: 30px;text-align: left;padding-left: 15px;color:#646870;}
        .right-span span{color:#000;}

        /*notvip*/
        .notvip{width:900px;height:18px;line-height:18px;margin:103px auto 0;text-align: center;color:#000;}
        .vipsmart{width:900px;height:42px;line-height:42px;margin: auto;text-align: center;color:#6e6e6e;}
        .getvip{width:140px;height:38px;line-height:38px;border-radius: 19px;background:url('//ss.51miz.com/V3/images/VIP_btn.png') 0 -78px no-repeat;background-size:249px 316px;margin:11px auto 0;color:#fff;}
        .getvip:hover{background-position: 0 -116px;}
        .buyvip_button{background: #33DA8D;color: #fff;display: block;width: 20%;height: 50px;text-align: center;line-height: 50px;border-radius: 25px;margin: 10px auto;width: 50%;}
    </style>
    <style>
        .homenav{width:100%;height:190px;background-color:#8d54e9;background:linear-gradient(90deg, #8d54e9 0%, #3f6fe0 100%);margin-bottom:90px;}
        .userinfo-box{width:1180px;height:190px;margin: auto;}
        .userinfo{padding-top:46px;}
        .userface{width:60px;height:60px;border-radius: 50%;}
        .userface img{width:60px;height:60px;border-radius: 50%;}
        .username-box{margin-left:15px;color:#fff;}
        .username{font-size: 24px;margin-top: 4px;}
        .userid{margin-top: 3px;}
        .myinfo{width:900px;height:60px;background-color: #fff;left:50%;margin-left: -450px;padding-left:40px;bottom:-33px;border-radius: 4px;box-shadow: 0 2px 4px rgba(0,0,0,0.2);border:1px solid #d9d9d9\9;+width:860px;}
        .myinfo a{height:60px;line-height: 60px;margin-right:60px;color:#000;}
        .myinfo .on{border-bottom:2px solid #7371ef;+height:58px;}
        .login-form-item{
            height: 50px;
            line-height: 50px;
        }
        .login-form-input{
            height: 35px;
            width: 250px;
            border: 1px #ccc solid;
            border-radius: 2px;
        }
        .login-form-label{
            width: 100px;
            text-align: right;
            padding-right: 20px;
            display: inline-block;
            font-size: 18px;
        }
        .login-submit,.reg-submit{
            display: block;
            height: 50px;
            background: #f66;
            border: none;
            width: 100%;
            max-width: 354px;
            color: #fff;
            border-radius: 5px;
            font-size: 18px;
            margin-top: 20px;
        }
    </style>
{{-- 当前用户{{ Auth::user()->user_login }}
 {{$posts_count}}<br>
 {{$user_credit['user_credit']}}<br>
 {{$user_collect}}<br>
@foreach($downlog as $k => $v)
 {{ $k }}
@endforeach--}}
<!--banner start-->
    <div class="homenav">
        <div class="userinfo-box pr">
            <div class="userinfo oh">
                <div class="userface fl"><img src="{{ asset('images/logo.png') }}" class="block"></div>
                <div class="username-box fl">
                    <span class="block username">{{ Auth::user()->name }}</span>
                    <span class="block userid fn14">ID:{{ Auth::user()->id }}</span>
                </div>
            </div>
            <div class="myinfo pa">
                <a href="{{ route('user') }}" class="iblock @if(in_array(\Request::route()->getName(), ['user', 'buyvip'])) on @endif">我的VIP</a>
                <a href="{{ route('downlog') }}" class="iblock @if(\Request::route()->getName() == 'downlog') on @endif">我的下载</a>
                <a href="{{ route('collect') }}" class="iblock @if(\Request::route()->getName() == 'collect') on @endif">我的收藏</a>
                <a href="{{ route('creditlog') }}" class="iblock @if(\Request::route()->getName() == 'creditlog') on @endif">我的积分</a>
                <a href="{{ route('selfinfo') }}" class="iblock @if(\Request::route()->getName() == 'selfinfo') on @endif">我的积分</a>
            </div>
        </div>
    </div>
    <!--banner end-->



    <div class="main-content oh">
        @yield('content')
    </div>


</div>


<!--bottom end-->

<script>
    $(".show-right").hover(function(){
        $(this).find(".download-right-box").show();
        $(this).find(".download-right-arr").show();
    },function(){
        $(this).find(".download-right-box").hide();
        $(this).find(".download-right-arr").hide();
    });
</script>
@include('home.common.bottom')