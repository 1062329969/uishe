@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>更新</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.weboption.update',['weboption'=>$op_type])}}" method="post">
                {{ method_field('put') }}
                @switch($op_type)
                    @case('category')
                        @include('admin.weboption.category_form')
                        @break
                    @case('links')
                        @include('admin.weboption.links_form')
                        @break
                    @case('banner')
                        @include('admin.weboption.banner_form')
                        @break
                    @case('index')
                        @include('admin.weboption.index_form')
                        @break
                    @break
                    Default ...
                @endswitch
                {{--@include('admin.weboption._form')--}}
            </form>
        </div>
    </div>
@endsection