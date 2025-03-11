@extends('layouts.header')

@section('main')
@push('title')
<title>{{ trans('messages.orders_lang', [], session('locale')) }}</title>
@endpush
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">

                        <h4> {{ trans('messages.orders_list_lang', [], session('locale')) }}</h4>
                        <h6>{{ trans('messages.search_orders_lang', [], session('locale')) }}</h6>
                    </div>

                </div>


                <!-- /product list -->
                <div class="card">
                    <div class="card-body">
                        @if(Session::has('success'))
                            <div id="success-message" class="alert alert-success">
                                {{ Session::get('success') }}
                            </div>
                        @endif

                        @if(Session::has('error'))
                            <div id="error-message" class="alert alert-danger">
                                {{ Session::get('error') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="all_order" class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ trans('messages.order_no_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.customer_name_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.total_amount_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.discount_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.paid_amount_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.remaining_amount_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.date',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.action_lang', [], session('locale')) }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('layouts.footer')
@endsection




