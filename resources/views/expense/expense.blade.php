@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.expense_lang', [], session('locale')) }}</title>
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
</style>ccout
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class=""><a href="javascript:void(0)">Dashboard/</a></li>
                <li class="active"><a href="javascript:void(0)">expenses</a></li>
            </ol>
        </div>
        <div class="form-head d-flex  mb-md-4 align-items-start flex-wrap">
            <div class="me-auto  mb-md-0">
                <a href="javascript:void();" class="btn btn-primary btn-rounded add-staff" data-bs-toggle="modal" data-bs-target="#add_expense_modal">+ Add expense</a>
            </div>


        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="all_expenses" class="table table-striped  mb-4 dataTablesCard fs-14">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>expense Name</th>
                                        <th>expense category</th>
                                        <th>Amount</th>
                                        <th>Expense Date</th>
                                        <th>Account</th>

                                        <th>Added By </th>
                                        <th>Added On </th>
                                        <th class="text-end">Action</th>
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



<div class="modal fade" id="add_expense_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{ trans('messages.add_expense_lang', [], session('locale')) }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body overflow-auto" style="max-height: 400px;">
                <form class="add_expense">
                    @csrf
                    <input type="hidden" class="expense_id" name="expense_id">

                    <div class="row">
                        <!-- اسم المصروف -->
                        <div class="col-md-4">
                            <label class="form-label">{{ trans('messages.expense_name_lang', [], session('locale')) }}</label>
                            <input class="form-control expense_name" name="expense_name" type="text">
                        </div>

                        <!-- اختيار الفئة -->
                        <div class="col-md-4">
                            <label class="form-label">{{ trans('messages.category_lang', [], session('locale')) }}</label>
                            <select class="form-control category_id default-select" name="category_id">
                                <option value="">{{ trans('messages.select_category_lang', [], session('locale')) }}</option>
                                @foreach($expense_cats as $expense_cat)
                                    <option value="{{ $expense_cat->id }}">{{ $expense_cat->expense_category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- تاريخ المصروف -->
                        <div class="col-md-4">
                            <label class="form-label">{{ trans('messages.expense_date_lang', [], session('locale')) }}</label>
                            <input class="form-control expense_date" name="expense_date" type="date">
                        </div>

                        <!-- المبلغ -->
                        <div class="col-md-4">
                            <label class="form-label">{{ trans('messages.amount_lang', [], session('locale')) }}</label>
                            <input class="form-control amount" name="amount" type="text">
                        </div>


                        <!-- اختيار الحساب -->
                        <div class="col-md-4">
                            <label class="form-label">{{ trans('messages.account_lang', [], session('locale')) }}</label>
                            <select class="form-control account_id default-select" name="account_id">
                                <option value="">{{ trans('messages.select_account_lang', [], session('locale')) }}</option>
                                @foreach($view_account as $account)
                                    <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                @endforeach
                            </select>
                        </div>


                    </div>
                    <div class="row mt-3">
                        <!-- Image Section -->


                        <!-- Note Section -->
                        <div class="col-12 col-md-8">
                            <div class="form-group">
                                <label class="col-form-label">Note:</label>
                                <textarea class="form-control notes" id="exampleFormControlTextarea2" rows="4" name="notes"></textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 d-flex justify-content-center align-items-center">
                            <div class="form-group text-center position-relative">
                                <label class="col-form-label">Upload File</label>

                                <img id="filePreview" src="{{ asset('images/dummy_images/cover-image-icon.png') }}"
                                    alt="Preview" class="img-fluid rounded expense_file"
                                    style="width: 100%; max-width: 100px; max-height: 100px; object-fit: cover; cursor: pointer;">

                                <!-- Hidden file input -->
                                <input type="file" id="fileUpload" name="expense_file" class="d-none expense_file"
                                    accept="image/*, .pdf, .doc, .docx, .xls, .xlsx">

                                <!-- Remove file icon -->
                                <span id="removeFile" class="position-absolute top-0 end-0 bg-danger text-white rounded-circle px-2"
                                    style="cursor: pointer; display: none;">&times;</span>

                                <!-- Display file name -->
                                <div id="fileName" style="margin-top: 10px;"></div>
                            </div>
                        </div>



                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">
                            {{ trans('messages.close_lang', [], session('locale')) }}
                        </button>
                        <button type="submit" class="btn btn-primary submit_form">
                            {{ trans('messages.submit_lang', [], session('locale')) }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@include('layouts.footer')
@endsection
