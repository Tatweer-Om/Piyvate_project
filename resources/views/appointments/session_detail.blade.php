@extends('layouts.header')

@section('main')
    @push('title')
        <title>Doctor Prescription Sessions</title>
    @endpush
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        $(document).ready(function () {
            // Call your function and pass session ID
            var sessionId = "{{ $session->id }}"; // Assuming session ID is available in Blade
            session_detail(sessionId);
        });
    </script>

    <div class="content-body">
        <div class="container">
    <div class="container ">
        <div class="card shadow-lg">
            {{-- <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Sessions Table</h5>
            </div> --}}
            <div class="card-body overflow-auto" style="max-height: 70vh;">
                <!-- Patient & Doctor Info -->
                <div class="row">
                    <div class="col-12 col-md-6">
                        <h5>Patient: <span id="patient_name" class="fw-normal">{{ $patient_name ?? '' }}</span></h5>
                        <h5>Doctor: <span id="doctor_name" class="fw-normal"></span>{{ $doctor_name ?? '' }}</h5>
                        @if(!empty($mini_name))
                        <h5>Agreement Under: <span id="doctor_name" class="fw-normal"></span>{{ $mini_name ?? ''}}</h5>
                    @endif

                    @if(!empty($offer_name))
                        <h5>Offer Name: <span id="doctor_name" class="fw-normal"></span>{{ $offer_name ?? ''}}</h5>
                    @endif
                    <h5>Total Sessions: <span id="patient_name" class="fw-normal">{{ $session->no_of_sessions ?? '' }}</span></h5>
                    <h5>Total Fee: <span id="patient_name" class="fw-normal">{{ $session->session_fee ?? '' }}</span></h5>

                    </div>

                </div>

                <!-- Table -->
                <div class="table-responsive mt-3">
                    <table id="session_table" class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th colspan="6" class="text-center bg-dark text-white" style="padding: 5px; font-size: 16px; height: 30px; vertical-align: middle;">
                                    Sessions
                                </th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>

            <!-- Footer Buttons -->
            <div class="card-footer text-end">
                <button type="button" class="btn btn-info btn-sm px-2 py-1" id="addSessionBtn">➕ Session</button>
                <button type="button" class="btn btn-warning btn-sm px-2 py-1" id="removeSessionBtn">➖ Session</button>
                <button type="button" class="btn btn-primary btn-sm px-2 py-1">Save Sessions</button>
            </div>

        </div>
    </div>

</div>
</div>


@include('layouts.footer')
@endsection
