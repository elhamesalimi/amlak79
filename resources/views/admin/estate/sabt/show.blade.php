@extends('admin.master')
@section('breadcrumbs', Breadcrumbs::render('dashboard'))
@section('title',$post->title )
@push('styles')
    <style>
        .error {
            color : red;
        }
    </style>
@endpush
@section('content')

    <div class="container-fluid">

        <!-- Row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default card-view">
                    <div class="panel-wrapper collapse in">
                        <div class="panel-body">
                            <div class="form-wrap">
                                <form action="{{ action('admin\PostController@update' , ['post' => $post->id]) }}" enctype="multipart/form-data" id="commentForm" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('PATCH') }}
                                    <div class="form-group">
                                        <label class="control-label mb-10 text-left">عنوان</label>
                                        <input readonly minlength="3"  class="form-control" name="title"
                                               placeholder="عنوان را وارد کنید" type="text" value="{{ $post->title }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label mb-10 text-left">توضیحات کوتاه</label>
                                        <input readonly minlength="5" maxlength="120" class="form-control" name="excerpt"
                                               placeholder="توضیحات کوتاه را وارد کنید" type="text" value="{{ $post->excerpt }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label mb-10 text-left">متن</label>
                                        <textarea readonly class="form-control" rows="5" name="body">{{ $post->body }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label mb-10 text-left">تصویر</label>
                                        <input readonly  class="form-control" name="image" type="file">
                                    </div>
                                    <br>
                                    <div class="pull-right">
                                        <a href="{{ action('admin\PostController@index') }}" class="btn btn-default">انصراف</a>
                                        {{--<button type="submit" class="btn btn-success mr-10">ذخیره</button>--}}
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Row -->


    </div>

@endsection

@push('scripts')
    <script>
        // $.validator.setDefaults({
        //     submitHandler: function() {
        //         alert("submitted!");
        //     }
        // });

        $().ready(function() {
            // validate the comment form when it is submitted
            $("#commentForm").validate();

        });
    </script>
@endpush


