@extends('layouts.header')

@section('main')
    @push('title')
        <title>Settings</title>
    @endpush

    <div class="content-body">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title text-center">CLINIC INFORMATION</h5>
                    </div>

                    <form class="add_setting" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" class="setting_id" value="{{ $setting->id ?? ''}}" name="setting_id">

                        <!-- Image Centered at the Top -->
                        <div class="d-flex justify-content-center align-items-center m-3">
                            <div class="form-group text-center position-relative">
                                <label class="col-form-label">Clinic Logo</label>
                                <img id="imagePreview" src="{{ isset($setting->logo) ? asset('images/company_logo/' . $setting->logo) : asset('images/dummy_images/cover-image-icon.png') }}"
                                     alt="Preview" class="img-fluid rounded company_logo"
                                     style="width: 100px; height: 100px; object-fit: cover; cursor: pointer;">
                                <input type="file" id="imageUpload" name="company_logo" class="d-none company_logo" accept="image/*">
                                <span id="removeImage" class="position-absolute top-0 end-0 bg-danger text-white rounded-circle px-2"
                                      style="cursor: pointer; display: none;">&times;</span>
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="col-form-label">Clinic Name:</label>
                                <input type="text" class="form-control form-control-sm" name="company_name" value="{{ $setting->company_name ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label">Clinic Email:</label>
                                <input type="email" class="form-control form-control-sm" name="company_email" value="{{ $setting->company_email ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label">Clinic Phone:</label>
                                <input type="text" class="form-control form-control-sm" name="company_phone" value="{{ $setting->company_phone ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label">Clinic CR:</label>
                                <input type="text" class="form-control form-control-sm" name="company_cr" value="{{ $setting->company_cr ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label">Clinic Address:</label>
                                <input type="text" class="form-control form-control-sm" name="company_address" value="{{ $setting->company_address ?? '' }}">
                            </div>

                            <div class="col-md-12">
                                <label class="col-form-label">Notes:</label>
                                <textarea class="form-control form-control-sm" name="notes" rows="3">{{ $setting->notes ?? '' }}</textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary btn-sm">Save Settings</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@include('layouts.footer')
@endsection
