<div class="fixed-sidebar-left">
    <ul class="nav navbar-nav side-nav nicescroll-bar">
        <li class="navigation-header">
            <span>اصلی</span>
        </li>
        <li>
            <a href="{{ action ('Admin\EstateController@index') }}">
                <div class="pull-left"><i class="fa fa-dashboard mr-20"></i><span
                            class="right-nav-text">{{trans('admin.dashboard')}}</span></div>
                <div class="clearfix"></div>
            </a>
        </li>
        <li>
            <hr class="light-grey-hr mb-10"/>
        </li>

        {{--        @can('create_article')--}}
        <li>
        {{--<a href="javascript:void(0);" data-toggle="collapse" data-target="#maps_dr" class="collapsed"--}}
        {{--aria-expanded="false">--}}
        {{--<div class="pull-left">--}}
        {{--<i class="fa fa-files-o mr-20"></i><span class="right-nav-text">ملک های ثبت شده </span></div>--}}
        {{--<div class="pull-right"><i class="fa fa-sort-down"></i></div>--}}
        {{--<div class="clearfix"></div>--}}
        {{--</a>--}}
        {{--<ul id="maps_dr" class="collapse-level-1 collapse" aria-expanded="false" style=" margin: 0;--}}
        {{--padding: 0;--}}
        {{--border: 0;--}}
        {{--vertical-align: baseline;">--}}

        <li>
            <a href="{{action('Admin\EstateController@index')}}"><i class="fa fa-list mr-20"></i> ملک ها</a>
        </li>
        {{--</ul>--}}
        <li>
            <a href="javascript:void(0);" data-toggle="collapse" data-target="#maps_sabt" class="collapsed"
               aria-expanded="false">
                <div class="pull-left">
                    <i class="fa fa-files-o mr-20"></i><span class="right-nav-text">ثبت ملک</span></div>
                <div class="pull-right"><i class="fa fa-sort-down"></i></div>
                <div class="clearfix"></div>
            </a>
            <ul id="maps_sabt" class="collapse-level-1 collapse" aria-expanded="false" style=" margin: 0;
padding: 0;
border: 0;
vertical-align: baseline;">

                <li>
                    <a href="{{action('Admin\EstateController@create' , 'rent')}}">اجاره</a>
                </li>
                <li>
                    <a href="{{action('Admin\EstateController@create' , 'sell')}}"> خرید</a>
                </li>

            </ul>

        </li>
        <li>
            <a href="{{action('Admin\DarkhastController@index')}}"><i class="fa fa-eye mr-20"></i>درخواست ها</a>
        </li>
        @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === 'super_admin')
            <li>
                <a href="javascript:void(0);" data-toggle="collapse" data-target="#maps_expert" class="collapsed"
                   aria-expanded="false">
                    <div class="pull-left">
                        <i class="fa fa-users mr-20"></i><span class="right-nav-text"> کاربران</span></div>
                    <div class="pull-right"><i class="fa fa-sort-down"></i></div>
                    <div class="clearfix"></div>
                </a>
                <ul id="maps_expert" class="collapse-level-1 collapse" aria-expanded="false" style=" margin: 0;
padding: 0;
border: 0;
vertical-align: baseline;">

                    <li>
                        <a href="{{action('Admin\UserController@index')}}">لیست کاربران</a>
                    </li>
                    <li>
                        <a href="{{action('Admin\UserController@create')}}"> تعریف کاربر جدید</a>
                    </li>

                </ul>

            </li>
            <li>
                <a href="{{action('Admin\TypeController@createTypeFields')}}"><i class="fa fa-file-text mr-20"></i>
                    تعریف فیلدها</a>
            </li>
            <li>
                <a href="{{action('Admin\RegionController@index')}}"><i class="fa fa-file-text mr-20"></i> تعریف منطقه
                    ها</a>
            </li>
        @endif
        <li>
            <a href="{{action('Admin\BugController@index')}}"><i class="fa fa-bullhorn mr-20"></i>گزارش اشکالات </a>
        </li>
        <li>
            <a href="{{action('Admin\ReportController@references')}}"><i class="fa fa-bar-chart mr-20"></i>آمار </a>
        </li>
        <li>
            <form method="POST" action="{{action('Admin\Auth\LoginController@logout')}}">
                @csrf()
                <button class="link" type="submit"><i class="fa fa-sign-out mr-20"></i> خروج</button>
            </form>

        </li>
        </li>
        {{--@endcan--}}
    </ul>
</div>