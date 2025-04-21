<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Occupational Therapy Assessment and Treatment Plan for Pediatric</title>
    <link rel="stylesheet" href="{{ asset('css/pediatric.css') }}">

</head>

<body>
    <div class="page">
        <form action="{{ url('add_otp_pediatric') }}" method="POST">
            @csrf
            <div class="header">
                <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Center Logo">
                <div class="header-info">
                    HN: <input name="hn" class="input-line" value="{{ $patient->HN ?? '' }}">
                    PT no: <input class="input-line" name="pt"><br>
                    Name: <input class="input-line" name="full_name" value="{{ $patient->full_name ?? '' }}">
                    Age: <input class="input-line" name="age" style="width: 40px;" value="{{ $patient->age ?? '' }}">
                    Gender:
                    <input type="checkbox" name="gender_male" {{ $patient->gender == 'Male' ? 'checked' : '' }}>M
                    <input type="checkbox" name="gender_female" {{ $patient->gender == 'Female' ? 'checked' : '' }}>F
                    <br>
                    Birth Date: <input class="input-line" name="dob" value="{{ $patient->dob ?? '' }}">
                    Therapist: <input class="input-line" name="therapist" value="{{ $doctor ?? '' }}">
                </div>
            </div>

            <input type="hidden" value="{{ $apt->id ?? '' }}" name="appointment_id" class="appointment_id">
            <input type="hidden" value="{{ $patient->id ?? '' }}" name="patient_id" class="patient_id">
            <input type="hidden" value="{{ $apt->doctor_id ?? '' }}" name="doctor_id" class="doctor_id">

            <div class="section-header">Occupational Therapy Assessment and treatment plan for Pediatric</div>

            <div class="box-section">
                Chief complaint: <input class="input-line full-width" name="chief_complaint"><br>
                General appearance: <input class="input-line full-width" name="general_appearance"><br>
                History of birth and illness: <input class="input-line full-width" name="birth_history"><br>
                Behavioural issue: <input class="input-line full-width" name="behavioral_issue">
            </div>

            <div class="box-section">
                <strong>Developmental</strong><br>
                Gross motor: <input class="input-line full-width" name="gross_motor"><br>
                Fine motor: <input class="input-line full-width" name="fine_motor"><br>
                Language: <input class="input-line full-width" name="language"><br>
                Personal social: <input class="input-line full-width" name="personal_social"><br>
                Cognitive function: <input class="input-line full-width" name="cognitive_function">
            </div>

            <div class="box-section">
                <strong>Functional assessment</strong><br>
                Vestibular system: <input class="input-line full-width" name="vestibular_system"><br>
                Proprioceptive system: <input class="input-line full-width" name="proprioceptive_system"><br>
                Tactile system: <input class="input-line full-width" name="tactile_system"><br>
                Muscle tone upper extremity: <input class="input-line half-width" name="muscle_tone_upper">
                Lower extremity: <input class="input-line half-width" name="muscle_tone_lower"><br>
                Sensation: <input class="input-line full-width" name="sensation"><br>
                ROM: <input class="input-line full-width" name="rom"><br>
                Hand use: <input class="input-line full-width" name="hand_use"><br>
                Oro motor function: <input class="input-line full-width" name="oro_motor_function"><br>
                Oral reflexes: <input class="input-line full-width" name="oral_reflexes"><br>
                ADL: <input class="input-line full-width" name="adl"><br>
                Visual perception: <input class="input-line full-width" name="visual_perception"><br>
                <strong>Reflexes</strong>: <input class="input-line full-width" name="reflexes"><br>

                Fall risk assessment
                <input type="checkbox" name="fall_risk_low">Low
                <input type="checkbox" name="fall_risk_high">High<br>

                Pain assessment Does patient have pain?
                <input type="checkbox" name="pain_no">No
                <input type="checkbox" name="pain_yes">Yes<br>

                Score: <input class="input-line" name="pain_score" style="width: 50px;">
                Pain location: <input class="input-line" name="pain_location" style="width: 150px;">
                Duration: <input class="input-line" name="pain_duration" style="width: 100px;">
                Characteristic: <input class="input-line" name="pain_characteristic" style="width: 100px;">
                Frequency: <input class="input-line" name="pain_frequency" style="width: 100px;"><br>

                <div style="display: flex; justify-content: space-between; gap: 20px;">
                    <div style="flex: 1;">
                        OT diagnosis is: <input class="input-line full-width" name="ot_diagnosis"><br>
                        OT program: <input class="input-line full-width" name="ot_program"><br>

                        Goal of treatment<br>
                        Short term goal: <input class="input-line full-width" name="short_term_goal"><br>
                        Long term goal: <input class="input-line full-width" name="long_term_goal"><br>

                        Patient and family education: <input class="input-line full-width" name="education"><br>
                        Occupational Therapist's name: <input class="input-line full-width" name="ot_name"><br>

                        Date: <input class="input-line" name="date">
                        Time: <input class="input-line" name="time">
                    </div>

                    <div class="pain-tool-box" style="margin: 0; float: none;">
                        <strong>Pain assessment tool</strong><br>
                        <input type="checkbox" name="tool_nips"> &lt;1 year (NIPS)<br>
                        <input type="checkbox" name="tool_flacc_1_3"> 1–3 years (FLACC)<br>
                        <input type="checkbox" name="tool_flacc_3_8"> &gt;3–8 years (FLACC)<br>
                        <input type="checkbox" name="tool_nrs"> &gt;8 years (NRS)<br>
                        <input type="checkbox" name="tool_bps"> BPS (impaired cognition / elder)
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <button type="submit" class="custom-grey-button">
                    Add Prescription
                </button>
            </div>
        </form>

    </div>

</body>

</html>
