<div class="row">
    <div class="col-xs-12 col-md-9">
        <br/>
        @if(session()->has('error'))
            <div class="alert alert-danger mb-30">
                {{ session()->get('error') }}
            </div>
        @endif
        @if(session()->has('message'))
            <div class="alert alert-success mb-30">
                {{ session()->get('message') }}
            </div>

        @endif
        <a href='{{action('Admin\UserController@create')}}'
           class="btn btn-xs btn-primary btn-rounded btn-lable-wrap right-label mb-30">
            <span class="btn-text">ایجاد ادمین / کارشناس </span>
            <span class="btn-label btn-label-sm"><i class="fa fa-plus-circle"></i> </span>
        </a>

        @php($i=1)
        <div class="table-wrap">
            <div class="table-responsive">
                <table class="table table-hover table-striped  table-bordered ">
                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام خانوادگی</th>
                        <th>نام کاربری</th>
                        <th>شماره تماس</th>
                        <th> ایمیل</th>
                        <th>نقش</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{$i}}</td>
                            <td> {{ $user->name }}</td>
                            <td> {{ $user->username }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{$user->role==='expert' ? 'کارشناس' : 'ادمین'}}</td>
                            <td>
                                <a href="{{ action('Admin\UserController@edit' , $user) }}"
                                   class="fa fa-pencil text-primary">
                                </a>
                                <a class="" title="تغییر رمز عبور"
                                   href="{{action('Admin\UserController@showChangePasswordForm',$user)}}">
                                    <i class="fa fa-key  text-warning" style="font-size: 22px"></i>
                                </a>
                                <a class="" title="حذف"
                                   href="javascript:if(confirm('آیا شما از حذف  {{ $user->name }} مطمئنن هستید؟')) ajaxDelete('{{action('Admin\UserController@destroy',$user)}}','{{csrf_token()}}')">
                                    <i class="fa fa-trash-o  text-danger" style="font-size: 22px"></i>
                                </a>
                            </td>
                        </tr>
                        @php($i++)
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <nav>
            <ul class="pagination justify-content-end">
                {{$users->links('vendor.pagination.bootstrap-4')}}
            </ul>
        </nav>
    </div>
</div>