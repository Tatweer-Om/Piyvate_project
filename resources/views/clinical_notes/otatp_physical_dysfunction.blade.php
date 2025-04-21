<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Occupational Therapy Assessment and Treatment Plan</title>
  <link rel="stylesheet" href="{{ asset('css/physical.css') }}">

</head>
<body>
  <div class="page">
    <form action="{{ url('add_physical_dysfunction') }}" method="POST">
        @csrf
        <div class="header">
            <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Center Logo">
            <div class="header-info">
                HN: <input class="input-dotted input-medium" value="{{ $patient->HN ?? '' }}" name="hn">
                PT no: <input class="input-dotted input-medium" name="pt_no"><br>
                Name: <input class="input-dotted input-long" value="{{ $patient->full_name ?? '' }}" name="name">
                Age: <input class="input-dotted input-short" value="{{ $patient->age ?? '' }}" name="age">
                Gender:
                <input type="checkbox" name="gender_m" {{ $patient->gender == 'Male' ? 'checked' : '' }}>M
                <input type="checkbox" name="gender_f" {{ $patient->gender == 'Female' ? 'checked' : '' }}>F <br>
                Birth Date: <input class="input-dotted input-medium" value="{{ $patient->dob ?? '' }}" name="birth_date">
                Therapist: <input class="input-dotted input-medium" value="{{ $doctor ?? '' }}" name="therapist">
            </div>
        </div>

        <input type="hidden" value="{{ $apt->id ?? '' }}" name="appointment_id">
        <input type="hidden" value="{{ $patient->id ?? '' }}" name="patient_id">
        <input type="hidden" value="{{ $apt->doctor_id ?? '' }}" name="doctor_id">

        <div class="section-header">Occupational Therapy Assessment and treatment plan for Physical dysfunction</div>

        <div class="box-section">
            Chief complaint <input class="input-dotted input-long" name="chief_complaint" style="width: 730px;"><br>
            History of birth and illness <input class="input-dotted input-long" name="birth_history" style="width: 674px;"><br>
            <input class="input-dotted input-long" name="birth_history_2" style="width: 813px;"><br>
            Underlying <input class="input-dotted input-medium" name="underlying" style="width: 752px;"><br>
            Operation <input class="input-dotted input-medium" name="operation" style="width: 754px;"><br>
            Laboratory Radiology result <input class="input-dotted input-long" name="lab_result" style="width: 660px;">
        </div>

        <div class="box-section">
            <strong>Physical examination</strong><br>
            Muscle tone <input class="input-dotted input-medium" name="muscle_tone" style="width: 320px;">
            Muscle strength <input class="input-dotted input-medium" name="muscle_strength" style="width: 320px;"><br>
            ROM <input class="input-dotted input-long" name="rom" style="width: 770px;"><br>
            Sensory <input class="input-dotted input-long" name="sensory" style="width: 754px;"><br>
            Coordination <input class="input-dotted input-medium" name="coordination" style="width: 320px;">
            Endurance <input class="input-dotted input-medium" name="endurance" style="width: 340px;"><br>
            ADL Independence <input class="input-dotted input-medium" name="adl_independence" style="width: 694px;">
            Assist <input class="input-dotted input-medium" name="adl_assist" style="width: 340px;">
            Dependence <input class="input-dotted input-medium" name="adl_dependence" style="width: 344px;"><br>
            Hand function and prehension <input class="input-dotted input-long" name="hand_function"><br>
            Dominant Hand
            <input type="checkbox" name="dominant_hand_right">Right
            <input type="checkbox" name="dominant_hand_left">Left &nbsp;&nbsp;
            Affected hand
            <input type="checkbox" name="affected_hand_right">Right
            <input type="checkbox" name="affected_hand_left">Left<br>
            Swallowing function
            <input type="checkbox" name="swallowing_normal">Normal
            <input type="checkbox" name="swallowing_aspiration">Risk for aspiration &nbsp;&nbsp;
            Current status <input class="input-dotted input-medium" name="current_status" style="width: 420px;"><br>
            Neck control
            <input type="checkbox" name="neck_good">Good
            <input type="checkbox" name="neck_fair">Fair
            <input type="checkbox" name="neck_poor">Poor<br>
            Oral phase <input class="input-dotted input-medium" name="oral_phase" style="width: 320px;">
            Pharyngeal phase <input class="input-dotted input-medium" name="pharyngeal_phase" style="width: 310px;"><br>
            Comments <input class="input-dotted input-long" name="comments" style="width: 740px;">
        </div>

        <div class="box-section">
            <strong>Perception and cognitive function</strong><br>
            Perception
            <input type="checkbox" name="perception_intact">Intact
            <input type="checkbox" name="perception_impaired">Impaired
            Attention
            <input type="checkbox" name="attention_intact">Intact
            <input type="checkbox" name="attention_impaired">Impaired<br>
            Memory
            <input type="checkbox" name="memory_intact">Intact
            <input type="checkbox" name="memory_impaired">Impaired
            Orientation
            <input type="checkbox" name="orientation_intact">Intact
            <input type="checkbox" name="orientation_impaired">Impaired<br>
            Executive function
            <input type="checkbox" name="executive_function_intact">Intact
            <input type="checkbox" name="executive_function_impaired">Impaired<br>
            Splint requirement <input class="input-dotted input-long" name="splint_requirement" style="width: 700px;"><br>
            Fall risk assessment
            <input type="checkbox" name="fall_risk_low">Low
            <input type="checkbox" name="fall_risk_high">High
        </div>

        <div class="box-section">
            <div style="display: flex; justify-content: space-between;">
                <div style="flex: 1; margin-right: 10px;">
                    <strong>Pain assessment</strong><br>
                    Does patient have pain?
                    <input type="checkbox" name="pain_no">No
                    <input type="checkbox" name="pain_yes">Yes<br>
                    Score <input class="input-dotted input-short" name="pain_score" style="width: 50px;">
                    Pain location <input class="input-dotted input-medium" name="pain_location" style="width: 50px;">
                    Duration <input class="input-dotted input-medium" name="pain_duration" style="width: 50px;">
                    Characteristic <input class="input-dotted input-medium" name="pain_characteristic" style="width: 50px;">
                    Frequency <input class="input-dotted input-medium" name="pain_frequency" style="width: 70px;"><br>
                    OT diagnosis is <input class="input-dotted input-long" name="ot_diagnosis" style="width: 505px;"><br>
                    OT program <input class="input-dotted input-long" name="ot_program" style="width: 520px;"><br>
                    Goal of treatment<br>
                    Short term goal <input class="input-dotted input-long" name="goal_short" style="width: 500px;"><br>
                    Long term goal <input class="input-dotted input-long" name="goal_long" style="width: 500px;"><br>
                    Patient and family education <input class="input-dotted input-long" name="education" style="width: 430px;"><br>
                    Occupational Therapist's name <input class="input-dotted input-medium" name="ot_name" style="width: 412px;"><br>
                    Date <input class="input-dotted input-short" name="date" style="width: 120px;">
                    Time <input class="input-dotted input-short" name="time" style="width: 120px;">
                </div>
                <div class="pain-tool-box">
                    <strong>Pain assessment tool</strong><br>
                    <input type="checkbox" name="tool_nips">&lt;1 year (NIPS)<br>
                    <input type="checkbox" name="tool_flacc_1_3">1–3 years (FLACC)<br>
                    <input type="checkbox" name="tool_flacc_3_8">&gt;3–8 years (FLACC)<br>
                    <input type="checkbox" name="tool_nrs">&gt;8 years (NRS)<br>
                    <input type="checkbox" name="tool_bps">BPS (impaired cognition / elder)
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <button type="submit" class="custom-grey-button">
                Save Prescription
            </button>
        </div>
    </form>

  </div>
</body>
</html>
