<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            这是头部
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                    <a href="{{ url('/') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        @endauth
                </div>
            @endif
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            这是底部
        </nav>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
