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
                HN: <input type="text" class="input-line" style="width: 110px;" name="hn" value="{{ $patient->HN ?? '' }}">
                PT: <input type="text" class="input-line" name="pt" value="{{ old('pt', $session_data->pt ?? '') }}"><br>
                Name: <input type="text" style="width: 150px;" class="input-line" name="full_name" value="{{ old('full_name', $patient->full_name ?? '') }}">
                Age: <input type="text" style="width: 150px;" class="input-short input-line" name="age" value="{{ old('age', $patient->age ?? '') }}"><br>
                Gender:
                <input type="radio" name="gender" value="male" {{ old('gender', $patient->gender) == 'Male' ? 'checked' : '' }}> M
                <input type="radio" name="gender" value="female" {{ old('gender', $patient->gender) == 'Female' ? 'checked' : '' }}> F
                <br>
                Therapist: <input type="text" class="input-line" name="therapist" value="{{ old('therapist', $doctor ?? '') }}">
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
                Date <input style="border: none;" type="date" name="date" value="{{ old('date', $session_data->date ?? '') }}">
                Time <input style="border: none;" type="time" name="time" value="{{ old('time', $session_data->time ?? '') }}">
                V/S BP <input type="text" name="bp" value="{{ old('bp', $session_data->bp ?? '') }}">
                P <input type="text" name="pulse" value="{{ old('pulse', $session_data->pulse ?? '') }}">
                O2sat <input type="text" name="o2sat" value="{{ old('o2sat', $session_data->o2sat ?? '') }}">
                % T <input type="text" name="temp" value="{{ old('temp', $session_data->temp ?? '') }}">
                PS: <input type="text" name="ps" value="{{ old('ps', $session_data->ps ?? '') }}">/10
            </div>

            <div class="row">S:<br><textarea rows="2" name="s">{{ old('s', $session_data->s ?? '') }}</textarea></div>
            <div class="row">O:<br><textarea rows="2" name="o">{{ old('o', $session_data->o ?? '') }}</textarea></div>
            <div class="row">A:<br><textarea rows="2" name="a">{{ old('a', $session_data->a ?? '') }}</textarea></div>
            <div class="row">P:<br><textarea rows="2" name="p">{{ old('p', $session_data->p ?? '') }}</textarea></div>

            <div class="signature-line">
                #<input type="text" name="number" value="{{ old('number', $session_data->number ?? '') }}">
                OT Signature <input type="text" name="signature" value="{{ old('signature', $session_data->signature ?? '') }}">
            </div>
        </div>

        <div class="col-lg-12 mt-3">
            <button type="submit" class="custom-grey-button">
                {{ $session_data->id ? 'Update Prescription' : 'Save Prescription' }}
            </button>
        </div>
    </form>

</div>
</body>
</html>

