<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="mobile-only-brand pull-left">
        <div class="nav-header pull-left">
            <div class="logo-wrap">
                <a href="{{url('/')}}">
                    <img class="brand-img" width="120"src="/asset/images/logo.png" alt="brand"/>
                </a>
            </div>
        </div>
        <a id="toggle_nav_btn" class="toggle-left-nav-btn inline-block ml-20 pull-left"
           href="javascript:void(0);"><i class="fa fa-bars"></i></a>
    </div>

    <div id="mobile_only_nav" class="mobile-only-nav pull-right">
        <ul class="nav navbar-right top-nav pull-right">
            @can('create_club')
            {{--<li class="dropdown alert-drp">--}}
                {{--<a href="{{ action('Admin\ClubController@clubNewIndex')}}"  title="باشگاهای جدید"><i class="fa fa-btc" style="font-size: 150%"></i>--}}
                    {{--<span class="top-nav-icon-badge" style="background: #40d25a">{{ $clubCount }}</span></a>--}}
            {{--</li>--}}
            @endcan
            <li class="dropdown auth-drp">
                <a href="#" class=" pr-0" data-toggle="dropdown">
                    <i class="fa fa-angle-down"></i>
                    <span>{{auth()->check() ? auth()->user()->username : ''}}</span>
                    <ul class="dropdown-menu user-auth-dropdown" data-dropdown-in="flipInX"
                        data-dropdown-out="flipOutX">
                        {{--<li>--}}
                            {{--<a href="{{ action('Admin\AdminController@edit') }}"><i class="fa fa-user"></i><span>{{ trans('admin.profile') }}</span></a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="{{ route('admin.logout') }}" onclick="event.preventDefault();   document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i><span>{{ trans('admin.logout') }}</span></a>--}}
                        {{--</li>--}}
                    </ul>
                </a>
            </li>
        </ul>
    </div>
    {{--<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">--}}
        {{--{{csrf_field()}}--}}
    {{--</form>--}}
</nav>

