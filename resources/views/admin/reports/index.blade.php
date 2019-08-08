@extends('admin.master')
{{--@section('breadcrumbs', Breadcrumbs::render('posts'))--}}
@section('title','آمار ملکهای ثبت شده')
@push('styles')
@endpush
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{session('message')}}</div>
    @endif
    <div class="col-md-6 col-xs-12">

        <table class="table table-bordered table-striped ">
            <thead>
            <tr>
                <th>عنوان</th>
                <th> آمار ملکها</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>املاک 79</td>
                <td>{{$amlak79_count}} </td>
            </tr>
            <tr>
                <td>شخصی</td>
                <td>{{$personal_count}}</td>
            </tr>
            @foreach($experts as $expert)
                <tr>
                    <td>{{$expert->name}}</td>
                    <td>{{$expert->estates()->count()}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
@push('scripts')

@endpush
