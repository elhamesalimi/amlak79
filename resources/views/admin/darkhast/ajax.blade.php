@extends('admin.master')
{{--@section('breadcrumbs', Breadcrumbs::render('contacts'))--}}
@section('title','لیست درخواست ها')
@push('styles')


@endpush
@section('content')
    <div id="content">
        @include('admin.darkhast.index')
    </div>
    <div class="loading">
        <i class="fa fa-refresh fa-spin fa-2x fa-fw"></i><br/>
    </div>


@endsection

@push('scripts')
    <script src="{{asset('/theme/admin/js/ajax-crud.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('.search').click();
        });
    </script>

@endpush