@extends('layouts.user')
@section('content')
    <?php $auth_user = Auth::user(); ?>
    @if( $auth_user->user_type != 3 )
        <div class="myvip">
            <div class="current-vip-title fn18">我的VIP</div>
            <div class="vip-type pr">
                @if($auth_user->user_type == 1)
                    <div class="vip-title fn18 center">黄金会员</div>
                    <div class="endtime fn14 center">
                        有效期至：<span style="color:red">{{ $auth_user->startTime }} - {{ $auth_user->endTime }}</span>
                    </div>
                @elseif($auth_user->user_type == 2)
                    <div class="vip-title fn18 center">钻石会员</div>
                    <div class="endtime fn14 center">
                        有效期至：<span style="color:red">{{ $auth_user->startTime }} - {{ $auth_user->endTime }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif
    @if( $auth_user->user_type == 3 )
        <a href="javascript:;" target="_blank" class="buyvip_button">
            尊贵的荣耀终身会员，您无需继续开通或续费
        </a>
    @else
        <a href="{{ route('buyvip') }}" target="_blank" class="buyvip_button">
            升级(三个月之内可以补差价升级)
        </a>
    @endif
@endsection