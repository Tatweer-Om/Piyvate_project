@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.users_lang', [], session('locale')) }}</title>
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
                <li class="active"><a href="javascript:void(0)">Users</a></li>
            </ol>
        </div>
        <div class="form-head d-flex mb-3 mb-md-4 align-items-start flex-wrap">
            <div class="me-auto mb-3 mb-md-0">
                <a href="javascript:void();" class="btn btn-primary btn-rounded add-staff" data-bs-toggle="modal" data-bs-target="#add_user_modal">+ Add User</a>
            </div>


        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="all_user" class="table table-striped  mb-4 dataTablesCard fs-14">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>User Name</th>
                                        <th>Phone</th>
                                        <th>User Type</th>
                                        <th>User Branch</th>
                                        <th>Added By </th>
                                        <th>Added On </th>
                                        <th >Action</th>
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

    <div class="modal fade" id="add_user_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            <form class="add_user">
                @csrf
                <div class="row">
                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">User Name</label>
                            <input type="text" class="form-control user_name" id="name1" name="user_name" placeholder="Name">
                        </div>
                    </div>
                    <input type="text" class="user_id" name="user_id" id="user_id" hidden>

                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">Mobile No</label>
                            <input type="number" class="form-control phone" id="moblie1" name="phone" placeholder="Mobile">
                        </div>
                    </div>

                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label " for="validationCustom02">Email <span class="text-danger">*</span></label>
                            <input type="text" class="form-control email" id="validationCustom02" name="email" placeholder="Your valid email.." >
                            <div class="invalid-feedback">
                                Please enter an email.
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">Password <span class="text-danger">*</span></label>
                            <div class=" position-relative">
                                <input type="password" id="dz-password" class="form-control password" name="password" >
                                <span class="show-pass eye">
                                    <i class="fa fa-eye-slash"></i>
                                    <i class="fa fa-eye"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">Select Branch <span class="text-danger">*</span></label>
                            <select class="branch_id form-control default-select wide mb-3" id="branch_id" name="branch_id">
                                <option value="">Choose...</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">User Type <span class="text-danger">*</span></label>
                            <select class="user_type default-select form-control wide mb-3 " id="user_type" name="user_type">
                                <option value="">Choose...</option>
                                <option value="1">User</option>
                                <option value="2">Admin</option>
                            </select>
                        </div>
                    </div>


                    <div class="container mt-3" id="checked_html">
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="selectAll">
                            <label class="form-check-label fw-bold fs-6" for="selectAll">All Permissions</label>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-1 col-3">
                                <label class="d-block fs-6" for="user">User</label>
                                <input type="checkbox" class="form-check-input permission-checkbox" name="permissions[]" value="1" id="user">
                            </div>

                            <div class="col-md-1 col-3">
                                <label class="d-block fs-6" for="expense">Expense</label>
                                <input type="checkbox" class="form-check-input permission-checkbox" name="permissions[]" value="2" id="expense">
                            </div>

                            <div class="col-md-1 col-3">
                                <label class="d-block fs-6" for="reports">Reports</label>
                                <input type="checkbox" class="form-check-input permission-checkbox" name="permissions[]" value="3" id="reports">
                            </div>

                            <div class="col-md-1 col-3">
                                <label class="d-block fs-6" for="doctors">Doctors</label>
                                <input type="checkbox" class="form-check-input permission-checkbox" name="permissions[]" value="4" id="doctors">
                            </div>

                            <div class="col-md-1 col-3">
                                <label class="d-block fs-6" for="staff">Staff</label>
                                <input type="checkbox" class="form-check-input permission-checkbox" name="permissions[]" value="5" id="staff">
                            </div>

                            <div class="col-md-1 col-3">
                                <label class="d-block fs-6" for="register">Register</label>
                                <input type="checkbox" class="form-check-input permission-checkbox" name="permissions[]" value="6" id="register">
                            </div>

                            <div class="col-md-1 col-3">
                                <label class="d-block fs-6" for="patients">Patients</label>
                                <input type="checkbox" class="form-check-input permission-checkbox" name="permissions[]" value="7" id="patients">
                            </div>

                            <div class="col-md-1 col-3">
                                <label class="d-block fs-6" for="stock">Stock</label>
                                <input type="checkbox" class="form-check-input permission-checkbox" name="permissions[]" value="8" id="stock">
                            </div>

                            <div class="col-md-1 col-3">
                                <label class="d-block fs-6" for="billing">Billing</label>
                                <input type="checkbox" class="form-check-input permission-checkbox" name="permissions[]" value="9" id="billing">
                            </div>

                            <div class="col-md-1 col-3">
                                <label class="d-block fs-6" for="accounts">Accounts</label>
                                <input type="checkbox" class="form-check-input permission-checkbox" name="permissions[]" value="12" id="accounts">
                            </div>

                            <div class="col-md-1 col-3">
                                <label class="d-block fs-6" for="settings">Settings</label>
                                <input type="checkbox" class="form-check-input permission-checkbox" name="permissions[]" value="11" id="settings">
                            </div>

                            <div class="col-md-1 col-3">
                                <label class="d-block fs-6" for="hr">HR</label>
                                <input type="checkbox" class="form-check-input permission-checkbox" name="permissions[]" value="10" id="hr">
                            </div>
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
                                <!-- Image Preview (Click to Upload) -->
                                <label class="col-form-label">Image</label>

                                <img id="imagePreview" src="{{ asset('images/dummy_images/cover-image-icon.png') }}"
                                    alt="Preview" class="img-fluid rounded user_image" style="width: 100%; max-width: 100px; max-height: 100px; object-fit: cover; cursor: pointer;">

                                <!-- Hidden File Input -->
                                <input type="file" id="imageUpload" name="user_image" class="d-none user_image" accept="image/*">

                                <!-- Remove Button (X) -->
                                <span id="removeImage" class="position-absolute top-0 end-0 bg-danger text-white rounded-circle px-2"
                                    style="cursor: pointer; display: none;">&times;</span>
                            </div>
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




