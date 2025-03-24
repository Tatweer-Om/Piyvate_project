@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.sessions_lang', [], session('locale')) }}</title>
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
                <li class="active"><a href="javascript:void(0)">session</a></li>
            </ol>
        </div>
        <div class="form-head d-flex mb-3 mb-md-4 align-items-start flex-wrap">
            <div class="me-auto mb-3 mb-md-0">
                <a href="javascript:void();" class="btn btn-primary btn-rounded add-staff" data-bs-toggle="modal" data-bs-target="#add_session_modal">+ Add session</a>
            </div>


        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="all_sessions" class="table table-striped patient-list mb-4 dataTablesCard fs-14">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>session Name</th>
                                        <th>Cost</th>
                                        <th>Department</th>
                                        <th>Ministry Cat</th>
                                        <th>Added By </th>
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

    <div class="modal fade" id="add_session_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Session Modal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="add_session">
                        @csrf
                        <div class="row align-items-center">
                            <!-- Session Type (Radio Buttons) -->
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">Session Type</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input session_type" type="radio" name="session_type" id="normal" value="normal" checked>
                                            <label class="form-check-label" for="normal">Normal</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input session_type" type="radio" name="session_type" id="ministry" value="ministry">
                                            <label class="form-check-label" for="ministry">Ministry</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Government Select Box (Hidden Initially) -->
                            <div class="col-lg-4 col-md-4 col-sm-12 ministry-options" id="ministry-options" style="display: none;">
                                <div class="form-group">
                                    <label class="col-form-label">Select Government</label>
                                    <select class="form-control government" id="government" name="government">
                                        <option value="">Select Government</option>
                                        @foreach ($govts as $gvt)
                                            <option value="{{ $gvt->id }}">{{ $gvt->govt_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Ministry Category Select Box (Hidden Initially) -->
                            <div class="col-lg-4 col-md-4 col-sm-12 ministry-options" id="ministry2-options" style="display: none;">
                                <div class="form-group">
                                    <label class="col-form-label">Select Ministry Category</label>
                                    <select class="form-control ministry_cat_id" name="ministry_cat_id">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->ministry_category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Session Name (Initially Visible) -->
                            <div class="col-lg-4 col-md-4 col-sm-12" id="session_name_div">
                                <div class="form-group">
                                    <label class="col-form-label">Session Name</label>
                                    <input type="text" class="form-control session_name" name="session_name" placeholder="Session Name">
                                </div>
                            </div>

                            <input type="hidden" class="session_id" name="session_id">

                            <!-- Session Price -->
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">Session Price</label>
                                    <input type="text" class="form-control session_price" name="session_price" placeholder="Price">
                                </div>
                            </div>
                        </div>

                        <!-- Notes (Full Width) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-form-label">Notes</label>
                                    <textarea class="form-control notes" rows="4" name="notes"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@include('layouts.footer')
@endsection
