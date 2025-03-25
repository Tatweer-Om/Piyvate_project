@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.appointments_lang', [], session('locale')) }}</title>
    @endpush


    <div class="content-body">
        <div class="container">

            <!-- /add -->
            <div class="card">
                <div class="card-body">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">APPOINTMENT</h5>

                        <div class="form-group mb-0">
                            <input type="text" class="form-control form-control-sm" id="clinic_no" class="clinic_no" name="clinic_no"
                                   placeholder="Patinet Detail" style="max-width: 170px;">
                        </div>
                    </div>

                   <form class="add_appointment">
                     @csrf
                        <div class="row g-2">
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
                                <input type="text" class="form-control form-control-sm" id="first_name" name="first_name">
                            </div>
                            <div class="col-md-3">
                                <label class="col-form-label">Second Name:</label>
                                <input type="text" class="form-control form-control-sm" id="second_name" name="second_name">
                            </div>
                            <div class="col-md-3">
                                <label class="col-form-label">Mobile No:</label>
                                <input type="number" class="form-control form-control-sm" id="mobile" name="mobile">
                            </div>
                            <div class="col-md-3">
                                <label class="col-form-label">ID / Passport No:</label>
                                <input type="text" class="form-control form-control-sm" id="id_passport" name="id_passport">
                            </div>
                            <div class="col-md-3">
                                <label class="col-form-label">Date Of Birth:</label>
                                <input type="date" class="form-control form-control-sm" id="dob" name="dob">
                            </div>
                            <div class="col-md-3">
                                <label class="col-form-label">Country:</label>
                                <select class="form-control form-control-sm" id="country" name="country">
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="col-form-label">Doctor:</label>
                                <select class="form-control form-control-sm" id="doctor" name="doctor">
                                    <option value="">Choose...</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">{{ $doctor->doctor_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="col-form-label">Appointment Date:</label>
                                <input type="date" class="form-control form-control-sm" id="appointment_date" name="appointment_date">
                            </div>
                            <div class="col-md-2">
                                <label class="col-form-label">From:</label>
                                <div class="input-group clockpicker">
                                    <input type="text" class="form-control form-control-sm" id="time_from" name="time_from" value="09:30">
                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="col-form-label">To:</label>
                                <div class="input-group clockpicker">
                                    <input type="text" class="form-control form-control-sm" id="time_to" name="time_to" value="10:30">
                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                </div>
                            </div>


                            <!-- Select Box for Session (Initially Hidden) -->

                            <div class="col-md-2">
                                <label class="col-form-label">Appointment Fee:</label>
                                <div class="alert alert-info p-2" id="appointment_fee" style="font-weight: bold;">
                                    OMR {{ $setting->appointment_fee ?? '' }}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="col-form-label">Notes:</label>
                                <textarea class="form-control form-control-sm" id="notes" name="notes" rows="2"></textarea>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" id="open_payment_modal">
                                Add Payment
                            </button>

                        </div>
                </form>
                </div>
            </div>
            <!-- /add -->
        </div>
    </div>
    </div>

    <div class="modal fade" id="payment_modal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
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
                                            <input class="form-check-input payment-method-checkbox" type="checkbox" name="payment_methods[]" id="account_{{ $account->id }}" value="{{ $account->id }}" onchange="toggleAmountInput({{ $account->id }}, {{ $account->account_status }})">
                                            <label class="form-check-label fw-bold" for="account_{{ $account->id }}">
                                                {{ $account->account_name }}
                                            </label>
                                        </div>

                                        <!-- Amount Input (Initially Hidden) -->
                                        <input type="number" class="form-control form-control-sm payment-amount-input mt-1" id="amount_{{ $account->id }}" name="payment_amounts[{{ $account->id }}]" value="{{ $setting->appointment_fee ?? '0.00' }}" placeholder="Enter amount" min="0" step="0.01" style="display: none;">

                                        <!-- Ref No Input (Initially Hidden, Only if account_status != 1) -->
                                        @if($account->account_status != 1)
                                            <input type="text" class="form-control form-control-sm ref-no-input mt-1" id="ref_no_{{ $account->id }}" name="ref_nos[{{ $account->id }}]" placeholder="Enter Reference Number" style="display: none;">
                                        @endif
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

    <!-- JavaScript to Show Amount & Ref No Input when Checkbox is Selected -->
    <script>
        function toggleAmountInput(accountId, accountStatus) {
            var checkbox = document.getElementById("account_" + accountId);
            var amountInput = document.getElementById("amount_" + accountId);
            var refNoInput = document.getElementById("ref_no_" + accountId);

            if (checkbox.checked) {
                amountInput.style.display = "block";
                amountInput.required = true;

                if (accountStatus !== 1 && refNoInput) {
                    refNoInput.style.display = "block";
                    refNoInput.required = true;
                }
            } else {
                amountInput.style.display = "none";
                amountInput.required = false;
                amountInput.value = "";

                if (accountStatus !== 1 && refNoInput) {
                    refNoInput.style.display = "none";
                    refNoInput.required = false;
                    refNoInput.value = "";
                }
            }
        }
    </script>



@include('layouts.footer')
@endsection
