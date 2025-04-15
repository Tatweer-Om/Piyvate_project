<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Occupational Therapy Assessment and Treatment Plan for Pediatric</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
      margin: 0;
      padding: 20px;
      background-color: #f9f9f9;
    }
    input.input-line.full-width {
  word-wrap: break-word; /* Allows text to wrap inside the input field */
  white-space: normal;    /* Ensures that the text wraps correctly when long */
}

    .page {
      width: 850px;
      margin: auto;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
      padding: 15px;
      background: #fff;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    .header img {
      height: 50px;
    }

    .header-info {
      font-size: 12px;
    }

    .section-header {
      text-align: center;
      font-weight: bold;
      border: 2px solid #000;
      padding: 5px;
      margin-top: 10px;
      margin-bottom: 10px;
    }

    .input-line {
      border: none;
      border-bottom: 1px dotted #000;
      display: inline-block;
      min-width: 80px;
      padding: 2px 4px;
      margin: 0 2px;
      background: transparent;
      font-size: 12px;
    }

    .input-line:focus {
      outline: none;
    }

    .checkbox-group {
      display: inline-block;
      margin-right: 10px;
    }

    .box-section {
      border: 2px solid #000;
      padding: 10px;
      margin-top: 10px;
    }

    .pain-tool-box {
      border: 2px solid green;
      padding: 5px;
      float: right;
      width: 200px;
      font-size: 11px;
    }

    .full-width {
      width: 100%;
    }

    .half-width {
      width: 48%;
    }

    strong {
      font-weight: bold;
    }

  </style>
</head>
<body>
  <div class="page">
    <form action="{{ url('add_otp_pediatric') }}">
        @csrf
    <div class="header">
      <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Center Logo">
      <div class="header-info">
        HN: <input class="input-line" value="{{ $patient->HN ?? '' }}">
        PT no: <input class="input-line"><br>
        Name: <input class="input-line" value="{{ $patient->full_name ?? '' }}">
        Age: <input class="input-line" style="width: 40px;" value="{{ $patient->age ?? '' }}">
        Gender:
        <input type="checkbox" {{ $patient->gender == 'Male' ? 'checked' : '' }}>M
        <input type="checkbox" {{ $patient->gender == 'Female' ? 'checked' : '' }}>F
        <br>
        Birth Date: <input class="input-line" value="{{ $patient->dob ?? '' }}">
        Therapist: <input class="input-line" value="{{ $doctor ?? '' }}">
      </div>
    </div>

    <input type="hidden" value="{{ $opt->id ?? '' }}" name="appointment_id" class="appointment_id">
    <input type="hidden" value="{{ $patient->id ?? '' }}" name="patient_id" class="patient_id">


    <div class="section-header">Occupational Therapy Assessment and treatment plan for Pediatric</div>

    <div class="box-section">
      Chief complaint: <input class="input-line full-width"><br>
      General appearance: <input class="input-line full-width"><br>
      History of birth and illness: <input class="input-line full-width"><br>
      Behavioural issue: <input class="input-line full-width">
    </div>

    <div class="box-section">
      <strong>Developmental</strong><br>
      Gross motor: <input class="input-line full-width"><br>
      Fine motor: <input class="input-line full-width"><br>
      Language: <input class="input-line full-width"><br>
      Personal social: <input class="input-line full-width"><br>
      Cognitive function: <input class="input-line full-width">
    </div>

    <div class="box-section">
      <strong>Functional assessment</strong><br>
      Vestibular system (balance /coordination /level of arousal): <input class="input-line full-width"><br>
      Proprioceptive system (bilateral coordination /muscle tone /motor planning): <input class="input-line full-width"><br>
      Tactile system (sensitivity /discrimination): <input class="input-line full-width"><br>
      Muscle tone upper extremity: <input class="input-line half-width">
      Lower extremity: <input class="input-line half-width"><br>
      Sensation (touch /tactile localization /proprioceptive /stereognosis): <input class="input-line full-width"><br>
      ROM: <input class="input-line full-width"><br>
      Hand use: <input class="input-line full-width"><br>
      Oro motor function: <input class="input-line full-width"><br>
      Oral reflexes (rooting /sucking /swallowing /bite /gag): <input class="input-line full-width"><br>
      ADL: <input class="input-line full-width"><br>
      Visual perception: <input class="input-line full-width"><br>
      <strong>Reflexes</strong>: <input class="input-line full-width"><br>



      Fall risk assessment
      <input type="checkbox">Low
      <input type="checkbox">High<br>

      Pain assessment Does patient have pain?
      <input type="checkbox">No
      <input type="checkbox">Yes<br>

      Score: <input class="input-line" style="width: 50px;">
      Pain location: <input class="input-line" style="width: 150px;">
      Duration: <input class="input-line" style="width: 100px;">
      Characteristic: <input class="input-line" style="width: 100px;">
      Frequency: <input class="input-line" style="width: 100px;"><br>

      <div style="display: flex; justify-content: space-between; gap: 20px;">
        <div style="flex: 1;">
          OT diagnosis is: <input class="input-line full-width"><br>
          OT program: <input class="input-line full-width"><br>

          Goal of treatment<br>
          Short term goal: <input class="input-line full-width"><br>
          Long term goal: <input class="input-line full-width"><br>

          Patient and family education: <input class="input-line full-width"><br>
          Occupational Therapist's name: <input class="input-line full-width"><br>

          Date: <input class="input-line">
          Time: <input class="input-line">
        </div>

        <div class="pain-tool-box" style="margin: 0; float: none;">
          <strong>Pain assessment tool</strong><br>
          <input type="checkbox"> &lt;1 year (NIPS)<br>
          <input type="checkbox"> 1-3 years (FLACC)<br>
          <input type="checkbox"> &gt;3-8 years (FLACC)<br>
          <input type="checkbox"> &gt;8 years (NRS)<br>
          <input type="checkbox"> BPS (impaired cognition / elder)
        </div>
      </div>


    </div>
</form>
  </div>
</body>
</html>
