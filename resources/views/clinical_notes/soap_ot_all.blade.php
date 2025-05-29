<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Occupational Therapy Follow-Up Form</title>
    <link rel="stylesheet" href="{{ asset('css/soap_ot.css') }}">


</head>
<body>




<div class="page">

        <div class="header">
            <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Center Logo" height="50">
            <div>
                HN:
                <input type="text" class="input-line" style="width: 110px;" name="hn" value="{{ $patient->HN ?? '' }}" readonly>

                PT:
                <input type="text" class="input-line" name="pt" value="{{ $pt ?? '' }}"><br> {{-- Editable --}}

                Name:
                <input type="text" style="width: 150px;" class="input-line" name="full_name" value="{{ $patient->full_name ?? '' }}" readonly>

                Age:
                <input type="text" style="width: 150px;" class="input-short input-line" name="age" value="{{ $patient->age ?? '' }}" readonly><br>

                Gender:
                <input type="radio" name="gender" value="male" {{ $patient->gender == 'Male' ? 'checked' : '' }} onclick="return false;"> M
                <input type="radio" name="gender" value="female" {{ $patient->gender == 'Female' ? 'checked' : '' }} onclick="return false;"> F
                <br>

                Therapist:
                <input type="text" class="input-line" name="therapist" value="{{ $doctor ?? '' }}" readonly>
            </div>

        </div>

        @foreach ($session_data as $data)
        <div class="section" id="section-template">
            <div class="section-header">
                Occupational Therapy Follow up and Re-assessment
            </div>

            <div class="row">
                Date <input style="border: none; border-bottom: 1px solid #000;" type="date" name="date" value="{{ $data->date }}">
                Time <input style="border: none; border-bottom: 1px solid #000;" type="time" name="time" value="{{ $data->time }}">
                V/S BP <input type="text" name="bp" value="{{ $data->bp }}">
                P <input type="text" name="pulse" value="{{ $data->pulse }}">
                O2sat <input type="text" name="o2sat" value="{{ $data->o2sat }}">
                % T <input type="text" name="temp" value="{{ $data->temp }}">
                PS: <input type="text" name="ps" value="{{ $data->ps }}"> /10
            </div>

            <div class="row">S:<br><textarea rows="2" name="s">{{ $data->s }}</textarea></div>
            <div class="row">O:<br><textarea rows="2" name="o">{{ $data->o }}</textarea></div>
            <div class="row">A:<br><textarea rows="2" name="a">{{ $data->a }}</textarea></div>
            <div class="row">P:<br><textarea rows="2" name="p">{{ $data->p }}</textarea></div>

            <div class="signature-line">
                #<input type="text" name="number" value="{{ $data->number }}">
                OT Signature <input type="text" name="signature" value="{{ $data->signature }}">
            </div>
        </div>
    @endforeach





</div>
</body>
</html>

