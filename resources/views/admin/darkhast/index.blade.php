<div class="row">
    <div class="col-xs-12">
        <div class="table-wrap">
            <div class="table-responsive">
                <table class="table table-hover table-striped  table-bordered ">
                    <thead>
                    <tr>
                        <th>حذف</th>
                        <th>نام خانوادگی</th>
                        <th>شماره تماس</th>
                        <th>تاریخ درخواست</th>
                        <th>متن درخواست</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($darkhasts as $darkhast)
                        <tr>
                            <td><a class="" title="حذف"
                                   href="javascript:if(confirm('آیا شما از حذف درخواست {{ $darkhast->user->name }} مطمئنن هستید؟')) ajaxDelete('{{action('Admin\DarkhastController@destroy',$darkhast->id)}}','{{csrf_token()}}')">
                                    <i class="fa fa-trash-o  text-danger" style="font-size: 22px"></i>
                                </a></td>
                            <td> {{ $darkhast->user->name }}</td>
                            <td>{{ $darkhast->user->phone }}</td>
                            <td style="white-space: nowrap">{{ $darkhast->created_at }}</td>
                            <td>{{ $darkhast->category ==='sell' ? 'خرید ' : 'اجاره ' }}
                                {{$types[$darkhast->type_id]}}
                                 @foreach( $darkhast->region_ids as $region_id){{$regions[$region_id]}} @endforeach
                                به متراژ {{$darkhast->min_area}}
                                تا {{ $darkhast->max_area ? $darkhast->max_area : 'بیشترین' }} و
                                از @if($darkhast->category ==='sell') @if($darkhast->min_price) {{$darkhast->min_price}} @else
                                    کمترین قیمت @endif تا {{ $darkhast->max_price }} @endif
                                @if($darkhast->category === 'rent') @if($darkhast->min_mortgage) {{$darkhast->min_mortgage}} @else
                                    کمترین  @endif
                                تا {{ $darkhast->max_mortgage }}@endif  {{ $darkhast->category =='sell' ? 'تومان' : 'تومان اجاره' }}
                                {{$darkhast->category ==='rent' ? 'و از ' : ''}} {{$darkhast->category ==='rent' ? $darkhast->min_rent ? $darkhast->min_rent : 'کمترین ':''}} {{$darkhast->category ==='rent' ? ' تا '. $darkhast->max_rent. ' تومان رهن' : ''}}
                                {{($darkhast->room || $darkhast->elevator || $darkhast->parking) ? 'دارای':''}}
                                <ul class="comma-list">
                                    @if($darkhast->room ) <li> {{$darkhast->room}} اتاق </li> @endif
                                    @if($darkhast->elevator ) <li> آسانسور</li> @endif
                                    @if($darkhast->parking ) <li> پارکینگ</li> @endif
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <nav>
            <ul class="pagination justify-content-end">
                {{$darkhasts->links('vendor.pagination.bootstrap-4')}}
            </ul>
        </nav>
