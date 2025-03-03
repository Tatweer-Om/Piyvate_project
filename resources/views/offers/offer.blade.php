@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.offers_lang', [], session('locale')) }}</title>
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
                <li class="active"><a href="javascript:void(0)">offer</a></li>
            </ol>
        </div>
        <div class="form-head d-flex mb-3 mb-md-4 align-items-start flex-wrap">
            <div class="me-auto mb-3 mb-md-0">
                <a href="javascript:void();" class="btn btn-primary btn-rounded add-staff" data-bs-toggle="modal" data-bs-target="#add_offer_modal">+ Add offer</a>
            </div>


        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="all_offer" class="table table-striped patient-list mb-4 dataTablesCard fs-14">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>offer Name</th>
                                        <th>Sessions</th>
                                        <th>Cost</th>
                                        <th>Branch</th>
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

    <div class="modal fade" id="add_offer_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">offer Model</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            <form class="add_offer">
                @csrf
                <div class="row">
                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">offer Name</label>
                            <input type="text" class="form-control offer_name" id="name1" name="offer_name" placeholder="offer Name" >
                        </div>
                    </div>
                    <input type="hidden" class="offer_id" name="offer_id">

                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">Sessions</label>
                            <input type="number" class="form-control sessions"  name="sessions" placeholder="Sessions">
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">Offer Price</label>
                            <input type="text" class="form-control offer_price"  name="offer_price" placeholder="Price">
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">Select Branch <span class="text-danger">*</span></label>
                            <select class="form-control default-select wide mb-3 branch_id" name="branch_id">
                                <option value="">Choose...</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                        <div class=" col-lg-12 col-12 col-md-12 col-xl-12">
                            <div class="form-group">
                                <label class="col-form-label">Notes</label>
                                <textarea class="form-control notes" id="exampleFormControlTextarea2" rows="4" name="notes"></textarea>
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
</div>

@include('layouts.footer')
@endsection
