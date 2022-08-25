<!DOCTYPE html>
<html lang="ja">
<head>
    <title>@yield('title', trans('core::text.Admin'))</title>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ url('/assets/ico/favicon.ico') }}"/>
    <link href="{{ mix('assets/admin/css/vendor.css') }}" rel="stylesheet" type="text/css" class="main-stylesheet"/>
    <link href="{{ mix('assets/admin/css/style.css') }}" rel="stylesheet" type="text/css"/>
    @stack('custom-styles')
</head>
<body class="fixed-header @if(\Illuminate\Support\Facades\Cookie::get('pin')) menu-pin @endif">
@include('core::_partials.sidebar')
<div class="page-container ">
    @include('core::_partials.header')
    <div class="page-content-wrapper ">
        <div class="content ">
            @yield('content')
        </div>
        @include('core::_partials.footer')
    </div>
</div>
<script src="{{ public_url('assets/admin/js/'.app()->getLocale().'.js') }}"></script>
<script src="{{ mix('assets/admin/js/vendor.bundle.js') }}"></script>
<script src="{{ mix('assets/admin/js/modernizr.custom.js') }}"></script>
<script src="{{ mix('assets/admin/js/template-core.js') }}"></script>
<script src="{{ mix('assets/admin/js/app.js') }}"></script>
@stack('custom-scripts')
</body>
</html>
