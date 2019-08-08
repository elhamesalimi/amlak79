<li class=" @if(!$category->parent)h4 @endif">{{ $category->name }}
    {{--edit ajax--}}
    <a href="javascript:ajaxLoad('{{ action('Admin\RegionController@edit',['region'=>$category]) }}','create')"><span
                class="glyphicon glyphicon-edit text-primary"></span></a>

    {{--delete ajax--}}
    <a data-toggle="modal" href="#myMoldal{{$category->id}}"><span class="glyphicon glyphicon-trash text-danger"></span></a>
    <!-- Modal -->
    <div class="modal fade modal-sm " id="myMoldal{{$category->id}}" role="dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title alert alert-danger">هشدار!</h3>
            </div>
            <div class="modal-body">
                <p><strong>آیا از حذف دسته مطمئن هستید؟</strong></p>
                <p><b>نکته:</b>در صورت حذف یک دسته زیردسته های آن نیز حذف می شود.</p>
            </div>
            <div class="modal-footer">
                <div class="col-md-6">
                    <form action="{{ action('Admin\RegionController@destroy',['region'=>$category]) }}" id="frm" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="submit" class="btn btn-danger btn-lg" value="حذف">
                    </form>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">انصراف</button>
                </div>
            </div>
        </div>
    </div>
</li>
@if ($category->children->count() > 0)
    <ul>
        @foreach($category->children as $child)
            @include('admin.cities.regions.partials._category', ['category' => $child])
        @endforeach
    </ul>
@endif