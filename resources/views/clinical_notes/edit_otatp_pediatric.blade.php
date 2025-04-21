<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Occupational Therapy Assessment and Treatment Plan for Pediatric</title>
    <link rel="stylesheet" href="{{ asset('css/pediatric.css') }}">

</head>

<body>
    <div class="page">
        <form action="{{ url('update_otp_pediatric/'. $note->id) }}" method="POST">
            @csrf
            <div class="header">
                <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Center Logo">
                <div class="header-info">
                    HN: <input name="hn" class="input-line" value="{{ $patient->HN ?? '' }}">
                    PT no: <input class="input-line" name="pt" value="{{ $data['pt'] ?? '' }}"><br>
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
                Chief complaint:
                <input class="input-line full-width" name="chief_complaint"
                       value="{{ old('chief_complaint', $data['chief_complaint'] ?? '') }}"><br>

                General appearance:
                <input class="input-line full-width" name="general_appearance"
                       value="{{ old('general_appearance', $data['general_appearance'] ?? '') }}"><br>

                History of birth and illness:
                <input class="input-line full-width" name="birth_history"
                       value="{{ old('birth_history', $data['birth_history'] ?? '') }}"><br>

                Behavioural issue:
                <input class="input-line full-width" name="behavioral_issue"
                       value="{{ old('behavioral_issue', $data['behavioral_issue'] ?? '') }}">
            </div>

            <div class="box-section">
                <strong>Developmental</strong><br>
                Gross motor:
                <input class="input-line full-width" name="gross_motor"
                       value="{{ old('gross_motor', $data['gross_motor'] ?? '') }}"><br>

                Fine motor:
                <input class="input-line full-width" name="fine_motor"
                       value="{{ old('fine_motor', $data['fine_motor'] ?? '') }}"><br>

                Language:
                <input class="input-line full-width" name="language"
                       value="{{ old('language', $data['language'] ?? '') }}"><br>

                Personal social:
                <input class="input-line full-width" name="personal_social"
                       value="{{ old('personal_social', $data['personal_social'] ?? '') }}"><br>

                Cognitive function:
                <input class="input-line full-width" name="cognitive_function"
                       value="{{ old('cognitive_function', $data['cognitive_function'] ?? '') }}">
            </div>

            <div class="box-section">
                <strong>Functional assessment</strong><br>
                Vestibular system:
                <input class="input-line full-width" name="vestibular_system"
                       value="{{ old('vestibular_system', $data['vestibular_system'] ?? '') }}"><br>

                Proprioceptive system:
                <input class="input-line full-width" name="proprioceptive_system"
                       value="{{ old('proprioceptive_system', $data['proprioceptive_system'] ?? '') }}"><br>

                Tactile system:
                <input class="input-line full-width" name="tactile_system"
                       value="{{ old('tactile_system', $data['tactile_system'] ?? '') }}"><br>

                Muscle tone upper extremity:
                <input class="input-line half-width" name="muscle_tone_upper"
                       value="{{ old('muscle_tone_upper', $data['muscle_tone_upper'] ?? '') }}">

                Lower extremity:
                <input class="input-line half-width" name="muscle_tone_lower"
                       value="{{ old('muscle_tone_lower', $data['muscle_tone_lower'] ?? '') }}"><br>

                Sensation:
                <input class="input-line full-width" name="sensation"
                       value="{{ old('sensation', $data['sensation'] ?? '') }}"><br>

                ROM:
                <input class="input-line full-width" name="rom"
                       value="{{ old('rom', $data['rom'] ?? '') }}"><br>

                Hand use:
                <input class="input-line full-width" name="hand_use"
                       value="{{ old('hand_use', $data['hand_use'] ?? '') }}"><br>

                Oro motor function:
                <input class="input-line full-width" name="oro_motor_function"
                       value="{{ old('oro_motor_function', $data['oro_motor_function'] ?? '') }}"><br>

                Oral reflexes:
                <input class="input-line full-width" name="oral_reflexes"
                       value="{{ old('oral_reflexes', $data['oral_reflexes'] ?? '') }}"><br>

                ADL:
                <input class="input-line full-width" name="adl"
                       value="{{ old('adl', $data['adl'] ?? '') }}"><br>

                Visual perception:
                <input class="input-line full-width" name="visual_perception"
                       value="{{ old('visual_perception', $data['visual_perception'] ?? '') }}"><br>

                <strong>Reflexes</strong>:
                <input class="input-line full-width" name="reflexes"
                       value="{{ old('reflexes', $data['reflexes'] ?? '') }}"><br>

                Fall risk assessment<br>
                <input type="checkbox" name="fall_risk_low"
                       {{ old('fall_risk_low', $data['fall_risk_low'] ?? false) ? 'checked' : '' }}> Low

                <input type="checkbox" name="fall_risk_high"
                       {{ old('fall_risk_high', $data['fall_risk_high'] ?? false) ? 'checked' : '' }}> High<br>

                Pain assessment - Does patient have pain?<br>
                <input type="checkbox" name="pain_no"
                       {{ old('pain_no', $data['pain_no'] ?? false) ? 'checked' : '' }}> No

                <input type="checkbox" name="pain_yes"
                       {{ old('pain_yes', $data['pain_yes'] ?? false) ? 'checked' : '' }}> Yes<br>

                Score:
                <input class="input-line" name="pain_score" style="width: 50px;"
                       value="{{ old('pain_score', $data['pain_score'] ?? '') }}">

                Pain location:
                <input class="input-line" name="pain_location" style="width: 150px;"
                       value="{{ old('pain_location', $data['pain_location'] ?? '') }}">

                Duration:
                <input class="input-line" name="pain_duration" style="width: 100px;"
                       value="{{ old('pain_duration', $data['pain_duration'] ?? '') }}">

                Characteristic:
                <input class="input-line" name="pain_characteristic" style="width: 100px;"
                       value="{{ old('pain_characteristic', $data['pain_characteristic'] ?? '') }}">

                Frequency:
                <input class="input-line" name="pain_frequency" style="width: 100px;"
                       value="{{ old('pain_frequency', $data['pain_frequency'] ?? '') }}"><br>



            <div style="display: flex; justify-content: space-between; gap: 20px;">
                <div style="flex: 1;">
                    OT diagnosis is:
                    <input class="input-line full-width" name="ot_diagnosis" value="{{ old('ot_diagnosis', $data['ot_diagnosis'] ?? '') }}"><br>

                    OT program:
                    <input class="input-line full-width" name="ot_program" value="{{ old('ot_program', $data['ot_program'] ?? '') }}"><br>

                    Goal of treatment<br>
                    Short term goal:
                    <input class="input-line full-width" name="short_term_goal" value="{{ old('short_term_goal', $data['short_term_goal'] ?? '') }}"><br>

                    Long term goal:
                    <input class="input-line full-width" name="long_term_goal" value="{{ old('long_term_goal', $data['long_term_goal'] ?? '') }}"><br>

                    Patient and family education:
                    <input class="input-line full-width" name="education" value="{{ old('education', $data['education'] ?? '') }}"><br>

                    Occupational Therapist's name:
                    <input class="input-line full-width" name="ot_name" value="{{ old('ot_name', $data['ot_name'] ?? '') }}"><br>

                    Date:
                    <input class="input-line" name="date" type="date" value="{{ old('date', $data['date'] ?? '') }}">

                    Time:
                    <input class="input-line" name="time" type="time" value="{{ old('time', $data['time'] ?? '') }}">
                </div>

                <div class="pain-tool-box" style="margin: 0; float: none;">
                    <strong>Pain assessment tool</strong><br>

                    <input type="checkbox" name="tool_nips" {{ old('tool_nips', $data['tool_nips'] ?? 'on') ? 'checked' : '' }}>
                    &lt;1 year (NIPS)<br>

                    <input type="checkbox" name="tool_flacc_1_3" {{ old('tool_flacc_1_3', $data['tool_flacc_1_3'] ?? 'on') ? 'checked' : '' }}>
                    1–3 years (FLACC)<br>

                    <input type="checkbox" name="tool_flacc_3_8" {{ old('tool_flacc_3_8', $data['tool_flacc_3_8'] ?? 'on') ? 'checked' : '' }}>
                    &gt;3–8 years (FLACC)<br>

                    <input type="checkbox" name="tool_nrs" {{ old('tool_nrs', $data['tool_nrs'] ?? 'on') ? 'checked' : '' }}>
                    &gt;8 years (NRS)<br>

                    <input type="checkbox" name="tool_bps" {{ old('tool_bps', $data['tool_bps'] ?? 'on') ? 'checked' : '' }}>
                    BPS (impaired cognition / elder)
                </div>
            </div>

            <div class="col-lg-12">
                <button type="submit" class="custom-grey-button">
                    Update Prescription
                </button>
            </div>
        </div>
        </form>

    </div>
</body>

</html>
