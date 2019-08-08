{{--* User: salimi--}}
{{--* Date: 02/27/2019--}}
{{--* Time: 09:10 AM--}}
<style>
    .modal-backdrop.fade.in {
        display: none;
    }
</style>

<div class="col-sm-8 col-xs-12">
    <div class="panel panel-warning">
        <div class="panel-heading">دسته ها</div>
        <div class="panel-body" style="padding: 15px;">
            <a href="javascript:ajaxLoad('{{ action('Admin\RegionController@create')}}','create')"
               class="btn btn-xs btn-primary btn-rounded btn-lable-wrap right-label ">
                <span class="btn-text">ایجاد منطقه جدید</span>
                <span class="btn-label btn-label-sm"><i class="fa fa-plus-circle"></i> </span></a>
            @if (session('delete-message'))
                <div class="alert alert-success">
                    {{ session('delete-message') }}
                </div>
            @endif
            <ul style="margin:10px">
                @foreach($cities as $city)
                    <li><h4>{{$city->name}}</h4></li>
                    <ul style="margin: 18px;">
                        @foreach($city->regions as $region)
                            <li class=" ">{{$region->arrange}}-{{ $region->name }}
                                {{--edit ajax--}}
                                <a href="javascript:ajaxLoad('{{ action('Admin\RegionController@edit',['region'=>$region]) }}','create')"><span
                                            class="glyphicon glyphicon-edit text-primary"></span></a>
                                @if($city->regions->count() > 1)
                                    {{--delete ajax--}}
                                    <a data-toggle="modal" href="#myMoldal{{$region->id}}"><span
                                                class="glyphicon glyphicon-trash text-danger"></span></a>
                                    <!-- Modal -->
                                    <div class="modal fade modal-sm " id="myMoldal{{$region->id}}" role="dialog">
                                        <div class="modal-content ">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h3 class="modal-title alert alert-danger">هشدار!</h3>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>آیا از حذف منطقه مطمئن هستید؟</strong></p>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="col-md-6">
                                                    <form action="{{ action('Admin\RegionController@destroy',['region'=>$region]) }}"
                                                          id="frm" method="POST">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="submit" class="btn btn-danger btn-lg" value="حذف">
                                                    </form>
                                                </div>
                                                <div class="col-md-6">
                                                    <button type="button" class="btn btn-default btn-lg"
                                                            data-dismiss="modal">انصراف
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                @endforeach
                {{--@each('admin.cities.regions.partials._category', $regions, 'category', 'admin.cities.regions.partials._empty-categories')--}}
            </ul>
        </div>
    </div>
</div>