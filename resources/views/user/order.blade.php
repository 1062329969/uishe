@extends('layouts.app')

@include('user.common',['message'=>'我是错误信息'])

@section('content')
    @foreach($user_order as $k => $v)
        {{ $k }} {{ json_encode($v) }}<br><br><br>
    @endforeach

    {{ $user_order->links() }}
@endsection
