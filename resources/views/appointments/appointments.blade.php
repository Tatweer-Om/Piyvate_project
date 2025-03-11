@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.appointments_lang', [], session('locale')) }}</title>
    @endpush
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li ><a href="javascript:void(0)">Dashboard</a></li>
                <li class=" active"><a href="javascript:void(0)">/Appointments</a></li>
            </ol>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="appointment_table" class="table table-striped patient-list mb-4 dataTablesCard fs-14">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="checkbox text-end align-self-center">
                                                <div class="form-check custom-checkbox ">
                                                    <input type="checkbox" class="form-check-input" id="checkAll" required="">
                                                    <label class="form-check-label" for="checkAll"></label>
                                                </div>
                                            </div>
                                        </th>
                                        <th>PatientName</th>
                                        <th>Phone</th>
                                        <th>Date Of Appointment</th>
                                        <th>Dcotor</th>
                                        <th>Consulting Doctor</th>
                                        <th>Injury/Condition</th>
                                        <th>Action</th>
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
    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Book Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <!-- Title -->
                            <div class="col-xl-4 col-md-6 col-sm-12">
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
                            <div class="col-xl-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">First Name:</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name">
                                </div>
                            </div>

                            <!-- Second Name -->
                            <div class="col-xl-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">Second Name:</label>
                                    <input type="text" class="form-control" id="second_name" name="second_name" placeholder="Second Name">
                                </div>
                            </div>

                            <!-- Country -->
                            <div class="col-xl-4 col-md-6 col-sm-12">
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
                            <div class="col-xl-4 col-md-6 col-sm-12">
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
                            <div class="col-xl-4 col-md-6 col-sm-12">
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
                            <div class="col-xl-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">Mobile No:</label>
                                    <input type="number" class="form-control" id="mobile" name="mobile" placeholder="Mobile">
                                </div>
                            </div>

                            <!-- ID Number / Passport Number -->
                            <div class="col-xl-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">ID / Passport No:</label>
                                    <input type="text" class="form-control" id="id_passport" name="id_passport" placeholder="ID or Passport Number">
                                </div>
                            </div>

                            <!-- Date Of Appointment -->
                            <div class="col-xl-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">Date Of Appointment:</label>
                                    <input type="date" class="form-control" id="appointment_date" name="appointment_date">
                                </div>
                            </div>

                            <!-- Appointment Timing -->
                            <div class="col-xl-4 col-md-6 col-sm-12">
                                <label class="form-label mt-3">From<span class="text-danger">*</span></label>
                                <div class="input-group clockpicker">
                                    <input type="text" class="form-control" id="time_from" name="time_from" value="09:30">
                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-sm-12">
                                <label class="form-label mt-3">To<span class="text-danger">*</span></label>
                                <div class="input-group clockpicker">
                                    <input type="text" class="form-control" id="time_to" name="time_to" value="10:30">
                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
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

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>




@include('layouts.footer')
@endsection
