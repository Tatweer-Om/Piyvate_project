@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.sessions_lang', [], session('locale')) }}</title>
    @endpush

<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="page-titles d-flex justify-content-between align-items-center">
            <ol class="breadcrumb mb-0">
                <li class=""><a href="javascript:void(0)">Dashboard /</a></li>
                <li class="active"><a href="javascript:void(0)">Sessions List</a></li>
            </ol>
            <div class="d-flex gap-2">
                <a href="appointments" class="btn btn-secondary btn-sm btn-rounded" data-bs-toggle="tooltip" data-bs-placement="top" title="Add a new appointment">
                    <i class="bi bi-calendar-plus"></i> Add Appointment
                </a>

                <a href="{{ url('sessions_list') }}" class="btn btn-secondary btn-sm btn-rounded" data-bs-toggle="tooltip" data-bs-placement="top" title="Add a new session">
                    <i class="bi bi-journal-plus"></i> Add Sessions
                </a>

                <a href="{{ url('session_data') }}" class="btn btn-secondary btn-sm btn-rounded" data-bs-toggle="tooltip" data-bs-placement="top" title="View all session data">
                    <i class="bi bi-collection"></i> All Sessions Data
                </a>

                <a href="{{ url('all_sessions') }}" class="btn btn-secondary btn-sm btn-rounded" data-bs-toggle="tooltip" data-bs-placement="top" title="See the Direct sessions Booking list">
                    <i class="bi bi-list-ul"></i> Sessions List
                </a>

                <a href="{{ url('all_appointments') }}" class="btn btn-secondary btn-sm btn-rounded" data-bs-toggle="tooltip" data-bs-placement="top" title="See the Direct sessions Booking list">
                    <i class="bi bi-card-list"></i> All Appointments
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="all_sessions" class="table table-striped  mb-4 dataTablesCard fs-14">
                                <thead>
                                    <tr>
                                        <th>Sess.No</th>
                                        <th> Patinet Name</th>
                                        <th>Total Sessions</th>
                                        <th>Remaining Sessions</th>
                                        <th>Sessions Fee</th>
                                        <th>Added By </th>
                                        <th>Added On </th>
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
