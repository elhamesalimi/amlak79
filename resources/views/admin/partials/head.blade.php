<head>
    <meta name="theme-color" content="#999999" />

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="icon" href="favicon.ico" type="image/x-icon">


{{--    <link href="{{ asset("theme/admin/components/jquery-toast-plugin/dist/jquery.toast.min.css") }}" rel="stylesheet"--}}
          {{--type="text/css">--}}
{{--    <link href="{{ asset("theme/admin/components/sweetalert/dist/sweetalert.css") }}" rel="stylesheet"--}}
          {{--type="text/css">--}}

    <link href="{{ asset("theme/admin/css/style-rtl.css") }}" rel="stylesheet" type="text/css">
{{--    <link rel="stylesheet" href="{{asset('css/temp/checkboxstyle.css')}}">--}}

    @stack('styles')
</head>