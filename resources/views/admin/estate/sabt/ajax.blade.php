@extends('admin.master')
{{--@section('breadcrumbs', Breadcrumbs::render('posts'))--}}
@section('title','ملک های ثبت شده')
@push('styles')
    <style>
        .loading {
            color: blue;
            padding: 15px;
            position: fixed;
            border-radius: 4px;
            left: 50%;
            top: 50%;
            text-align: center;
            margin: -40px 0 0 -50px;
            z-index: 2000;
            display: none;
        }

        /*a, a:hover {*/
        /*color: white;*/
        /*}*/

        .form-group.required label:after {
            content: " *";
            color: red;
            font-weight: bold;
        }

        .is-invalid {
            border-color: red;
        }
    </style>
@endpush
@section('content')
    <div id="content">
        @include('admin.estate.sabt.index')
    </div>
    <div class="loading">
        <i class="fa fa-refresh fa-spin fa-2x fa-fw"></i><br/>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('/theme/admin/js/ajax-crud.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.search').click();
        });
    </script>
    <script>

        $(document).on('click', '#selectAll', function () {
            $(".idsChk").prop("checked", $(this).is(':checked'));
        });
        $(document).on('click', '#removeAllBtn', function () {
            var status = $('#actionsSlct').val();
            if ($('#actionsSlct').val() && $(".idsChk:checked").length > 0) {
                $('.loading').show();
                var checkedValue = $(".idsChk:checked").map(function () {
                    return parseInt($(this).val());
                }).get();
                $.ajax({
                    type: "GET",
                    url: '{{action('Admin\EstateController@changeEstateStatus')}}',
                    data: {status, checkedValue},
                    contentType: false,
                    success: function (data) {
                        $("#content").html(data);
                        $('.loading').hide();
                        $(document.body).removeClass('modal-open');
                    },
                    error: function (xhr, status, error) {
                        alert(xhr.responseText);
                    }
                });
            }else{
                alert('ملک یا عملیات موردنظر انتخاب نشده است.');
            }

        })
        ;
    </script>
@endpush