@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.purchases_lang', [], session('locale')) }}</title>
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
                <li class="active"><a href="javascript:void(0)">purchase</a></li>
            </ol>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="all_purchase" class="table table-striped patient-list mb-4 dataTablesCard fs-14">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Invoice No</th>
                                        <th>Supplier Name</th>
                                        <th>Purchase Date</th>
                                        <th>Invoice Price</th>
                                        <th>Shipping Cost</th>
                                        {{-- <th>Paid Amount</th>
                                        <th>Remaining Amount</th> --}}
                                        <th>Document</th>
                                        <th>Added By</th>
                                        <th>Added On </th>
                                        <th class="text-center">Action</th>
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


</div>

<div class="modal fade" id="add_purchase_payment_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Purchase Model</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="add_purchase_payment">
                    @csrf
                    <div class="row">

                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Supplier Name</label>
                                <input type="text" class="form-control supplier_name" name="supplier_name" placeholder="Supplier Name">
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Invoice No</label>
                                <input type="text" class="form-control invoice_no" name="invoice_no" placeholder="Invoice Number">
                            </div>
                        </div>

                        <!-- Row with three inputs in another row -->
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Purchase Date</label>
                                <input type="date" class="form-control purchase_date" name="purchase_date">
                            </div>
                        </div>


                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Total Amount</label>
                                <input type="number" class="form-control total_amount" name="total_amount" placeholder="Total Amount" step="0.01">
                            </div>
                        </div>

                        <input type="hidden" name="purchase_id" class="purchase_id" hidden>
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Remaining Amount</label>
                                <input type="number" class="form-control remaining_amount" name="remaining_amount" placeholder="Remaining Amount" step="0.01">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Paid Amount</label>
                                <input type="number" class="form-control paid_amount" name="paid_amount" placeholder="Remaining Amount" step="0.01">
                            </div>
                        </div>

                        <!-- Row with payment method and date -->
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Payment Method</label>
                                <select class="form-control account_id" name="account_id">
                                    @foreach($accounts as $account)
                                    <option value="">choose..</option>

                                        <option value="{{ $account->id}}">{{ $account->account_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Payment Date</label>
                                <input type="date" class="form-control payment_date" name="payment_date">
                            </div>
                        </div>


                        <div class="col-xl-4 col-lg-4 col-md-6 d-flex justify-content-center align-items-center">
                            <div class="form-group text-center position-relative">
                                <label class="col-form-label">Upload File</label>

                                <img id="filePreview" src="{{ asset('images/dummy_images/cover-image-icon.png') }}"
                                    alt="Preview" class="img-fluid rounded payment_file"
                                    style="width: 100%; max-width: 100px; max-height: 100px; object-fit: cover; cursor: pointer;">

                                <!-- Hidden file input -->
                                <input type="file" id="fileUpload" name="payment_file" class="d-none payment_file"
                                    accept="image/*, .pdf, .doc, .docx, .xls, .xlsx">

                                <!-- Remove file icon -->
                                <span id="removeFile" class="position-absolute top-0 end-0 bg-danger text-white rounded-circle px-2"
                                    style="cursor: pointer; display: none;">&times;</span>

                                <!-- Display file name -->
                                <div id="fileName" style="margin-top: 10px;"></div>
                            </div>
                        </div>

                        <div class="col-xl-12 col-lg-8 col-md-12">
                            <div class="form-group">
                                <label class="col-form-label">Notes</label>
                                <textarea class="form-control notes" rows="3" name="notes"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@include('layouts.footer')
@endsection
