@extends('admin.master')
{{--@section('breadcrumbs', Breadcrumbs::render('dashboard'))--}}
@section('title','تعریف فیلدها')
@section('content')
    <form method="POST" action="{{action('Admin\TypeController@storeTypeFields')}}">
        {{csrf_field()}}
        <div class="form-group col-md-3">
        هدف ملک :
        <select class="form-control"  name="category" id="">
            {{--<option value="">انتخاب کنید</option>--}}
                <option value='sell'>خرید</option>
                <option value='rent'>رهن و اجاره</option>
                <option value='filter'>فیلتر</option>
        </select>
        </div>
        <div class="form-group col-md-3">
        نوع آپارتمان :
        <select name="type" class="form-control"  id="">
            @foreach($types as $key=>$value)
                <option value={{$key}}>{{$value}}</option>
                @endforeach
        </select>
        </div>
        <div class="form-group col-md-3">
        نام فیلد :
        <select class="form-control" name="field" id="">
            <option value="">انتخاب کنید</option>
            @foreach($fields as $key=>$value)
                <option value={{$key}}>{{$value}}</option>
                @endforeach
        </select>
        </div>
        <div class="form-group col-md-3">
        ترتیب چینش :
            <input type="number" name="order"  value="0"class="form-control"/>
        </div>
        <div class="form-group col-md-12">
        <input type="submit" class="btn btn-primary pull-right " value="ایجاد کن">
        </div>
    </form>
    <div class=" col-md-12">

    <table class="table table-bordered">
            <thead>
            <tr>
                <th>هدف ملک</th>
                <th>عنوان ورودی</th>
                <th>نوع ملک </th>
                <th> ترتیب </th>
                <th>عملیات</th>
            </tr>
            </thead>
            <body>
            @foreach($concats as $concat)
                <tr>
                    <td>{{$concat->category=='rent' ? 'رهن و اجاره': 'فروش'}}</td>
                    <td>{{$concat->label}}</td>
                    <td>{{$concat->name}}</td>
                    <td>{{$concat->order}}</td>
                    <td>
                        <form method="POST" action="{{action('Admin\TypeController@destroy',['type_id'=>$concat->id , 'field_id'=>$concat->field_id])}}">
                            @method('DELETE')
                            @csrf()
                            <button style="background: none;border: 0;" class="delete " title="حذف"
                                    type="submit" >
                                <i class="fa fa-trash-o  text-danger" style="font-size: 22px"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </body>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).on('click','.delete',function(e){
            e.preventDefault() // Don't post the form, unless confirmed
            if (confirm('آیا شما مطمئن هستید?')) {
                // Post the form
                $(e.target).closest('form').submit() // Post the surrounding form
            }
        });
    </script>
    @endsection