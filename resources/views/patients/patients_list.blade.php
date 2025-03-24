

@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.patients_list_lang', [], session('locale')) }}</title>
    @endpush

<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Patient List</a></li>
            </ol>
        </div>

        <div class="form-head d-flex mb-3 mb-md-4 align-items-start">
            <div class="me-auto mb-3 mb-md-0">
                <a href="javascript:void(0);" class="btn btn-primary btn-rounded add-staff" data-bs-toggle="modal" data-bs-target="#add_patient">+ Patient</a>
            </div>

            <div class="input-group search-area ms-auto d-inline-flex me-3">
                <input type="text" class="form-control" placeholder="Search here">
                <div class="input-group-append">
                    <button type="button" class="input-group-text"><i class="flaticon-381-search-2"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="all_patient" class="table table-striped patient-list mb-4 dataTablesCard fs-14">
                                <thead>
                                    <tr>

                                        <th>Sr No.</th>
                                        <th>Patient ID</th>
                                        <th>Patient Name</th>
                                        <th>Patient Phone</th>
                                        <th>Country</th>
                                        <th>Branch</th>
                                        <th>Added On</th>
                                        <th>Added By</th>
                                        <th>Action</th>
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

<!-- Patient Modal -->
<div class="modal fade" id="add_patient" tabindex="-1" aria-labelledby="patientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="patientModalLabel">Add New Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="add_patient">
                    @csrf
                    <input type="hidden" name="patient_id" id="patient_id" class="patient_id">

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="col-form-label">Title:</label>
                            <select class="form-control form-control-sm" id="title" name="title">
                                <option value="">Choose..</option>
                                <option value="1">Miss</option>
                                <option value="2">Mr.</option>
                                <option value="3">Mrs.</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="col-form-label">First Name:</label>
                            <input type="text" class="form-control form-control-sm" id="first_name" name="first_name" >
                        </div>
                        <div class="col-md-3">
                            <label class="col-form-label">Second Name:</label>
                            <input type="text" class="form-control form-control-sm" id="second_name" name="second_name">
                        </div>
                        <div class="col-md-3">
                            <label class="col-form-label">Mobile No:</label>
                            <input type="tel" class="form-control form-control-sm" id="mobile" name="mobile" >
                        </div>
                        <div class="col-md-3">
                            <label class="col-form-label">ID / Passport No:</label>
                            <input type="text" class="form-control form-control-sm" id="id_passport" name="id_passport" >
                        </div>
                        <div class="col-md-3">
                            <label class="col-form-label">Date Of Birth:</label>
                            <input type="date" class="form-control form-control-sm" id="dob" name="dob" >
                        </div>
                        <div class="col-md-3">
                            <label class="col-form-label">Country:</label>
                            <select class="form-control form-control-sm country" id="country" name="country" >
                                <option value="">Select Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Details Section -->
                    <div class="mt-3">
                        <label class="col-form-label">Additional Details:</label>
                        <textarea class="form-control" name="details" id="details" rows="3" placeholder="Enter any additional details about the patient..."></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Patient</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@include('layouts.footer')
@endsection
