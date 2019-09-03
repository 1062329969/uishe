@extends('layouts.app')

@section('content')
    {{--<form method="post" action="{{ url('/login') }}">--}}
    <form method="post" action="{{ url('/admin/login') }}">
        {{--user: <input type="text" name="user_login">--}}
        {{--pwd: <input type="text" name="user_pass">--}}
        user: <input type="text" name="username">
        pwd: <input type="text" name="password">
        {{ csrf_field() }}
        <input type="submit" value="submit" name="dologin">
    </form>
@endsection
