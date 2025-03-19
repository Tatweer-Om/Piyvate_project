@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.appointments_lang', [], session('locale')) }}</title>
    @endpush
    <style>
    /* Make button full-width on small screens */

</style>
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="page-titles d-flex justify-content-between align-items-center">
            <ol class="breadcrumb mb-0">
                <li class=""><a href="javascript:void(0)">Dashboard /</a></li>
                <li class="active"><a href="javascript:void(0)">Appointments</a></li>
            </ol>
            <div>
                <a href="appointments" class="btn btn-primary btn-rounded">+ Add Appointment</a>
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
                                        <th>Sr.No</th>
                                        <th> Patinet Name</th>
                                        <th>Doctor Name</th>
                                        <th>Recomendations</th>
                                        <th>Appoitnemnt Fee</th>
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
                <div class="row">
                    <div class="col-12 col-md-6">
                        <h5>Patient: <span id="patient_name" class="fw-normal"></span></h5>
                        <h5>Doctor: <span id="doctor_name" class="fw-normal"></span></h5>
                        <h5>Appointment Date: <span id="appointment_date" class="fw-normal"></span></h5>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="addSessionBtn">➕ Session</button>
                <button type="button" class="btn btn-warning" id="removeSessionBtn">➖ Session</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveSessionBtn">Save Sessions</button>
            </div>
        </div>
    </div>
</div>

<!-- Second Modal (Opens when clicking "Save Sessions") -->
<div class="modal fade" id="secondModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="secondModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Styled Header with Total Amount -->
            <div class="modal-header bg-primary text-white d-flex justify-content-between">
                <h5 class="modal-title fw-bold" id="paymentModalLabel">Payment</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form class="add_payment">
                    @csrf

                    <!-- Total Amount -->
                    <div class="mb-3">
                        <h4 class="text-center fw-bold text-danger">Total Amount: OMR <span id="total_amount">{{ $setting->appointment_fee ?? '0.00' }}</span></h4>
                    </div>

                    <hr>

                    <!-- Payment Method Title -->
                    <div class="col-lg-12">
                        <label class="col-form-label fw-bold fs-5">Select Payment Method</label>
                        <p class="text-muted">You can choose multiple payment methods and specify the amount for each.</p>
                    </div>

                    <!-- Payment Methods with Amount Input -->
                    <div class="col-lg-12">
                        <div class="row">
                            @foreach ($accounts as $account)
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input payment-method-checkbox" type="checkbox" name="payment_methods[]" id="account_{{ $account->id }}" value="{{ $account->id }}" onchange="toggleAmountInput({{ $account->id }})">
                                        <label class="form-check-label fw-bold" for="account_{{ $account->id }}">
                                            {{ $account->account_name }}
                                        </label>
                                    </div>
                                    <!-- Amount Input (Initially Hidden) -->
                                    <input type="number" class="form-control form-control-sm payment-amount-input mt-1" id="amount_{{ $account->id }}" name="payment_amounts[{{ $account->id }}]" placeholder="Enter amount" min="0" step="0.01" style="display: none;">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <hr>

                    <!-- Submit Buttons -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="confirm_payment">
                            <i class="fas fa-check"></i> Confirm Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to Open Second Modal -->
<script>
    document.getElementById('saveSessionBtn').addEventListener('click', function () {
        var secondModal = new bootstrap.Modal(document.getElementById('secondModal'));
        secondModal.show();
    });
</script>





<!-- Second Modal (Confirmation Modal) -->








@include('layouts.footer')
@endsection
