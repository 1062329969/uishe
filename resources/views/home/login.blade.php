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

    <!--banner start-->
    <div class="homenav">
        <div class="userinfo-box pr">
            <div class="myinfo pa">
                <a href="/login" class="iblock @if(request()->get('type') != 'reg') on @endif">登录</a>
                <a href="/login?type=reg" class="iblock @if(request()->get('type') == 'reg') on @endif">注册</a>
            </div>
        </div>
    </div>
    <!--banner end-->



    <div class="main-content oh">

        <div class="myvip">

            @foreach($errors->all() as $error)
                <p style="color: red;padding-bottom: 10px;">注：{{$error}}</p>
                @break
            @endforeach
            @if(request()->get('type') != 'reg')
                <form method="post" action="{{ url('/login') }}">
                    <div class="login-form-item">
                        <label class="login-form-label">用户名:</label>
                        <input type="text" name="username" class="login-form-input">
                    </div>
                    <div class="login-form-item">
                        <label class="login-form-label">密码:</label>
                        <input type="password" name="password" class="login-form-input">
                    </div>
                    {{ csrf_field() }}
                    <input type="submit" value="登录" name="dologin" class="login-submit">
                </form>
            @endif



            @if(request()->get('type') == 'reg')
                <form method="post" action="{{ url('/reg') }}" onsubmit="return checkreg(this)">
                    <div class="login-form-item">
                        <label class="login-form-label">用户名:</label>
                        <input type="text" required name="username" id="username" class="login-form-input" value="{{ old('username') }}">
                        <span id="username_span" style="color: red;">请输入6-16位用户名</span>
                    </div>
                    <div class="login-form-item">
                        <label class="login-form-label">邮箱:</label>
                        <input type="text" required name="email" id="email" class="login-form-input" value="{{ old('email') }}">
                        <span id="email_span" style="color: red;">请输入正确邮箱</span>
                    </div>
                    <div class="login-form-item">
                        <label class="login-form-label">密码:</label>
                        <input type="password" required name="password" id="password" class="login-form-input" value="{{ old('password') }}">
                        <span id="password_span" style="color: red;"></span>
                    </div>
                    <div class="login-form-item">
                        <label class="login-form-label">确认密码:</label>
                        <input type="password" required name="password_confirmation" id="password_confirmation" class="login-form-input" value="{{ old('password_confirmation') }}">
                        <span id="password_confirmation_span" style="color: red;"></span>
                    </div>
                    {{ csrf_field() }}
                    <input type="submit" value="注册" name="doreg" class="reg-submit">
                </form>
                <script>

                    function checkreg(_self) {
                        var username = $('#username').val()
                        var email = $('#email').val()
                        var password = $('#password').val()
                        var password_confirmation = $('#password_confirmation').val()

                        if(username.length <6 || username.length >16){
                            $('#username_span').css({'color':'red'}).html('请输入6-16位用户名')
                            $('#username').css({'border-color':'red'})
                            return false;
                        }

                        if(password != password_confirmation){
                            $('#password_confirmation_span').css({'color':'red'}).html('两次输入密码不一致')
                            $('#password_confirmation').css({'border-color':'red'})
                            return false;
                        }

                        var field = new Array();
                        field = ['username','email','password','password_confirmation']
                        console.log(field)
                        for ( x in field){
                            var new_field = field[x];
                            if($.trim($('#'+new_field).val()) == ''){
                                $('#'+new_field+'_span').css({'color':'red'}).html('此项不能为空')
                                $('#'+new_field).css({'border-color':'red'})
                                return false;
                            }else{
                                $('#'+new_field+'_span').css({'color':'red'}).html('')
                                $('#'+new_field).css({'border-color':'#ccc'})
                            }
                            console.log(field[x])
                        }
                    }
                </script>
            @endif

        </div>
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