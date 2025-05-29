@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.sessions_lang', [], session('locale')) }}</title>
    @endpush


<style>
    .fc-event .fc-event-title {
    font-size: 9px !important;
}

.fc-event-time{
    font-size: 9px !important;
}

</style>

<input type="hidden" value="{{ $doctor->id }}" name="doctor_id" id="doctor_id" class="doctor_id">

    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">App</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Calendar</a></li>
            </ol>
            </div>
            <!-- row -->
            <div class="row">

                <div class="col-xl-12 col-xxl-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="calendar" ></div>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>
    @include('layouts.footer')
@endsection
