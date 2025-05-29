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
                        <div class="card shadow-sm rounded-3 position-relative">
                            <!-- Display appointment number at top-right corner -->
                            {{-- <div class="position-absolute top-0 end-0 p-3">
                                <span class="fw-bold text-primary" style="font-size: 0.6rem;">#{{ $apt->appointment_no }}</span>
                            </div> --}}


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
                                        <a href="#appointmentsdetailtable" data-bs-toggle="tab" class="nav-link active">
                                            <i class="bi bi-file-check-fill me-1"></i>Appointments Detail
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#total_appt_session" data-bs-toggle="tab" class="nav-link">
                                            <i class="bi bi-globe-americas me-1"></i>Sessions Detail
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#payment" data-bs-toggle="tab" class="nav-link">
                                            <i class="bi bi-globe-americas me-1"></i>Payment History
                                        </a>
                                    </li>


                                </ul>

                                <div class="tab-content">



                                    <div class="tab-pane fade  show active" id="appointmentsdetailtable">
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
                                    <div class="tab-pane fade" id="total_appt_session">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered align-middle text-center">
                                                <thead class="table-danger">
                                                    <tr>
                                                        <th>Session/Apt No.</th>
                                                        <th>Status</th>
                                                        <th>Info</th>
                                                        <th>Fee/Session</th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="payment">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered align-middle text-center" id="payment_history">
                                                <thead class="table-info">
                                                    <tr>
                                                        <th>Session/Apt No.</th>
                                                        <th>Status</th>
                                                        <th>Payment</th>
                                                        <th>sessions</th>

                                                        <th>Fee/Session</th>
                                                        <th>Appt_fee</th>


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






 <!-- Modal for Prescription Notes -->
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
