@extends('core::layout.management.management')
@section('body-inside')
    <div class="container body">
        <div class="main_container">
            @include('core::_partials.sidebar')
            @include('core::_partials.top_nav')
            <!-- page content -->
            <div class="right_col" role="main">
            </div>
            <!-- /page content -->
            @include('core::_partials.footer')
        </div>
    </div>
@endsection