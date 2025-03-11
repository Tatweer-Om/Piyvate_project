@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.doctors_lang', [], session('locale')) }}</title>
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
                <li class="active"><a href="javascript:void(0)">doctors</a></li>
            </ol>
        </div>
        <div class="form-head d-flex mb-3 mb-md-4 align-items-start flex-wrap">
            <div class="me-auto mb-3 mb-md-0">
                <a href="javascript:void();" class="btn btn-primary btn-rounded add-staff" data-bs-toggle="modal" data-bs-target="#add_doctor_modal">+ Add doctor</a>
            </div>


        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="all_doctors" class="table table-striped  mb-4 dataTablesCard fs-14">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>doctor Name</th>
                                        <th>User Name</th>
                                        <th>Speciality</th>

                                        <th>Phone</th>
                                        <th>doctor Branch</th>
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

    <div class="modal fade" id="add_doctor_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Doctor</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form class="add_doctor">
                @csrf
                <div class="row">
                  <div class="col-lg-4 col-xl-4">
                    <div class="form-group">
                      <label class="col-form-label">Full Name</label>
                      <input type="text" class="form-control doctor_name" name="doctor_name" placeholder="doctor Name">
                    </div>
                  </div>
                  <div class="col-lg-4 col-xl-4">
                    <div class="form-group">
                      <label class="col-form-label">Username</label>
                      <input type="text" class="form-control user_name" name="user_name" placeholder="Username">
                    </div>
                  </div>
                  <div class="col-lg-4 col-xl-4">
                    <div class="form-group">
                      <label class="col-form-label">Email</label>
                      <input type="text" class="form-control email" name="email" placeholder="Your valid email..">
                    </div>
                  </div>
                  <div class="col-lg-4 col-xl-4">
                    <div class="form-group">
                      <label class="col-form-label">Mobile No</label>
                      <input type="number" class="form-control phone" name="phone" placeholder="Mobile">
                    </div>
                  </div>
                  <div class="col-lg-4 col-xl-4">
                    <div class="form-group">
                      <label class="col-form-label">Password</label>
                      <input type="password" class="form-control password" name="password">
                    </div>
                  </div>
                  <input type="hidden" name= "doctor_id" class="doctor_id">
                  <div class="col-lg-4 col-xl-4">
                    <div class="form-group">
                      <label class="col-form-label">Speciality</label>
                      <select class="form-control selectpicker speciality" name="speciality">
                        <option value="">Choose...</option>
                        @foreach($specials as $speciality)
                          <option value="{{ $speciality->id }}">{{ $speciality->speciality_name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-4 col-xl-4">
                    <div class="form-group">
                      <label class="col-form-label">Select Branch</label>
                      <select class="form-control selectpicker branch_id" name="branch_id">
                        <option value="">Choose...</option>
                        @foreach($branches as $branch)
                          <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-12 col-md-8">
                      <div class="form-group">
                        <label class="col-form-label">Note:</label>
                        <textarea class="form-control notes" rows="4" name="notes"></textarea>
                      </div>
                    </div>
                    <div class="col-12 col-md-4 d-flex justify-content-center align-items-center">
                        <div class="form-group text-center position-relative">
                            <label class="col-form-label">Image</label>
                            <img id="imagePreview" src="{{ asset('images/dummy_images/cover-image-icon.png') }}"
                                alt="Preview" class="img-fluid rounded doctor_image"
                                style="width: 100px; height: 100px; object-fit: cover; cursor: pointer;">

                            <input type="file" id="imageUpload" name="doctor_image" class="d-none" accept="image/*">

                            <!-- Remove Button -->
                            <span id="removeImage"
                                class="position-absolute top-0 end-0 bg-danger text-white rounded-circle px-2"
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




