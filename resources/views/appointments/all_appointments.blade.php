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
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class=""><a href="javascript:void(0)">Dashboard/</a></li>
                <li class="active"><a href="javascript:void(0)">appointmentss</a></li>
            </ol>
        </div>
        <div class="form-head d-flex  mb-md-4 align-items-start flex-wrap">
            <div class="me-auto  mb-md-0">
                <a href="javascript:void();" class="btn btn-primary btn-rounded add-staff" data-bs-toggle="modal" data-bs-target="#add_appointments_modal">+ Add appointments</a>
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
                                        <th>Appointment Date</th>
                                        <th>Appoitnemnt Fee</th>
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
                <!-- Patient & Doctor Info with Radio Buttons -->
                <div class="row">
                    <div class="col-12 col-md-6">
                        <h5>Patient: <span id="patient_name" class="fw-normal"></span></h5>
                        <h5>Doctor: <span id="doctor_name" class="fw-normal"></span></h5>
                        <h5>Appointment Date: <span id="appointment_date" class="fw-normal"></span></h5>
                    </div>
                    <div class="col-12 col-md-6 d-flex flex-column align-items-end">
                        <div class="d-flex flex-wrap justify-content-end">
                            <div class="form-check me-2">
                                <input class="form-check-input" type="radio" name="sessionType" id="normal" value="Normal">
                                <label class="form-check-label" for="normal">Normal</label>
                            </div>
                            <div class="form-check me-2">
                                <input class="form-check-input" type="radio" name="sessionType" id="pact" value="Pact">
                                <label class="form-check-label" for="pact">Pact</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sessionType" id="offer" value="Offer">
                                <label class="form-check-label" for="offer">Offer</label>
                            </div>
                        </div>
                        <!-- Additional Inputs for Offer and Pact -->
                        <div class="row mt-2 w-100" id="extraFields" style="display: none;">
                            <div id="offerFields" class="col-12 col-md-6" style="display: none;">
                                <label for="offerSelect">Select Offer:</label>
                                <select id="offerSelect" class="form-control">
                                    <option value="offer1">Offer 1</option>
                                    <option value="offer2">Offer 2</option>
                                </select>
                                <label for="offerPrice" class="mt-2">Offer Price:</label>
                            </div>

                            <div id="pactFields" class="col-12 col-md-6" style="display: none;">
                                <label for="ministrySelect">Select Ministry:</label>
                                <select id="ministrySelect" class="form-control">
                                    <option value="ministry1">Ministry 1</option>
                                    <option value="ministry2">Ministry 2</option>
                                </select>
                                <label for="sessionPrice" class="mt-2">Session Price:</label>
                                <label for="sessionType">Session Type:</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table with small inputs and better styling -->
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
                <button type="button" class="btn btn-primary">Save Sessions</button>
            </div>
        </div>
    </div>
</div>









@include('layouts.footer')
@endsection
