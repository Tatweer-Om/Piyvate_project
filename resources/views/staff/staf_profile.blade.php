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
                {{-- <div class="ms-auto mb-3">
                    <a href="javascript:void();" class="btn btn-primary btn-rounded add-staff" data-bs-toggle="modal" data-bs-target="#exampleModal" >+ Add Staff</a>
                </div> --}}
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-8">
                                    <div class="profile">
                                        <input type="hidden" id="employee_id" value="{{ $staff->id }}">
                                        <div class="staff">
                                            <img src="{{ asset($staff->employee_image ? 'images/employee_images/' . $staff->employee_image : 'images/dummy_images/cover-image-icon.png') }}" alt="">
                                        </div>
                                        <div class="staf-info">
                                            <div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <h4 class="mb-0">{{ trans('messages.employee_name_lang',[],session('locale')) }} :</h4><p class="ms-2 mb-0">{{ $staff->employee_name }}</p>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <h4 class="mb-0">{{ trans('messages.designation_lang',[],session('locale')) }} :</h4><p class="ms-2 mb-0">{{ $role }}</p>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <h4 class="mb-0">{{ trans('messages.branch_name_lang',[],session('locale')) }} :</h4><p class="ms-2 mb-0">{{ $branch }}</p>
                                                </div>
                                            </div>
                                            <div class="location mt-4">
                                                
                                                <div>
                                                    <span><i class="fa fa-phone me-2 text-primary"></i>{{ $staff->employee_phone }}</span>
                                                    <span><i class="fa fa-envelope me-2 text-secondary"></i>{{ $staff->employee_email }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <h4><i class="fas fa-address-card me-2"></i> {{ trans('messages.about_lang',[],session('locale')) }}</h4>
                                    <p style="white-space:pre-line">{{ $staff->notes }}</p>
                                    
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
