<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$user->name ?? 'Mi Sitio Web'}}</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/' .$user->imagen)}}" />
    @include('layouts.frontend.styles')
</head>

<body>
    @include('layouts.frontend.header')
    <div class="main-container" id="container">
        <div class="overlay"></div>
        <div class="search-overlay"></div>
        <div id="content" class="main-content">
            <div class="layout-px-spacing ">
                @yield('content')
            </div>

        </div>
    </div>
    @include('layouts.frontend.footer')
    @include('layouts.frontend.scripts')
</body>

</html>
