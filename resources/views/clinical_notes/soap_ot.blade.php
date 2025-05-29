<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Occupational Therapy Follow-Up Form</title>
    <link rel="stylesheet" href="{{ asset('css/soap_ot.css') }}">


</head>
<body>
    @if ($errors->any())
    <div class="flash-message flash-error" id="flashMessage">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

@if (session('success'))
    <div class="flash-message flash-success" id="flashMessage">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="flash-message flash-error" id="flashMessage">
        {{ session('error') }}
    </div>
@endif


<div class="page">
    <form action="{{ url( 'add_soap_ot') }}" method="POST">
        @csrf

        <div class="header">
            <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Center Logo" height="50">
            <div>
                HN:
                <input type="text" class="input-line" style="width: 110px;" name="hn"
                       value="{{ $patient->HN ?? '' }}" readonly>

                PT:
                <input type="text" class="input-line" name="pt"
                       value="{{ isset($soap) ? $soap->pt : '' }}"><br> {{-- Editable --}}

                Name:
                <input type="text" style="width: 150px;" class="input-line" name="full_name"
                       value="{{ old('full_name', $patient->full_name ?? '') }}" readonly>

                Age:
                <input type="text" style="width: 150px;" class="input-short input-line" name="age"
                       value="{{ old('age', $patient->age ?? '') }}" readonly><br>

                Gender:
                <input type="radio" name="gender" value="male"
                       {{ old('gender', $patient->gender) == 'Male' ? 'checked' : '' }} onclick="return false;"> M
                <input type="radio" name="gender" value="female"
                       {{ old('gender', $patient->gender) == 'Female' ? 'checked' : '' }} onclick="return false;"> F
                <br>

                Therapist:
                <input type="text" class="input-line" name="therapist"
                       value="{{ $doctor ?? '' }}" readonly>
            </div>

        </div>

        <input type="hidden" value="{{ old('main_session_id', $session_data->main_session_id ?? '') }}" name="main_session_id">
        <input type="hidden" value="{{ old('main_appointment_id', $session_data->main_appointment_id ?? '') }}" name="main_appointment_id">
        <input type="hidden" value="{{ old('session_id', $session_data->id ?? '') }}" name="session_id">
        <input type="hidden" value="{{ old('patient_id', $session_data->patient_id ?? '') }}" name="patient_id">
        <input type="hidden" value="{{ old('doctor_id', $session_data->doctor_id ?? '') }}" name="doctor_id">

        <div class="section" id="section-template">
            <div class="section-header">
                Occupational Therapy Follow up and Re-assessment
            </div>

            <div class="row">
                Date:
                <input style="border: none;" type="date" name="date"
                value="{{ old('date', isset($soap->date) ? \Carbon\Carbon::parse($soap->date)->format('Y-m-d') : \Carbon\Carbon::parse($session_data->session_date)->format('Y-m-d')) }}">

                Time:
                <input style="border: none;" type="time" name="time"
                value="{{ old('time', isset($soap->time) ? \Carbon\Carbon::parse($soap->time)->format('H:i') : \Carbon\Carbon::parse($session_data->session_time)->format('H:i')) }}">

                V/S BP <input type="text" name="bp" value="{{ old('bp', $soap->bp ?? '') }}">
                P <input type="text" name="pulse" value="{{ old('pulse', $soap->pulse ?? '') }}">
                O2sat <input type="text" name="o2sat" value="{{ old('o2sat', $soap->o2sat ?? '') }}">
                % T <input type="text" name="temp" value="{{ old('temp', $soap->temp ?? '') }}">
                PS: <input type="text" name="ps" value="{{ old('ps', $soap->ps ?? '') }}">/10
            </div>

            <div class="row">S:<br><textarea rows="2" name="s">{{ old('s', $soap->s ?? '') }}</textarea></div>
            <div class="row">O:<br><textarea rows="2" name="o">{{ old('o', $soap->o ?? '') }}</textarea></div>
            <div class="row">A:<br><textarea rows="2" name="a">{{ old('a', $soap->a ?? '') }}</textarea></div>
            <div class="row">P:<br><textarea rows="2" name="p">{{ old('p', $soap->p ?? '') }}</textarea></div>

            <div class="signature-line">
                #<input type="text" name="number" value="{{ old('number', $soap->number ?? '') }}">
                OT Signature <input type="text" name="signature" value="{{ old('signature', $soap->signature ?? '') }}">
            </div>
        </div>

        <div class="col-lg-12 mt-3">
            <button type="submit" class="custom-grey-button">
                {{ isset($soap) && $soap->id ? 'Update Prescription' : 'Save Prescription' }}
            </button>
        </div>
    </form>

</div>
</body>
</html>

