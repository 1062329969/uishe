@extends('layouts.app')

@include('user.common',['message'=>'我是错误信息'])

@section('content')

    当前用户{{ Auth::user()->user_login }}
    {{$posts_count}}<br>
    {{$user_credit['user_credit']}}<br>
    {{$user_collect}}<br>
    @foreach($downlog as $k => $v)
        {{ $k }}
    @endforeach
@endsection
