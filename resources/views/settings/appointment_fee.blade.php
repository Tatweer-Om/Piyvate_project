@extends('layouts.header')

@section('main')
    @push('title')
        <title>Appointment Fee</title>
    @endpush

    <div class="content-body">
        <div class="container">
            <div class="card">
                <div class="card-body text-center">
                    <div class="card-header">
                        <h5 class="card-title">Set Appointment Fee</h5>
                    </div>

                    <form class="add_fee" method="POST">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="card shadow-sm p-3">
                                    <label class="col-form-label">Appointment Fee(OMR):</label>
                                    <input type="number" class="form-control form-control-sm text-center" name="appointment_fee" value="{{ $setting->appointment_fee ?? '' }}" step="0.01">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer mt-3">
                            <button type="submit" class="btn btn-primary btn-sm">Save Fee</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@include('layouts.footer')
@endsection
