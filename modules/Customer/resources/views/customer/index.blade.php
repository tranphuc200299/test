@extends('customer::layout')
@section('title', trans('customer::text.customer management'))
@section('content')
    <div class="container-fluid bg-white">
        @include('customer::customer._partials.filter')
        <div class="card card-transparent pt-2">
            @include('core::_messages.flash')
            <div class="">
                <div class="d-flex justify-content-end">
                    <div class="">
                        <a href="{{ route('cp.customers.export') . str_replace('/cp/customers', '', request()->getRequestUri()) }}"
                           class="pull-right csv-export-customer">
                            <button type="button" class="btn btn-success btn btn-secondary">
                                {{trans('log::text.csv')}}
                            </button>
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="basicTable">
                        @if(!empty($list) && count($list) > 0)
                            <div class="col-xs-12 col-sm-5 mb-2">
                                <nav class="mt-3">
                                    @include('core::_pagination.counting', ['paginator' => $list])
                                </nav>
                            </div>
                        @else
                            <div class="col-xs-12 col-sm-12 mt-2">
                                <div class="text-center top-20 pull-left mb-2">
                                    {{ trans('core::message.paging.No corresponding record') }}
                                </div>
                            </div>
                        @endif

                        <thead>
                        <tr>
                            {!!  Html::renderHeader(
                             [
                             '' => ['name' => '<input type="checkbox" id="checkAll" > ', 'style' => 'width: 80px'],
                             'stt' => ['name' => trans('log::text.stt'), 'style' => 'width: 80px'],
                             'id' => ['name' => 'ID'],
                             'gender' => ['name' => trans('log::text.gender')],
                             'age' => ['name' => trans('log::text.age')],
                             ],'id', route(Route::currentRouteName()), false)  !!}
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $k => $log)
                            <tr>
                                <td class="v-align-middle text-center">
                                    <input type="checkbox" name="deleteItem" id="checkBox_delete" value="{{$log->id}}">
                                </td>
                                <td class="v-align-middle text-center">{{($list->currentpage()-1)*$list->perpage()+ $k + 1}}</td>
                                <td class="v-align-middle text-center">ID{{$log->id}}</td>
                                <td class="v-align-middle text-center">{{$log->gender ==  'Male' ? '男性' : '女性'}}</td>
                                <td class="v-align-middle text-center">{{$log->age}}</td>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    @if(!empty($list) && count($list) > 0)
                        <div class="col-xs-12 col-sm-5">
                            <nav class="mt-3">
{{--                                @include('core::_pagination.counting', ['paginator' => $list])--}}
                            </nav>
                        </div>
                    @else
                        <div class="col-xs-12 col-sm-12 mt-2">
                            <div class="text-center top-20 pull-left">
{{--                                {{ trans('core::message.paging.No corresponding record') }}--}}
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-12 col-sm-7">
                        <nav class="mt-3">
                            @if(!empty($list))
                                {{ $list->appends($_GET)->links('vendor.pagination.custom') }}
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
    <script>
        $(function () {

            setTimeout(function() {
                $('.alert-danger').fadeOut(2000);
            }, 2000);

            $("input[name='deleteItem']").change(function () {
                let $this = $(this);
                if ($("input[name='deleteItem']").is(":checked")) {
                    $('.btn-delete-list').removeAttr('disabled');
                } else {
                    $('.btn-delete-list').attr('disabled', 'disabled');
                }
            });
            //handel delete checkbox log
            $('#delete-customer').on('click', function (e) {
                e.preventDefault();
                let dataId = [];
                $("input[name='deleteItem']").each(function () {
                    let $this = $(this);
                    if ($this.is(":checked")) {
                        dataId.push($this.val());
                    }
                });
                Swal.fire({
                    text: `${dataId.length}件が選択されています。削除する際にユーザーデータも削除されます。削除してもよろしいですか。。`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'はい',
                    cancelButtonText: 'いいえ'
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            "url": '{{ route('cp.customers.destroy') }}',
                            "method": "POST",
                            data: {
                                id: dataId,
                            },
                            success: function (data) {
                                console.log(data)
                                if (data.code == 200)
                                {
                                    Swal.fire(
                                        {
                                            type: 'success',
                                            allowOutsideClick: false,
                                            text: `${data.message}`,
                                        }

                                    ).then((result) => {
                                        if (result.value) {
                                            location.reload();
                                        }
                                    })
                                }
                            }, error: function (error) {
                                console.log(error);
                            }
                        });
                    }
                })
            })
            // delete all button
            $(document).on('click', '#delete-cutomer-button', function (e) {
                e.preventDefault();
                let dataId = [];
                Swal.fire({
                    text: `全件が削除され、IDがリセットされます。削除してもよろしいですか。`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'はい',
                    cancelButtonText: 'いいえ'
                }).then((result) => {
                    if (result.value) {
                        $('#deleteCustomer').submit();
                    }
                })
            })


            $('.show-image').on('click', function (e) {
                e.preventDefault()
                let image = $(this).attr('data-image');
                Swal.fire({
                    imageUrl: `${image}`,
                    imageHeight: '100%',
                    imageAlt: 'Image Logs',
                    showCloseButton: true,
                    showConfirmButton: false
                })
            })
            //handler for select all change
            $('#checkAll').change(function () {
                $("input[name='deleteItem']").prop('checked', $(this).prop('checked'));
                if ($('#checkAll').is(":checked")) {
                    $('.btn-delete-list').removeAttr('disabled');
                } else {
                    $('.btn-delete-list').attr('disabled', 'disabled');
                }
            })
            //handler for all checkboxes to refect selectAll status
            $("input[name='deleteItem']").change(function () {
                $("#checkAll").prop('checked', true)
                $("input[name='deleteItem']").each(function () {
                    if (!$(this).prop('checked')) {
                        $("#checkAll").prop('checked', $(this).prop('checked'));
                    }
                })
            })

            $(document).on('keypress', '.filter_age', function (event) {
                if (((event.which != 46 || (event.which == 46 && $(this).val() == '')) ||
                    $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
            });

            $('.csv-export-customer').on('click', function (e) {
                setTimeout(function () {
                    Swal.fire({
                        type: 'success',
                        text: 'ファイルが正常に抽出されました。',
                        showConfirmButton: false,
                        timer: 2000
                    })
                }, 500);
            });

        });

    </script>
@endpush
