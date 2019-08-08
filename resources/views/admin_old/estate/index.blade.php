@extends('admin_old.layouts.master')
@section('title','داشبورد')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="m-b-30">
                                <button id="addToTable" class="btn btn-primary waves-effect waves-light">افزودن <i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="editable-responsive">
                        <div id="datatable-editable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"><div class="dataTables_length" id="datatable-editable_length"><label>Show <select name="datatable-editable_length" aria-controls="datatable-editable" class="form-control input-sm"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> entries</label></div></div><div class="col-sm-6"><div id="datatable-editable_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control input-sm" placeholder="" aria-controls="datatable-editable"></label></div></div></div><div class="row"><div class="col-sm-12"><table class="table table-striped dataTable no-footer" id="datatable-editable" role="grid" aria-describedby="datatable-editable_info">
                                        <thead>
                                        <tr role="row">
                                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-editable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="موتور جستجو: activate to sort column descending" style="width: 155px;">دسته </th>
                                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-editable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="موتور جستجو: activate to sort column descending" style="width: 155px;">نوع ملک</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable-editable" rowspan="1" colspan="1" aria-label="مرورگر: activate to sort column ascending" style="width: 327px;">آگهی گزار</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable-editable" rowspan="1" colspan="1" aria-label="سیستن عامل: activate to sort column ascending" style="width: 201px;">شماره تماس</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable-editable" rowspan="1" colspan="1" aria-label="سیستن عامل: activate to sort column ascending" style="width: 201px;">منطقه</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable-editable" rowspan="1" colspan="1" aria-label="سیستن عامل: activate to sort column ascending" style="width: 201px;">متراژ</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable-editable" rowspan="1" colspan="1" aria-label="سیستن عامل: activate to sort column ascending" style="width: 201px;">قیمت کل/رهن</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable-editable" rowspan="1" colspan="1" aria-label="سیستن عامل: activate to sort column ascending" style="width: 201px;">قیمت متری/اجاره</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="فعالیت" style="width: 98px;">فعالیت</th></tr>
                                        </thead>
                                        <tbody>
                                        @foreach($estates as $estate)
                                            <tr class="gradeX odd" role="row">
                                                <td class="sorting_1">
                                                    @switch($estate->category)
                                                        @case('rent')
                                                        <span>رهن و اجاره</span>
                                                        @break

                                                        @case('sell')
                                                        <span>فروش</span>

                                                        @break
                                                        @case('presell')
                                                        <span>پیش خرید</span>
                                                        @break

                                                        @default
                                                        <span>نامشخص</span>
                                                    @endswitch
                                                </td>
                                                <td>
                                                {{$types[$estate->type_id]}}
                                                </td>
                                                <td>{{$estate->owner->name}}</td>
                                                <td>{{$estate->owner->phone}}</td>
                                                <td>{{$regions[$estate->region_id]}}</td>
                                                <td>{{$estate->area}}</td>
                                                <td>{{$estate->total_price}}</td>
                                                <td>{{$estate->price}}</td>
                                                <td class="actions">
                                                    <a href="#" class="hidden on-editing save-row"><i class="fa fa-save"></i></a>
                                                    <a href="#" class="hidden on-editing cancel-row"><i class="fa fa-times"></i></a>
                                                    <a href="#" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                                    <a href="#" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div></div><div class="row"><div class="col-sm-6"><div class="dataTables_info" id="datatable-editable_info" role="status" aria-live="polite">Showing 1 to 10 of 80 entries</div></div><div class="col-sm-6"><div class="dataTables_paginate paging_simple_numbers" id="datatable-editable_paginate"><ul class="pagination"><li class="paginate_button previous disabled" aria-controls="datatable-editable" tabindex="0" id="datatable-editable_previous"><a href="#">Previous</a></li><li class="paginate_button active" aria-controls="datatable-editable" tabindex="0"><a href="#">1</a></li><li class="paginate_button " aria-controls="datatable-editable" tabindex="0"><a href="#">2</a></li><li class="paginate_button " aria-controls="datatable-editable" tabindex="0"><a href="#">3</a></li><li class="paginate_button " aria-controls="datatable-editable" tabindex="0"><a href="#">4</a></li><li class="paginate_button " aria-controls="datatable-editable" tabindex="0"><a href="#">5</a></li><li class="paginate_button disabled" aria-controls="datatable-editable" tabindex="0" id="datatable-editable_ellipsis"><a href="#">…</a></li><li class="paginate_button " aria-controls="datatable-editable" tabindex="0"><a href="#">8</a></li><li class="paginate_button next" aria-controls="datatable-editable" tabindex="0" id="datatable-editable_next"><a href="#">Next</a></li></ul></div></div></div></div>
                    </div>
                </div>
                <!-- end: panel body -->

            </div> <!-- end panel -->
        </div> <!-- end col-->
    </div>@endsection
