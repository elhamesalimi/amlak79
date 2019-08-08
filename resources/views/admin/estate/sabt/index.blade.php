<div class="row">
    <div class="col-sm-6 col-xs-12">
        <a href='{{ action('Admin\EstateController@create','sell')}}'
           class="btn btn-xs btn-primary btn-rounded btn-lable-wrap right-label mb-30">
            <span class="btn-text">ثبت ملک جدید برای فروش</span>
            <span class="btn-label btn-label-sm"><i class="fa fa-plus-circle"></i> </span>
        </a>
    </div>
    <div class="col-sm-6 col-xs-12">
        <a href='{{ action('Admin\EstateController@create','rent')}}'
           class="btn btn-xs btn-warning btn-rounded btn-lable-wrap right-label mb-30">
            <span class="btn-text">ثبت ملک جدید برای اجاره</span>
            <span class="btn-label btn-label-sm"><i class="fa fa-plus-circle"></i> </span>
        </a>
    </div>
    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
    <div class="col-sm-6 form-group">
        <div class="input-group ">
            <div class="input-group-btn">
                <button type="submit" class="btn btn-warning search"
                        onclick="ajaxLoad('{{action('Admin\EstateController@index')}}?code='+$('#code').val())">
                    جستجو
                </button>
            </div>
            <input class="form-control" id="code"
                   value="{{ request()->session()->get('code') }}"
                   onkeydown="if (event.keyCode == 13) ajaxLoad('{{action('Admin\EstateController@index')}}?code='+this.value)"
                   placeholder=" بر اساس کد ملک" name="code"
                   type="text"/>
        </div>
    </div>
    <div class="col-sm-6 form-group">
        <div class="input-group ">
            <div class="input-group-btn">
                <button type="submit" class="btn btn-warning search"
                        onclick="ajaxLoad('{{action('Admin\EstateController@index')}}?search='+$('#search').val())">
                    جستجو
                </button>
            </div>
            <input class="form-control" id="search"
                   value="{{ request()->session()->get('search') }}"
                   onkeydown="if (event.keyCode == 13) ajaxLoad('{{action('Admin\EstateController@index')}}?search='+this.value)"
                   placeholder="بر اساس عنوان /تلفن /نام مالک" name="search"
                   type="text"/>
        </div>
    </div>
    @if(session()->get('type')==='draft' && count($estates) >0)
        <div class="col-sm-6 col-xs-12">
            <a href="javascript:if(confirm('آیا شما از پاک کردن زباله دان مطمئنن هستید؟')) ajaxDelete('{{ action('Admin\EstateController@emptyTrash') }}','{{csrf_token()}}')"
               class="btn btn-xs btn-danger btn-rounded btn-lable-wrap right-label mb-30">
                <span class="btn-text">پاک کردن زباله دان</span>
                <span class="btn-label btn-label-sm"><i class="fa fa-trash-o"></i> </span>
            </a>

        </div>
    @endif
    <div class="col-sm-12">
        <div class="col-sm-2 col-xs-6 p1">
            <a href="javascript:ajaxLoad('{{url('/admin/estates?type='.('all'))}}')"
               class="btn btn-default btn-block {{session()->get('type')==='all'?'btn-outline':''}} ">همه
                ملک ها</a>
        </div>
        <div class="col-sm-2 col-xs-6 p1">
            <a href="javascript:ajaxLoad('{{url('/admin/estates?type='.('published'))}}')"
               class="btn btn-success btn-block {{session()->get('type')==='published'?'btn-outline':''}}">منتشر شده</a>
        </div>
        <div class="col-sm-2 col-xs-6 p1 ">
            <a href="javascript:ajaxLoad('{{url('/admin/estates?type='.('waiting'))}}')"
               class="btn btn-info btn-block {{session()->get('type')==='waiting'?'btn-outline':''}}">در
                انتظار بررسی</a>
        </div>
        <div class="col-sm-2 col-xs-6 p1 ">
            <a href="javascript:ajaxLoad('{{url('/admin/estates?type='.('pending'))}}')"
               class="btn btn-primary btn-block {{session()->get('type')==='pending'?'btn-outline':''}}">در حال
                انتظار</a>
        </div>
        <div class="col-sm-1 col-xs-3 p1">
            <a href="javascript:ajaxLoad('{{url('/admin/estates?type='.('failed'))}}')"
               class="btn btn-success btn-block {{session()->get('type')==='failed'?'btn-outline':''}}">باطل</a>
        </div>
        <div class=" col-sm-1 col-xs-3 p1">
            <a href="javascript:ajaxLoad('{{url('/admin/estates?type='.('draft'))}}')"
               class="btn btn-danger btn-block {{session()->get('type')==='draft'?'btn-outline':''}}"
               style="padding:10px">زباله
            </a>
        </div>
        <div class="col-sm-1 col-xs-3 p1">
            <a href="javascript:ajaxLoad('{{url('/admin/estates?type='.('hidden'))}}')"
               class="btn btn-warning btn-block {{session()->get('type')==='hidden'?'btn-outline':''}}"
               style="padding:10px">مخفی</a>
        </div>
        <div class="col-sm-1 col-xs-3 p1">
            <a href="javascript:ajaxLoad('{{url('/admin/estates?type='.('removed'))}}')"
               class="btn btn-danger btn-block {{session()->get('type')==='removed'?'btn-outline':''}}">م حذف</a>
        </div>

    </div>
</div>
<div class="table-wrap">
    <div class="table-responsive">
        <table class="table table-hover  fit table-bordered  display  pb-30">
            <thead>
            <tr style="background: #faf3f3;">
                <th>
                    <input type="checkbox" id="selectAll" >
                </th>
                <th>عملیات</th>
                <th>
                    <a href="javascript:ajaxLoad('{{url('/admin/estates?field_title=id&sort='.(request()->session()->get('sort')=='asc'?'desc':'asc'))}}')">
                        <i class="{{request()->session()->get('field_title')=='id'?(request()->session()->get('sort')=='asc'?'fa fa-sort-numeric-asc':'fa fa-sort-numeric-desc'):''}}"></i>
                        کد ملک
                    </a>
                </th>
                <th>
                    تاریخ ثبت
                </th>
                <th>
                    <a href="javascript:ajaxLoad('{{url('/admin/estates?field_title=title&sort='.(request()->session()->get('sort')=='asc'?'desc':'asc'))}}')">
                        <i class="{{request()->session()->get('field_title')=='title'?(request()->session()->get('sort')=='asc'?'fa fa-sort-alpha-asc':'fa fa-sort-alpha-desc'):''}}"></i>
                        عنوان آگهی</a>
                </th>
                <th>آگهی گزار</th>
                <th>شماره تماس</th>
                <th>
                    <a href="javascript:ajaxLoad('{{url('/admin/estates?field_title=total_price&sort='.(request()->session()->get('sort')=='asc'?'desc':'asc'))}}')">
                        <i class="{{request()->session()->get('field_title')=='total_price'?(request()->session()->get('sort')=='asc'?'fa fa-sort-amount-asc':'fa fa-sort-amount-desc'):''}}"></i>
                        قیمت کل / مبلغ رهن</a>
                </th>
                <th>
                    <a href="javascript:ajaxLoad('{{url('/admin/estates?field_title=price&sort='.(request()->session()->get('sort')=='asc'?'desc':'asc'))}}')">
                        <i class="{{request()->session()->get('field_title')=='price'?(request()->session()->get('sort')=='asc'?'fa fa-sort-amount-asc':'fa fa-sort-amount-desc'):''}}"></i>
                        متری / مبلغ اجاره</a></th>
                <th>بازدید</th>

            </tr>
            </thead>
            <tbody>
            @foreach($estates as $estate)
                <tr class="@switch($estate->status)@case('PUBLISHED') bg-success @break @case('PENDING') bg-info @break @case('WAITING') bg-warning @break @case('REMOVED') bg-danger @break @endswitch"
                    title="@switch($estate->status)@case('PUBLISHED')منتشر شده @break @case('PENDING')در حال انتظار @break @case('WAITING')منتظر بررسی @break @case('REMOVED')منتظر حذف @break @case('HIDDEN') مخفی@break @case('FAILED')باطله @break @case('DRAFT') زباله دان@break @endswitch">
                    <td><input type="checkbox" name="remove[]" class="idsChk" value="{{$estate->id}}"></td>
                    <td style="padding:0px">
                        <a href="{{url('/estate',['id'=>$estate->id])}}" title="نمایش"
                           class="fa fa-eye text-success" style="padding: 4px;font-size: 2em">
                        </a>
                        <a href="{{ action('Admin\EstateController@edit' , $estate->id) }}" title="ویرایش"
                           class="fa fa-pencil text-primary" style="padding: 4px;font-size: 2em">
                        </a>
                        <a class="fa fa-times text-danger" title="حذف" style="padding: 4px;font-size: 2em"
                           href="javascript:if(confirm('آیا شما از حذف {{ $estate->title }} مطمئنن هستید؟')) ajaxDelete('{{ action('Admin\EstateController@destroy' , $estate->id) }}','{{csrf_token()}}')">
                        </a>
                    </td>
                    <td>{{ $estate->id }}</td>
                    <td style="direction:ltr">{{jdate($estate->created_at)->format('Y-m-d H:i')}}</td>
                    <td>
                        <a href="{{url('/estate',$estate->id)}}"> {{ $estate->title }}</a></td>
                    <td>{{$estate->owner->name}}</td>
                    <td>{{$estate->owner->phone}}</td>
                    <td>{{$estate->total_price}}</td>
                    <td>{{$estate->price}}</td>
                    <td>{{ $estate->view_count }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="input-group col-xs-12 col-sm-6 col-md-3">
        <select name="actions" id="actionsSlct" class="form-control">
            <option value="">انتخاب کنید</option>
            @if(session()->get('type')==='draft')
                <option value="DELETE">حذف</option>
            @else
                <option value="DRAFT">زباله دان</option>
            @endif
            <option value="PUBLISHED">انتشار</option>
        </select>
        <div class="input-group-btn">
            <button id="removeAllBtn" class="btn btn-default" type="submit"><i
                        class="glyphicon glyphicon-check"></i></button>
        </div>
    </div>
    {{$estates->links('vendor.pagination.bootstrap-4')}}
</div>
