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
                                        <th> Appointment Time </th>
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







@include('layouts.footer')
@endsection
