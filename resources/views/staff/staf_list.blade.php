@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.employees_lang', [], session('locale')) }}</title>
    @endpush
    <style>
    /* Make button full-width on small screens */
    .form-head .add-employee {
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

        .form-head .add-employee {
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
                <li class="active"><a href="javascript:void(0)">employees</a></li>
            </ol>
        </div>
        <div class="form-head d-flex mb-3 mb-md-4 align-items-start flex-wrap">
            <div class="me-auto mb-3 mb-md-0">
                <a href="javascript:void();" class="btn btn-primary btn-rounded add-employee" data-bs-toggle="modal" data-bs-target="#add_employee_modal">+ Add employee</a>
            </div>


        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="all_employee" class="table table-striped  mb-4 dataTablesCard fs-14">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>employee Name</th>
                                        <th>Phone</th>
                                        <th>employee Type</th>
                                        <th>employee Branch</th>
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

    <div class="modal fade" id="add_employee_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Employees</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            <form class="add_employee">
                @csrf
                <div class="row">
                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">Employee Name</label>
                            <input type="text" class="form-control employee_name" id="name1" name="employee_name" placeholder="Name">
                        </div>
                    </div>
                    <input type="text" class="employee_id" name="employee_id" id="employee_id" hidden>

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
                            <label class="col-form-label">Select Role <span class="text-danger">*</span></label>
                            <select class="role_id form-control default-select wide mb-3" id="role_id" name="role_id">
                                <option value="">Choose...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">Annual Leave Name</label>
                            <input type="text" class="form-control annual_leaves isnumber"   name="annual_leaves"  >
                        </div>
                    </div>
 
                    <div class="col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="col-form-label">Emergeny Leaves</label>
                            <input type="text" class="form-control emergency_leaves isnumber"   name="emergency_leaves"  >
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

                               <!-- Image Preview -->
                                    <img id="imagePreview" 
                                    src="{{ asset('images/dummy_images/cover-image-icon.png') }}"
                                    alt="Preview" 
                                    class="img-fluid rounded employee_image" 
                                    style="width: 100%; max-width: 100px; max-height: 100px; object-fit: cover; cursor: pointer;"
                                    onclick="triggerUpload()" />

                                    <!-- Hidden File Input -->
                                    <input type="file" 
                                    id="imageUpload" 
                                    name="employee_image" 
                                    class="d-none employee_image" 
                                    accept="image/*" 
                                    onchange="handleImageChange(event)" />

                                    <!-- Remove Button -->
                                    <span id="removeImage" 
                                    class="position-absolute top-0 end-0 bg-danger text-white rounded-circle px-2" 
                                    style="cursor: pointer; display: none;" 
                                    onclick="removeSelectedImage()">&times;</span>
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
