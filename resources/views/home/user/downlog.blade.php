@extends('layouts.user')

@section('content')
    <div id="down_log_div">
        @foreach($downlog as $k => $v)
            {{--{{ $v['news_info'] }}
            {{ $k }} {{ $v->id }} <img src="{{ $v['news_info'] }}" ><br><br><br>--}}
                <dl class="down_log_dl">
                    <dd class="down_log_dd">
                        <img src="{{ $v['news_info'] }}" >
                    </dd>
                    <dt class="down_log_dt">{{ $v['new_name'] }}</dt>
                    <dt class="down_log_dt">{{ $v['log_time'] }}</dt>
                </dl>
        @endforeach
    </div>

    {{ $downlog->links() }}
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

