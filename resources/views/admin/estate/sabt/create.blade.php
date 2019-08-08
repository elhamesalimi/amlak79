@extends('admin.master')
{{--@section('title','ایجاد ملک جدید')--}}
{{--@if (isset($post))  @section('breadcrumbs', Breadcrumbs::render('posts.edit'  , $post_id , $post_title))--}}
{{--@else @section('breadcrumbs', Breadcrumbs::render('posts.create'))--}}
{{--@endif--}}
@section('title','ایجاد ملک جدید')
@push('styles')

    <style>
        .error {
            color: red;
            position: absolute;
            font-size: 9px;
        }

        body {
            font-size: 12px;
        }

        .radio-inline input[type=radio] {
            margin-right: -20px;
        }

        h4 {
            padding: 5px;
        }
    </style>
    <link href="/theme/admin/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('/theme/admin/dropzone/css/dropzone.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/admin/dropzone/css/custom.css')}}">
@endpush
@section('content')

    <div class="container-fluid">
        <section id="content">
            <div id="msform">
                <div id="progressbar">
                    <div className="logo-breadcrumb"><img src="/asset/images/registerhome-icon.png" alt=""/></div>
                    <span className="text-breadcrumb">
                    </span>
                </div>
                <form id="register-form" action="{{action('Admin\EstateController@store')}}" method="POST">
                    @csrf()
                    <input name="category" type="hidden" value="{{$category}}"/>
                    <fieldset class="register-form">
                        <div class="info-person">
                            <h4 class="ff-title">اطلاعات آگهی دهنده</h4>
                            <div class="show-person">
                                <div class="col-sm-3 col-xs-12 mb20">
                                    <div class="form-group">
                                        <label for="LastName" class="active">نام خانوادگی</label>
                                        <input class="form-control" id="name" maxlength="50" name="name" type="text"
                                               value="" placeholder=""
                                               data-rule-required="true"
                                               data-msg-required="این فیلد الزامی است"
                                        >
                                        <span id="error-name"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-12 mb20">
                                <div class="form-group">
                                    <label for="phone" class="active">تلفن همراه</label>
                                    <input class="form-control" id="phone" name="phone" type="number"
                                           data-category="quantity" value="" placeholder=""
                                           data-rule-required="true"
                                           data-msg-required="این فیلد الزامی است"
                                           data-rule-pattern="(0|\+98)?([ ]|-|[()]){0,2}9[1|2|3|4]([ ]|-|[()]){0,2}(?:[0-9]([ ]|-|[()]){0,2}){8}"
                                           data-msg-pattern="شماره صحیح نیست">
                                </div>
                            </div>
                            <div class="show-person">
                                <div class="col-sm-6 col-xs-12 mb20">
                                    <div class="form-group">
                                        <label class="active">ایمیل</label>
                                        <input class="form-control" name="email" id="email" type="email"
                                               value="" placeholder="info@amlak79.ir"
                                               data-rule-required="true"
                                               data-msg-required="این فیلد الزامی است"
                                               email="true"
                                               data-msg-email="آدرس ایمیل صحیح نیست">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="info-state">
                        <h4 class="ff-title"> مشخصات اصلی ملک</h4>
                        <div class="col-sm-3 col-xs-12 mb20">
                            <div class="form-group">
                                <label for="city_id" class="active">شهر</label>
                                <select id="city_id" class="label form-control" name="city_id" data-rule-required="true"
                                        data-msg-required="این فیلد الزامی است">
                                    <option value="" hidden>انتخاب کنید</option>
                                    @foreach($cities as $key=>$city)
                                        <option value="{{$key}}">{{$city}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-12 mb20">
                            <div class="form-group">
                                <label for="region_id" class="active">منطقه</label>
                                <select id="region_id" class="label form-control" name="region_id"
                                        data-rule-required="true"
                                        data-msg-required="این فیلد الزامی است">
                                    <option value="" hidden>انتخاب کنید</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-12 mb20">
                            <div class="form-group">
                                <label for="position" class="active">نوع ملک</label>
                                <select id="typeId" class="label form-control" name="type_id" data-rule-required="true"
                                        data-msg-required="این فیلد الزامی است">
                                    <option value="" hidden>انتخاب کنید</option>
                                    @foreach($types as $key=>$type)
                                        <option value="{{$key}}">{{$type}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-12 mb20">
                            <div class="form-group">
                                <label for="position" class="active">موقعیت</label>
                                <select id="position" class="label form-control" name="plan_id"
                                        data-rule-required="true"
                                        data-msg-required="این فیلد الزامی است">
                                    <option value="" hidden>انتخاب کنید</option>
                                    @foreach($plans as $key=>$plan)
                                        <option value="{{$key}}">{{$plan}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-12 mb20">
                            <div class="form-group">
                                <label for="area" class="active">متراژ (متر مربع)</label>
                                <input class="form-control currency" id="area" name="area" type="text"
                                       data-category="quantity" value="" placeholder=""
                                       data-rule-required="true"
                                       data-msg-required="این فیلد الزامی است">

                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-12 mb20">
                            <div class="form-group">
                                <label for="total_price"
                                       class="active">{{$category==='sell'? 'قیمت کل (تومان)' : 'مبلغ رهن (تومان)'}}</label>
                                <input class="form-control currency" id="total_price" name="total_price"
                                       type="text" data-category="quantity" value="" placeholder=""
                                       data-rule-required="true"
                                       data-msg-required="این فیلد الزامی است">

                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-12 mb20">
                            <div class="form-group">
                                <label for="price"
                                       class="active">{{$category==='sell'? 'قیمت متر مربع (تومان)' : 'مبلغ اجاره (تومان)'}}</label>
                                <input class="form-control currency" id="price" name="price" type="text"
                                       data-category="quantity" value="" placeholder=""
                                       data-rule-required="true"
                                       data-msg-required="این فیلد الزامی است">
                            </div>
                        </div>

                        <div id="field_content">
                            {{--                            @include('admin.estate.sabt.partials.fieldLoop',['fields'=>[],'facilities'=>[],'fields_value'=>[]])--}}
                        </div>

                        <div class="col-sm-3 col-xs-12 ">
                            <div class="form-group">
                                <label for="status">وضعیت آگهی</label>
                                <select id="status" class="label form-control" name="status">
                                    <option value="PENDING">در حال انتظار</option>
                                    <option value="PUBLISHED">منتشر شده</option>
                                    <option value="HIDDEN">مخفی</option>
                                    <option value="DRAFT">زباله دان</option>
                                    <option value="WAITING">منتظر بررسی</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-12 ">
                            <div class="form-group">
                                <label for="offer">نوع آگهی</label>
                                <select id="offer" class="label form-control" name="offer">
                                    <option value="">معمولی</option>
                                    <option value="lux">لوکس</option>
                                    <option value="special"> ویژه</option>
                                    <option value="underprice">زیرقیمت</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 form-group">
                            <h6>ارجاع به :</h6>
                            <div class="col-sm-2 col-xs-6 ">
                                <label class="radio-inline">
                                    <input type="radio" name="reference" checked>شخصی
                                </label>
                            </div>
                            <div class="col-sm-2 col-xs-6 ">
                                <label class="radio-inline">
                                    <input type="radio" name="reference" value="amlak79"> املاک 79
                                </label>
                            </div>
                            <div class="col-sm-2 col-xs-3 ">
                                <label class="radio-inline">
                                    <input id="expert" type="radio" name="reference" value="expert">ارجاع به کارشناس
                                </label>
                            </div>
                            <div class="col-sm-6 col-xs-9 ">
                                <select name="experts[]" id="experts" class="select2" multiple disabled>
                                    @foreach($experts as $id=>$name)
                                        <option value="{{$id}}">{{$name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <label class=" control-label">آپلود عکس های ملک:</label>
                            <div class="clsbox-1" runat="server">
                                <div class="dropzone clsbox" id="mydropzone"
                                     data-url="{{ action('ImageController@uploadPhotos') }}">
                                </div>
                            </div>
                            {{--Dropzone Preview Template--}}
                            <div id="preview" style="display: none;">
                                <div class="dz-preview dz-file-preview">
                                    <div class="dz-image"><img data-dz-thumbnail/></div>

                                    <div class="dz-details">
                                        <div class="dz-size"><span data-dz-size></span></div>
                                        <div class="dz-filename"><span data-dz-name></span></div>
                                    </div>
                                    <div class="dz-progress"><span class="dz-upload"
                                                                   data-dz-uploadprogress></span></div>
                                    <div class="dz-error-message"><span data-dz-errormessage></span></div>
                                    <div class="dz-success-mark">
                                        <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink"
                                             xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                                            <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->
                                            <title>Check</title>
                                            <desc>Created with Sketch.</desc>
                                            <defs></defs>
                                            <g id="Page-1" stroke="none" stroke-width="1" fill="none"
                                               fill-rule="evenodd" sketch:type="MSPage">
                                                <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z"
                                                      id="Oval-2" stroke-opacity="0.198794158"
                                                      stroke="#747474" fill-opacity="0.816519475"
                                                      fill="#FFFFFF" sketch:type="MSShapeGroup"></path>
                                            </g>
                                        </svg>

                                    </div>
                                    <div class="dz-error-mark">

                                        <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink"
                                             xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                                            <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->
                                            <title>error</title>
                                            <desc>Created with Sketch.</desc>
                                            <defs></defs>
                                            <g id="Page-1" stroke="none" stroke-width="1" fill="none"
                                               fill-rule="evenodd" sketch:type="MSPage">
                                                <g id="Check-+-Oval-2" sketch:type="MSLayerGroup"
                                                   stroke="#747474" stroke-opacity="0.198794158"
                                                   fill="#FFFFFF" fill-opacity="0.816519475">
                                                    <path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z"
                                                          id="Oval-2" sketch:type="MSShapeGroup"></path>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div id="messages" style="display: none" class="alert alert-danger alert-dismissible"
                             role="alert">
                            <div><h4>لطفا مبلغ وام را وارد کنید</h4></div>
                        </div>

                        <div class="col-m">
                            <button type="submit" id="submit-form" name="submit" class="btn pull-right btn-default"
                                    value="Submit">ثبت آگهی
                            </button>

                        </div>
                    </div>

                    </fieldset>
                </form>

            </div>
        </section>
    </div>
    <div class="loading">
        <i class="fa fa-refresh fa-spin fa-2x fa-fw"></i><br/>
    </div>

@endsection

@push('scripts')
    <script src="/asset/js/jquery.easing.min.js"></script> <!--only for this page-->
    <script src="{{ asset('/theme/admin/js/select2.min.js') }}"></script>

    <script>
        $('.select2').select2({
            placeholder: "انتخاب کنید ...",
            dir: "rtl",
            // initSelection: function(element, callback) {
            // }
        });
        $(document).on('change', '#city_id', function () {
            $('.loading').show()
            if ($(this).val() === null) {
                $('#region_id').html("<option value=\"\">انتخاب کنید ...</option>");
                return;
            }
            var cityId = $(this).val();
            $.ajax({
                url: '{{action('Admin\EstateController@renderRegions')}}',
                dataType: 'JSON',
                type: 'get',
                data: {'city_id': cityId},
                success: function (response) {
                    $('#region_id')
                        .find('option')
                        .remove();
                    if (Object.keys(response).length > 1) {
                        $('#region_id').html("<option value=\"\">انتخاب کنید ...</option>");
                    }
                    $.each(response, function (key, value) {
                        $('#region_id')
                            .append($("<option></option>")
                                .attr("value", key)
                                .text(value));
                    });
                    $('.loading').hide()

                },
                error: function (error) {
                    console.log(error);
                }
            });

        });
    </script>
    <script>
        $('#typeId').on('change', function () {

            var typeId = $(this).val();
            $('.loading').show();
            $.ajax({
                type: "GET",
                url: '{{action('Admin\EstateController@getFields')}}',
                data: {'category': '{{$category}}', 'type_id': typeId},
                contentType: false,
                success: function (data) {
                    $("#field_content").html(data);
                    $('.loading').hide();
                    $('#floor').select2({
                        width: '100%',
                        tags: true,
                        dir: "rtl",
                        placeholder: 'انتخاب کنید...',
                        createTag: function (tag) {
                            console.log(tag);
                            return {
                                id: tag.term,
                                text: tag.term,
                                // add indicator:
                                isNew: true
                            };
                        }
                    }).on("select2:select", function(e) {
                        if(e.params.data.isNew){
                            // store the new tag:
                            {{--$.ajax({--}}
                            {{--url: '{{ action('client\SportController@store') }}',--}}
                            {{--dataType: 'JSON',--}}
                            {{--type: 'POST',--}}
                            {{--data:{'name': e.params.data.text },--}}
                            {{--success: function (response) {--}}
                            {{--console .log(response.id);--}}
                            {{--// append the new option element prenamently:--}}
                            {{--$('#sports').find('[value="'+e.params.data.id+'"]').replaceWith('<option selected value="'+response.id+'">'+e.params.data.text+'</option>');--}}
                            {{--},--}}
                            {{--error: function () {--}}

                            {{--}--}}
                            {{--});--}}
                        }
                    });
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });

        })
    </script>

    <script>
        //jQuery time
        var current_fs, next_fs, previous_fs; //fieldsets
        var left, opacity, scale; //fieldset properties which we will animate
        var animating; //flag to prevent quick multi-click glitches


        // disable-enable checkbox
        $(document).on('change', '#exchange', function () {
            document.getElementById('exchange_with').disabled = !this.checked;
            document.getElementById('exchange_with').focus();
            $("#exchange_with").val('');
        });
        $(document).on('change', '#has_loan', function () {
            document.getElementById('loan_amount').disabled = !this.checked;
            document.getElementById('loan_amount').focus();

            $("#loan_amount").val('');
        });
        $(document).on('change', '#delivery', function () {
            document.getElementById('age').value = 0;
        });
        $(document).on('change', '#presell', function () {
            document.getElementById('delivery').disabled = !this.checked;
            document.getElementById('delivery').focus();
            $("#delivery").val('');
        });
        $(document).on('change', '#cabinet', function () {
            document.getElementById('type_cabinet').disabled = !this.checked;
            document.getElementById('type_cabinet').focus();
            $("#type_cabinet").val('');
        });
        $('input[type=radio][name=reference]').change(function () {
            if (this.value == 'expert') {
                document.getElementById('experts').disabled = false;
                document.getElementById('experts').focus();
                $(".select2").select2("val", "");
            } else {
                document.getElementById('experts').disabled = true;
                document.getElementById('experts').focus();
                $(".select2").select2("val", "");
            }
        });
        // $(document).on('keyup', '.currency', function () {
        //     $(this).val(parseInt($('.currency').val(),replace(/,/g,'')).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        //
        // });

        // regex only number for some input

        $(".currency").on("keypress keyup blur", function (event) {
            $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

        // change color of selected option
        $('select.label').change(function () {
            $(this).css('color', 'green');
        });

        // $("#register-form").validate();

        $(document).ready(function () {
            // apply on typing and focus
            $('input.currency').on(' blur', function () {
                $(this).manageCommas();
            });
            // then sanatize on leave
            // if sanitizing needed on form submission time,
            // then comment beloc function here and call in in form submit function.
            $('input.currency').on('focus', function () {
                $(this).santizeCommas();
            });
        });

        String.prototype.addComma = function () {
            return this.replace(/(.)(?=(.{3})+$)/g, "$1,")
        };
        //Jquery global extension method
        $.fn.manageCommas = function () {
            return this.each(function () {
                $(this).val($(this).val().replace(/(,| )/g, '').addComma());
            })
        };

        $.fn.santizeCommas = function () {
            return $(this).val($(this).val().replace(/(,| )/g, ''));
        };

        function isfill() {
            if ($("input[name='loan']").val() != "") {
                return true;
            }
            return false;
        }

        $(document).on('submit', '#register-form', function () {
            event.preventDefault();
            $('.error').remove();
            $('.has-error').removeClass('has-error');
            var form = $(this);
            $('.currency').each(function () {
                $(this).val($(this).val().replace(/(,| )/g, ''));
            });

            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            $.ajax({
                type: form.attr('method'),
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    window.location.replace('{{action('Admin\EstateController@index')}}');
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log(xhr);
                    for (control in errorThrown) {
                        $('#' + control).addClass('is-invalid');
                        $('#error-' + control).html(errorThrown[control]);
                    }
                    $('#validation-errors').html('');
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        $('#' + key).addClass('is-invalid');
                        $('#' + key).parent().addClass('has-error');
                        $('#' + key).parent().find('label').append('<span class="error">' + value + '</span>');

                    });
                    $('.is-invalid:first').focus()
                }
            });
            return false;
        });
    </script>

    <script src="{{asset('theme/admin/dropzone/js/dropzone.js')}}"></script>
    {{--    <script src="{{asset('theme/admin/dropzone/js/dropzone-config.js')}}"></script>--}}
    <script>
        var total_photos_counter = 0;
        var photo_counter = 0;
        var url = $('#mydropzone').data('url');

        Dropzone.autoDiscover = false;
        $("div#mydropzone").dropzone({
            url: url,
            headers: {
                'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content').value,
            },
            uploadMultiple: false,
            parallelUploads: 2,
            maxFilesize: 10,
            previewTemplate: document.querySelector('#preview').innerHTML,
            addRemoveLinks: true,
            dictRemoveFile: 'حذف فایل',
            dictFileTooBig: 'حجم تصویر انتخاب شده بیشتر از 10 مگابایت میباشد.',
            timeout: 10000,
            acceptedMimeTypes: 'image/*',
            init: function () {

                this.on("removedfile", function (file) {
                    console.log(file.xhr);
                    var id;
                    if (file.xhr == undefined) {
                        id = file.id;
                    } else {
                        id = JSON.parse(file.xhr.response);
                    }
                    console.log('id', id);
                    $.ajax({
                        url: '/photos',
                        dataType: 'JSON',
                        type: 'DELETE',
                        data: {id: id},
                        success: function (data) {
                            total_photos_counter--;
                            $("#counter").text("# " + total_photos_counter);
                        },
                        error: function () {

                        }
                    });
                });
            },
            success: function (file, done) {
                console.log(done);
                total_photos_counter++;
                $("#counter").text("# " + total_photos_counter);
                var input = document.createElement("input");
                input.type = "hidden";
                input.value = done;
                input.name = "images[]";
                input.className = "dz-hidden-file";
                file.previewTemplate.appendChild(input);
                var a = document.createElement("a");
                a.innerHTML = 'مخفی کن';
                a.href = "javascript:undefined;";
                a.setAttribute('data-id', done);
                a.className = "dz-hidden-link dz-remove text-danger";
                file.previewTemplate.appendChild(a);
            }
        });

        $(document).on('click', '.dz-hidden-link', function () {
            event.preventDefault();
            var id = $(this).data('id');
            $('.loading').show();
            var element = $(this);
            $.ajax({
                type: "GET",
                url: "{{action('ImageController@hidePhoto')}}",
                data: {id},
                contentType: false,
                success: function (data) {
                    element.text(null);
                    if (data.status) {
                        element.text('مخغی کن');
                        element.removeClass('text-success');
                        element.addClass('text-danger');
                    } else {
                        element.text('ظاهر کن');

                        element.removeClass('text-danger');
                        element.addClass('text-success');
                    }

                    $('.loading').hide();
                    // $(".dz-hidden-link").text('salam');
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });
    </script>

@endpush


