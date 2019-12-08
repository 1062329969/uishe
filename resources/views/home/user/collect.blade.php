@extends('layouts.user')

@include('home.user.common',['message'=>'我是错误信息'])

@section('content')

    <div id="down_log_div">
        @foreach($user_collect as $k => $v)
            <dl class="down_log_dl">
                <dd class="down_log_dd">
                    <img src="{{ $v['img'] }}" alt="{{ $v['img'] }}" >
                </dd>
                <dt class="down_log_dt">{{ $v['title'] }}</dt>
            </dl>
        @endforeach
    </div>

    {{ $user_collect->links() }}
    <style type="text/css">
        #down_log_div{
            overflow: hidden;
        }
        .down_log_dl{
            width: 300px;
            float: left;
            height: 360px;
            margin: 40px;
        }
        .down_log_dl dd img{
            width: 300px;
            display: block;
            height: 300px;
        }
        .down_log_dt{
            text-align: center;
            font-size: 14px;

            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            line-height: 23px;
            max-height: 46px;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }
    </style>
@endsection
