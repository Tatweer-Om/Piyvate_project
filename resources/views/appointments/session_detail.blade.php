@extends('layouts.header')

@section('main')
    @push('title')
        <title>Doctor Prescription Sessions</title>
    @endpush
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        $(document).ready(function () {
            var sessionId = "{{ $session->id }}"; // Assuming session ID is available in Blade
            session_detail(sessionId);
        });
    </script>

    <div class="content-body">
        <div class="container">
            <form id="addSessionForm">
                <div class="container">
                    <div class="card shadow-lg">
                        <div class="card-body overflow-auto" style="max-height: 70vh;">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <h5>Patient: <span id="patient_name" class="fw-normal">{{ $patient_name ?? '' }}</span></h5>
                                    <h5>Doctor: <span id="doctor_name" class="fw-normal">{{ $doctor_name ?? '' }}</h5>

                                    @if(!empty($mini_name))
                                        <h5>Agreement Under: <span class="fw-normal">{{ $mini_name ?? ''}}</span></h5>
                                    @endif

                                    @if(!empty($offer_name))
                                        <h5>Offer Name: <span class="fw-normal">{{ $offer_name ?? ''}}</span></h5>
                                    @endif

                                    <h5>Total Sessions: <span id="total_sessions" class="fw-normal">{{ $session->no_of_sessions ?? '' }}</span></h5>
                                    <h5>Total Fee:
                                        <span id="total_fee" class="fw-normal">
                                            {{ $session->session_fee * $session->no_of_sessions ?? ''}}
                                        </span>
                                    </h5>


                                    <!-- Hidden Inputs for Submission -->
                                    <input type="hidden" name="session_id" value="{{ $session->id ?? '' }}">
                                    <input type="hidden" name="session_type" value="{{ $session->session_type ?? '' }}">

                                    <input type="hidden" name="patient_id" value="{{ $session->patient_id ?? '' }}">
                                    <input type="hidden" name="doctor_id" value="{{ $session->doctor_id ?? '' }}">
                                    <input type="hidden" name="mini_id" value="{{ $session->ministry_id ?? '' }}">
                                    <input type="hidden" name="offer_id" value="{{ $session->offer_id ?? '' }}">
                                    <input type="hidden" name="no_of_sessions" value="{{ $session->no_of_sessions ?? '' }}">
                                    <input type="hidden" id="session_fee" name="session_fee" value="{{ $session->session_fee ?? '' }}">
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive mt-3">
                                <table id="session_table" class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th colspan="6" class="text-center bg-dark text-white" style="padding: 5px; font-size: 16px; height: 30px; vertical-align: middle;">
                                                Sessions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Dynamic session rows will be added here via JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Footer Buttons -->
                        <div class="card-footer text-end ">
                            @if(empty($offer_name))
                            <button type="button" class="btn btn-info btn-sm px-2 py-1 d-none" id="addSessionBtn">➕ Session</button>
                            <button type="button" class="btn btn-warning btn-sm px-2 py-1 d-none" id="removeSessionBtn">➖ Session</button>
                        @endif

                            <button type="submit" class="btn btn-primary btn-sm px-2 py-1">Save Sessions</button>
                        </div>
                    </div>
                </div>
            </form>


</div>
</div>

<div class="modal fade" id="secondModal2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="secondModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- Centered and larger modal for better view -->
        <div class="modal-content">
            <!-- Styled Header with Total Amount -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="paymentModalLabel">Payment</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form class="add_payment3">
                    @csrf

                    <!-- Total Amount -->
                    <div class="mb-3 text-center">
                        <h4 class="fw-bold text-danger">Total Amount: OMR <span id="total_amount">{{ ($session_price ?? '') }}</span></h4>
                        <h4 class="fw-bold text-danger" style="disply:none" id="voucher_discount_div">Discount: OMR <span id="voucher_discount">0.000</span></h4>
                        <h4 class="fw-bold text-danger" style="disply:none" id="after_discount_div">After Discount: OMR <span id="after_discount">{{ ($session_price ?? '') }}</span></h4>

                    </div>
                    <input type="hidden" id="total_amount_input" value=" {{ ($session_price ?? '') }}">
                    <input type="hidden" id="total_amount_discount" name="total_amount_discount" value="0.000">
                    <input type="hidden" id="total_amount_after_discount" value=" {{ ($session_price ?? '') }}">

                    <hr>

                    <input type="hidden" name="session_id2" id="session_id" class="session_id2" value="{{ $session->id ?? '' }}">
                    <input type="hidden" name="payment_status" class="payment_status3" id="payment_status" value="{{ $session->payment_status ?? '' }}">

                    <div id="pendingPaymentAlert" class="alert alert-warning text-center d-none">
                        <i class="fas fa-exclamation-triangle"></i> <strong>This payment will be kept as pending.</strong>
                    </div>
                    {{-- voucerh only for normal sessions --}}
                    <div class="mb-3 col-md-6" style="disply:none" id="voucher_div">
                        <label class="col-form-label fw-bold fs-5">Voucher Code</label>
                        <input type="text" class="form-control form-control-sm  mt-1" id="voucher_code" name="voucher_code"  >
                        <br>
                        <button type="button" class="btn btn-success w-100" id="check_voucher">
                              Check Voucher
                        </button>
                    </div>
                    <!-- Payment Method Title -->
                    <div class="mb-3 select_payment">
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

                                <input type="text" class="form-control form-control-sm payment-amount-input mt-1 isnumber" id="amount_{{ $account->id }}" name="payment_amounts[{{ $account->id }}]"   style="display: none;">
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
                        <button type="submit" class="btn btn-success w-100" id="confirm_payment2">
                            <i class="fas fa-check"></i> Continue
                        </button>
                        <button type="button" class="btn btn-danger w-100 me-2" data-bs-dismiss="modal">Close</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>





@include('layouts.footer')
@endsection
