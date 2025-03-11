@extends('layouts.header')

@section('main')
@push('title')
<title>{{ trans('messages.quantity_audit_lang', [], session('locale')) }}</title>
@endpush

<style>
    .form-head .add-staff {
        width: auto;
    }

    .search-area {
        max-width: 250px;
        width: 100%;
    }

    @media (max-width: 767px) {
        .form-head {
            flex-direction: column;
            align-items: flex-start;
        }

        .form-head .add-staff {
            width: 100%;
            margin-bottom: 10px;
        }

        .table-responsive {
            margin-top: 20px;
        }
    }
</style>

<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class=""><a href="javascript:void(0)">Dashboard/</a></li>
                <li class="active"><a href="javascript:void(0)">Quantity Audit</a></li>
            </ol>
        </div>
        <div class="form-head d-flex mb-3 mb-md-4 align-items-start flex-wrap">
            <div class="me-auto mb-3 mb-md-0">
                <h4>{{ trans('messages.quantity_audit_lang', [], session('locale')) }}</h4>

            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('qty_audit') }}" class="get_qty_audit" enctype="multipart/form-data">
                    @csrf
                    <div class="row d-flex align-items-end">
                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                            <div class="form-group">
                                <label>{{ trans('messages.start_date_lang', [], session('locale')) }}</label>
                                <input type="text" class="form-control start_date datetimepicker" value="{{ $start_date }}" name="start_date">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                            <div class="form-group">
                                <label>{{ trans('messages.end_date_lang', [], session('locale')) }}</label>
                                <input type="text" class="form-control end_date datetimepicker" value="{{ $end_date }}" name="end_date">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                            <div class="form-group">
                                <label>{{ trans('messages.products_lang', [], session('locale')) }}</label>
                                <select class="searchable_select select2 product_id form-control" name="product_id">
                                    <option value="">{{ trans('messages.choose_lang', [], session('locale')) }}</option>
                                    @foreach ($product as $pro)
                                        <option value="{{ $pro->id }}" {{ $pro->id == $product_id ? 'selected' : '' }}>
                                            {{ $pro->product_name }}-{{ $pro->barcode }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-2 text-center" style="margin-bottom: 2.2rem !important;">
                            <button type="submit" class="btn btn-primary btn-submit submit_form report_btn">
                                {{ trans('messages.submit_lang', [], session('locale')) }}
                            </button>
                        </div>

                    </div>
                </form>

                <div class="table-responsive">
                    <table id="all_qty_audit" class="table table-striped patient-list mb-4 dataTablesCard fs-14">
                        <thead>
                            <tr>
                                <th>order/purchase</th>
                                <th>title</th>
                                <th>barcode</th>
                                <th>previous_quantity</th>
                                <th>given_quantity</th>
                                <th>current_quantity</th>
                                <th>source</th>
                                <th>reason</th>
                                <th>created_by</th>
                                <th>add_date</th>
                                {{-- <th class="d-none"></th> --}}
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

@include('layouts.footer')
@endsection
