@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.patients_appointment_lang', [], session('locale')) }}</title>
    @endpush

    <style>
        #rightPopup {
            width: 70%;
            /* Adjust to your preferred width */
            height: 70%;
        }

        #leftPopup {
            width: 60%;
            /* Adjust to your preferred width */
            height: 60%;
        }

        .file-preview-item {
            position: relative;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
            width: 100px;
            text-align: center;
        }

        .file-preview-img {
            max-width: 100%;
            max-height: 70px;
            margin-bottom: 5px;
        }

        .remove-btn {
            position: absolute;
            top: 2px;
            right: 2px;
            background: #dc3545;
            color: #fff;
            border: none;
            border-radius: 50%;
            font-size: 12px;
            line-height: 1;
            width: 20px;
            height: 20px;
            cursor: pointer;
            padding: 0;
        }
    </style>

    <div class="content-body">

        @if (session('success'))
    <div class="alert alert-info alert-dismissible fade show" role="alert" id="flash-alert">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="flash-alert">
        {{ session('error') }}
    </div>
@endif
<script>
    setTimeout(function () {
        let alert = document.getElementById('flash-alert');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500); // Remove from DOM
        }
    }, 4000); // 2 seconds
</script>

        <!-- row -->
        <div class="container-fluid">

            <div class="row">
                <div class="row">
                    <!-- Compact Patient Details Card -->
                    <div class="col-lg-6 mb-3">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-body p-3">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-person-vcard-fill me-2"></i>Patient Overview
                                    <i class=" me-5"></i>#{{ $patient->HN ?? '' }}

                                </h6>
                                <div class="row gy-2 small">
                                    <div class="col-6 d-flex align-items-center">
                                        <i class="bi bi-person-fill text-info me-2 fs-6"></i>
                                        <span><strong>Name: </strong> {{ $patient->full_name ?? '' }}</span>
                                    </div>
                                    <div class="col-6 d-flex align-items-center">
                                        <i class="bi bi-gender-male text-primary me-2 fs-6"></i>
                                        <span><strong>Gender: </strong> {{ $patient->gender ?? '' }} </span>
                                    </div>
                                    <div class="col-6 d-flex align-items-center">
                                        <i class="bi bi-calendar-date-fill text-success me-2 fs-6"></i>
                                        <span><strong>Age:</strong>
                                            @if ($age !== 'N/A')
                                                {{ $age }}
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </div>

                                    <div class="col-6 d-flex align-items-center">
                                        <i class="bi bi-phone-fill text-warning me-2 fs-6"></i>
                                        <span><strong>Mobile: </strong> {{ $patient->mobile ?? '' }}</span>
                                    </div>

                                </div>


                            </div>
                        </div>
                    </div>
                    <!-- Compact Button Panel Card -->

                    <div class="col-lg-6 mb-3">
                        <div class="card shadow-sm rounded-3 position-relative">

                            <div class="card-body p-3 text-center">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-ui-checks-grid me-2"></i> Actions
                                </h6>

                                <!-- Existing buttons -->
                                <div class="d-grid gap-2 mb-3">
                                    @if (!empty($check1) || !empty($check2))
                                    <button class="btn btn-info btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#paymentModal">
                                        Contract Payment
                                    </button>
                                @endif

                                @if(isset($appointment) && $appointment->session_status != 1)
                                <button class="btn btn-success btn-sm rounded-pill" data-bs-toggle="offcanvas"
                                    data-bs-target="#rightPopup" aria-controls="rightPopup">Add Prescription</button>
                            @endif

                                    <button class="btn btn-warning btn-sm rounded-pill" data-bs-toggle="offcanvas"
                                        data-bs-target="#leftPopup" aria-controls="leftPopup">Lab Reports</button>
                                </div>

                                <!-- Other content -->
                                <div class="row g-3">
                                    <div class="col-4 d-none">
                                        <a href="{{ url("soap_pt/$patient->id") }}" class="text-decoration-none text-dark">
                                            <img src="{{ asset('images/logo/1.png') }}" class="img-fluid rounded shadow-sm"
                                                style="width: 45px; height: 45px; object-fit: cover;">
                                            <div class="small mt-1">SOAP-PT</div>
                                        </a>
                                    </div>
                                    <div class="col-4 d-none">
                                        <a href="{{ url("soap_ot/$patient->id") }}" class="text-decoration-none text-dark">
                                            <img src="{{ asset('images/logo/2.png') }}" class="img-fluid rounded shadow-sm"
                                                style="width: 45px; height: 45px; object-fit: cover;">
                                            <div class="small mt-1">SOAP-OT</div>
                                        </a>

                                    </div>
                                    <div class="col-4">
                                        <a href="{{ url("physical_dysfunction/$patient->id") }}" class="text-decoration-none text-dark">
                                            <img src="{{ asset('images/logo/3.png') }}" class="img-fluid rounded shadow-sm"
                                                style="width: 45px; height: 45px; object-fit: cover;">
                                            <div class="small mt-1">OTP-PHY.DF</div>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ url("otatp_ortho/$patient->id") }}" class="text-decoration-none text-dark">
                                            <img src="{{ asset('images/logo/4.png') }}" class="img-fluid rounded shadow-sm"
                                                style="width: 45px; height: 45px; object-fit: cover;">
                                            <div class="small mt-1">PT-ORTHO</div>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ url("otatp_pedriatic/$patient->id") }}" class="text-decoration-none text-dark">
                                            <img src="{{ asset('images/logo/5.png') }}" class="img-fluid rounded shadow-sm"
                                                style="width: 45px; height: 45px; object-fit: cover;">
                                            <div class="small mt-1">OTP-PEDIATRICS</div>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ url("neuro_pedriatic/$patient->id") }}" class="text-decoration-none text-dark">
                                            <img src="{{ asset('images/logo/6.png') }}" class="img-fluid rounded shadow-sm"
                                                style="width: 45px; height: 45px; object-fit: cover;">
                                            <div class="small mt-1">PT-NEURO-PED</div>
                                        </a>
                                    </div>
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
                                        <a href="#singleappointmentsdetailtable" data-bs-toggle="tab" class="nav-link active">
                                            <i class="bi bi-file-check-fill me-1"></i>Appointments Detail
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#allappointmentsessiontable2" data-bs-toggle="tab" class="nav-link">
                                            <i class="bi bi-globe-americas me-1"></i>Sessions Detail
                                        </a>
                                    </li>


                                </ul>

                                <div class="tab-content">

                                    <div class="tab-pane fade  show active" id="singleappointmentsdetailtable">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered align-middle text-center">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th>Apointment</th>
                                                        <th>Sessions</th>
                                                        <th>Status</th>
                                                        <th>Tests</th>
                                                        <th>Reports</th>
                                                        <th>Detail</th>
                                                        <th>Notes</th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="allappointmentsessiontable2">
                                        <div class="table-responsive">
                                            <table class="allappointmentsessiontable table table-striped table-bordered align-middle text-center" id="allappointmentsessiontable">
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th> Date</th>
                                                        <th>Doctor</th>
                                                        <th>Time</th>
                                                        <th>Type</th>
                                                        <th>Status</th>

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
    </div>



    <div class="offcanvas offcanvas-end" tabindex="-1" id="rightPopup" aria-labelledby="rightPopupLabel">
        <div class="offcanvas-header bg-primary text-white">
            <h5 class="offcanvas-title" id="rightPopupLabel">Create Prescription</h5>
            <button type="button" class="btn-close text-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <form id="prescriptionForm">
                <!-- Prescription Type -->
                <div class="mb-3">
                    <label class="form-label me-3">Prescription Type:</label>
                    @if(empty($data_check))
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="prescription_type" id="type_appointment"
                            value="appointment" checked>
                        <label class="form-check-label" for="type_appointment">Appointment Only</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="prescription_type" id="type_session"
                            value="session">
                        <label class="form-check-label" for="type_session">Sessions Recommendation</label>
                    </div>
                    @endif
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="prescription_type" id="type_test"
                            value="test">
                        <label class="form-check-label" for="type_test">Test Recommendation</label>
                    </div>
                </div>

                <!-- Session Inputs (hidden initially) -->
                <div id="sessionInputs" class="row mb-3" style="display: none;">
                    <input type="hidden" name="patient_id" value="{{ $patient->id ?? '' }}">
                    <input type="hidden" name="appointment_id" value="{{ $apt_id ?? '' }}">

                    <div class="col-md-4">
                        <label class="col-form-label">Session Category</label>
                        <div class="form-check">
                            <input class="form-check-input session-checkbox" type="checkbox" value="OT" id="checkbox_ot" name="session_types[]">
                            <label class="form-check-label" for="checkbox_ot">OT</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input session-checkbox" type="checkbox" value="PT" id="checkbox_pt" name="session_types[]">
                            <label class="form-check-label" for="checkbox_pt">PT</label>
                        </div>
                    </div>

                    <div class="col-md-2" id="ot_sessions_box" style="display: none;">
                        <div class="form-group">
                            <label for="ot_sessions" class="col-form-label">OT Sessions</label>
                            <input type="number" class="form-control" id="ot_sessions" name="ot_sessions"  min="0">
                        </div>
                    </div>

                    <div class="col-md-2" id="pt_sessions_box" style="display: none;">
                        <div class="form-group">
                            <label for="pt_sessions" class="col-form-label">PT Sessions</label>
                            <input type="number" class="form-control" id="pt_sessions" name="pt_sessions"  min="0">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="session_gap" class="col-form-label">Session Gap</label>
                            <input type="number" class="form-control" id="session_gap" name="session_gap" placeholder="Enter Session Gap" min="0">
                        </div>
                    </div>
                </div>




                <!-- Test Inputs (hidden initially) -->
                <!-- Test Recommendations -->
                <div id="testInputs" class="mb-3" style="display: none;">
                    <label for="test_recommendations" class="form-label">Test Recommendations</label>
                    <div id="testList">
                        <div class="row mb-2" id="testInput1">
                            <div class="col-lg-4">
                                <input type="text" class="form-control" name="test_recommendation[]"
                                    placeholder="Enter test name">
                            </div>
                            <div class="col-lg-2">
                                <button type="button" class="btn btn-outline-success addTestInput">+</button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Notes -->
                <div class="mb-3">
                    <label for="notes" class="form-label">Clinical Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Enter additional notes"></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Save Prescription</button>
            </form>
        </div>
    </div>



    <div class="offcanvas offcanvas-start" tabindex="-1" id="leftPopup" aria-labelledby="leftPopupLabel">
        <div class="offcanvas-header bg-warning text-dark">
            <h5 class="offcanvas-title" id="leftPopupLabel">Upload Lab Reports</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <form id="labReportForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id ?? '' }}">
                <input type="hidden" name="appoint_id" value="{{ $apt_id ?? '' }}">


                <div class="mb-3">
                    <label class="form-label">Select Lab Report Files</label>

                    <!-- Hidden file input -->
                    <input type="file" id="file_upload" name="lab_reports[]" multiple class="d-none"
                        accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx">

                    <!-- Trigger for file input -->
                    <div id="filePreview" class="border p-3 rounded text-center bg-light" style="cursor: pointer;">
                        <i class="bi bi-upload me-2"></i> Click to select files
                    </div>

                    <!-- File preview area -->
                    <div id="file-preview" class="row mt-3 g-2"></div>
                </div>

                <button type="submit" class="btn btn-warning mt-3">Upload Reports</button>
            </form>


        </div>
    </div>


    <div class="modal fade" id="notesModal" tabindex="-1" aria-labelledby="notesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="notesModalLabel">Prescription Notes</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="notesContent">
              <!-- Prescription notes will be loaded here -->
            </div>
          </div>
        </div>
      </div>

      <!-- Modal for Appointment Notes -->
      <div class="modal fade" id="appointmentNotesModal" tabindex="-1" aria-labelledby="appointmentNotesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="appointmentNotesModalLabel">Appointment Notes</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="appointmentNotesContent">
              <!-- Appointment notes will be loaded here -->
            </div>
          </div>
        </div>
      </div>

    @include('layouts.footer')
@endsection
