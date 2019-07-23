@extends('layouts.app')

@include('user.common',['message'=>'我是错误信息'])

@section('content')
    {{ dd($user_collect) }}
    @foreach($user_collect as $k => $v)
        {{ $k }} {{ json_encode($v) }}
    @endforeach
@endsection
