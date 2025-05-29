@extends('layouts.header')

@section('main')
    @push('title')
        <title>Settings</title>
    @endpush

    <div class="content-body">
        <div class="container my-5">
            <!-- Ministry Name -->
            <div class="mb-4 text-center">
                <h3 class="fw-bold text-primary mb-1">{{ $mini->govt_name ?? 'Ministry Name' }}</h3>
                <div style="width: 120px; height: 3px; background-color: #0d6efd; margin: 0 auto; border-radius: 2px;"></div>
            </div>

            <!-- Four-Box Row -->
            <div class="row g-4">
                <!-- Total Patients -->
                <div class="col-lg-3 col-md-6">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-people-fill fs-2 text-primary mb-2"></i>
                            <h6 class="fw-bold mb-1">Total Patients</h6>
                            <p class="fw-bold fs-5 mb-0">{{ $totalUniquePatients ?? '0' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Pending -->
                <div class="col-lg-3 col-md-6">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-hourglass-split fs-2 text-warning mb-2"></i>
                            <h6 class="fw-bold mb-1">Total Pending</h6>
                            <p class="fw-bold fs-5 mb-0">OMR {{ $total_pending ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Paid -->
                <div class="col-lg-3 col-md-6">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-cash-stack fs-2 text-success mb-2"></i>
                            <h6 class="fw-bold mb-1">Total Paid</h6>
                            <p class="fw-bold fs-5 mb-0">OMR {{ $total_paid_combined ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <input type="hidden" class="mini_id" id="mini_id" name="mini_id" value="{{ $mini->id ?? '' }}">

                <!-- Ministry Contact Info -->
                <div class="col-lg-3 col-md-6">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-building fs-2 text-info mb-2"></i>
                            <h6 class="fw-bold mb-1">{{ $mini->govt_name ?? 'Ministry Name' }}</h6>
                            <p class="mb-1 small text-muted">
                                <i class="bi bi-envelope-fill me-1"></i> {{ $mini->govt_email ?? 'N/A' }}
                            </p>
                            <p class="mb-0 small text-muted">
                                <i class="bi bi-telephone-fill me-1"></i> {{ $mini->govt_phone ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div> <br>
            <div class="mb-4 text-center">
                <h3 class="fw-bold text-primary mb-1">PATIENTS UNDER CONTRACT</h3>
                <div style="width: 120px; height: 3px; background-color: #0d6efd; margin: 0 auto; border-radius: 2px;"></div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table id="all_patients_contract" class="table table-striped patient-list mb-4 dataTablesCard fs-14">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Appt/Session No.</th>
                                            <th>Source</th>
                                            <th>Pay-Status</th>
                                            <th>Sessions</th>
                                            <th>Fee/Session</th>
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



@include('layouts.footer')
@endsection
