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
                    <div class="card-header">
                        <h5 class="card-title">{{ trans('messages.appointment_lang', [], session('locale')) }}</h5>
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
                            <div class="col-md-3">
                                <label class="col-form-label">From:</label>
                                <div class="input-group clockpicker">
                                    <input type="text" class="form-control form-control-sm" id="time_from" name="time_from" value="09:30">
                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="col-form-label">To:</label>
                                <div class="input-group clockpicker">
                                    <input type="text" class="form-control form-control-sm" id="time_to" name="time_to" value="10:30">
                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="col-form-label">Notes:</label>
                                <textarea class="form-control form-control-sm" id="notes" name="notes" rows="2"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
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
