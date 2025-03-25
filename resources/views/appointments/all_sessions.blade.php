@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.sessions_lang', [], session('locale')) }}</title>
    @endpush
    <style>
    /* Make button full-width on small screens */

</style>
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="page-titles d-flex justify-content-between align-items-center">
            <ol class="breadcrumb mb-0">
                <li class=""><a href="javascript:void(0)">Dashboard /</a></li>
                <li class="active"><a href="javascript:void(0)">sessions</a></li>
            </ol>
            <div class="d-flex gap-2">
                <a href="sessions" class="btn btn-primary btn-rounded">+ session</a>
                <a href="{{ url('sessions_list') }}" class="btn btn-secondary btn-rounded">+ Session</a>
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
