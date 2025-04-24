@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.doctor_profile_lang', [], session('locale')) }}</title>
    @endpush


    <div class="content-body">


        <!-- row -->
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class=" active"><a href="javascript:void(0)"> /Doctor Details</a></li>
                </ol>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="profile card card-body px-3 pt-3 text-center">
                        <div class="profile-head d-flex flex-column align-items-center">
                            <div class="profile-photo mb-3">
                                <img src="{{ asset($doctor->doctor_image ? 'images/doctor_images/' . $doctor->doctor_image : 'images/dummy_images/cover-image-icon.png') }}"
                                    class="img-fluid rounded-circle" alt="" style="width: 100px; height: 100px;">
                            </div>
                            <div class="profile-details">
                                <div class="profile-name">
                                    <h4 class="text-primary mb-0">{{ $doctor->doctor_name ?? 'N/A' }}</h4>
                                    <p class="text-muted small">{{ $special ?? 'N/A' }}</p>
                                </div>
                                <div class="profile-email">
                                    <h5 class="text-muted mb-0">{{ $branch ?? 'N/A' }}</h5>
                                    <p class="small">{{ $doctor->email ?? '' }}</p>
                                </div>
                            </div>
                            <ul class="list-group w-100 text-start mt-3">
                                <li class="list-group-item d-flex justify-content-between align-items-center small">
                                    <span><i class="fas fa-calendar-check text-primary"></i> Appointments</span>
                                    <span class="badge bg-primary rounded-pill">{{ $total_apt ?? '0' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center small">
                                    <span><i class="fas fa-clock text-success"></i> Sessions</span>
                                    <span class="badge bg-success rounded-pill">{{ $total_sessions ?? '0' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center small">
                                    <span><i class="fas fa-user text-danger"></i> Patients</span>
                                    <span class="badge bg-danger rounded-pill">{{ $total_patient ?? '0' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header border-0 pb-0">
                            <h4 class="fs-16 font-w600 mb-0">Today's Appointments & Sessions</h4>
                        </div>
                        <div class="card-body px-0 pt-3">

                            <div id="DZ_W_Todo2" class="widget-media dz-scroll px-3"
                                style="max-height: 300px; overflow-y: auto;">
                            </div>
                        </div>
                    </div>
                </div>

            </div>




            <!-- Tab navigation -->
            <ul class="nav nav-pills mb-3" id="sessionTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="appointmentSessionsTab" data-bs-toggle="pill" href="#appointmentSessions"
                        role="tab" aria-controls="appointmentSessions" aria-selected="true">Appointments</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="allSessionsTab" data-bs-toggle="pill" href="#allSessions" role="tab"
                        aria-controls="allSessions" aria-selected="false">All Sessions</a>
                </li>
            </ul>

            <!-- Tab content -->
            <div class="tab-content" id="sessionTabsContent">
                <!-- Appointment Sessions Tab -->
                <div class="tab-pane fade show active" id="appointmentSessions" role="tabpanel"
                    aria-labelledby="appointmentSessionsTab">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Appointments</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="all_patient_doctor" class="table table-striped table-bordered"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Appt No.</th>
                                                    <th>Patient Name</th>
                                                    <th>Appointment Date</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be populated dynamically here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- All Sessions Tab -->
                <div class="tab-pane fade" id="allSessions" role="tabpanel" aria-labelledby="allSessionsTab">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">All Sessions</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="all_session_table" class="table table-striped table-bordered"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Session Date</th>
                                                    <th>Patient</th>
                                                    <th>Session Time</th>
                                                    <th>Session Status</th>
                                                    <th>Source</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be populated dynamically here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>



        @include('layouts.footer')
    @endsection
