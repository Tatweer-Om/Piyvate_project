@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.appointments_lang', [], session('locale')) }}</title>
    @endpush


    <div class="content-body">
        <div class="container">

            <!-- /add -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Sessions</h5>
                    <div class="form-group mb-0">
                        <input type="text" class="form-control form-control-sm" id="clinic_no" name="clinic_no"
                               placeholder="Patient Detail" style="max-width: 170px;">
                    </div>
                </div>

                <div class="card-body">
                    <form class="add_session">
                        @csrf
                        <!-- Session Type (Moved inside the form but visually same position) -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <label class="col-form-label mb-0">Session Type:</label>
                                <div class="d-flex gap-2">
                                    <input type="radio" name="session_type" value="normal" checked> Normal
                                    <input type="radio" name="session_type" value="offer"> Offer
                                    <input type="radio" name="session_type" value="ministry"> Pact
                                </div>
                            </div>
                        </div>
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
                              <div class="col-md-2" id="session_select_box">
                                <label class="col-form-label"> Session Type:</label>
                                <select class="form-control form-control-sm session_cat" id="session_cat" name="session_cat">
                                    <option value="">Choose...</option>
                                    <option value="OT">OT</option>
                                    <option value="PT">PT</option>
                                    <option value="CT">CT</option>
                                </select>
                            </div>

                            <!-- Offer Dropdown (Hidden Initially) -->
                            <div class="col-md-3" id="offer_select_box" style="display: none;">
                                <label class="col-form-label">Select Offer:</label>
                                <select class="form-control form-control-sm" name="offer_id">
                                    <option value="">Choose...</option>
                                    @foreach ($offers as $offer)
                                        <option value="{{ $offer->id }}">{{ $offer->offer_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Ministry Dropdown (Hidden Initially) -->
                            <div class="col-md-3" id="ministry_select_box" style="display: none;">
                                <label class="col-form-label">Select Ministry:</label>
                                <select class="form-control form-control-sm" name="ministry_id">
                                    <option value="">Choose...</option>
                                    @foreach ($ministries as $ministry)
                                        <option value="{{ $ministry->id }}">{{ $ministry->govt_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="col-form-label">Sessions:</label>
                                <input type="number" class="form-control form-control-sm number" id="no_of_sessions" name="no_of_sessions">
                            </div>
                            <div class="col-md-2">
                                <label class="col-form-label">Interval:</label>
                                <input type="number" class="form-control form-control-sm number" id="session_gap" name="session_gap">
                            </div>
                            <div class="col-md-2">
                                <label class="col-form-label">First Session:</label>
                                <input type="date" class="form-control form-control-sm " id="session_date" name="session_date">
                            </div>

                            <div class="col-md-2">
                                <label class="col-form-label">Session Fee:</label>
                                <div class="alert alert-info p-2" id="session_fee" style="font-weight: bold;">
                                    OMR 0.00
                                </div>
                                <input type="hidden" name="session_fee" id="session_fee_input" value="0.00">

                            </div>
                            <div class="col-md-12">
                                <label class="col-form-label">Notes:</label>
                                <textarea class="form-control form-control-sm" id="notes" name="notes" rows="2"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary btn-sm">Add Data</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- /add -->
        </div>
    </div>
    </div>



@include('layouts.footer')
@endsection
