@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.appointments_lang', [], session('locale')) }}</title>
    @endpush


    <div class="content-body">
        <div class="container mt-5">

            <!-- /add -->
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">{{ trans('messages.appointment_lang', [], session('locale')) }}</h5>
                    </div>
                    <form>
                        <div class="row">
                            <!-- Title -->
                            <div class="col-xl-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">Title:</label>
                                    <select class="form-control" id="title" name="title">
                                        <option value="">Choose..</option>
                                        <option value="1">Miss</option>
                                        <option value="2">Mr.</option>
                                        <option value="3">Mrs.</option>
                                    </select>
                                </div>
                            </div>

                            <!-- First Name -->
                            <div class="col-xl-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">First Name:</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name">
                                </div>
                            </div>

                            <!-- Second Name -->
                            <div class="col-xl-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">Second Name:</label>
                                    <input type="text" class="form-control" id="second_name" name="second_name" placeholder="Second Name">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">Mobile No:</label>
                                    <input type="number" class="form-control" id="mobile" name="mobile" placeholder="Mobile">
                                </div>
                            </div>

                            <!-- ID Number / Passport Number -->
                            <div class="col-xl-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">ID / Passport No:</label>
                                    <input type="text" class="form-control" id="id_passport" name="id_passport" placeholder="ID Number">
                                </div>
                            </div>

                            <!-- Country -->
                            <div class="col-xl-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">Country:</label>
                                    <select class="form-control" id="country" name="country">
                                        <option value="">Select Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Service -->
                            <div class="col-xl-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">Service:</label>
                                    <select class="form-control" id="service" name="service">
                                        <option value="">Choose..</option>
                                        <option value="1">OT</option>
                                        <option value="2">PT</option>
                                        <option value="3">CT</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Consulting Doctor -->
                            <div class="col-xl-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">Consulting Doctor:</label>
                                    <select class="form-control" id="doctor" name="doctor">
                                        <option value="">Choose...</option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}">{{ $doctor->doctor_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Phone -->


                            <!-- Date Of Appointment -->
                            <div class="row">
                                <!-- Date of Appointment -->
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Date Of Appointment:</label>
                                        <input type="date" class="form-control" id="appointment_date" name="appointment_date">
                                    </div>
                                </div>

                                <!-- Appointment Timing - From -->
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label class="col-form-label">From <span class="text-danger">*</span></label>
                                        <div class="input-group clockpicker">
                                            <input type="text" class="form-control" id="time_from" name="time_from" value="09:30">
                                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Appointment Timing - To -->
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label class="col-form-label">To <span class="text-danger">*</span></label>
                                        <div class="input-group clockpicker">
                                            <input type="text" class="form-control" id="time_to" name="time_to" value="10:30">
                                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Patient Type -->
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Patient Type:</label>
                                        <div class="bootstrap-badge d-flex gap-2">
                                            <a href="javascript:void(0)" data-value="1" class="badge badge-pill badge-secondary">Direct</a>
                                            <a href="javascript:void(0)" data-value="2" class="badge badge-pill badge-info">Pact</a>
                                            <a href="javascript:void(0)" data-value="3" class="badge badge-pill badge-success" data-bs-toggle="modal" data-bs-target="#offerModal">Offer</a>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Notes -->
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label class="col-form-label">Note:</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                </div>
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
