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
        <div class="panel-heading">Vocabularies</div>
        <div class="panel-body">
    <a href="javascript:ajaxLoad('{{ action('Admin\VocabularyController@create')}}','create')"
                                   class="btn btn-xs btn-primary btn-rounded btn-lable-wrap right-label mb-30">
        <span class="btn-text">add new Vocab</span>
        <span class="btn-label btn-label-sm"><i class="fa fa-plus-circle"></i> </span></a>
            @if (session('delete-message'))
                <div class="alert alert-success">
                    {{ session('delete-message') }}
                </div>
            @endif
            <div class="card-box">

                <h4 class="header-title m-t-0 m-b-30"><i class="fa fa-vimeo" style="
    margin-left: 10px;
"></i>لیست لغت ها</h4>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>لغت</th>
                            <th>ترجمه </th>
                            <th>دسته</th>
                            <th>عملیات</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($vocabularies as $vocabulary)
                        <tr>
                            <td>1</td>
                            <td>{{$vocabulary->vocab}}</td>
                            <td>{{$vocabulary->trans}}</td>

                            <td>{{$vocabulary->vocat->name}}</td>
                            <td>
                                <a href="javascript:ajaxLoad('{{ action('Admin\VocabularyController@edit',$vocabulary) }}','create')"><span
                                            class="glyphicon glyphicon-edit text-primary"></span></a>
                                <a data-toggle="modal" href="#myMoldal{{$vocabulary->id}}"><span class="glyphicon glyphicon-trash text-danger"></span></a>
                                <!-- Modal -->
                                <div class="modal fade modal-sm " id="myMoldal{{$vocabulary->id}}" role="dialog">
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
                                                <form action="{{ action('Admin\VocabularyController@destroy',$vocabulary) }}" id="frm" method="POST">
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
                            </td>
                        </tr>
                      @endforeach
                        </tbody>
                    </table>
                    {{$vocabularies->links('admin.vendor.pagination.bootstrap-4')}}

                </div>
            </div>
        </div>
    </div>
</div>


