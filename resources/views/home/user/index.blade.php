@extends('layouts.user')
@section('content')
    <div class="myvip">
        <div class="current-vip-title fn18">我的VIP</div>
        <div class="vip-type pr">
            <div class="vip-title fn18 center">普通会员</div>
            <div class="endtime fn14 center">
                有效期至：<span style="color:red">未开通</span>
            </div>
            <a href="https://www.51miz.com/index.php?m=pay&amp;viptype=1" onclick="payFrom(3)" target="_blank" style="background:#33DA8D;" class="pa block center css3-background-size">
                立即开通VIP
            </a>
        </div>
    </div>
@endsection