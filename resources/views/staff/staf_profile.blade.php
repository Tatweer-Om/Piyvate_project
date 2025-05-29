@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.staff_profile_lang', [], session('locale')) }}</title>
    @endpush


    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <div class="d-md-flex align-items-center">
                <div class="page-titles mb-2">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">{{ trans('messages.dashboard_lang',[],session('locale')) }} / </a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ trans('messages.staff_profile_lang',[],session('locale')) }}</a></li>
                    </ol>
                </div>

            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow-lg border-0 rounded-4" style="background: linear-gradient(to right, #f8f9fa, #e9ecef);">
                        <div class="card-body p-5">
                            <div class="row align-items-center">
                                <!-- Left Profile Section -->
                                <div class="col-xl-8 mb-4 mb-xl-0">
                                    <div class="d-flex align-items-center gap-4">
                                        <div class="rounded-circle border border-3 border-primary overflow-hidden" style="width: 130px; height: 130px;">
                                            <img src="{{ asset($staff->employee_image ? 'images/employee_images/' . $staff->employee_image : 'images/dummy_images/cover-image-icon.png') }}" alt="Staff Image" class="img-fluid w-100 h-100 object-fit-cover">
                                        </div>
                                        <div>
                                            <input type="hidden" id="employee_id" value="{{ $staff->id }}">

                                            <h5 class="text-dark fw-bold mb-2">
                                                <i class="fas fa-user-circle text-primary me-2"></i>
                                                {{ trans('messages.employee_name_lang',[],session('locale')) }}:
                                                <span class="text-secondary">{{ $staff->employee_name }}</span>
                                            </h5>

                                            <h6 class="mb-2">
                                                <i class="fas fa-briefcase text-success me-2"></i>
                                                {{ trans('messages.designation_lang',[],session('locale')) }}:
                                                <span class="text-muted">{{ $role }}</span>
                                            </h6>

                                            <h6 class="mb-3">
                                                <i class="fas fa-map-marker-alt text-warning me-2"></i>
                                                {{ trans('messages.branch_name_lang',[],session('locale')) }}:
                                                <span class="text-muted">{{ $branch }}</span>
                                            </h6>

                                            <div class="d-flex flex-column">
                                                <span class="mb-2"><i class="fa fa-phone me-2 text-info"></i><strong>{{ $staff->employee_phone }}</strong></span>
                                                <span><i class="fa fa-envelope me-2 text-danger"></i>{{ $staff->employee_email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right About Section -->
                                <div class="col-xl-4">
                                    <h5 class="text-primary fw-bold mb-3">
                                        <i class="fas fa-address-card me-2"></i> {{ trans('messages.about_lang',[],session('locale')) }}
                                    </h5>
                                    <p class="text-dark" style="white-space:pre-line; background-color: #fff; border-radius: 0.5rem; padding: 1rem; box-shadow: inset 0 0 10px rgba(0,0,0,0.05);">
                                        {{ $staff->notes }}
                                    </p>
                                </div>
                            </div>

                            @php
                                $sessionClass = $total_session <= 900 ? 'bg-danger' : 'bg-success';
                            @endphp
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="bg-gradient bg-info text-white rounded-4 p-4 shadow text-center h-100">
                                        <h6 class="mb-2"><i class="fas fa-calendar-check me-2"></i> Total Appointments</h6>
                                        <h3 class="fw-bold mb-0">{{ $appointments ?? '' }}</h3>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-gradient {{ $sessionClass }} text-white rounded-4 p-4 shadow text-center h-100">
                                        <h6 class="mb-2"><i class="fas fa-stethoscope me-2"></i> Total Sessions</h6>
                                        <h3 class="fw-bold mb-0">{{ $total_session ?? '' }}</h3>
                                    </div>
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
