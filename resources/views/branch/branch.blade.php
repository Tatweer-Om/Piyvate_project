@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.branchs_lang', [], session('locale')) }}</title>
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
                <li class="active"><a href="javascript:void(0)">Branch</a></li>
            </ol>
        </div>
        <div class="form-head d-flex mb-3 mb-md-4 align-items-start flex-wrap">
            <div class="me-auto mb-3 mb-md-0">
                <a href="javascript:void();" class="btn btn-primary btn-rounded add-staff" data-bs-toggle="modal" data-bs-target="#add_branch_modal">+ Add branch</a>
            </div>


        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="all_branch" class="table table-striped patient-list mb-4 dataTablesCard fs-14">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Branch Name</th>
                                        <th>Branch Phone</th>
                                        <th>Branch Email</th>
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

    <div class="modal fade" id="add_branch_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Branch Model</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            <form class="add_branch">
                @csrf
                <div class="row">
                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">Branch Name</label>
                            <input type="text" class="form-control branch_name" id="name1" name="branch_name" placeholder="Branch Name" >
                        </div>
                    </div>
                    <input type="hidden" class="branch_id" name="branch_id">

                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">Branch Phone</label>
                            <input type="number" class="form-control branch_phone" id="moblie1" name="branch_phone" placeholder="Mobile">
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">Branch Email</label>
                            <input type="text" class="form-control branch_email"  name="branch_email" placeholder="Email">
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
