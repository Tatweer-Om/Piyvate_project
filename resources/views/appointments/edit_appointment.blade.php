@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.edit_appointment_lang', [], session('locale')) }}</title>
    @endpush

    <div class="content-body">
        <div class="container">

            <div class="card">
                <div class="card-body">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">EDIT APPOINTMENT</h5>

                        {{-- <div class="form-group mb-0">
                            <input type="text" class="form-control form-control-sm" id="clinic_no" name="clinic_no"
                                   placeholder="Patient Detail" style="max-width: 170px;" value="{{ old('clinic_no', $patient->clinic_no ?? '') }}">
                        </div> --}}
                    </div>

                    <form class="edit_appointment" >
                        @csrf

                        <div class="row g-2">
                            <div class="col-md-3">
                                <label class="col-form-label">Title:</label>
                                <select class="form-control form-control-sm" id="title" name="title">
                                    <option value="">Choose..</option>
                                    <option value="1" {{ old('title', $patient->title) == 1 ? 'selected' : '' }}>Miss</option>
                                    <option value="2" {{ old('title', $patient->title) == 2 ? 'selected' : '' }}>Mr.</option>
                                    <option value="3" {{ old('title', $patient->title) == 3 ? 'selected' : '' }}>Mrs.</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="col-form-label">First Name:</label>
                                <input type="text" class="form-control form-control-sm" id="first_name" name="first_name"
                                       value="{{ old('first_name', $patient->first_name ?? '') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="col-form-label">Second Name:</label>
                                <input type="text" class="form-control form-control-sm" id="second_name" name="second_name"
                                       value="{{ old('second_name', $patient->second_name ?? '') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="col-form-label">Mobile No:</label>
                                <input type="number" class="form-control form-control-sm" id="mobile" name="mobile"
                                       value="{{ old('mobile', $patient->mobile ?? '') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="col-form-label">ID / Passport No:</label>
                                <input type="text" class="form-control form-control-sm" id="id_passport" name="id_passport"
                                       value="{{ old('id_passport', $patient->id_passport ?? '') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="col-form-label">Date Of Birth:</label>
                                <input type="date" class="form-control form-control-sm" id="dob" name="dob"
                                       value="{{ old('dob', $patient->dob ?? '') }}">
                            </div>
                            <input type="hidden" name="appointment_id" value="{{ $appointment->id ?? '' }}">

                            <div class="col-md-3">
                                <label class="col-form-label">Country:</label>
                                <select class="form-control form-control-sm" id="country" name="country">
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}" {{ old('country', $patient->country_id ?? '') == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="col-form-label">Doctor:</label>
                                <select class="form-control form-control-sm" id="doctor" name="doctor">
                                    <option value="">Choose...</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->doctor_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="col-form-label">Appointment Date:</label>
                                <input type="date" class="form-control form-control-sm" id="appointment_date" name="appointment_date"
                                       value="{{ old('appointment_date', $appointment->appointment_date) }}">
                            </div>

                            <div class="col-md-2">
                                <label class="col-form-label">From:</label>
                                <input type="text" class="form-control form-control-sm" id="time_from" name="time_from"
                                       value="{{ old('time_from', $appointment->time_from) }}">
                            </div>

                            <div class="col-md-2">
                                <label class="col-form-label">To:</label>
                                <input type="text" class="form-control form-control-sm" id="time_to" name="time_to"
                                       value="{{ old('time_to', $appointment->time_to) }}">
                            </div>

                            <div class="col-md-2">
                                <label class="col-form-label">Appointment Fee:</label>
                                <div class="input-group">
                                    <span class="input-group-text">OMR</span>
                                    <input type="text" class="form-control form-control-sm" id="appointment_fee" name="appointment_fee"
                                           value="{{ $setting->appointment_fee ?? '' }}" style="font-weight: bold;">
                                </div>
                            </div>

                            <div class="col-md-3 mt-3">
                                <!-- Age Badge & Hidden Input -->
                                <div class="col-md-3 d-flex align-items-center">
                                    @php
                                        $age = $patient->age ?? ''; // Fetch stored age directly from DB
                                    @endphp

                                    <span id="age_badge" class="badge bg-success" style="font-size: 14px; {{ $age ? '' : 'display: none;' }}">
                                        Age: <span id="age_value">{{ $age ?: '--' }}</span>
                                    </span>
                                    <input type="hidden" id="age_input" name="age" value="{{ $age }}">
                                </div>
                                <br>

                                <!-- Gender Badge & Hidden Input -->
                                <div class="col-md-3 d-flex align-items-center">
                                    @php
                                        $gender = $patient->gender ?? ''; // Fetch stored gender from DB

                                    @endphp

                                    <span id="gender_badge" class="badge bg-info" style="font-size: 14px; {{ $gender ? '' : 'display: none;' }}">
                                        <i class="{{ $genderMap[$gender]['icon'] ?? 'fas fa-venus-mars' }}"></i>
                                        Gender: <span id="gender_value">{{ $gender ?? '' }}</span>
                                    </span>
                                    <input type="hidden" class="gender" id="gender_input" name="gender" value="{{ $gender }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="col-form-label">Notes:</label>
                                <textarea class="form-control form-control-sm" id="notes" name="notes" rows="2">{{ old('notes', $appointment->notes) }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm">Update Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@include('layouts.footer')
@endsection
