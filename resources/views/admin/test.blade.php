<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    {{--<link href='/theme/admin/persianDatePicker/css/normalize.css' rel='stylesheet' />--}}
    {{--<link href='/theme/admin/persianDatePicker/css/fontawesome/css/font-awesome.min.css' rel='stylesheet' />--}}
    {{--<link href="/theme/admin/persianDatePicker/css/vertical-responsive-menu.min.css" rel="stylesheet" />--}}
    {{--<link href="/theme/admin/persianDatePicker/css/style.css" rel="stylesheet" />--}}
    {{--<link href="/theme/admin/persianDatePicker/css/prism.css" rel="stylesheet" />--}}
    <link rel="stylesheet" href="{{asset('/theme/admin/persianDatePicker/css/persianDatepicker-default.css')}}">

</head>
<body>
<section id="multi-element" style="margin-top: 200px; float: right;">
    <h2> - Multi element : text, label, span, div, p, ...</h2>
    <input type="text" placeholder="a text box" class="usage"/>
    <span class="usage">a span</span>
    <label class="usage">a label</label>
</section>
{{--<script src="{{asset('asset/js/jquery-1.10.1.min.js')}}"></script>--}}
<script src="{{asset('/asset/js/jquery.min.js')}}"></script>
<script src="{{asset('/theme/admin/persianDatePicker/js/persianDatepicker.js')}}"></script>
<?php $date="1395/5/5 14:22:00" ?>
<script>
    $(".usage").persianDatepicker({
        selectedBefore: !0,
        selectedDate:'{{$date}}',
        formatDate: "YYYY/0M/0D hh:mm:ss"

        }
    );
    $("#pdpSelectedDate").persianDatepicker({
        selectedDate:"1395/5/5"
    });
    $("#pdpSelectedBefore").persianDatepicker({
        selectedBefore: !0
    });

</script>
</body>
</html>