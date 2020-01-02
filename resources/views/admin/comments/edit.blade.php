@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>评论详情</h2>
        </div>
        <div class="layui-card-body">
            <table class="layui-table">
                <colgroup>
                    <col width="100">
                    <col width="200">
                    <col>
                </colgroup>

                <tbody>

                <tr>
                    <td>素材编号</td>
                    <td>  {{$comments->new_id}}</td>
                </tr>
                <tr>
                    <td>用户ID</td>
                    <td>  {{$comments->user_id}}</td>
                </tr>
                <tr>
                    <td>用户姓名</td>
                    <td>  {{$comments->user_name}}</td>
                </tr>

                <tr>
                    <td>评论内容</td>
                    <td>  {{$comments->content}}</td>
                </tr>


                </tbody>
            </table>
            <table class="layui-table">
                <colgroup>
                    <col width="100">
                    <col width="200">
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <td>回复评论</td>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td>
                        <form class="layui-form" action="{{route('admin.comments.store')}}" method="post">
                            {{ method_field('put') }}
                            @include('admin.comments._form')
                        </form>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.news._js')
@endsection
