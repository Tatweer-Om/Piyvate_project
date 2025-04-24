@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.patients_profile_lang', [], session('locale')) }}</title>
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


                                <hr class="my-3">

                                <div class="row text-center small">
                                    <div class="col-4 d-flex align-items-stretch">
                                        <div class="bg-warning bg-opacity-10 rounded-3 p-3 d-flex flex-column justify-content-between" style="height: 120px;">
                                            <i class="fas fa-calendar-check text-info fs-5 mb-2"></i>
                                            <div class=" text-warning">{{ $total_apt ?? 'N/A' }}</div>
                                            <div class="text-warning">Total Appointments</div>
                                        </div>
                                    </div>
                                    <div class="col-4 d-flex align-items-stretch">
                                        <div class="bg-success bg-opacity-10 rounded-3 p-3 d-flex flex-column justify-content-between" style="height: 120px;">
                                            <i class="fas fa-video text-success fs-5 mb-2"></i>
                                            <div class=" text-success">{{ $apt->appointment_type ?? 'N/A' }}</div>
                                            <div class="text-success">Appointment Type</div>
                                        </div>
                                    </div>
                                    <div class="col-4 d-flex align-items-stretch">
                                        <div class="bg-danger bg-opacity-10 rounded-3 p-3 d-flex flex-column justify-content-between" style="height: 120px;">
                                            <i class="fas fa-map-marker-alt text-danger fs-5 mb-2"></i>
                                            <div class=" text-danger">{{ $country_name ?? 'Not Provided' }}</div>
                                            <div class="text-danger">Country</div>
                                        </div>
                                    </div>
                                </div>
                                <hr>

                                <div class="row text-center small">
                                    <div class="col-4 d-flex align-items-stretch">
                                        <div class="bg-success bg-opacity-10 rounded-3 p-3 d-flex flex-column justify-content-between" style="height: 120px;">
                                            <i class="fas fa-check-circle text-success fs-5 mb-2"></i>
                                            <div class=" text-success">{{ $patient_total_sessions ?? 'N/A' }}</div>
                                            <div class="text-success">Total Sessions</div>
                                        </div>
                                    </div>
                                    <div class="col-4 d-flex align-items-stretch">
                                        <div class="bg-danger bg-opacity-10 rounded-3 p-3 d-flex flex-column justify-content-between" style="height: 120px;">
                                            <i class="fas fa-user-times text-danger fs-5 mb-2"></i>
                                            <div class=" text-danger">{{ $total_active_session ?? 'N/A' }}</div>
                                            <div class="text-danger">Active Sessions</div>
                                        </div>
                                    </div>
                                    <div class="col-4 d-flex align-items-stretch">
                                        <div class="bg-info bg-opacity-10 rounded-3 p-3 d-flex flex-column justify-content-between" style="height: 120px;">
                                            <i class="fas fa-check text-info fs-5 mb-2"></i>
                                            <div class=" text-info">{{ $total_session_taken ?? 'N/A' }}</div>
                                            <div class="text-info">Sessions Taken</div>
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                    <!-- Compact Button Panel Card -->
                    <div class="col-lg-6 mb-3">
                        <div class="card shadow-sm rounded-3">
                            <div class="card-body p-3 text-center">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-ui-checks-grid me-2"></i> Actions
                                </h6>

                                <!-- Existing buttons -->
                                <div class="d-grid gap-2 mb-3">
                                    <button class="btn btn-info btn-sm rounded-pill" data-bs-toggle="modal"
                                        data-bs-target="#paymentModal">Contract Payment</button>
                                    <button class="btn btn-success btn-sm rounded-pill" data-bs-toggle="offcanvas"
                                        data-bs-target="#rightPopup" aria-controls="rightPopup">Add Prescription</button>
                                    <button class="btn btn-warning btn-sm rounded-pill" data-bs-toggle="offcanvas"
                                        data-bs-target="#leftPopup" aria-controls="leftPopup">Lab Reports</button>
                                </div>

                                <!-- Grid of 6 clickable images -->
                                <div class="row g-3">
                                    <div class="col-4">
                                        <a href="{{ url("soap_pt/$patient->id") }}"
                                            class="text-decoration-none text-dark">
                                            <img src="{{ asset('images/logo/1.png') }}" class="img-fluid rounded shadow-sm"
                                                style="width: 45px; height: 45px; object-fit: cover;">
                                            <div class="small mt-1">SOAP-PT</div>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ url("soap_ot/$patient->id") }}"
                                            class="text-decoration-none text-dark">
                                            <img src="{{ asset('images/logo/2.png') }}" class="img-fluid rounded shadow-sm"
                                                style="width: 45px; height: 45px; object-fit: cover;">
                                            <div class="small mt-1">SOAP-OT</div>
                                        </a>

                                    </div>
                                    <div class="col-4">
                                        <a href="{{ url("physical_dysfunction/$patient->id") }}"
                                            class="text-decoration-none text-dark">
                                            <img src="{{ asset('images/logo/3.png') }}" class="img-fluid rounded shadow-sm"
                                                style="width: 45px; height: 45px; object-fit: cover;">
                                            <div class="small mt-1">OTP-PHY.DF</div>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ url("otatp_ortho/$patient->id") }}"
                                            class="text-decoration-none text-dark">
                                            <img src="{{ asset('images/logo/4.png') }}" class="img-fluid rounded shadow-sm"
                                                style="width: 45px; height: 45px; object-fit: cover;">
                                            <div class="small mt-1">PT-ORTHO</div>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ url("otatp_pedriatic/$patient->id") }}"
                                            class="text-decoration-none text-dark">
                                            <img src="{{ asset('images/logo/5.png') }}" class="img-fluid rounded shadow-sm"
                                                style="width: 45px; height: 45px; object-fit: cover;">
                                            <div class="small mt-1">OTP-PEDIATRICS</div>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ url("neuro_pedriatic/$patient->id") }}"
                                            class="text-decoration-none text-dark">
                                            <img src="{{ asset('images/logo/6.png') }}"
                                                class="img-fluid rounded shadow-sm"
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
                                        <a href="#appointmentstable" data-bs-toggle="tab" class="nav-link active">
                                            <i class="bi bi-calendar-check-fill me-1"></i>Appointments
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#sessionsBody" data-bs-toggle="tab" class="nav-link">
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

                                    <!-- Appointments Tab -->
                                    <div class="tab-pane fade show active" id="appointmentstable">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered align-middle text-center">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th>Aptt_no</th>
                                                        <th>Appt Date</th>
                                                        <th>Doctor</th>
                                                        <th>Status</th>
                                                        <th>Action</th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Sessions Tab -->
                                    <div class="tab-pane fade" id="sessionsBody">
                                        <div class="table-responsive">
                                            <table id="all_patient_session_table"
                                                class="table table-striped table-bordered" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Session Date</th>
                                                        <th>Doctor</th>
                                                        <th>Session Time</th>
                                                        {{-- <th>Session Fee</th> --}}
                                                        <th>Session Status</th>
                                                        <th>Action</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Data will be populated dynamically here -->
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
                                    {{-- <div class="tab-pane fade" id="payment_history">
                                        <div class="table-responsive">
                                            <table id="payment_table" class="table table-striped table-bordered align-middle text-center">
                                                <thead class="table-danger">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Appt/Session </th>
                                                        <th>Status</th>
                                                        <th>Doctor Name</th>
                                                        <th>Session Date</th>
                                                        <th>Session-Payment</th>
                                                        <th>Fee</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Data will be dynamically loaded here -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div> --}}


                                </div> <!-- end tab-content -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-xxl-6">
                    <div class="card">
                        <div class="card-header border-0 pb-0">
                            <h4 class="fs-20 font-w600">Assigned Doctor</h4>
                        </div>
                        <div class="card-body">
                            <div class="media d-sm-flex text-sm-start d-block text-center">
                                <img alt="image" class="rounded me-sm-4 me-0 mb-2 mb-sm-0" width="130"
                                    src="images/avatar/2.jpg">
                                <div class="media-body">
                                    <h3 class="fs-22 text-black font-w600 mb-0">Dr. Samantha</h3>
                                    <p class="text-primary">Physical Therapy</p>
                                    <div class="social-media mb-sm-0 mb-3 justify-content-sm-start justify-content-center">
                                        <a href="javascript:void(0);"><i class="lab la-instagram ms-0"></i></a>
                                        <a href="javascript:void(0);"><i class="lab la-facebook-f"></i></a>
                                        <a href="javascript:void(0);"><i class="lab la-twitter"></i></a>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <span class="num">4.0</span>
                                    <div class="star-icons">
                                        <i class="las la-star"></i>
                                        <i class="las la-star"></i>
                                        <i class="las la-star"></i>
                                        <i class="las la-star"></i>
                                        <i class="las la-star"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (!empty($notes))
                <div class="col-xl-6 col-xxl-6">
                    <div class="card patient-detail">
                        <div class="card-header border-0 pb-0">
                            <h4 class="fs-20 font-w600 text-white">Clinical Notes</h4>
                        </div>
                        <div class="card-body fs-14 font-w300">
                            <table class="table table-borderless align-middle mb-0">
                                <tbody>
                                    @foreach ($notes as $note)
                                    @php
                                        if ($note->notes_status == 1) {
                                            $img = asset('images/logo/6.png');
                                            $view = route('neuro_pedriatic_view', $note->id);
                                            $edit = route('neuro_pedriatic_view', $note->id);
                                        } elseif ($note->notes_status == 2) {
                                            $img = asset('images/logo/4.png');
                                            $view = route('edit_otatp_ortho', $note->id);
                                            $edit = route('edit_otatp_ortho', $note->id);
                                        } elseif ($note->notes_status == 3) {
                                            $img = asset('images/logo/5.png');
                                            $view = route('edit_otp_pediatric', $note->id);
                                            $edit = route('edit_otp_pediatric', $note->id);
                                        } elseif ($note->notes_status == 4) {
                                            $img = asset('images/logo/3.png');
                                            $view = route('edit_physical_dysfunction', $note->id);
                                            $edit = route('edit_physical_dysfunction', $note->id);
                                        } elseif ($note->notes_status == 5) {
                                            $img = asset('images/logo/2.png');

                                            $view = route('edit_soap_ot', $note->id);
                                            $edit = route('edit_soap_ot', $note->id);
                                        } elseif ($note->notes_status == 6) {
                                            $img = asset('images/logo/1.png');
                                            $view = route('edit_soap_pt', $note->id);
                                            $edit = route('edit_soap_pt', $note->id);
                                        } else {
                                            $img = asset('images/dummy_images/no_image.jpg');

                                            $view = '#';
                                            $edit = '#';
                                        }
                                    @endphp

                                    <tr>
                                        <td style="width: 60px;">
                                            <img src="{{ $img }}" alt="Patient Image"
                                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                        </td>
                                        <td>
                                            <strong>{{ $note->form_type ?? '' }}</strong>
                                        </td>
                                        <td style="text-align: right;">

                                            <a href="{{ $view }}" target="_blank" title="View" class="me-2">
                                                <i class="fas fa-eye text-primary"></i>
                                            </a>
                                            <a href="{{ $edit }}" title="Edit">
                                                <i class="fas fa-edit text-warning"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content rounded-4 shadow-sm">

                <!-- Header -->
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold text-primary" id="paymentModalLabel">
                        <i class="bi bi-cash-stack me-2"></i> Payment Details under Contract
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Patient & Contract Info -->
                <div class="modal-body px-4 py-3">
                    <div class="mb-4 text-center">
                        <h6 class="text-dark fw-semibold mb-1">{{ $patient->full_name ?? '' }}</h6>
                        <small class="text-muted d-block mb-1">HN: {{ $patient->HN ?? '' }}</small>

                        @if (!empty($ministry_name))
                            <small class="text-muted d-block mb-1">Ministry: {{ $ministry_name }}</small>
                        @endif

                        @if (!empty($ministry_data))
                            @if ($ministry_data['type'] === 'session')
                                <small class="text-muted d-block mb-1">
                                    Sessions: <strong>{{ $ministry_data['no_of_sessions'] }}</strong>
                                </small>
                                <small class="text-muted d-block mb-1">
                                    Sessions Fee: <strong>{{ $ministry_data['session_fee'] }}</strong>
                                </small>
                            @elseif ($ministry_data['type'] === 'appointment')
                                <small class="text-muted d-block mb-1">
                                    Total Sessions: <strong>{{ $ministry_data['total_sessions'] }}</strong>
                                </small>
                                <small class="text-muted d-block mb-1">
                                    Total Price: <strong>{{ $ministry_data['total_price'] }}</strong>
                                </small>
                            @endif
                        @endif
                    </div>

                    <!-- Payment Form -->
                    <form class="add_payment" id="paymentForm">
                        @csrf

                        <!-- Hidden Inputs -->
                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                        <input type="hidden" name="appointment_id"
                            value="{{ $ministry_data['appointment_id'] ?? '' }}">
                        <input type="hidden" name="session_id" value="{{ $ministry_data['id'] ?? '' }}">
                        <input type="hidden" name="ministry_id" value="{{ $ministry_data['ministry_id'] ?? '' }}">
                        <input type="hidden" name="type" value="{{ $ministry_data['type'] ?? '' }}">
                        <input type="hidden" name="total_sessions"
                            value="{{ $ministry_data['total_sessions'] ?? ($ministry_data['no_of_sessions'] ?? '') }}">
                        <input type="hidden" name="total_price"
                            value="{{ $ministry_data['total_price'] ?? ($ministry_data['session_fee'] ?? '') }}">

                        <!-- Total Amount -->
                        <div class="mb-3">
                            <h4 class="text-center fw-bold text-danger">Total Amount: OMR <span
                                    id="total_amount">{{ $ministry_data['total_price'] ?? ($ministry_data['session_fee'] ?? '0.00') }}</span>
                            </h4>
                        </div>

                        <hr>

                        <!-- Payment Method Selection -->
                        <div class="col-lg-12">
                            <label class="col-form-label fw-bold fs-5">Select Payment Method</label>
                            <p class="text-muted">You can choose multiple payment methods and specify the amount for each.
                            </p>

                            <div class="row">
                                @foreach ($accounts as $account)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input payment-method-checkbox" type="checkbox"
                                                name="payment_methods[]" id="account_{{ $account->id }}"
                                                value="{{ $account->id }}"
                                                onchange="toggleAmountInput({{ $account->id }}, {{ $account->account_status }})">
                                            <label class="form-check-label fw-bold" for="account_{{ $account->id }}">
                                                {{ $account->account_name }}
                                            </label>
                                        </div>

                                        <input type="number"
                                            class="form-control form-control-sm payment-amount-input mt-1"
                                            id="amount_{{ $account->id }}" name="payment_amounts[{{ $account->id }}]"
                                            placeholder="Enter amount" min="0" step="0.01"
                                            style="display: none;">

                                        @if ($account->account_status != 1)
                                            <input type="text" class="form-control form-control-sm ref-no-input mt-1"
                                                id="ref_no_{{ $account->id }}" name="ref_nos[{{ $account->id }}]"
                                                placeholder="Enter Reference Number" style="display: none;">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Footer Buttons -->
                <div class="modal-footer border-0 px-4 pb-4 pt-2">
                    <button type="button" class="btn btn-outline-secondary rounded-pill"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" form="paymentForm" class="btn btn-success rounded-pill contract_payment">
                        <i class="fas fa-check"></i> Submit Payment
                    </button>
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
                        <div class="form-group">
                            <label for="session_cat" class="col-form-label">Session Category</label>
                            <select class="form-control" id="session_cat" name="session_cat">
                                <option value="">Choose...</option>
                                <option value="OT">OT</option>
                                <option value="PT">PT</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sessions_recommended" class="col-form-label">Sessions Recommended</label>
                            <input type="number" class="form-control" id="sessions_recommended"
                                name="sessions_recommended" placeholder="Enter number of sessions">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="session_gap" class="col-form-label">Session Gap</label>
                            <input type="text" class="form-control" id="session_gap" name="session_gap"
                                placeholder="Enter Session Gap">
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
