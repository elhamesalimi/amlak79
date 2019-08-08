<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">--}}
    {{--<link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-icon-57x57.png">--}}
    {{--<link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-icon-60x60.png">--}}
    {{--<link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-icon-72x72.png">--}}
    {{--<link rel="apple-touch-icon" sizes="76x76" href="/favicons/apple-icon-76x76.png">--}}
    {{--<link rel="apple-touch-icon" sizes="114x114" href="/favicons/apple-icon-114x114.png">--}}
    {{--<link rel="apple-touch-icon" sizes="120x120" href="/favicons/apple-icon-120x120.png">--}}
    {{--<link rel="apple-touch-icon" sizes="144x144" href="/favicons/apple-icon-144x144.png">--}}
    {{--<link rel="apple-touch-icon" sizes="152x152" href="/favicons/apple-icon-152x152.png">--}}
    {{--<link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-icon-180x180.png">--}}
    {{--<link rel="icon" type="image/png" sizes="192x192"  href="/favicons/android-icon-192x192.png">--}}
    {{--<link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">--}}
    {{--<link rel="icon" type="image/png" sizes="96x96" href="/favicons/favicon-96x96.png">--}}
    {{--<link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>املاک 79</title>

    <link rel="stylesheet" href="/asset/css/bootstrap.rtl.full.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel='stylesheet' id='responsive-css-css'  href='/asset/css/responsive.css' type='text/css' media='all' />
    {{--<link href="/asset/css/singleshow.css" rel="stylesheet" type="text/css">--}}
    <link rel='stylesheet' id='main-css-css'  href='/asset/css/main.css' type='text/css' media='all' />

    <link rel='stylesheet' id='rtl-main-css-css'  href='/asset/css/rtl-main.css' type='text/css' media='all' />
    <link rel='stylesheet' id='rtl-custom-responsive-css-css'  href='/asset/css/custom-responsive.css' type='text/css' media='all' />
    <link rel='stylesheet' id='rtl-custom-responsive-css-css'  href='/asset/css/rtl-custom-responsive.css' type='text/css' media='all' />
    {{--<link rel="stylesheet" type="text/css" charset="UTF-8" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css" />--}}
    {{--<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css" />--}}
    <style>@-moz-document url-prefix() {
               select{
                   -moz-appearance: none;
                   border: 1px solid #ccc !important;
                   text-align: right !important;
               }
               .selectwrap::after {
                   content: '\25BE';
                   position: absolute;
                   top: 5px;
                   left: 3px;
                   bottom: 0;
                   padding: 0;
                   background: none;
                   pointer-events: none;
                   color: green;
                   font-size: 20px;
               }
           }</style>

    <meta name="theme-color" content="#ffffff">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.Laravel = {!! json_encode([
            'window_AUTH_TOKEN' => csrf_token(),
            'base_url' => env("BASE_URL"),
            'client_secret' => env("PASSWORD_CLIENT_SECRET")
        ]) !!} ;
    </script>
</head>
<body>
<div id="app"></div>
<script src="{{mix('js/app.js')}}" ></script>
</body>
</html>
