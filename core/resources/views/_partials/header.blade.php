<div class="header ">
    <!-- START MOBILE SIDEBAR TOGGLE -->
    <a href="#" class="btn-link toggle-sidebar d-lg-none pg pg-menu" data-toggle="sidebar">
    </a>
    <!-- END MOBILE SIDEBAR TOGGLE -->
    <div class="">
        <div class="brand inline">
            <a href="{{route('cp')}}">
                <img src="/assets/img/logo.png" alt="logo" data-src="/assets/img/logo.png" data-src-retina="/assets/img/logo_2x.png" width="78" height="22">
            </a>
        </div>
    </div>
    @if(auth()->user())
        <div class="header-bar w-100 px-3">
            <!-- START User Info-->
            <div class="pull-right p-r-10 fs-14 font-heading d-lg-block d-none">
                @if(fn_has_login_as())
                    <span class="semi-bold back-to">
                        <a href="{{ route('cp.users.backTo') }}">{{ trans('auth::text.Return to main account') }}</a>
                    </span>
                @endif
            </div>
            <div class="pull-left p-r-10 fs-14 font-heading d-lg-block d-none">
                @include('core::_partials.breadcrumbs', ['breadcrumbs' => Breadcrumb::breadcrumbs()])
            </div>
            <div class="pull-left p-r-10 fs-14 font-heading d-lg-block d-none">
                {{ auth()->user()->name }} | {{ auth()->user()->email }}
            </div>
        </div>
    @endif
</div>
