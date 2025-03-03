@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.accounts_lang', [], session('locale')) }}</title>
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
                <li class="active"><a href="javascript:void(0)">Accounts</a></li>
            </ol>
        </div>
        <div class="form-head d-flex  mb-md-4 align-items-start flex-wrap">
            <div class="me-auto  mb-md-0">
                <a href="javascript:void();" class="btn btn-primary btn-rounded add-staff" data-bs-toggle="modal" data-bs-target="#add_account_modal">+ Add Account</a>
            </div>


        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="all_accounts" class="table table-striped  mb-4 dataTablesCard fs-14">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Account Name</th>
                                        <th>Account Number</th>
                                        <th>Opening Balance</th>
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

<div class="modal fade" id="add_account_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trans('messages.add_data_lang',[],session('locale')) }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body overflow-auto" style="max-height: 400px;">
                <form class="add_account">
                    @csrf
                    <input type="hidden" class="account_id" name="account_id">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="">
                                <label for="example-text-input" class="form-label">{{ trans('messages.account_name_lang',[],session('locale')) }}</label>
                                <input class="form-control account_name" name="account_name" type="text" id="example-text-input">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <label for="example-text-input" class="form-label">{{ trans('messages.account_branch_lang',[],session('locale')) }}</label>
                                <input class="form-control account_branch" name="account_branch" type="text" id="example-text-input">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <label for="example-text-input" class="form-label">{{ trans('messages.account_no_lang',[],session('locale')) }}</label>
                                <input class="form-control account_no" name="account_no" type="text" id="example-text-input">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <label for="example-text-input" class="form-label">{{ trans('messages.opening_balance_lang',[],session('locale')) }}</label>
                                <input class="form-control opening_balance" name="opening_balance" type="text" id="example-text-input">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <label for="example-text-input" class="form-label">{{ trans('messages.commission_lang',[],session('locale')) }}</label>
                                <input class="form-control commission isnumber" name="commission" type="text" id="example-text-input">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class=" form-group">
                                <label>{{ trans('messages.account_type', [], session('locale')) }}</label>
                                <select class="form-control account_type default-select " name="account_type">
                                    <option value="1">{{ trans('messages.normal_account_lang', [], session('locale')) }}</option>
                                    <option value="2">{{ trans('messages.saving_account_lang', [], session('locale')) }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label font-size-13">{{ trans('messages.select_branch', [], session('locale')) }}</label>
                                <select class="branch_id form-control default-select " name="branch_id">
                                    <option value="">{{ trans('messages.select_branch_lang', [], session('locale')) }}</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->branch_name ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="checkboxs">{{ trans('messages.cash_lang', [], session('locale')) }}
                                <input type="checkbox" name="account_status" value="1" id="account_status" class="account_status">
                                <span class="checkmarks" for="account_status"></span>
                            </label>
                        </div>
                        <div class="col-md-12">
                            <div class=" form-group">
                                <label>{{ trans('messages.notes_lang', [], session('locale')) }}</label>
                                <textarea class="form-control notes" rows="3" name="notes"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">CLose</button>
                        <button type="submit" class="btn btn-primary submit_form">Submit</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>



@include('layouts.footer')
@endsection
