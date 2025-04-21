@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.products_lang', [], session('locale')) }}</title>
    @endpush
    <style>
    /* Make button full-width on small screens */
    .form-head .add-staff {
        width: auto;
    }

    .search-area {
        max-width: 250px;
        width: 100%;
    }

    /* Adjust table font size and padding for smaller screens */
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

        .table th, .table td {
            padding: 10px 8px;
            font-size: 12px;
        }

        .table {
            font-size: 12px;
        }

        .checkbox {
            padding: 0;
        }
    }

    /* Adjust the padding for the form fields */
    .input-group {
        max-width: 300px;
    }
</style>
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class=""><a href="javascript:void(0)">Dashboard/</a></li>
                <li class="active"><a href="javascript:void(0)">product</a></li>
            </ol>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row p-4">
                            <div class="col-lg-8">
                                <div class="col-lg-6 col-6">
                                    <h6 class=" text-uppercase fw-semibold">  {{ trans('messages.company_detail_lang', [], session('locale')) }}</h6>
                                    <p class=" mb-1" id="zip-code"><span> {{ trans('messages.invoice_lang', [], session('locale')) }}</span> {{ $purchase_invoice->invoice_no }}</p>
                                    <p class=" mb-1" id="zip-code"><span> {{ trans('messages.created_by_lang', [], session('locale')) }}</span> admin</p>
                                    <p class=" mb-1" id="zip-code"><span>{{ trans('messages.purchase_date_lang', [], session('locale')) }}</span> {{ $purchase_invoice->purchase_date }}</p>
                                </div>
                            </div>
                            <div class="col-lg-4 text-end">
                                <h6 class=" text-uppercase fw-semibold"> {{ trans('messages.purchase_customer_details_lang', [], session('locale')) }}</h6>
                                <p class=" mb-1" id="zip-code"><span> </span> {{ $supplier_name }}</p>
                                <p class=" mb-1" id="zip-code"><span> </span> {{ $supplier_phone }}</p>
                                <p class=" mb-1" id="zip-code"><span></span> {{ $supplier_email }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive card-body">
                                <h6 class=" text-uppercase fw-semibold"> {{ trans('messages.product_detail_lang', [], session('locale')) }}</h6>
                                <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <div class="row table-active">
                                            <th class="col-lg-1">#</th>
                                            <th class="col-lg-2"> {{ trans('messages.image_lang', [], session('locale')) }}</th>
                                            <th class="col-lg-1"> {{ trans('messages.unit_price_lang', [], session('locale')) }}</th>
                                            <th class="col-lg-1"> {{ trans('messages.quantity_lang', [], session('locale')) }}</th>
                                            <th class="col-lg-1"> {{ trans('messages.tax_lang', [], session('locale')) }}</th>
                                            
                                            <th class="col-lg-1">{{ trans('messages.subtotal_lang', [], session('locale')) }}</th>
                                            </div>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $purchase_detail_table; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php if(!empty($purchase_payment_detail)){ ?>
                            <div class="card-body p-4">
                                <h6 class=" text-uppercase fw-semibold"> {{ trans('messages.purchase_detail_lang', [], session('locale')) }}</h6>
                                <div class="table-responsive">
                                    <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr class="table-active">
                                                <th scope="col">{{ trans('messages.payment_date_lang', [], session('locale')) }}:</th>
                                                <th scope="col">{{ trans('messages.payment_method_lang', [], session('locale')) }}:</th>
                                                <th scope="col"> {{ trans('messages.total_price_lang', [], session('locale')) }}:</th>
                                                <th scope="col">{{ trans('messages.paid_amount_lang', [], session('locale')) }}:</th>
                                                <th scope="col">{{ trans('messages.action_lang', [], session('locale')) }}:</th>
                                                 
        
                                            </tr>
                                        </thead>
                                        <tbody id="products-list">
                                            <?php echo $purchase_payment_detail; ?>
                                        </tbody>
                                    </table><!--end table-->
                                </div>
                            </div>
                        <?php }?>
                        <div class="mt-2">
                            <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto"
                                style="width:250px">
                                <tbody>
                                    <tr>
                                        <td>{{ trans('messages.invoice_detail', [], session('locale')) }}:</td>
                                        <td class="text-end">
        
                                            <p class="small-text">
                                                <span>{{ trans('messages.subtotal_lang', [], session('locale')) }}:</span>
                                                {{ number_format($sub_invo, 3) }}
                                            </p>
        
                                            <p class="small-text">
                                                <span>{{ trans('messages.shipping_lang', [], session('locale')) }}:</span>
                                                {{ number_format($invo_ship, 3) }}
                                            </p>
        
                                            {{-- <p class="small-text">
                                                <span>{{ trans('messages.tax', [], session('locale')) }}:</span>
                                                {{ number_format($invo_tx, 3) }}%
                                            </p> --}}
        
                                            <p class="small-text">
                                                <span>{{ trans('messages.invoice_total_lang', [], session('locale')) }}:</span>
                                                {{ number_format($total_invo_price, 3) }}
                                            </p>
                                        </td>
                                    </tr>
        
                                    <tr>
                                        <td>{{ trans('messages.products_subtotal_lang', [], session('locale')) }}:</td>
        
                                        {{-- <td class="text-end">{{ number_format($sub_total_all, 3) }}                                        </td> --}}
        
                                        <td class="text-end">{{ number_format($without_shipping_sub_total, 3) }}    </td>                                    </td>
        
                                    </tr>
                                    <tr>
                                        <td> {{ trans('messages.tax_lang', [], session('locale')) }}:</td>
                                        <td class="text-end">{{ $total_tax}}</td>
                                    </tr>
                                    <tr>
                                        <td> {{ trans('messages.shipping_lang', [], session('locale')) }}:</td>
                                        <td class="text-end">{{ number_format($shipping_cost, 3) }}                                        </td>
                                    </tr>
                                    <tr class="border-top border-top-dashed fs-15">
                                        <th scope="row">{{ trans('messages.invoice_total_lang', [], session('locale')) }}:</th>
                                        <th class="text-end">{{ $grand_total }}</th>
                                    </tr>
                                    <tr>
                                        <td>{{ trans('messages.products_total_paid_lang', [], session('locale')) }}:</td>
                                        <td class="text-end">{{ number_format($payment_paid,3) }}</td>
                                    </tr>
                                    <tr>
                                        <td> {{ trans('messages.remaining_lang', [], session('locale')) }}:</td>
                                        <td class="text-end">{{ $payment_remaining }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--end table-->
                        </div>
                        @if(!empty($purchase_invoice->description))
                            <div class="mt-4">
                                <div class="alert alert-info">
                                    <p class="mb-0">
                                        <span class="fw-semibold">{{ trans('messages.notes_lang', [], session('locale')) }}:</span>
                                        <span id="note">{{ $purchase_invoice->description }}</span>
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>

@include('layouts.footer')
@endsection
















 