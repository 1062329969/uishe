@extends('layouts.user')

@section('content')
    <table id="credit_log_table">

            <tr>
                <td>序号</td>
                <td>时间</td>
                <td>内容</td>
                <td>途径</td>
            </tr>
        @foreach($creditlog as $k => $v)
            <tr>
                <td>{{ $v->id }}</td>
                <td>{{ $v->created_at }}</td>
                <td>{{ $v->content }}</td>
                <td>
                    @switch($v->from)
                    @case(\App\Models\Usercredit::Credit_From_Null)
                        未知
                    @break
                    @case(\App\Models\Usercredit::Credit_From_Buy)
                        购买
                    @break
                    @default
                        未知
                    @endswitch
                </td>
            </tr>
            {{--{{ $v['news_info'] }}
            {{ $k }} {{ $v->id }} <img src="{{ $v['news_info'] }}" ><br><br><br>--}}
        @endforeach
    </table>

    {{ $creditlog->links() }}
    <style type="text/css">
        table
        {
            border-collapse: collapse;
            margin: 0 auto;
            width: 100%;
            font-size: 14px;
            text-align: center;
        }
        table td, table th
        {
            border: 1px solid #cad9ea;
            color: #666;
            height: 30px;
        }
        table thead th
        {
            background-color: #CCE8EB;
            width: 100px;
        }
        table tr:nth-child(odd)
        {
            background: #F5FAFA;
        }
        table tr:nth-child(even)
        {
            background: #fff;
        }
/    </style>
@endsection

