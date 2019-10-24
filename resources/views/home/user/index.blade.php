@extends('layouts.user')
@section('content')
    <div class="myvip">
        <div class="current-vip-title fn18">我的VIP</div>
        <?php $auth_user = Auth::user(); ?>
        @if( $auth_user->user_type )
        <div class="vip-type pr">

            @if($auth_user->user_type == 1)
                <div class="vip-title fn18 center">黄金会员</div>
                <div class="endtime fn14 center">
                    有效期至：<span style="color:red">未开通</span>
                </div>
            @elseif($auth_user->user_type == 2)
                <div class="vip-title fn18 center">钻石会员</div>
                <div class="endtime fn14 center">
                    有效期至：<span style="color:red">$auth_user</span>
                </div>
            @elseif($auth_user->user_type == 3)
                <div class="vip-title fn18 center">终身会员</div>
                <div class="endtime fn14 center">
                    有效期至：<span style="color:red"> —— </span>
                </div>
            @endif
        </div>
        @endif
        @if( $auth_user->user_type != 3 )
        <a href="{{ route('buyvip') }}" target="_blank" class="buyvip_button">
            立即开通VIP
        </a>
        @endif
    </div>
@endsection