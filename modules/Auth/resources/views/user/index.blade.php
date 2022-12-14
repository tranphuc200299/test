@extends('auth::layout')
@section('content')
    <div class=" container-fluid bg-white">
        @include('auth::user._partials.filter')
        <div class="card card-transparent pt-2">
            @include('core::_messages.flash')
            <div class="">
                <div class="row bold">
                    <div class="col-12">
                        @can('create', Modules\Auth\Entities\Models\User::class)
                            <a href="{{ route('cp.users.create') }}" class="pull-right">
                                <button type="button" class="btn btn-success btn-xs">{{ trans('auth::user.create') }} <i
                                            class="fa fa-plus-circle"></i></button>
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mw-1500">
                        <thead>
                            <tr>
                                {!!  Html::renderHeader(
                                 [
                                 'id' => ['name' => trans('core::common.No'), 'style' => 'width: 80px'],
                                 'name' => ['name' => trans('auth::user.name'), 'sortable' => true],
                                 'email' => ['name' => trans('core::common.email'), 'sortable' => true],
                                 'created_at' => ['name' => trans('core::common.created at'), 'sortable' => true],
                                 'action' => ['name' => '', 'sortable' => false, 'style' => "width: 270px"],
                                 ],'id', route(Route::currentRouteName()), false)  !!}
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $key => $user)
                            <tr>
                                <td class="v-align-middle text-center">
                                    {{($users->currentpage()-1)*$users->perpage()+ $key + 1}}
                                    @can('loginAs', $user)
                                        <a href="{{ route('cp.users.loginAs', $user->id) }}"><i
                                                    class="fa fa-external-link" aria-hidden="true"></i></a>
                                    @endcan
                                </td>
                                <td class="v-align-middle  text-center">{{ $user->name }}</td>
                                <td class="v-align-middle">{{ $user->email }}</td>
                                <td class="v-align-middle  text-center">{{ $user->created_at }}</td>
                                <td class="v-align-middle  text-center">
                                    @can('update', $user)
                                        <a class="btn btn-primary btn-xs"
                                           href="{{ route('cp.users.edit', [$user->id]) }}">
                                            <i class="fa fa-pencil"></i>
                                            {{ trans('core::common.edit') }}
                                        </a>
                                    @endcan
                                    @can('delete', $user)
                                        <form action="{{route('cp.users.destroy', $user->id)}}"
                                              class="d-inline" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="btn btn-danger btn-xs" type="submit"
                                                    data-toggle="confirmation"
                                                    data-original-title="{{ trans('core::message.delete message confirmed') }}">
                                                <i class="fa fa-remove"></i> {{ trans('core::common.delete') }}
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-5">
                        <nav class="mt-3">
                            @include('core::_pagination.counting', ['paginator' => $users])
                        </nav>
                    </div>
                    <div class="col-xs-12 col-sm-7">
                        <nav class="mt-3">
                            @if(!empty($users))
                                {{ $users->appends(request()->input())->links() }}
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- END card -->
    </div>
@endsection
@push('custom-scripts')
@endpush