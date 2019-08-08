{{--<div class="container">--}}
    {{--<h1>{{isset($post)?'ویرایش ':'افزودن  '}} </h1>--}}
    {{--<hr/>--}}
    {{--<form action="{{ isset($post) ? action('admin\FoodController@update' , $post->id) : action('admin\FoodController@create')  }}"--}}
          {{--method="POST" id="frm" onsubmit="return validateForm()">--}}
        {{--{{ csrf_field() }}--}}
        {{--{{ isset($post) ? method_field('PUT') :'' }}--}}
        {{--<div class="col-md-6 form-group  required">--}}
            {{--<label for="name"> دسته بندی مواد غذایی</label>--}}
            {{--<select name="category_id" required class="form-control {{$errors->has('category_id')?" is-invalid":""}}" id="">--}}
                {{--<option value="" selected>انتخاب کنید...</option>--}}
                {{--@foreach($categories as $id=>$name)--}}
                    {{--<option value="{{ $id }}" @if(isset($post)) {{ $id == $post->category_id ? 'selected' : '' }} @endif>{{ $name }}</option>--}}
                {{--@endforeach--}}
            {{--</select>--}}
            {{--<span id="error-category_id" class="text-danger"></span>--}}
        {{--</div>--}}


        {{--<div class="col-md-6 form-group  required">--}}
            {{--<label for="name"> نام</label>--}}
            {{--<input type="text" required name="name" id="name" class="form-control  {{$errors->has('name')?" is-invalid":""}}"--}}
                   {{--value="{{old('name',$post->name ?? '')}}" placeholder=" نام را به فارسی وارد نمایید." autofocus>--}}
            {{--<span id="error-name" class="text-danger"></span>--}}
        {{--</div>--}}

        {{--<div class="col-sm-12">--}}
            {{--<div class="col-sm-4">--}}
                {{--<label class="control-label mb-10 text-left">واحد</label>--}}
            {{--</div>--}}
            {{--<div class="col-sm-4">--}}
                {{--<label class="control-label mb-10 text-left ">مقدارواحد</label>--}}
            {{--</div>--}}

            {{--<div class="col-sm-4">--}}
                {{--<label class="control-label mb-10 text-left ">مقدارکالری</label>--}}
            {{--</div>--}}

        {{--</div>--}}
        {{--@if(isset($post))--}}
            {{--@foreach($post->units as $unit)--}}
                {{--<div class="box">--}}
                    {{--<div class="form-group col-sm-4">--}}
                        {{--<select required name="unit_id[]" class="form-control {{$errors->has('unit_id')?" is-invalid":""}}"--}}
                                {{--id="unit_id">--}}
                            {{--<option value="" selected>انتخاب کنید...</option>--}}
                            {{--@foreach($units as $id => $name)--}}
                                {{--<option value="{{ $id }}"{{ $id === $unit->id ? 'selected' : '' }}>{{ $name }}</option>--}}
                            {{--@endforeach--}}
                        {{--</select>--}}
                        {{--<span id="error-unit_id" class="text-danger"></span>--}}
                    {{--</div>--}}
                    {{--<div class="form-group col-sm-4">--}}
                        {{--<input required class="form-control {{$errors->has('amount')?" is-invalid":""}}"--}}
                               {{--name="amount[]" placeholder="مقدار را وارد کنید" type="text" id="amount"--}}
                               {{--value="{{old('amount', $unit->pivot->amount ?? '')}}">--}}
                        {{--<span id="error-amount" class="text-danger"></span>--}}
                    {{--</div>--}}
                    {{--<div class="form-group col-sm-3">--}}
                        {{--<input required class="form-control {{$errors->has('calory')?" is-invalid":""}}"--}}
                               {{--name="calory[]" placeholder="مقدار را وارد کنید" type="text"--}}
                               {{--value="{{old('calory', $unit->pivot->calory ?? '')}}">--}}
                        {{--<span id="error-calory" class="text-danger"></span>--}}
                    {{--</div>--}}
                    {{--@if ($loop->first)--}}
                        {{--<div class="col-sm-1">--}}
                            {{--<a class="btn btn-primary" disabled=""--}}
                               {{--id="add-calory"--}}
                               {{--data-url="{{ action('admin\FoodController@addCalory') }}"><i--}}
                                        {{--class="fa fa-plus-circle"></i></a>--}}
                        {{--</div>--}}
                    {{--@else--}}
                        {{--<div class="col-sm-1">--}}
                            {{--<a class="remove-calory">--}}
                                {{--<i class="fa fa-trash"--}}
                                   {{--style="font-size: 150% ; color: red ;margin-top: 0.5em;margin-right: 1em;"></i></a>--}}
                        {{--</div>--}}
                    {{--@endif--}}
                {{--</div>--}}
            {{--@endforeach--}}
        {{--@else--}}
            {{--<div class="form-group col-sm-4">--}}
                {{--<select name="unit_id[]" class="form-control unit {{$errors->has('unit_id')?" is-invalid":""}}" id="">--}}
                    {{--@foreach($units as $id => $name)--}}
                        {{--<option value="{{ $id }}"{{ $name === 'گرم' ? 'selected' : '' }}>{{ $name }}</option>--}}
                    {{--@endforeach--}}
                {{--</select>--}}
                {{--<span id="error-unit_id" class="text-danger"></span>--}}
            {{--</div>--}}
            {{--<div class="form-group col-sm-3">--}}
                {{--<input class="form-control unit {{$errors->has('amount')?" is-invalid":""}}"--}}
                       {{--name="amount[]" placeholder="مقدار را وارد کنید" type="text"--}}
                       {{--value="100">--}}
                {{--<span id="error-amount" class="text-danger"></span>--}}
            {{--</div>--}}
            {{--<div class="form-group col-sm-3">--}}
                {{--<input class="form-control unit {{$errors->has('calory')?" is-invalid":""}}"--}}
                       {{--name="calory[]" placeholder="مقدار را وارد کنید" type="text" id="calory-input"--}}
                       {{--value="{{old('calory', $unit->pivot->calory ?? '')}}">--}}
                {{--<span id="calory" style="display: none" class="text-danger">این مقدار باید وارد شود</span>--}}
            {{--</div>--}}

            {{--<div class="col-sm-2">--}}
                {{--<a class="btn btn-primary " id="add-calory"--}}
                   {{--data-url="{{ action('admin\FoodController@addCalory') }}"><i class="fa fa-plus-circle"></i></a>--}}
                {{--<a class="btn btn-primary " id="scrool"><i class="fa fa-sort-desc"></i></a>--}}
            {{--</div>--}}

            {{--<div id="property" >--}}
                {{--@foreach($properties as $property)--}}
                    {{--<div class="col-md-4 form-group">--}}
                        {{--<label class="col-sm-12">{{ $property->name }}</label>--}}
                        {{--<input type="text" name="{{$property->id}}[]" id="fat" class="form-control  {{$errors->has($property->name)?" is-invalid":""}}"--}}
                               {{--value="@if(isset($post)){{$property->foods()->find($post->id)->pivot->value ?? ''}}@endif"--}}
                               {{--placeholder="{{$property->name}} را وارد نمایید."--}}
                               {{--autofocus>--}}
                        {{--<span id="error-{{ $property->name }}" class="text-danger"></span>--}}
                    {{--</div>--}}
                {{--@endforeach--}}
            {{--</div>--}}
        {{--@endif--}}
        {{--<div id="calory_list">--}}

        {{--</div>--}}




        {{--<div class="col-md-12 pull-right form-group " style="text-align: left ; margin-top: 2em">--}}
            {{--<input type="submit" class="btn btn-primary" id="add" value="{{isset($post)?'ویرایش':'افزودن'}}">--}}

            {{--<a href="javascript:ajaxLoad('{{action('admin\FoodController@index')}}')" class="btn btn-danger">--}}
                {{--برگشت</a>--}}
        {{--</div>--}}

    {{--</form>--}}
{{--</div>--}}












{{--<div class="container-fluid">--}}

    {{--<!-- Row -->--}}
    {{--<form action="{{isset($post) ? action('admin\PostController@update' , $post->id) : action('admin\PostController@create')  }}" method="POST"--}}
          {{--enctype="multipart/form-data" id="commentForm">--}}
        {{--{{ csrf_field() }}--}}
        {{--@if(isset($post)) {{ method_field('PATCH') }} @endif--}}
        {{--<div class="row">--}}
            {{--<div class="col-sm-8">--}}
                {{--<div class="panel panel-default card-view">--}}
                    {{--<div class="panel-wrapper collapse in">--}}
                        {{--<div class="panel-body">--}}
                            {{--<div class="form-wrap">--}}

                                {{--<div class="form-group col-sm-12">--}}
                                    {{--<label class="control-label mb-10 text-left">عنوان</label>--}}
                                    {{--<input data-parsley-required="true"--}}
                                           {{--data-parsley-pattern="^[\u0600-\u06FF\s0-9\s_.-]+$"--}}
                                           {{--data-parsley-pattern-message="مقدار بالا را با حروف فارسی پر کنید"--}}
                                           {{--class="form-control" name="title"--}}
                                           {{--placeholder="عنوان را وارد کنید" type="text" id="title" value="@if(isset($post)){{ $post->title }}@else{{ old('title') }}@endif">--}}
                                {{--</div>--}}
                                {{--<div class="form-group col-sm-12">--}}
                                    {{--<label class="control-label mb-10 text-left">توضیحات کوتاه</label>--}}
                                    {{--<input class="form-control"--}}
                                           {{--name="excerpt" placeholder="توضیحات کوتاه را وارد کنید" type="text" value="@if(isset($post)){{ $post->excerpt }}@else{{ old('excerpt') }}@endif">--}}
                                {{--</div>--}}

                                {{--<div class="form-group col-sm-12">--}}
                                    {{--<label class="control-label mb-10 text-left ">متن</label>--}}

                                    {{--<textarea--}}
                                            {{--data-parsley-required="true"--}}
                                            {{--data-parsley-minlength="10"--}}
                                            {{--data-parsley-pattern="^[\u0600-\u06FF\s0-9\s_.-]+$"--}}
                                            {{--data-parsley-pattern-message="مقدار بالا را با حروف فارسی پر کنید"--}}
                                            {{--class="form-control my-editor" rows="20" id="body" name="body">--}}
                                            {{--@if(isset($post)) {{ $post->body }} @else {{ old('body') }} @endif--}}
                                        {{--</textarea>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

            {{--</div>--}}

            {{--<div class="col-md-4">--}}

                {{--<!-- ### DETAILS ### -->--}}
                {{--<div class="panel  panel-bordered panel-warning">--}}
                    {{--<div class="panel-heading">--}}
                        {{--<h3 class="panel-title"><i class="icon wb-clipboard"></i> اطلاعات تخصصی</h3>--}}
                        {{--<div class="panel-actions">--}}
                            {{--<a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class=" panel-body">--}}
                        {{--<div class="form-group col-md-12" style="margin-top: 1em">--}}
                            {{--<label for="gender"> نامک :</label>--}}
                            {{--<input type="text" name="slug" class="form-control" id="slug" placeholder="نامک را وارد کنید" value="@if(isset($post)){{ $post->slug }}@else{{ old('slug') }}@endif">--}}
                        {{--</div>--}}

                        {{--<div class="form-group col-md-12">--}}

                            {{--<label for="gender" style="margin-bottom: 1em"> وضعیت :</label>--}}

                            {{--<select data-parsley-required="true"--}}
                                    {{--class="form-control" name="status" id="">--}}
                                {{--<option value="PENDING"@if(isset($post)) {{ $post->status == "PENDING" ? 'selected' : '' }}  @endif>درحال انتظار</option>--}}
                                {{--<option value="PUBLISHED" @if(isset($post)) {{ $post->status == "PUBLISHED" ? 'selected' : '' }}  @endif>انتشار</option>--}}
                            {{--</select>--}}
                            {{--<span id="error-gender" class="text-danger"></span>--}}

                        {{--</div>--}}

                        {{--<div class="form-group col-md-12" style="margin-top: 1em">--}}
                            {{--<label for="gender"> دسته بندی :</label>--}}
                            {{--<br>--}}
                            {{--<div style="text-align: center">--}}
                                {{--<select name="category_id" id="" class="form-control">--}}
                                    {{--@foreach($categories as $category)--}}
                                        {{--<option value="{{ $category->id }}">{{ $category->name }}</option>--}}
                                    {{--@endforeach--}}
                                {{--</select>--}}
                                {{--<ul class="categories-select" >--}}
                                {{--@foreach($categories as $category)--}}
                                {{--@if($category->parent_id == 0)--}}
                                {{--<li data-dropdown-id="{{ $category->id }}"--}}
                                {{--data-dropdown-text="{{ $category->name }}">--}}
                                {{--<input class="cate" type="hidden" value="{{ $category->id }}">--}}
                                {{--{!! \App\Http\Controllers\admin\PostController::getParentCategory($category->id) !!}--}}
                                {{--</li>--}}
                                {{--@endif--}}
                                {{--@endforeach--}}
                                {{--</ul>--}}
                            {{--</div>--}}
                        {{--</div>--}}


                        {{--<div class="form-group col-md-12">--}}
                            {{--<label for="gender" style="margin-bottom: 1em"> برچسب:</label>--}}
                            {{--<select  class="js-example-basic-multiple" name="tag_id[]" multiple="multiple" id="tags">--}}
                                {{--@foreach($tags as $tag)--}}
                                    {{--<option value="{{ $tag->id }}" @if(isset($post)) {{  in_array(trim($tag->id) , $post->tags()->pluck('id')->toArray()) ? 'selected' : '' }} @endif>{{ $tag->name }}</option>--}}
                                {{--@endforeach--}}
                            {{--</select>--}}
                            {{--<span id="error-gender" class="text-danger"></span>--}}

                        {{--</div>--}}

                    {{--</div>--}}
                {{--</div>--}}


                {{--<div class="panel  panel-bordered panel-primary">--}}
                    {{--<div class="panel-heading">--}}
                        {{--<h3 class="panel-title"><i class="icon wb-clipboard"></i>تصویر شاخص</h3>--}}
                        {{--<div class="panel-actions">--}}
                            {{--<a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class=" panel-body">--}}

                        {{--<div class="file-upload">--}}
                            {{--<button class="file-upload-btn" type="button"--}}
                                    {{--onclick="$('.file-upload-input').trigger( 'click' )">انتخاب تصویر--}}
                            {{--</button>--}}

                            {{--<div class="image-upload-wrap">--}}
                                {{--<input class="file-upload-input" name="image" type='file'--}}
                                       {{--onchange="readURL(this);" accept="image/*"/>--}}

                                {{--@if(isset($post) && $post->image)--}}
                                    {{--<img src="{{(isset($post->image))? asset('/public_data/post/images/thumbs/'.$post->image): asset('/images/no-picture.png') }}"--}}
                                         {{--width="360" height="250" alt="your image"/>--}}
                                {{--@else--}}
                                    {{--<div class="drag-text">--}}
                                        {{--<h3>تصویر شاخص را انتخاب کنید</h3>--}}
                                    {{--</div>--}}
                                {{--@endif--}}

                            {{--</div>--}}

                            {{--<div class="file-upload-content">--}}
                                {{--<img class="file-upload-image" src="#" alt="your image"/>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                    {{--</div>--}}
                {{--</div>--}}

            {{--</div>--}}

            {{--<div class="col-sm-12">--}}
                {{--<div class="panel  panel-bordered panel-success">--}}
                    {{--<div class="panel-heading">--}}
                        {{--<h3 class="panel-title"><i class="icon wb-clipboard"></i>اطلاعات سئو</h3>--}}
                        {{--<div class="panel-actions">--}}
                            {{--<a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class=" panel-body">--}}



                        {{--<div class="form-group col-sm-12"><br>--}}
                            {{--<label class="control-label mb-10 text-left"> کلمه کلیدی</label>--}}
                            {{--<input class="form-control"--}}
                                   {{--name="keywords" placeholder=" کلمه کلیدی را وارد کنید" type="text" value="@if(isset($post)){{ $post->keywords }}@else{{ old('keywords') }}@endif">--}}
                        {{--</div>--}}
                        {{--<div class="form-group col-sm-12"><br>--}}
                            {{--<label class="control-label mb-10 text-left">عنوان سئو</label>--}}
                            {{--<input class="form-control" name="keywordTitle" placeholder="کلید واژه را وارد کنید"--}}
                                   {{--type="text" value="@if(isset($post)){{ $post->keywordTitle }}@else{{ old('keywordTitle') }}@endif">--}}
                        {{--</div>--}}
                        {{--<div class="form-group col-sm-12"><br>--}}
                            {{--<label class="control-label mb-10 text-left">توضیحات</label>--}}
                            {{--<textarea name="description"  maxlength="120" rows="3" class="form-control">@if(isset($post)){{$post->description}}@else{{ old('description') }}@endif</textarea>--}}

                        {{--</div>--}}
                        {{--<br>--}}

                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<div class="col-sm-12">--}}
                {{--<div class="pull-right">--}}
                    {{--<a href="{{ action('admin\PostController@index') }}" class="btn btn-default">انصراف</a>--}}
                    {{--<button type="submit" class="btn btn-success mr-10">ذخیره</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</form>--}}
{{--</div>--}}

{{--<script src="{{asset('js/admin/jquery.dropdown.js')}}"></script>--}}
{{--<script src="{{asset('js/admin/select2.min.js')}}"></script>--}}

{{--<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>--}}


{{--<script>--}}

    {{--tinymce.init({--}}
        {{--selector: '#body',--}}
        {{--height: 500,--}}
        {{--setup: function (editor) {--}}
            {{--editor.on('init change', function () {--}}
                {{--editor.save();--}}
            {{--});--}}
        {{--},--}}
        {{--plugins: [--}}
            {{--"advlist autolink lists link image charmap print preview anchor",--}}
            {{--"searchreplace visualblocks code fullscreen",--}}
            {{--"insertdatetime media table contextmenu paste imagetools"--}}
        {{--],--}}
        {{--toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",--}}
        {{--content_css: [--}}
            {{--'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',--}}
            {{--'//www.tinymce.com/css/codepen.min.css'--}}
        {{--],--}}
        {{--image_title: true,--}}
        {{--automatic_uploads: true,--}}
        {{--images_upload_url: 'upload',--}}
        {{--file_picker_types: 'image',--}}


        {{--images_upload_handler: function (blobInfo, success, failure) {--}}
            {{--var xhr, formData;--}}
            {{--xhr = new XMLHttpRequest();--}}
            {{--xhr.withCredentials = false;--}}
            {{--xhr.open('POST', 'upload');--}}
            {{--xhr.setRequestHeader("X-CSRF-Token", "{{ csrf_token() }}");--}}

            {{--xhr.onload = function() {--}}
                {{--var json;--}}

                {{--if (xhr.status != 200) {--}}
                    {{--failure('HTTP Error: ' + xhr.status);--}}
                    {{--return;--}}
                {{--}--}}

                {{--json = JSON.parse(xhr.responseText);--}}

                {{--if (!json || typeof json.location != 'string') {--}}
                    {{--failure('Invalid JSON: ' + xhr.responseText);--}}
                    {{--return;--}}
                {{--}--}}

                {{--success(json.location);--}}
            {{--};--}}

            {{--formData = new FormData();--}}
            {{--formData.append('file', blobInfo.blob(), blobInfo.filename());--}}

            {{--xhr.send(formData);--}}
        {{--},--}}

        {{--file_picker_callback: function (cb, value, meta) {--}}
            {{--var input = document.createElement('input');--}}
            {{--input.setAttribute('type', 'file');--}}
            {{--input.setAttribute('name', 'photo');--}}
            {{--input.setAttribute('accept', 'image/*');--}}
            {{--input.onchange = function () {--}}
                {{--var file = this.files[0];--}}

                {{--var reader = new FileReader();--}}
                {{--reader.readAsDataURL(file);--}}
                {{--reader.onload = function () {--}}
                    {{--var id = 'blobid' + (new Date()).getTime();--}}
                    {{--var blobCache = tinymce.activeEditor.editorUpload.blobCache;--}}
                    {{--var base64 = reader.result.split(',')[1];--}}
                    {{--var blobInfo = blobCache.create(id, file, base64);--}}
                    {{--blobCache.add(blobInfo);--}}
                    {{--cb(blobInfo.blobUri(), {title: file.name});--}}
                {{--};--}}
            {{--};--}}
            {{--input.click();--}}
        {{--}--}}
    {{--});--}}

{{--</script>--}}

{{--<script>--}}
    {{--$('#commentForm').parsley();--}}

    {{--$("#title").keyup(function() {--}}
        {{--let title = $(this).val();--}}
        {{--str = title.replace(/\s+/g, '-').toLowerCase();--}}
        {{--$("#slug").val(str);--}}
    {{--});--}}

    {{--$("#slug").keyup(function() {--}}
        {{--let slug = $(this).val();--}}
        {{--str = slug.replace(/\s+/g, '-').toLowerCase();--}}
        {{--$(this).val(str)--}}
    {{--});--}}


    {{--$('.categories-select').dropdown();--}}

    {{--$(document).ready(function () {--}}
        {{--$('.dropdown-submenu a.test').on("click", function (e) {--}}
            {{--$(this).next('ul').toggle();--}}

            {{--$(this).data--}}
            {{--e.stopPropagation();--}}
            {{--e.preventDefault();--}}
        {{--});--}}
    {{--});--}}

    {{--$('#tags').select2({--}}
        {{--width: '100%',--}}
        {{--tags: true,--}}
        {{--dir: "rtl",--}}
        {{--placeholder: 'انتخاب کنید...',--}}
        {{--createTag: function (tag) {--}}
            {{--return {--}}
                {{--id: tag.term,--}}
                {{--text: tag.term,--}}
                {{--// add indicator:--}}
                {{--isNew: true--}}
            {{--};--}}
        {{--}--}}
    {{--}).on("select2:select", function(e) {--}}
        {{--if(e.params.data.isNew){--}}
            {{--// store the new tag:--}}
            {{--$.ajax({--}}
                {{--url: '{{ action('admin\TagController@store') }}',--}}
                {{--dataType: 'JSON',--}}
                {{--type: 'POST',--}}
                {{--data:{'name': e.params.data.text },--}}
                {{--success: function (response) {--}}
                    {{--console .log(response.id);--}}
                    {{--// append the new option element prenamently:--}}
                    {{--$('#tags').find('[value="'+e.params.data.id+'"]').replaceWith('<option selected value="'+response.id+'">'+e.params.data.text+'</option>');--}}

                {{--},--}}
                {{--error: function () {--}}

                {{--}--}}
            {{--});--}}
        {{--}--}}
    {{--});--}}
{{--</script>--}}

