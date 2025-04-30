@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.appointments_lang', [], session('locale')) }}</title>
    @endpush

<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="page-titles d-flex justify-content-between align-items-center">
            <ol class="breadcrumb mb-0">
                <li class=""><a href="javascript:void(0)">Dashboard /</a></li>
                <li class="active"><a href="javascript:void(0)">Appointments</a></li>
            </ol>
            <div class="d-flex gap-2">
                <a href="appointments" class="btn btn-primary btn-rounded">+ Appointment</a>
                <a href="{{ url('sessions_list') }}" class="btn btn-secondary btn-rounded">+ Session</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="all_appointments" class="table table-striped  mb-4 dataTablesCard fs-14">
                                <thead>
                                    <tr>
                                        <th>Appt.No</th>
                                        <th> Patinet Name</th>
                                        <th>Doctor Name</th>
                                        <th>Appointment Status</th>
                                        <th>Appoitnemnt Fee</th>
                                        <th>Session Fee</th>
                                        <th>Appointment Date</th>
                                        <th>Added By </th>
                                        <th>Added On </th>
                                        <th class="text-end">Action</th>
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

<div class="modal fade" id="sessionModal" tabindex="-1" aria-labelledby="sessionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sessionModalLabel">Doctor Prescription Sessions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body overflow-auto" style="max-height: 70vh;">
                <form class="sessionForm">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <h5>Patient: <span id="patient_name" class="fw-normal"></span></h5>
                            <input type="hidden" id="patient_id" name="patient_id">
                            <h5>Doctor: <span id="doctor_name" class="fw-normal"></span></h5>
                            <h5>Appointment Date: <span id="appointment_date" class="fw-normal"></span></h5>
                        </div>
                        <input type="hidden" id="doctor_id" name="doctor_id">
                        <input type="hidden" id="appointment_id" name="appointment_id">

                        <div class="col-12 col-md-6">
                            <div class="d-flex justify-content-end align-items-center">
                                <label class="col-form-label mb-0 me-2">Session Type:</label>
                                <div class="d-flex gap-2">
                                    <input type="radio" name="session_type" value="normal" checked> Normal
                                    <input type="radio" name="session_type" value="offer"> Offer
                                    <input type="radio" name="session_type" value="ministry"> Pact
                                </div>
                            </div>

                            <input type="hidden" id="hiddenMinistryPrice" name="ministry_price">
                            <input type="hidden" id="hiddenOfferPrice" name="offer_price">
                            <input type="hidden" id="hiddenSessionPrice" name="session_price">
                            <input type="hidden" id="hiddenTotalPrice" name="total_price">
                            <!-- Ministry & Offer Select Box -->
                            <div class="row mt-3">
                                <div id="ministryOptions" class="col-md-6" style="display: none;">
                                    <label class="col-form-label">Ministry:</label>
                                    <select id="ministrySelect" name="ministry_id" class="form-control form-control-sm">
                                        <option value="">Select Ministry</option>
                                        <?php foreach ($ministries as $ministry) : ?>
                                            <option value="<?= $ministry['id'] ?>"><?= $ministry['govt_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="d-flex gap-2 mt-2">
                                        <span id="sessionCategory" class="badge bg-primary">Department Category: </span>
                                        <span id="sessionPrice" class="badge bg-success">Price: OMR</span>
                                    </div>
                                </div>

                                <div id="offerOptions" class="col-md-6" style="display: none;">
                                    <label class="col-form-label">Offer:</label>
                                    <select id="offerSelect" name="offer_id" class="form-control form-control-sm">
                                        <option value="">Select Offer</option>
                                        <?php foreach ($offers as $offer) : ?>
                                            <option value="<?= $offer['id'] ?>"><?= $offer['offer_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="d-flex gap-2 mt-2">
                                        <span id="offerPrice" class="badge bg-success">Price: OMR</span>
                                        <span id="session_count" class="badge bg-success">Total Sessions: </span>

                                    </div>
                                </div>
                                <div id="sessionOptions" class="col-md-6" style="display: none;">
                                    <label class="col-form-label">Session:</label>
                                    <select id="sessionSelect" name="session_id" class="form-control form-control-sm">
                                        <option value="">Select Session</option>
                                        <?php foreach ($sessions as $session) : ?>
                                            <option value="<?= $session['id'] ?>"><?= $session['session_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="d-flex gap-2 mt-2">
                                    <span id="session_Price" class="badge bg-success">Single Session Price: OMR</span>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table id="session_table" class="table table-bordered mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="6" class="text-center bg-dark text-white" style="padding: 5px; font-size: 16px; height: 30px; vertical-align: middle;">
                                        Sessions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" name="sessions" id="sessions_input">

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="addSessionBtn">+ Session</button>
                <button type="button" class="btn btn-warning" id="removeSessionBtn">- Session</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="saveSessionBtn">Save Sessions</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="secondModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="secondModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- Centered and larger modal for better view -->
        <div class="modal-content">
            <!-- Styled Header with Total Amount -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="paymentModalLabel">Payment</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form class="add_payment2">
                    @csrf

                    <!-- Total Amount -->
                    <div class="mb-3 text-center">
                        <h4 class="fw-bold text-danger">Total Amount: OMR <span id="total_amount"></span></h4>
                    </div>

                    <hr>

                    <input type="hidden" name="appointment_id2" class="appointment_id2">
                    <input type="hidden" name="payment_status" class="payment_status" id="payment_status">

                    <!-- Payment Status Message (Only for Pending Payments) -->
                    <div id="pendingPaymentAlert" class="alert alert-warning text-center d-none">
                        <i class="fas fa-exclamation-triangle"></i> <strong>This payment will be kept as pending.</strong>
                    </div>

                    <!-- Payment Method Title -->
                    <div class="mb-3">
                        <label class="col-form-label fw-bold fs-5">Select Payment Method</label>
                        <p class="text-muted">You can choose multiple payment methods and specify the amount for each.</p>
                    </div>

                    <!-- Payment Methods with Amount Input -->
                    <div class="row" id="accountss">
                        @foreach ($accounts as $account)
                            <div class="col-12 col-md-6 mb-3"> <!-- Stacks on small screens, 2 columns on medium+ -->
                                <div class="form-check">
                                    <input class="form-check-input payment-method-checkbox" type="checkbox" name="payment_methods[]" id="account_{{ $account->id }}" value="{{ $account->id }}" onchange="toggleAmountInput({{ $account->id }})">
                                    <label class="form-check-label fw-bold" for="account_{{ $account->id }}">
                                        {{ $account->account_name }}
                                    </label>
                                </div>
                                <!-- Amount Input (Initially Hidden) -->

                                <input type="number" class="form-control form-control-sm payment-amount-input mt-1" id="amount_{{ $account->id }}" name="payment_amounts[{{ $account->id }}]" placeholder="Enter amount" min="0" step="0.01" style="display: none;">
                                @if($account->account_status != 1)
                                <input type="text" class="form-control form-control-sm payment-ref-input mt-1"
                                id="ref_no_{{ $account->id }}" name="payment_ref_nos[{{ $account->id }}]"
                                placeholder="Enter Ref No (if required)" style="display: none;">
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <hr>

                    <span id="paymentStatusBadge" class="badge d-none"></span>

                    <!-- Submit Buttons -->
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-danger w-100 me-2" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success w-100" id="confirm_payment2">
                            <i class="fas fa-check"></i> Confirm Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>





<script>
 function toggleAmountInput(accountId) {
    var checkbox = document.getElementById("account_" + accountId);
    var amountInput = document.getElementById("amount_" + accountId);
    var refNoInput = document.getElementById("ref_no_" + accountId);

    if (checkbox.checked) {
        amountInput.style.display = "block";
        refNoInput.style.display = "block";
        amountInput.required = true;
    } else {
        amountInput.style.display = "none";
        refNoInput.style.display = "none";
        amountInput.required = false;
        amountInput.value = "";
        refNoInput.value = "";
    }
}

</script>




@include('layouts.footer')
@endsection
