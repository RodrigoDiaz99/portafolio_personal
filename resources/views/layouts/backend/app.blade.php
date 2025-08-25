<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">       
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('storage/images/icon.png') }}"/>
        <!-- Scripts -->
        @include('layouts.backend.styles')
    </head>
    <body>
        <!-- BEGIN LOADER -->
        <div id="load_screen">
            <div class="loader">
                <div class="loader-content">
                    <div class="spinner-grow align-self-center"></div>
                </div>
            </div>
        </div>
     <!--  END LOADER -->
        @include('layouts.backend.header')
        <div class="main-container" id="container">
            <div class="overlay"></div>
            <div class="search-overlay"></div>
                @include('layouts.backend.sidebar')
                <div id="content" class="main-content">
                     <div class="layout-px-spacing">
                        @yield('content')
                    </div>
                        @include('layouts.backend.footer')
                </div>
        </div>
        @include('layouts.backend.scripts')
    </body>
</html>
