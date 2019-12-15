@extends('layouts.user')
@section('content')
    <?php $auth_user = Auth::user(); ?>
    <form method="post" action="{{ route('set_order') }}" class="myvip" id="buy_vip_form" style="height: auto">
        {{ csrf_field() }}
        @if( $auth_user->user_type == 3 )
            <a href="{{ route('buyvip') }}" target="_blank" class="buyvip_button">
                尊贵的荣耀终身会员，您无需继续开通或续费
            </a>
        @else
            <div class="current-vip-title fn18">VIP</div>
            <div style="overflow: hidden;padding: 10px;">
                @foreach($vip_option as $item)
                <div class="vip-type pr" vip-data="{{ $item['level'] }}">
                    <div class="vip-title fn18 center">{{ $item['name'] }}</div>
                    <div class="endtime fn14 center">
                        活动价：
                        <span style="color:red">{{ $item['actual_total'] }}
                           @if($item['currency_type'] == \App\Models\Orders::Currency_Type_Credit) 积分 @else 元 @endif /365天
                        </span>
                    </div>
                    <div class="endtime fn14 center">
                        原价：<del style="color:red">{{ $item['total'] }} @if($item['currency_type'] == \App\Models\Orders::Currency_Type_Credit) 积分 @else 元 @endif</del>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="current-vip-title fn18">购买方式</div>
            <div style="overflow: hidden;padding: 10px;">
                <div class="pay_list">
                    <label class="lab3" pay_type="{{ \App\Models\Orders::Order_Pay_Type_Alipay }}">
                        <img src="{{ asset('images/pay/ALIPAY.jpg') }}" width="142" height="42" style="border: 1px #fff solid">
                    </label>
                    <label class="lab3" pay_type="{{ \App\Models\Orders::Order_Pay_Type_Wxpay }}">
                        <img src="{{ asset('images/pay/WEIXIN.jpg') }}" width="142" height="42" style="border: 1px #fff solid">
                    </label>
                    <label class="lab3" pay_type="{{ \App\Models\Orders::Order_Pay_Type_Credit }}">
                        <img src="{{ asset('images/pay/WEIXIN.jpg') }}" width="142" height="42" style="border: 1px #fff solid">
                    </label>
                </div>
            </div>

            <input class="order_type" id="order_type" name="order_type" value="{{ \App\Models\Orders::Order_Type_Vip }}" type="hidden">
            <input class="pay_type" id="pay_type" name="pay_type" value="" type="hidden">
            <input class="vip_type" id="vip_type" name="vip_type" value="" type="hidden">
            <input class="platform" id="platform" name="platform" value="web" type="hidden">

            <a href="javascript:;" onclick="checkOrderSubmit()" class="buyvip_button">
                立即开通VIP
            </a>
        @endif
    </form>
    <style>
        .vip_type_on{box-shadow:0 0 4px rgba(255, 0, 0, 2);}
    </style>
    <script>
        $(function () {
            $('.vip-type').click(function () {
                $('.vip-type').removeClass('vip_type_on')
                $(this).addClass('vip_type_on')
                var vip_data = $(this).attr('vip-data')
                $('#vip_type').val(vip_data)
            })
            $('.lab3').click(function () {
                $('.lab3').find('img').css('border', '1px #fff solid')
                $(this).find('img').css('border','1px #F00 solid')
                var pay_type = $(this).attr('pay_type')
                $('#pay_type').val(pay_type)
            })
        })
        
        function checkOrderSubmit() {
            var pay_type = $('#pay_type').val()
            var vip_type = $('#vip_type').val()
            if(pay_type != '' && vip_type != ''){
                $('#buy_vip_form').submit()
            }
        }

    </script>
@endsection