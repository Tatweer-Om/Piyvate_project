@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.session_data_lang', [], session('locale')) }}</title>
    @endpush

<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        {{-- <div class="page-titles d-flex justify-content-between align-items-center">
            <ol class="breadcrumb mb-0">
                <li class=""><a href="javascript:void(0)">Dashboard /</a></li>
                <li class="active"><a href="javascript:void(0)">Appointments</a></li>
            </ol>
            <div class="d-flex gap-2">
                <a href="appointments" class="btn btn-primary btn-rounded">+ Appointment</a>
                <a href="{{ url('sessions_list') }}" class="btn btn-secondary btn-rounded">+ Session</a>
            </div>
        </div> --}}

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="all_session_data" class="table table-striped mb-4 dataTablesCard fs-9" style="font-weight: 300; font-size: 12px;">
                                <thead >
                                    <tr>
                                        <th>Action</th>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th> Date</th>
                                        <th> Time</th>
                                        <th>Status</th>
                                        <th>Fee</th>
                                        <th>OT,PT</th>
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







<div class="modal fade" id="editSessionModal2" tabindex="-1" aria-labelledby="editSessionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title" id="editSessionModalLabel">Edit Session</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editSessionForm2" autocomplete="off">
                    <div class="row g-3">
                        <!-- Input 1: Text -->
                        <div class="col-lg-4">
                            <label for="patient_name" class="form-label">Patient Name</label>
                            <input type="text" class="form-control shadow-sm" id="patient_name" name="patient_name" placeholder="Enter session name" readonly>
                        </div>

                        <!-- Input 2: Date -->
                        <div class="col-lg-4">
                            <label for="inputDate" class="form-label">Date</label>
                            <input type="date" class="form-control shadow-sm" id="inputDate" name="session_date">
                        </div>

                        <!-- Input 3: Time -->
                        <div class="col-lg-4">
                            <label for="inputTime" class="form-label">Time</label>
                            <input type="time" class="form-control shadow-sm" id="inputTime" name="session_time" autocomplete="off">
                        </div>

                        <!-- Hidden Inputs -->
                        <input type="hidden" name="session_primary_id" id="session_primary_id">
                        <input type="hidden" name="patient_primary_id" id="patient_primary_id">

                        <input type="hidden" name="source" id="source">

                        <!-- Input 4: Select box -->
                        <div class="col-lg-4">
                            <label for="doctor" class="col-form-label">Doctor</label>
                            <select class="form-control  shadow-sm" id="doctor" name="doctor">
                                <option value="">Choose...</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->doctor_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="col-form-label">Session Cat</label>
                            <select class="form-control shadow-sm" id="session_cat" name="session_cat">
                                <option value="">Choose...</option>

                                    <option value="OT">OT</option>
                                    <option value="PT">PT</option>


                            </select>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary px-4">Confirm</button>
                        <button type="button" class="btn btn-outline-secondary ms-2" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Ensure proper z-index and visibility for inputs inside modal */
    .modal {
        z-index: 1050; /* Bootstrap default z-index for modals */
    }

    .modal-backdrop {
        z-index: 1040; /* Behind modal but in front of other content */
    }

    /* Make sure the dropdown does not get overlapped by other content */
    .modal-body select, .modal-body input {
        z-index: 1060; /* Ensures form elements are on top of other content */
    }

    /* Specific margin fix for the time dropdown if it's causing issues */
    .modal-body .form-control {
        margin-top: 10px; /* Adds space around form controls to avoid overlap */
    }
</style>




@include('layouts.footer')
@endsection
