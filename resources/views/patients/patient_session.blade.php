@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.patients_profile_lang', [], session('locale')) }}</title>
    @endpush


    <div class="content-body">
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif        <div class="container-fluid">


            <div class="row">
                <!-- Compact Patient Details Card -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 rounded-4 shadow-lg bg-white h-100">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between mb-4">
                                <h5 class="text-primary fw-bold d-flex align-items-center">
                                    <i class="bi bi-person-vcard-fill me-2 fs-4"></i> Patient Overview
                                </h5>
                                <span class="text-muted small">#{{ $patient->HN ?? 'N/A' }}</span>
                            </div>

                            <!-- Patient Info Section -->
                            <div class="row mb-4">
                                <div class="col-12 col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-fill text-info me-3 fs-4"></i>
                                        <div>
                                            <strong>Name:</strong>
                                            <span class="text-muted">{{ $patient->full_name ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-gender-male text-pink me-3 fs-4"></i>
                                        <div>
                                            <strong>Gender:</strong>
                                            <span class="text-muted">{{ $patient->gender ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar-date-fill text-success me-3 fs-4"></i>
                                        <div>
                                            <strong>Age:</strong>
                                            <span class="text-muted">{{ $age !== 'N/A' ? $age : 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-phone-fill text-warning me-3 fs-4"></i>
                                        <div>
                                            <strong>Mobile:</strong>
                                            <span class="text-muted">{{ $patient->mobile ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sessions Progress Section -->
                            <div class="row mt-auto">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong class="text-info">Sessions Bar</strong>
                                        <div>
                                            <span class="badge rounded-pill" style="background-color: #f9ad08; color: white;">
                                                Pending: {{ $patient_total_sessions - $total_session_taken ?? 'N/A' }}
                                            </span>
                                            <span class="badge rounded-pill" style="background-color: #28a745; color: white;">
                                                Taken: {{ $total_session_taken ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="progress" style="height: 8px; background-color: #f9ad08;">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ ($total_session_taken / $patient_total_sessions) * 100 }}%;
                                                background-color: {{ $total_session_taken > 0 ? '#28a745' : '#f9ad08' }};"
                                            aria-valuenow="{{ $total_session_taken }}"
                                            aria-valuemin="0"
                                            aria-valuemax="{{ $patient_total_sessions }}">
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <!-- SOAP Forms Card -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 rounded-4 shadow-lg bg-white h-100">
                        <div class="card-body p-4 d-flex flex-column justify-content-between">
                            <!-- SOAP Forms Title -->
                            <h5 class="text-primary fw-bold text-center mb-4">
                                <i class="bi bi-journal-text me-2 fs-4"></i> SOAP Forms
                            </h5>

                            <!-- Session Categories (PT and OT) -->
                            <div class="mb-4">
                                <h6 class="text-info fw-bold mb-3">
                                    <i class="bi bi-tags-fill me-2"></i> Session Categories
                                </h6>

                                <div class="mb-3">
                                    <h6 class="fw-bold text-primary small">
                                        <i class="bi bi-person-walking me-2"></i> PT Sessions
                                    </h6>
                                    <div class="d-flex flex-wrap gap-2">

                                        <span class="badge rounded-pill bg-success">
                                            Total: {{ $pt_sessions ?? 'N/A' }}
                                        </span>
                                        <span class="badge rounded-pill bg-warning text-dark">
                                            Pending: {{ $pt_sessions_pending ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    <h6 class="fw-bold text-primary small">
                                        <i class="bi bi-person-standing-dress me-2"></i> OT Sessions
                                    </h6>
                                    <div class="d-flex flex-wrap gap-2">

                                        <span class="badge rounded-pill bg-success">
                                            Total: {{ $ot_sessions ?? 'N/A' }}
                                        </span>
                                        <span class="badge rounded-pill bg-warning text-dark">
                                            Pending: {{ $ot_sessions_pending ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- SOAP Links -->
                            <div class="d-flex justify-content-around mt-auto">
                                <a href="{{ url('soap_pt_all/' . $session->patient_id) }}" class="text-decoration-none text-dark">
                                    <div class="d-flex flex-column align-items-center">
                                        <img src="{{ asset('images/logo/1.png') }}" class="rounded-circle shadow-sm mb-2"
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                        <div class="fw-bold small">SOAP-PT</div>
                                    </div>
                                </a>

                                <a href="{{ url('soap_ot_all/' . $session->patient_id ) }}" class="text-decoration-none text-dark">
                                    <div class="d-flex flex-column align-items-center">
                                        <img src="{{ asset('images/logo/2.png') }}" class="rounded-circle shadow-sm mb-2"
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                        <div class="fw-bold small">SOAP-OT</div>
                                    </div>
                                </a>
                            </div>



                        </div>
                    </div>
                </div>


            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm rounded-4 border-0">
                        <div class="card-body">


                                <ul class="nav nav-tabs mb-3">
                                    <li class="nav-item">
                                        <a href="#sessionsBody" data-bs-toggle="tab" class="nav-link active">
                                            <i class="bi bi-person-video2 me-1"></i>Sessions
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#total_appt_session" data-bs-toggle="tab" class="nav-link">
                                            <i class="bi bi-globe-americas me-1"></i>Sessions Detail
                                        </a>
                                    </li>
                                </ul>



                            <div class="tab-content">

                                <div class="tab-pane fade show active" id="sessionsBody">
                                    <div class="table-responsive">
                                        <table id="all_patient_session_table"
                                            class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th> Date</th>
                                                    <th>Doctor</th>
                                                    <th>Time</th>
                                                    <th>Type</th>
                                                    <th>Status</th>
                                                    <th>Action</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Visits Tab -->
                                <div class="tab-pane fade" id="total_appt_session">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered align-middle text-center">
                                            <thead class="table-danger">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Appt/Session No.</th>
                                                    <th>Source</th>
                                                    <th>Fee</th>
                                                    <th>Sessions</th>
                                                    <th>Fee/Session</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>



                            </div> <!-- end tab-content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



    <div class="modal fade" id="editSessionModal" tabindex="-1" aria-labelledby="editSessionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title" id="editSessionModalLabel">Edit Session</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="editSessionForm" autocomplete="off">
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
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4">Save</button>
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
<!-- Transfer Modal -->
<div class="modal fade" id="transferModal" tabindex="-1" aria-labelledby="transferModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg rounded-4 border-0">
            <div class="modal-header bg-info text-white rounded-top-4">
                <h5 class="modal-title" id="transferModalLabel">Transfer Session</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="transferForm">
                    <div class="row align-items-center g-3">
                        <!-- Source Patient -->
                        <div class="col-md-5">
                            <label for="source_patient" class="form-label">Source Patient</label>
                            <input type="text" class="form-control shadow-sm rounded" id="source_patient" name="source_patient" readonly>
                        </div>

                        <!-- Arrow Icon -->
                        <div class="col-md-2 text-center">
                            <div class="arrow-container">
                                <i class="bi bi-arrow-right-circle-fill arrow-icon text-primary"></i>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <label for="target_patient" class="form-label">New Patient</label>
                            <select class="form-control  shadow-sm" id="target_patient" name="target_patient">
                                <option value="">Choose...</option>
                                @foreach ($patients as $pat)
                                    <option value="{{ $pat->id }}">{{ $pat->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <!-- Date -->
                        <div class="col-md-6">
                            <label for="transfer_date" class="form-label">Session Date</label>
                            <input type="date" class="form-control shadow-sm rounded" id="ses_date" name="ses_date" readonly>
                        </div>

                        <!-- Notes -->
                        <div class="col-md-6">
                            <label for="notes" class="form-label">Session Time</label>
                            <input type="text" class="form-control shadow-sm rounded" id="ses_time" name="ses_time" readonly>
                        </div>
                    </div>
                    <input type="hidden" name="session_primary_id2" id="session_primary_id2">
                    <input type="hidden" name="patient_primary_id2" id="patient_primary_id2">

                    <input type="hidden" name="source2" id="source2">

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-info px-4 text-white">Transfer</button>
                        <button type="button" class="btn btn-outline-secondary ms-2" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>

                <style>
                    .arrow-container {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100%;
                    }

                    .arrow-icon {
                        font-size: 3rem;
                        font-weight: bold;
                        color: #0d6efd; /* Bootstrap primary */
                        animation: arrow-pop 0.3s ease-in-out;
                    }

                    @keyframes arrow-pop {
                        0% { transform: scale(0.8); opacity: 0; }
                        100% { transform: scale(1); opacity: 1; }
                    }
                </style>


            </div>
        </div>
    </div>
</div>
    @include('layouts.footer')
@endsection
