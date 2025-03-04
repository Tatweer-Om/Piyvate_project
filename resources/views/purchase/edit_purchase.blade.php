@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.edit_purchase', [], session('locale')) }}</title>
    @endpush

    {{-- <div class="content-body">
        <div class="container mt-5">

            <!-- /add -->
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">{{ trans('messages.supplier_&_invoice_lang', [], session('locale')) }}</h5>
                    </div>
                    <form class="add_purchase_product" enctype="multipart/form-data" action="{{ route('update_purchase', $purchase_order->id) }}" method="POST">
                        @csrf
                        {{-- @method('PUT') --}}
                        {{-- <input type="hidden" value="{{ $purchase->total_product }}" class="total_product"> --}}
                        <div class="row ">
                            <div class="col-xl-3 ">
                                <div class="form-group">
                                    <label class="col-form-label">Invoice No:</label>
                                    <input type="text" class="form-control invoice_no" name="invoice_no" value="{{ $purchase_order->invoice_no }}">
                                    <span class="invoice_err text-danger small"></span>
                                </div>
                            </div>
                            <div class="col-xl-3">
                                <div class="form-group">
                                    <label class="col-form-label">Supplier:</label>
                                    <select class="supplier_id form-control" name="supplier_id_stk" id="supplier_id">
                                        <option value="">Choose</option>
                                        @foreach ($suppliers as $supp)
                                            <option value="{{ $supp->id }}" {{ $purchase_order->supplier_id == $supp->id ? 'selected' : '' }}>{{ $supp->supplier_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-3 ">
                                <div class="form-group">
                                    <label class="col-form-label">Purchase Date:</label>
                                    <input type="date" class="form-control purchase_date datetimepicker" value="{{ $purchase_order->purchase_date }}" name="purchase_date">
                                </div>
                            </div>

                            <div class="col-xl-3 ">
                                <div class="form-group">
                                    <label class="col-form-label">Invoice Price:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">OMR</span>
                                        <input type="text" class="form-control invoice_price isnumber" name="invoice_price" value="{{ $purchase_order->invoice_price }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Repeat similar steps for other fields -->

                        <div class="card-header">
                            <h5 class="card-title">Inventory_Detail</h5>
                        </div>
                        @foreach ($purchase_detail as $index => $stock)
                        <div class="stocks_class stock_no_{{ $index + 1 }}">
                            <div class="row p-2">
                                <div class="col-md-12">
                                    <h4>stock {{ $index + 1 }}</h4>
                                </div>
                            </div>

                            <div class="row p-2">
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <label class="col-form-label">stores:</label>
                                        <select class="store_id_{{ $index + 1 }} form-control select2" id="store_id_{{ $index + 1 }}" name="store_id_stk[]">
                                            <option value="">choose</option>
                                            @foreach ($stores as $store)
                                                <option value="{{ $store->id }}" {{ $stock->store_id == $store->id ? 'selected' : '' }}>{{ $store->branch_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Category:</label>
                                        <select class="category_id_{{ $index + 1 }} form-control select2" id="category_id_{{ $index + 1 }}" name="category_id_stk[]">
                                            <option value="">choose</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ $stock->category_id == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Product_name:</label>
                                        <input type="text" class="form-control product_name_{{ $index + 1 }}" name="product_name[]" value="{{ $stock->product_name }}">
                                    </div>
                                </div>

                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Barcode Generator:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control barcodes barcode_{{ $index + 1 }}" onkeyup="search_barcode('{{ $index + 1 }}')" onchange="search_barcode('{{ $index + 1 }}')" name="barcode[]" value="{{ $stock->barcode }}">
                                            <span class="input-group-text" onclick="get_rand_barcode({{ $index + 1 }})">
                                                <i class="fas fa-barcode"></i>
                                            </span>
                                        </div>
                                        <span class="barcode_err_{{ $index + 1 }} text-danger small"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Repeat similar steps for other stock fields -->

                        </div>
                        @endforeach

                        <div id="more_stk"></div>
                        <div class="row">
                            <div class="col-xl-12 d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">
                                    {{ trans('messages.close_lang', [], session('locale')) }}
                                </button>
                                <a id="add_more_stk_btn" class="btn btn-secondary">
                                    {{ trans('messages.add_stock_lang', [], session('locale')) }}
                                </a>
                                <button type="submit" class="btn btn-primary submit_form">
                                    {{ trans('messages.update_data_lang', [], session('locale')) }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /add -->
        </div>
    </div> --}}




@include('layouts.footer')
@endsection
