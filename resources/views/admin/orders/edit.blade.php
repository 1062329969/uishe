@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>订单详情</h2>
        </div>
        <div class="layui-card-body">
            @include('admin.orders._table')
        </div>
        @if ($orders->order_pay->refund_no)
        <div class="layui-card-body">
            <table class="layui-table">
                <thead>
                <tr>
                    <th colspan="2" align="center"> 退款详情 </th>
                </tr>
                </thead>
                <colgroup>
                    <col width="100">
                    <col width="200">
                    <col>
                </colgroup>

                <tbody>

                <tr>
                    <td>退款编号</td>
                    <td>  {{$orders->order_pay->refund_code}}</td>
                </tr>
                <tr>
                    <td>退款回执编号</td>
                    <td>  {{$orders->order_pay->refund_no}}</td>
                </tr>
                <tr>
                    <td>退款方式</td>
                    <td>  {{$orders->order_pay->refund_type}}</td>
                </tr>
                <tr>
                    <td>退款金额</td>
                    <td>  {{$orders->order_pay->refund_price}}</td>
                </tr>

                <tr>
                    <td>退款时间</td>
                    <td>  {{$orders->order_pay->refund_at}}</td>
                </tr>

                </tbody>
            </table>
        </div>
        @endif
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <a class="layui-btn" href="{{route('admin.orders')}}">返 回</a>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.news._js')
@endsection
