<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Physical Therapy Follow-Up Form</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .page {
      width: 850px;
      margin: auto;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
      padding: 15px;
      background: #fff;
    }        .header { display: flex; justify-content: space-between; align-items: center; }
        .section-header { text-align: center; font-weight: bold; border: 1px solid #000; padding: 5px; margin-top: 10px; }
        .section { border: 1px solid #000; padding: 10px; margin-bottom: 20px; }
        input[type="text"] {
            border: none;
            border-bottom: 1px dotted #000;
            outline: none;
            font-family: inherit;
            font-size: 1em;
        }
        .input-line { width: 80px; }
        .input-short { width: 60px; }
        textarea {
            width: 100%;
            border: none;
            border-bottom: 1px dotted #000;
            outline: none;
            resize: vertical;
            font-family: inherit;
        }
        .checkbox-label { margin-right: 20px; }
        .row { margin-bottom: 5px; }
        .signature-line { text-align: right; margin-top: 30px; }
        .soap-wrapper { display: flex; gap: 20px; }
        .soap-content { flex: 0 0 66.66%; }
        .anatomy-img-wrapper { flex: 0 0 33.33%; display: flex; justify-content: center; align-items: center; }
        .anatomy-img { max-width: 91%; height: auto; }
    </style>
</head>
<body>
<div class="page">
    <form action="{{ url('add_soap_pt') }}" method="POST">
        @csrf
      <div class="header">
        <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Center Logo" height="50">
        <div>
            HN:<input type="text" class="input-line" value="{{ $patient->HN ?? '' }}"> PT:<input type="text" class="input-line"><br>
            Name:<input type="text" class="input-line" value="{{ $patient->full_name ?? '' }}"> Age:<input type="text" class="input-short input-line" value="{{ $patient->age ?? '' }}">
            Gender:<label>
                <input type="checkbox" name="gender_m" value="male" {{ $patient->gender == 'Male' ? 'checked' : '' }}> M
            </label>
            <label>
                <input type="checkbox" name="gender_f" value="female" {{ $patient->gender == 'Female' ? 'checked' : '' }}> F
            </label><br>
            Therapist:<input type="text" class="input-line" value="{{ $doctor ?? '' }}">
        </div>
    </div>
    <input type="hidden" value="{{ $apt->id ?? '' }}" name="appointment_id" class="appointment_id">
    <input type="hidden" value="{{ $patient->id ?? '' }}" name="patient_id" class="patient_id">
    <input type="hidden" value="{{ $apt->doctor_id ?? '' }}" name="doctor_id" class="doctor_id">

    <div class="section">
        <div class="section-header">Physical Therapy Follow up and Re-assessment</div>
        <div class="soap-wrapper">
            <div class="soap-content">
                <div class="row">
                    Date <input type="text" class="input-line"> Time <input type="text" class="input-line">
                    V/S BP <input type="text" class="input-line"> P <input type="text" class="input-line">
                    O2sat <input type="text" class="input-line"> % T <input type="text" class="input-line">
                    PS: <input type="text" class="input-line">/10
                </div>

                <div class="row">S:<br><textarea rows="2"></textarea></div>
                <div class="row">O:<br><textarea rows="2"></textarea></div>
                <div class="row">A:<br><textarea rows="2"></textarea></div>
                <div class="row">P:<br><textarea rows="2"></textarea></div>

                <div class="signature-line">
                    #<input type="text" class="input-line"> PT Signature <input type="text" class="input-line">
                </div>
            </div>
            <div class="anatomy-img-wrapper">
                <img src="{{ asset('images/logo/model3.png') }}" alt="Anatomy" class="anatomy-img">
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-header">Physical Therapy Follow up and Re-assessment</div>
        <div class="soap-wrapper">
            <div class="soap-content">
                <div class="row">
                    Date <input type="text" class="input-line"> Time <input type="text" class="input-line">
                    V/S BP <input type="text" class="input-line"> P <input type="text" class="input-line">
                    O2sat <input type="text" class="input-line"> % T <input type="text" class="input-line">
                    PS: <input type="text" class="input-line">/10
                </div>

                <div class="row">S:<br><textarea rows="2"></textarea></div>
                <div class="row">O:<br><textarea rows="2"></textarea></div>
                <div class="row">A:<br><textarea rows="2"></textarea></div>
                <div class="row">P:<br><textarea rows="2"></textarea></div>

                <div class="signature-line">
                    #<input type="text" class="input-line"> PT Signature <input type="text" class="input-line">
                </div>
            </div>
            <div class="anatomy-img-wrapper">
                <img src="{{ asset('images/logo/model3.png') }}" alt="Anatomy" class="anatomy-img">
            </div>
        </div>
    </div>
</form>
</div>
</body>
</html>
