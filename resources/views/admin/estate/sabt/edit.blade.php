@extends('admin.master')

@section('title', 'ویرایش ملک')
@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    {{--    <link rel="stylesheet" href="{{asset('/theme/admin/persianDatePicker/css/persianDatepicker-default.css')}}">--}}

    <style>
        .dz-hidden-link {
            font-size: 14px;
            text-align: center;
            display: block;
            cursor: pointer;
            border: none;
        }

        .filled {
            color: red;
        }

        .mouse {
            cursor: pointer;
        }

        .cat {
            margin-bottom: 1em;
            display: inline-block;
        }

        #categories-wrraper {
            float: right;
            margin-right: 2px;
            margin-top: 1em;
        }

        .margin {
            margin-right: 1em;
            margin-top: 1em;
            height: 100px;
        }

        .error {
            color: red;
            position: absolute;
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

        .date-time {
            display: inline-flex;
        }

        .date-time .error {
            display: block;
            margin-top: 41px;
            width: 100%;
        }

        @media (min-width: 768px) {
            .date-time input {
                padding-right: 2px;
                padding-left: 1px;
            }

            .date-time select {
                padding-right: 1px;
                padding-left: 1px;
            }
        }

        @media (max-width: 440px) {
            .date-time input {
                padding-right: 2px;
                padding-left: 1px;
            }

            .date-time select {
                padding-right: 1px;
                padding-left: 1px;
            }
        }
    </style>
    <link href="/theme/admin/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('/theme/admin/dropzone/css/dropzone.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/admin/dropzone/css/custom.css')}}">

@endpush
@section('content')
    @php($months=[
    '1'=>'فروردین',
    '2'=>'اردیبهشت',
    '3'=>'خرداد',
    '4'=>'تیر',
    '5'=>'مرداد',
    '6'=>'شهریور',
    '7'=>'مهر',
    '8'=>'آبان',
    '9'=>'آذر',
    '10'=>'دی',
    '11'=>'بهمن',
    '12'=>'اسفند'])
    <div class="container-fluid row">
        <label for="" class="date-picker">date Picker</label>
        <section id="content">
            <div id="msform">
                <div id="progressbar">
                    <div className="logo-breadcrumb"><img src="/asset/images/registerhome-icon.png" alt=""/></div>
                    <span className="text-breadcrumb">
                    </span>
                </div>
                <form id="register-form" action="{{action('Admin\EstateController@update',$estate->id)}}" method="POST">
                    {{ method_field('PUT') }}
                    @csrf()

                    <fieldset class="register-form">
                        <div class="info-person">
                            <h4 class="ff-title">اطلاعات آگهی دهنده</h4>

                            <div class="show-person">
                                <div class="col-sm-3 col-xs-12 mb20">
                                    <div class="form-group">
                                        <label for="name">نام خانوادگی</label>
                                        <input class="form-control" id="name" maxlength="50" name="name" type="text"
                                               value="{{$estate->owner->name}}" placeholder=""
                                               data-rule-required="true"
                                               data-msg-required="این فیلد الزامی است"
                                        >
                                        <span id="error-name"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3 col-xs-12 mb20">
                                <div class="form-group">
                                    <label for="phone">تلفن همراه</label>
                                    <input class="form-control" id="phone" name="phone" type="number"
                                           data-category="quantity" value="{{$estate->owner->phone}}" placeholder=""
                                           data-rule-required="true"
                                           data-msg-required="این فیلد الزامی است"
                                           data-rule-pattern="(0|\+98)?([ ]|-|[()]){0,2}9[1|2|3|4]([ ]|-|[()]){0,2}(?:[0-9]([ ]|-|[()]){0,2}){8}"
                                           data-msg-pattern="شماره صحیح نیست">

                                </div>
                            </div>
                            <div class="show-person">
                                <div class="col-sm-6 col-xs-12 mb20">
                                    <div class="form-group">
                                        <label>ایمیل</label>
                                        <input class="form-control" id="email" name="email" type="email"
                                               value="@if(isset($estate->fields['email'] )) {{$estate->fields['email']}} @elseif(isset($estate->owner->email)) {{$estate->owner->email}} @endif"
                                               placeholder="info@amlak79.ir" }}
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
                                <label for="title">عنوان آگهی</label>
                                <input type="text" class="form-control" id="title" name="title"
                                       value="{{$estate->title}}">
                            </div>
                        </div>

                        <div class="col-sm-3 col-xs-12 mb20">
                            <div class="form-group">
                                <label for="category">هدف ملک</label>
                                <select id="category" class="label form-control" name="category"
                                        data-rule-required="true"
                                        data-msg-required="این فیلد الزامی است">
                                    <option @if($estate->category==='sell') selected @endif value="sell">خرید</option>
                                    <option @if($estate->category==='presell') selected @endif value="presell">پیش خرید
                                    </option>
                                    <option @if($estate->category==='rent') selected @endif value="rent">رهن و اجاره
                                    </option>

                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-12 mb20">
                            <div class="form-group">
                                <label for="city_id">شهر</label>
                                <select id="city_id" class="label form-control" name="city_id" data-rule-required="true"
                                        data-msg-required="این فیلد الزامی است">
                                    <option value="" hidden>انتخاب کنید</option>
                                    @foreach($cities as $key=>$city)
                                        <option @if($estate->city_id===$key) selected
                                                @endif value="{{$key}}">{{$city}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-12 mb20">
                            <div class="form-group">
                                <label for="region_id">منطقه</label>
                                <select id="region_id" class="label form-control" name="region_id"
                                        data-rule-required="true"
                                        data-msg-required="این فیلد الزامی است">
                                    <option value="" hidden>انتخاب کنید</option>
                                    @foreach($regions as $key=>$value)
                                        <option @if($estate->region_id===$key) selected
                                                @endif value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-12 mb20">
                            <div class="form-group">
                                <label for="type_id">نوع ملک</label>
                                <select id="type_id" class="label form-control" name="type_id" data-rule-required="true"
                                        data-msg-required="این فیلد الزامی است">
                                    <option value="" hidden>انتخاب کنید</option>
                                    @foreach($types as $key=>$value)
                                        <option {{$estate->type_id===$key ? 'selected' : ''}} value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-12 mb20">
                            <div class="form-group">
                                <label for="plan_id">موقعیت</label>
                                <select id="plan_id" class="label form-control" name="plan_id"
                                        data-rule-required="true"
                                        data-msg-required="این فیلد الزامی است">
                                    <option value="" hidden>انتخاب کنید</option>
                                    @foreach($plans as $key=>$plan)
                                        <option value="{{$key}}"
                                                @if($estate->plan_id===$key) selected @endif>{{$plan}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-12 mb20">
                            <div class="form-group">
                                <label for="area">متراژ (متر مربع)</label>
                                <input class="form-control currency" id="area" name="area" type="text"
                                       data-category="quantity" value="{{$estate->area}}" placeholder=""
                                       data-rule-required="true"
                                       data-msg-required="این فیلد الزامی است">

                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-12 mb20">
                            <div class="form-group">
                                <label for="total_price">{{($estate->category==='sell' || $estate->category==='presell')? 'قیمت کل (تومان)' : 'مبلغ رهن (تومان)'}}</label>
                                <input class="form-control currency" id="total_price" name="total_price"
                                       type="text" data-category="quantity" value="{{$estate->total_price }}"
                                       placeholder=""
                                       data-rule-required="true"
                                       data-msg-required="این فیلد الزامی است">

                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-12 mb20">
                            <div class="form-group">
                                <label for="price">{{($estate->category==='sell' || $estate->category==='presell')? 'قیمت متر مربع (تومان)' : 'مبلغ اجاره (تومان)'}}</label>
                                <input class="form-control currency" id="price" name="price" type="text"
                                       data-category="quantity" value="{{$estate->price}}" placeholder=""
                                       data-rule-required="true"
                                       data-msg-required="این فیلد الزامی است">
                            </div>
                        </div>

                        <div id="field_content">
                            @include('admin.estate.sabt.partials.fieldLoop',['fields'=>$fields,'facilities'=>$facilities,'facilities_value'=>$facilities_value,'fields_value'=>$fields_value])
                        </div>


                        <div class="col-sm-2 col-xs-12 ">
                            <div class="form-group">
                                <label for="status">وضعیت آگهی</label>
                                <select id="status" class="label form-control" name="status">
                                    <option @if($estate->status==='PENDING') selected @endif value="PENDING">در حال
                                        انتظار
                                    </option>
                                    <option @if($estate->status==='PUBLISHED') selected @endif value="PUBLISHED">منتشر
                                        شده
                                    </option>
                                    <option @if($estate->status==='HIDDEN') selected @endif value="HIDDEN">مخفی</option>
                                    <option @if($estate->status==='DRAFT') selected @endif value="DRAFT">زباله دان
                                    </option>
                                    <option @if($estate->status==='WAITING') selected @endif value="WAITING">منتظر
                                        بررسی
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2 col-xs-12 ">
                            <div class="form-group">
                                <label for="offer">نوع آگهی</label>
                                <select id="offer" class="label form-control" name="offer">
                                    <option value="">معمولی</option>
                                    <option @if($estate->offer==='lux') selected @endif  value="lux">لوکس</option>
                                    <option @if($estate->offer==='special') selected @endif value="special"> ویژه
                                    </option>
                                    <option @if($estate->offer==='underprice') selected @endif value="underprice">
                                        زیرقیمت
                                    </option>
                                    d
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12 ">
                            {{--<div class="form-group date-time ">--}}
                            <div class="form-group  ">
                                <label for="datePicker">تاریخ ثبت: </label>
                                <input type="text" style="padding:5px" class="form-control"
                                       value="{{$estate->created_at}}" id="datePicker">
                                <input type="hidden" name="created_at" class="observer-created-at">

                                {{--<div style="width: 12%;">--}}
                                {{--<label>روز</label>--}}
                                {{--<input type="text" id="day" class="form-control "--}}
                                {{--name="day" value="{{old('day', jdate($estate->created_at)->getDay())}}">--}}
                                {{--</div>--}}

                                {{--<div style="width: 33%;">--}}
                                {{--<label>ماه</label>--}}
                                {{--<select name="month" id="month" class="form-control">--}}
                                {{--@foreach($months as $key=>$value)--}}
                                {{--<option value={{$key}} {{(old('month')&& old('month')===$key) ? 'selected':jdate($estate->created_at)->getMonth()===$key? 'selected':'' }}>{{$value}}</option>--}}
                                {{--@endforeach--}}
                                {{--</select>--}}
                                {{--</div>--}}
                                {{--<div style="width: 16%;">--}}
                                {{--<label>سال</label>--}}
                                {{--<input id="year" type="text" class="form-control "--}}
                                {{--name="year" value="{{old('year', jdate($estate->created_at)->getYear())}}">--}}
                                {{--</div>--}}
                                {{--<div style="width: 12%;">--}}
                                {{--<label>دقیقه</label>--}}
                                {{--<input type="text" class="form-control " name="minute" id="minute"--}}
                                {{--value="{{old('minute', jdate($estate->created_at)->getMinute())}}">--}}
                                {{--</div>--}}
                                {{--<div style="width: 12%;">--}}
                                {{--<label>ساعت</label>--}}
                                {{--<input type="text" class="form-control " id="hour"--}}
                                {{--name="hour" value="{{old('hour', jdate($estate->created_at)->getHour())}}">--}}
                                {{--</div>--}}
                            </div>

                        </div>
                    </div>
                    <div class="col-sm-2 col-xs-12 ">
                        <div class="form-group " style="padding: 30px 0px;">
                            <label for="listen">
                                <input id="listen" type="checkbox"
                                       name="listen" {{old('listen') || $estate->listen ? 'checked':''}}> گوش به زنگ
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-2 col-xs-12 ">
                        <div class="form-group " style="padding: 30px 0px;">
                            <label for="telegram">
                                <input id="telegram" type="checkbox"
                                       name="telegram" {{old('telegram') ? '':'checked'}}> تلگرام
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-12 form-group">
                        <h6>ارجاع به :</h6>
                        <div class="col-sm-2 col-xs-6 ">
                            <label class="radio-inline">
                                <input {{$estate->reference ? '': 'checked'}} type="radio" name="reference">شخصی
                            </label>
                        </div>
                        <div class="col-sm-2 col-xs-6 ">
                            <label class="radio-inline">
                                <input {{!count($estate->experts) && $estate->reference ? 'checked': '' }} type="radio"
                                       name="reference" value="amlak79"> املاک 79
                            </label>
                        </div>
                        <div class="col-sm-2 col-xs-3 ">
                            <label class="radio-inline">
                                <input id="expert" {{count($estate->experts) ? 'checked': ''}} type="radio"
                                       name="reference" value="expert">ارجاع به کارشناس
                            </label>
                        </div>
                        <div class="col-sm-6 col-xs-9 ">
                            <select name="experts[]" id="experts" class="select2"
                                    multiple {{count($estate->experts) ? '': 'disabled'}}>
                                @foreach($experts as $id=>$name)
                                    <option {{in_array($id,$estate->experts->pluck('id')->toArray())?'selected':''}} value="{{$id}}">{{$name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <label for="mydropzone">آپلود عکس های ملک:</label>
                        <div class="clsbox-1" runat="server">
                            <div class="dropzone clsbox" id="mydropzone"
                                 data-url="{{ action('ImageController@uploadPhotos') }}">

                                @foreach($estate->images as $image)
                                    <div class="dz-preview dz-processing dz-image-preview dz-complete">
                                            <input type="radio" name="avatar" value="{{$image->id}}" {{$image->avatar ? 'checked' :''}} class="dz-index"/>

                                        <div class="dz-image"><img data-dz-thumbnail=""
                                                                   alt="8a90fe47-2f64-4969-9488-d40354bcb6fe.jpg"
                                                                   src="{{asset('public_data/images/thumbs160x160/'.$image->uri)}}">
                                        </div>

                                        <div class="dz-details">
                                            <div class="dz-size"><span
                                                        data-dz-size=""><strong></strong> MB</span></div>
                                            <div class="dz-filename"><span
                                                        data-dz-name="">{{$image->uri}}</span></div>
                                        </div>
                                        <div class="dz-progress"><span class="dz-upload"
                                                                       data-dz-uploadprogress=""
                                                                       style="width: 100%;"></span></div>
                                        <div class="dz-error-message"><span data-dz-errormessage=""></span>
                                        </div>
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
                                        <a class="dz-remove" data-id="{{$image->id}}"
                                           href="javascript:undefined;" data-dz-remove="">حذف فایل</a>
                                        <input type="hidden" value="{{$image->id}}" name="images[]">
                                        <a href="javascript:undefined;" data-id="{{$image->id}}"
                                           class="dz-hidden-link  {{$image->status ? 'text-danger': 'text-success'}}">{{$image->status ? 'مخفی کن': 'ظاهر کن'}}</a>
                                    </div>
                                @endforeach

                            </div>
                        </div>
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
                    {{--Dropzone Preview Template--}}
                    <div class="col-m">
                        <button type="submit" id="submit-form" name="submit" class="btn pull-right btn-default"
                                value="Submit">ویرایش آگهی
                        </button>

                    </div>
                    </fieldset>
                </form>
            </div>
        </section>
    </div>
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

        function renderFields(type_id) {

            var typeId = type_id;
            var category = $('#category').val();
            $('.loading').show();
            $.ajax({
                type: "GET",
                url: '{{action('Admin\EstateController@getFields')}}',
                data: {'category': category, 'type_id': typeId, 'estate_id': '{{$estate->id}}'},
                contentType: false,
                success: function (data) {
                    $("#field_content").html(data);
                    $('.loading').hide();
                },
                error: function (xhr, status, error) {
                    alert(xhr.responseText);
                }
            });
        }

        function renderRegions($city_id) {
            $('.loading').show()
            var cityId = $city_id;

            $('#region_id').html("<option value=\"\">انتخاب کنید ...</option>");
            $.ajax({
                url: '{{action('Admin\EstateController@renderRegions')}}',
                dataType: 'JSON',
                type: 'get',
                data: {'city_id': cityId},
                success: function (response) {
                    $('#region_id')
                        .find('option')
                        .remove();
                    console.log(Object.keys(response).length);
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
                    alert(error);
                }
            });
        }

        $(document).on('change', '#city_id', function () {
            renderRegions($(this).val())

        });
    </script>
    <script>
        $('#type_id').on('change', function () {
            renderFields($(this).val())

        })
    </script>

    <script>
        //jQuery time
        var current_fs, next_fs, previous_fs; //fieldsets
        var left, opacity, scale; //fieldset properties which we will animate
        var animating; //flag to prevent quick multi-click glitches


        // disable-enable checkbox
        $(document).on('change', '#exchange', function (startChange) {
            console.log(startChange);
            document.getElementById('exchange_with').disabled = !this.checked;
            document.getElementById('exchange_with').focus();
            $("#exchange_with").val('');
        });
        $(document).on('change', '#has_loan', function () {
            document.getElementById('loan_amount').disabled = !this.checked;
            document.getElementById('loan_amount').focus();

            $("#loan_amount").val('');
        });
        $(document).on('change', '#presell', function () {
            document.getElementById('delivery').disabled = !this.checked;
            if (!this.checked) {
                document.getElementById('category').value = 'sell';
            }
            document.getElementById('delivery').focus();
            $("#delivery").val('');
        });
        $(document).on('change', '#delivery', function () {
            document.getElementById('age').value = 0;
            document.getElementById('housting').value = 'تخلیه';
            document.getElementById('category').value = 'presell';
        });
        $(document).on('change', '#category', function () {
            renderFields($('#type_id').val());
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


        // $('input[type=radio][value=expert]').change();

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
            })

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
                    $('label.error').removeClass('error');
                    $('.label.error').text('');
                    window.location.replace('{{action('Admin\EstateController@index')}}');
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log('textStatus', textStatus);
                    for (control in errorThrown) {
                        $('#' + control).addClass('is-invalid');
                        $('#error-' + control).html(errorThrown[control]);
                    }
                    $('#validation-errors').html('');
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        $('#' + key).addClass('is-invalid');
                        $('#' + key).parent().addClass('has-error');
                        $('#' + key).parent().find('label').append('<span class="error" >' + value + '</span>');

                    });
                    $('.is-invalid:first').focus()
                    // alert("Error: " + errorThrown);
                }
            });
            return false;
        });

    </script>
    <script src="{{asset('/asset/js/jquery.inputmask.bundle.min.js')}}"></script>
    <script>
        $('#created_at').inputmask();
        $('#datetime').inputmask();
    </script>
    <script src="/theme/admin/imageCompressor/image-compressor.min.js"></script>

    <script src="{{asset('theme/admin/dropzone/js/dropzone.js')}}"></script>
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
            transformFile: function (file, done) {
                const imageCompressor = new ImageCompressor();

                imageCompressor.compress(file, {
                    checkOrientation: false,
                    maxWidth: 700,
                    quality: 0.8,
                    convertSize: 0,
                })
                    .then((result) => {
                        // Handle the compressed image file.
                        done(result)
                    })
                    .catch((err) => {
                        // Handle the error
                        throw err
                    })
            },
            success: function (file, done) {
                total_photos_counter++;
                var previewTemplate = file.previewTemplate;
                $("#counter").text("# " + total_photos_counter);
                var input = document.createElement("input");
                input.type = "hidden";
                input.value = done;
                input.name = "images[]";
                input.className = "dz-hidden-file";
                previewTemplate.appendChild(input);
                var a = document.createElement("a");
                a.innerHTML = 'مخفی کن';
                // a.href = "javascript:undefined;";
                a.setAttribute('data-id', done);
                a.className = "dz-hidden-link text-danger";
                previewTemplate.appendChild(a);
                var radio = document.createElement("input");
                radio.type = "radio";
                radio.value = done;
                radio.name = "avatar";
                radio.className = "dz-index";
                previewTemplate.insertBefore(radio, previewTemplate.childNodes[0]);

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
                        element.text('مخفی کن');
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
                    alert(xhr.responseText);
                }
            });
        });

        $(document).on('click', '.dz-index', function () {
            var id = $(this).val();
            var estate_id = '{{$estate->id}}'
            $('.loading').show();
            var element = $(this);
            $.ajax({
                type: "GET",
                url: "{{action('ImageController@setAvatar')}}",
                data: {id , estate_id},
                contentType: false,
                success: function (data) {

                    $('.loading').hide();
                    // $(".dz-hidden-link").text('salam');
                },
                error: function (xhr, status, error) {
                    alert(xhr.responseText);
                }
            });
        });
        $(document).on('click', '.dz-remove', function () {
            event.preventDefault();
            var id = $(this).data('id');
            $('.loading').show();
            var element = $(this);
            $.ajax({
                url: '/photos',
                dataType: 'JSON',
                type: 'DELETE',
                data: {id: id},
                success: function (data) {
                    element.parents('.dz-preview').remove();
                    total_photos_counter--;
                    $("#counter").text("# " + total_photos_counter);
                    $('.loading').hide();
                },
                error: function () {

                }
            });
        });
    </script>
    <script>
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
        }).on("select2:select", function (e) {
            if (e.params.data.isNew) {
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
    </script>
    <?php $date = old('created_at', jdate($estate->created_at)->format('Y/m/d H:i')) ?>
    <?php //$date = '1398-11-12 ' ?>
    <script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
    <script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
    {{--    <script src="{{asset('/theme/admin/persianDatePicker/js/persianDatepicker.js')}}"></script>--}}
    <script>

        $("#datePicker ").pDatepicker({
            format: 'LLLL',
            persianDigit: false,
            altField: '.observer-created-at',
            timePicker: {
                enabled: true
            },
            responsive: false
            // formatter: function(unix){
            //     return 'selected unix: ' + unix;
            // }
        });

    </script>
@endpush


