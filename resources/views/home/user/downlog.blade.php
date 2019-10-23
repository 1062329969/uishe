@extends('layouts.user')

@section('content')
    @foreach($downlog as $k => $v)
        {{ $k }} {{ $v->id }}<br><br><br>
    @endforeach

    {{ $downlog->links() }}
@endsection
