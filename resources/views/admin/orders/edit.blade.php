@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>订单详情</h2>
        </div>
        <div class="layui-card-body">
                @include('admin.orders._table')
        </div>
    </div>
@endsection

@section('script')
    @include('admin.news._js')
@endsection
