@extends('layouts.app')

@section('content')
    <form method="post" action="{{ url('/login') }}">
        user: <input type="text" name="user_login">
        pwd: <input type="text" name="user_pass">
        {{ csrf_field() }}
        <input type="submit" value="submit" name="dologin">
    </form>
@endsection
