<!DOCTYPE html>
<?php
	$locale = session('locale');
	if($locale=="ar")
	{
		$dir="dir='rtl'";
	}
	else
	{
		$dir="dir='ltr'";
	}
?>
<html lang="en" <?php echo $dir; ?>>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS Page</title>


        <link rel="stylesheet" href="{{asset('css/pos_page/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">

    {{-- datapicker --}}
    <link href="{{ asset('vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css') }}"
        rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('vendor/toastr/css/toastr.min.css') }}">

    <!-- Animation CSS -->
        {{-- <link rel="stylesheet" href="{{ asset('css/rtl/animate.css') }}"> --}}
        <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">

    {{-- bootsrap 5 css --}}
    <link rel="stylesheet" href=" {{ asset('css/pos_page/dataTables.bootstrap5.min.css') }}">


    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('fonts/css/all.min.css') }}">

    {{-- datarange css --}}
    <link rel="stylesheet" href="{{ asset('css/pos_page/daterangepicker.css') }}">

    {{-- carousel css --}}
    <link rel="stylesheet" href="{{ asset('plugins/owlcarousel/owl.carousel.min.css') }}">

    {{-- theme default css --}}
    <link rel="stylesheet" href="{{ asset('css/pos_page/owl.theme.default.min.css') }}">

    {{-- toastr css --}}
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.css') }}">

    <!-- Select2 CSS -->
	<link rel="stylesheet" href="{{asset('css/select2.min.css')}}">

    {{-- toastr css --}}

        {{-- <link rel="stylesheet" href="{{asset('css/pos_page_rtl/style.css')}}"> --}}
        <link rel="stylesheet" href="{{asset('css/pos_page/style.css')}}">

    {{-- custom css --}}
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
    <link rel="stylesheet" href="{{ asset('vendor/toastr/css/toastr.min.css') }}">

<!-- Latest Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


    <!-- jQuery UI CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">

    {{-- style --}}
    <style>
        .order_list_table {
            width: 100%;
            border-collapse: collapse;
        }

        .order_list_table th, .order_list_table td {
            border: 1px solid #d3cccc5e;
            padding: 8px; /* Adjust padding as needed */
        }
    </style>
</head>

<body>
    <div id="global-loader" >
        <div id="preloader-img">
            <img src="{{asset('images/system_images/logo.png')}}" alt="Logo">
        </div>
    </div>

    <div class="main-wrapper">

        <div class="header">
            <a id="mobile_btn" class="mobile_btn d-none" href="#sidebar">
                <span class="bar-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </a>

            <ul class="nav user-menu">
                <li class="nav-item nav-searchinputs"></li>
                <li class="nav-item nav-item-box">
                    <div class="btn-row d-sm-flex align-items-center">
                        <a href="{{ route('logout') }}" class="btn btn-danger mb-xs-3"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        <a href="#" target="_blank" style="color: black; border:1px solid" class="btn btn-default mb-xs-3">Salesman: {{ $user->user_name }}</a>
                        <a href="#" target="_blank" style="color: black; border:1px solid" class="btn btn-default mb-xs-3"><i class="fas fa-calendar-week"></i> {{ date('Y-m-d') }}</a>
                        <a href="javascript:void(0);" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#return_modal"><i class="fas fa-undo"></i> Reset</a>
                        <a href="javascript:void(0);" class="btn btn-secondary mb-xs-3" data-bs-toggle="modal" data-bs-target="#orders"><i class="fas fa-shopping-cart"></i> View Orders</a>
                        <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary mb-xs-3"><i class="fas fa-shield-alt"></i> Home</a>
                    </div>
                </li>
                <li class="nav-item nav-item-box">
                    <a href="javascript:void(0);" id="btnFullscreen">
                        <i data-feather="maximize"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="page-wrapper pos-pg-wrapper ms-0">
            <div class="content pos-design p-0">
                <div class="row align-items-start pos-wrapper">
                    <div class="col-md-12 col-lg-12 ps-0">
                        <aside class="product-order-list">
                            <div class="customer-info block-section">
                                <div class="d-flex align-items-center">
                                    <div class="input-block ms-3">
                                        <input type="text" class="product_input form-control" placeholder="Enter IMEI/Barcode">
                                    </div>
                                    <div class="input-block">
                                        <input type="hidden" class="pos_customer_id form-control">
                                        <input type="text" class="add_customer form-control" id="customer_input_data" name="customer_id" placeholder="Enter Customer">
                                    </div>
                                    <div class="ms-3">
                                        <a href="javascript:void(0);" class="btn btn-primary btn-icon" data-bs-toggle="modal" data-bs-target="#add_customer_modal"><i data-feather="user-plus" class="feather-16"></i></a>
                                    </div>
                                    {{-- <div class="input-block ms-3" style="width: 95px">
                                        <p>Points</p>
                                    </div> --}}
                                    {{-- <div class="input-block ms-3">
                                        <input type="text" readonly class="customer_point form-control" placeholder="Points">
                                    </div> --}}
                                    {{-- <div class="input-block ms-3" style="width: 95px">
                                        <p>Current Offer</p>
                                    </div>
                                    <div class="input-block ms-3">
                                        <input type="text" readonly class="customer_offer form-control" placeholder="Current Offer">
                                    </div> --}}
                                </div>
                            </div>
                            <div class="product-added block-section">
                                <div class="head-text d-flex align-items-center justify-content-between">
                                    <h6 class="d-flex align-items-center mb-0">Total Quantity <span class="count">0</span></h6>
                                    <a href="javascript:void(0);" class="d-flex align-items-center text-danger" id="clear_list"><span class="me-1"><i data-feather="x" class="feather-16"></i></span>Clear All</a>
                                </div>
                                <div class="product-wrap">
                                    <table class="order_list_table">
                                        <thead>
                                            <tr>
                                                <th style="width:5%">Serial No</th>
                                                <th>Product Name</th>
                                                <th class="text-center" style="width:10%">Unit Price</th>
                                                <th class="text-center" style="width:10%">Quantity</th>
                                                <th class="text-center" style="width:10%">Total Price</th>
                                                <th class="text-center" style="width:10%">Discount</th>
                                                <th class="text-center" style="width:10%">Grand Total</th>
                                                <th class="text-center" style="width:10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="order_list"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="btn-row d-sm-flex align-items-center justify-content-between">
                                <table class="order_list_table" style="width:20%!important">
                                    <thead>
                                        <tr><th class="td_font">Total</th><th class="sub_total_show td_font"></th></tr>
                                        <tr><th class="td_font">Discount</th><th class="grand_discount_show td_font"></th></tr>
                                        <tr><th class="td_font">Tax</th><th class="total_tax_show td_font"></th></tr>
                                        <tr><th class="td_font">Grand Total</th><th class="grand_total_show td_font"></th></tr>
                                    </thead>
                                </table>
                                <div class="d-flex justify-content-end" style="width: 30%">
                                    <a href="javascript:void(0);" id="hold" class="btn btn-info btn-icon flex-fill me-2 submit_form" data-bs-toggle="modal" data-bs-target="#hold-order"><i data-feather="pause" class="feather-16"></i> Hold</a>
                                    <a href="javascript:void(0);" id="payment_modal_id" class="btn btn-success btn-icon flex-fill"><i data-feather="credit-card" class="feather-16"></i> Payment</a>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <div class="modal fade modal-default" id="payment-completed" aria-labelledby="payment-completed">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <form action="pos.html">
                        <div class="icon-head">
                            <a href="javascript:void(0);">
                                <i data-feather="check-circle" class="feather-40"></i>
                            </a>
                        </div>
                        <table class="table" style="width:100%">
                            <thead>
                                <tr>
                                    <td style="text-align: left"><h4>{{ trans('messages.grand_total_lang', [], session('locale')) }} </h4></td>
                                    <td><h4><span id="last_final_amount"></span></h4></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left"><h4>{{ trans('messages.paid_amount_lang', [], session('locale')) }} </h4></td>
                                    <td><h4><span id="last_paid_amount"></span></h4></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left"><h4>{{ trans('messages.cash_back_pos_lang', [], session('locale')) }} </h4></td>
                                    <td><h4><span id="last_cash_back"></span></h4></td>
                                </tr>
                            </thead>
                        </table>

                         <div class="modal-footer d-sm-flex justify-content-between">

                            <button type="button" id="next_order_btn" class="btn btn-secondary flex-fill">{{ trans('messages.next_order_lang', [], session('locale')) }} <i class="feather-arrow-right-circle icon-me-5"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>





    <div class="modal fade modal-default pos-modal" id="hold_order" aria-labelledby="create">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h5>SELECT IMEI</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" id="search_imei" class="form-control" onkeyup="searchImei()" placeholder="{{ trans('messages.imei_serial_no_lang',[],session('locale')) }}">
                        </div>
                    </div>
                    <br>
                    <div class="row" id="all_pro_imei"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade pos-modal" id="orders" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h5 class="modal-title">{{ trans('messages.orders_lang', [], session('locale')) }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="tabs-sets">
                        <ul class="nav nav-tabs" id="myTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="onhold-tab" data-bs-toggle="tab"
                                    data-bs-target="#onhold" type="button" aria-controls="onhold"
                                    aria-selected="true" role="tab">{{ trans('messages.onhold_lang', [], session('locale')) }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="paid-tab" data-bs-toggle="tab"
                                    data-bs-target="#paid" type="button" aria-controls="paid"
                                    aria-selected="false" role="tab">{{ trans('messages.paid_lang', [], session('locale')) }}</button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="onhold" role="tabpanel"
                                aria-labelledby="onhold-tab">

                                <div class="order-body" id= "hold_data">

                                </div>
                            </div>
                            <div class="tab-pane fade" id="paid" role="tabpanel">

                                <div class="order-body">
                                    <div class="default-cover p-4 mb-4">

                                        @foreach ($orders as $order )
                                        <div class="order-details" data-order-no="{{ $order->order_no }}">
                                            <span class="badge bg-secondary d-inline-block mb-4">{{ trans('messages.invoice_lang',[],session('locale')) }} : {{ $order->order_no }}</span>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6 record mb-3">
                                                    <table>
                                                        <tr class="mb-3">
                                                            <td>{{ trans('messages.cashier_lang',[],session('locale')) }}</td>
                                                            <td class="colon">:</td>
                                                            <td class="text">{{ $user->username }}</td>
                                                        </tr>
                                                        {{-- @php
                                                            $customer_name= DB::table('customers')->where('id', $order->customer_id)->value('customer_name');
                                                        @endphp --}}
                                                        <tr>
                                                            <td>{{ trans('messages.customer_name_lang',[],session('locale')) }}</td>
                                                            <td class="colon">:</td>
                                                            <td class="text">haseeb</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-sm-12 col-md-6 record mb-3">
                                                    <table>
                                                        <tr>
                                                            <td>{{ trans('messages.total_price_lang',[],session('locale')) }}</td>
                                                            <td class="colon">:</td>

                                                            <td class="text">{{ trans('messages.OMR_lang', [], session('locale')) }} {{ $order->total_amount }}</td>

                                                        </tr>
                                                        <tr>
                                                            <td>{{ trans('messages.add_date_lang',[],session('locale')) }}</td>
                                                            <td class="colon">:</td>
                                                            <td class="text">{{ $order->created_at->format('Y-m-d') }}</td>
                                                        </tr>
                                                    </table>
                                                </div>

                                            </div>
                                            {{-- <p class="p-4">Customer need to recheck the product once</p> --}}
                                            {{-- <div class="btn-row d-sm-flex align-items-center justify-content-between">

                                                <a href="{{ route('pos_bill', ['order_no' => $order->order_no]) }}" target="_blank" class="btn btn-success btn-icon flex-fill">{{ trans('messages.print_lang',[],session('locale')) }} </a>
                                                <a href="{{ route('a5_print', ['order_no' => $order->order_no]) }}" target="_blank" class="btn btn-success btn-icon flex-fill">{{ trans('messages.a4print_lang',[],session('locale')) }} </a>

                                            </div><br> --}}
                                        </div>
                                     @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade pos-modal" id="return_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h5 class="modal-title">{{ trans('messages.return_items_lang',[],session('locale')) }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-return-tab" data-bs-toggle="pill" href="#pills-return" role="tab" aria-controls="pills-return" aria-selected="true">{{ trans('messages.replace_lang',[],session('locale')) }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-restore-tab" data-bs-toggle="pill" href="#pills-restore" role="tab" aria-controls="pills-restore" aria-selected="false">{{ trans('messages.restore_lang',[],session('locale')) }}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-return" role="tabpanel" aria-labelledby="pills-return-tab">
                            <div class="row d-none">
                                <div class="col-md-4 col-6">
                                    <label class="radios">
                                        <input type="radio" checked class="return" name="return" value="1" id="replace">
                                        <span class="radiomarks" for="replace"></span> {{ trans('messages.replace_lang',[],session('locale')) }}
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>{{ trans('messages.order_or_reference_no_lang', [], session('locale')) }}</label>
                                        <input type="text" class="form-control return_order_no" name="return_order_no">
                                     </div>
                                </div>
                            </div>
                            <div class="row" id="return_data">
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-restore" role="tabpanel" aria-labelledby="pills-restore-tab">
                            <div class="row">
                                <div class="col-md-3 col-4">
                                    <label class="radios">
                                        <input type="radio" checked class="restore_type" name="restore_type" value="1">
                                        <span class="radiomarks" for="contact"></span> {{ trans('messages.contact_lang',[],session('locale')) }}
                                    </label>
                                </div>
                                <div class="col-md-3 col-4">
                                    <label class="radios">
                                        <input type="radio" class="restore_type" name="restore_type" value="2">
                                        <span class="radiomarks" for="order_no"></span> {{ trans('messages.order_no_lang',[],session('locale')) }}
                                    </label>
                                </div>
                                <div class="col-md-3 col-4">
                                    <label class="radios">
                                        <input type="radio" class="restore_type" name="restore_type" value="3">
                                        <span class="radiomarks" for="imei_no"></span> {{ trans('messages.imei_no_lang',[],session('locale')) }}
                                    </label>
                                </div>
                            </div>
                            <div class="row d-none>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>{{ trans('messages.order_or_contact_or_imei_no_lang', [], session('locale')) }}</label>
                                        <input type="text" class="form-control restore_order_no" name="restore_order_no">
                                        <input type="hidden" class="form-control restore_order_nos" name="restore_order_nos">
                                     </div>
                                </div>
                            </div>
                            <div class="row" id="restore_data">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    {{-- quick_Sale --}}
    <div class="modal fade modal-default pos-modal" id="edit-product" aria-labelledby="edit-product">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h5 class="edit_pro_name"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <input type="hidden" class="edit_barcode">
                        <div class="col-lg-6 col-sm-6 col-6 d-none">
                            <div class="input-blocks add-product">
                                <label>{{ trans('messages.price_pos_lang', [], session('locale')) }} <span>*</span></label>
                                <input type="text"  class="edit_price">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-6 d-none">
                            <div class="input-blocks add-product">
                                <label>{{ trans('messages.tax_pos_lang', [], session('locale')) }} <span>*</span></label>
                                <input type="text"  class="edit_tax">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-6 d-none">
                            <div class="input-blocks add-product">
                                <label>{{ trans('messages.discount_pos_lang', [], session('locale')) }} <span>*</span></label>
                                <input type="text" value="0"  class="edit_discount" >
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-6">
                            <div class="input-blocks add-product">
                                <label>{{ trans('messages.min_sale_price_pos_lang', [], session('locale')) }} <span>*</span></label>
                                <input type="text" readonly class="edit_min_price" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-sm-flex justify-content-end">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ trans('messages.cancel_lang', [], session('locale')) }}</button>
                        {{-- <button type="submit" class="btn btn-primary" onclick="update_product()">Submit</button> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>


     {{-- replace and return --}}
     <div class="modal fade pos-modal" id="payment_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h5 class="modal-title">{{ trans('messages.checkout_lang',[],session('locale')) }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <!-- Payment Details Section -->
                        <div class="col-md-6">
                            <h3 class="text-center">Payment Details</h3>
                            <table class="order_list_table">
                                <tr class="mb-3">
                                    <th>Subtotal</th>
                                    <th class="text sub_total"></th>
                                </tr>
                                <tr class="mb-3">
                                    <th>Discount</th>
                                    <th class="text grand_discount"></th>
                                </tr>
                                <tr class="mb-3">
                                    <th>Total Tax</th>
                                    <th class="text total_tax"></th>
                                </tr>
                                <tr class="mb-3">
                                    <th>Grand Total</th>
                                    <th class="text grand_total"></th>
                                </tr>
                            </table>
                        </div>

                        <!-- Payment Method Section -->
                        <div class="col-md-6">
                            <h3 class="text-center">Payment Method</h3>
                            @php $a = 1; @endphp
                            @foreach ($view_account as $account)
                                <div class="row" style="padding-bottom: 10px">
                                    <div class="col-md-4 col-6">
                                        <label class="checkboxs">
                                            <input type="checkbox" onclick="add_payment_method('{{ $account->id }}')" class="payment_methods" name="payment_methods[]" value="{{ $account->id }}" id="{{ $account->id }}_acc">
                                            <span class="checkmarks" for="{{ $account->id }}_acc"></span>{{ $account->account_name }}
                                        </label>
                                    </div>
                                    <div class="col-md-8 col-6">
                                        <input type="text" readonly cash-type="{{ $account->account_status }}" class="form-control payment_methods_value isnumber" id="payment_methods_value_id{{ $account->id }}" name="payment_methods_value[]" value="">
                                    </div>
                                </div>
                                <br>
                            @php $a++; @endphp
                            @endforeach
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <!-- Left Column: Customer Info and Points -->
                        <div class="col-md-6">
                            <div class="row">
                                <!-- Customer Name -->
                                <div class="col-md-6">
                                    <input type="text" class="form-control" readonly value="Customer Name">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control payment_customer_name" readonly placeholder="Sultan Al Masroori">
                                </div>
                            </div>

                            <div class="row">
                                <!-- Total Points Label -->
                                <div class="col-md-6">
                                    <input type="hidden" class="form-control" readonly value="Total Points">
                                </div>
                                <div class="col-md-6">
                                    <input type="hidden" class="form-control payment_customer_point" readonly placeholder="200">
                                    <input type="hidden" class="form-control payment_customer_point_from">
                                    <input type="hidden" class="form-control payment_customer_amount_to">
                                </div>
                            </div>

                            <div class="row">
                                <!-- Points Amount Label -->
                                <div class="col-md-6">
                                    <input type="hidden" class="form-control" readonly value="Points Amount">
                                </div>
                                <div class="col-md-6">
                                    <input type="hidden" class="form-control payment_customer_point_amount" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Get Point Amount Button (hidden) -->
                                <div class="col-md-6">
                                    <input type="hidden" class="form-control get_point_amount isnumber">
                                </div>
                                <div class="col-md-6 d-none">
                                    <button class="btn btn-block btn-secondary" id="get_total_point_value" style="width:100%">Total Points</button>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Remaining Amount Table -->
                        <div class="col-md-6">
                            <table class="order_list_table">
                                <!-- Paid Point Amount Row (hidden) -->
                                <tr class="mb-3 d-none">
                                    <th>Point Amount</th>
                                    <th class="text paid_point_amount"></th>
                                    <input type="hidden" class="paid_point_amount_input">
                                </tr>
                                <!-- Remaining Amount Row -->
                                <tr class="mb-3">
                                    <th>Remaining Amount</th>
                                    <th class="text remaining_point_amount"></th>
                                    <input type="hidden" class="remaining_point_amount_input">
                                </tr>
                            </table>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-12 ">
                        <div class="keys d-none" aria-labelledby="inputKeys">
                            <!-- operators and other keys -->
                            <span tabindex="0" class="digit">{{ trans('messages.7_lang', [], session('locale')) }}</span>
                            <span tabindex="0" class="digit">{{ trans('messages.8_lang', [], session('locale')) }}</span>
                            <span tabindex="0" class="digit">{{ trans('messages.9_lang', [], session('locale')) }}</span>
                            <span tabindex="0"   class="operator d-none">+</span>
                            <span tabindex="0" class="digit">{{ trans('messages.4_lang', [], session('locale')) }}</span>
                            <span tabindex="0" class="digit">{{ trans('messages.5_lang', [], session('locale')) }}</span>
                            <span tabindex="0" class="digit">{{ trans('messages.6_lang', [], session('locale')) }}</span>
                            <span tabindex="0"   class="operator d-none">-</span>
                            <span tabindex="0" class="digit">{{ trans('messages.1_lang', [], session('locale')) }}</span>
                            <span tabindex="0" class="digit">{{ trans('messages.2_lang', [], session('locale')) }}</span>
                            <span tabindex="0" class="digit">{{ trans('messages.3_lang', [], session('locale')) }}</span>
                            <span tabindex="0"  class="operator d-none">x</span>
                            <span tabindex="0"  class="clear d-none">C</span>
                            <span tabindex="0" class="digit">{{ trans('messages.0_lang', [], session('locale')) }}</span>
                            <span tabindex="0" class="digit">.</span>
                            <span tabindex="0" class="back_space">←</span>
                            <span tabindex="0"   class="operator d-none">÷</span>
                            <span tabindex="0"   class="eval d-none">=</span>
                            <span tabindex="0"  class="operator d-none">^</span>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-block btn-success submit_form" id="add_pos_order" style="width:100%" >{{ trans('messages.final_payment_lang', [], session('locale')) }}</button>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>






        {{-- <script src="{{ asset('js/pos_page/jquery-3.7.1.min.js')}}" type="7a3fc97ac244f422b7ec338a-text/javascript"></script> --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <!-- jQuery UI library -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <!-- Feather Icon JS -->
        <script src="{{ asset('js/pos_page/feather.min.js') }}"></script>

        <!-- Slimscroll JS -->
        <script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>

        <!-- Datatable JS -->
        <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('js/pos_page/dataTables.bootstrap5.min.js') }}"></script>


        <script src="{{  asset('plugins/toastr/toastr.min.js')}}"></script>
		<script src="{{  asset('plugins/toastr/toastr.js')}}"></script>

        <!-- Bootstrap Core JS -->
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

        <!-- Chart JS -->
        <script src="{{ asset('js/apexcharts.min.js') }}"></script>
        <script src="{{ asset('js/chart-data.js') }}"></script>

        <!-- Datetimepicker JS -->
        <script src="{{ asset('js/moment.min.js') }}"></script>
        <script src="{{ asset('js/pos_page/daterangepicker.js') }}"></script>

        {{-- caousel js --}}
        <script src="{{ asset('plugins/owlcarousel/owl.carousel.min.js') }}"></script>
        <script src="{{  asset('js/bootstrap-datetimepicker.min.js')}}"></script>

        <!-- Select2 JS -->
		<script src="{{  asset('js/select2.min.js')}}"></script>
        <script src="{{  asset('plugins/select2/js/custom-select.js')}}"></script>

        <!-- Sweetalert 2 -->
        <script src="{{ asset('plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
        <script src="{{ asset('plugins/sweetalert/sweetalerts.min.js') }}"></script>

        {{-- theme script --}}
        <script src="{{ asset('js/pos_page/theme-script.js')}}" ></script>

        {{-- script js --}}
        <script src="{{ asset('js/pos_page/script.js') }}"></script>
        <script src="{{ asset('vendor/toastr/js/toastr.min.js')}}"></script>
        <script src="{{ asset('js/plugins-init/toastr-init.js')}}"></script>

        {{-- rocket loader --}}
        <script src="{{ asset('js/pos_page/rocket-loader.min.js') }}" data-cf-settings="7a3fc97ac244f422b7ec338a-|49" defer >
        </script>

        {{-- custom js --}}
        @include('custom_js.custom_js')

        {{-- Include the JavaScript file for pos --}}
        @include('custom_js.pos_js')




    </body>

</html>
