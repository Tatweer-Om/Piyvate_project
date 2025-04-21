<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Occupational Therapy Assessment and Treatment Plan</title>
  <link rel="stylesheet" href="{{ asset('css/physical.css') }}">

</head>
<body>
  <div class="page">
    <form action="{{ url('update_physical_dysfunction/' . $note->id )}}" method="POST">
        @csrf
        <div class="header">
            <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Center Logo">
            <div class="header-info">
                HN: <input class="input-dotted input-medium" value="{{ $patient->HN ?? '' }}" name="hn">
                PT no: <input class="input-dotted input-medium" name="pt_no" value="{{ $data['pt_no'] ?? '' }}"><br>
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
            Chief complaint
            <input class="input-dotted input-long" name="chief_complaint"
                   value="{{ $data['chief_complaint'] ?? '' }}" style="width: 730px;"><br>

            History of birth and illness
            <input class="input-dotted input-long" name="birth_history"
                   value="{{ $data['birth_history'] ?? '' }}" style="width: 674px;"><br>

            <input class="input-dotted input-long" name="birth_history_2"
                   value="{{ $data['birth_history_2'] ?? '' }}" style="width: 813px;"><br>

            Underlying
            <input class="input-dotted input-medium" name="underlying"
                   value="{{ $data['underlying'] ?? '' }}" style="width: 752px;"><br>

            Operation
            <input class="input-dotted input-medium" name="operation"
                   value="{{ $data['operation'] ?? '' }}" style="width: 754px;"><br>

            Laboratory Radiology result
            <input class="input-dotted input-long" name="lab_result"
                   value="{{ $data['lab_result'] ?? '' }}" style="width: 660px;">
        </div>

        <div class="box-section">
            <strong>Physical examination</strong><br>

            Muscle tone
            <input class="input-dotted input-medium" name="muscle_tone"
                   value="{{ $data['muscle_tone'] ?? '' }}" style="width: 320px;">

            Muscle strength
            <input class="input-dotted input-medium" name="muscle_strength"
                   value="{{ $data['muscle_strength'] ?? '' }}" style="width: 320px;"><br>

            ROM
            <input class="input-dotted input-long" name="rom"
                   value="{{ $data['rom'] ?? '' }}" style="width: 770px;"><br>

            Sensory
            <input class="input-dotted input-long" name="sensory"
                   value="{{ $data['sensory'] ?? '' }}" style="width: 754px;"><br>

            Coordination
            <input class="input-dotted input-medium" name="coordination"
                   value="{{ $data['coordination'] ?? '' }}" style="width: 320px;">

            Endurance
            <input class="input-dotted input-medium" name="endurance"
                   value="{{ $data['endurance'] ?? '' }}" style="width: 340px;"><br>

            ADL Independence
            <input class="input-dotted input-medium" name="adl_independence"
                   value="{{ $data['adl_independence'] ?? '' }}" style="width: 694px;">

            Assist
            <input class="input-dotted input-medium" name="adl_assist"
                   value="{{ $data['adl_assist'] ?? '' }}" style="width: 340px;">

            Dependence
            <input class="input-dotted input-medium" name="adl_dependence"
                   value="{{ $data['adl_dependence'] ?? '' }}" style="width: 344px;"><br>

            Hand function and prehension
            <input class="input-dotted input-long" name="hand_function"
                   value="{{ $data['hand_function'] ?? '' }}"><br>

            Dominant Hand
            <input type="checkbox" name="dominant_hand_right" {{ !empty($data['dominant_hand_right']) ? 'checked' : '' }}>Right
            <input type="checkbox" name="dominant_hand_left" {{ !empty($data['dominant_hand_left']) ? 'checked' : '' }}>Left &nbsp;&nbsp;

            Affected hand
            <input type="checkbox" name="affected_hand_right" {{ !empty($data['affected_hand_right']) ? 'checked' : '' }}>Right
            <input type="checkbox" name="affected_hand_left" {{ !empty($data['affected_hand_left']) ? 'checked' : '' }}>Left<br>

            Swallowing function
            <input type="checkbox" name="swallowing_normal" {{ !empty($data['swallowing_normal']) ? 'checked' : '' }}>Normal
            <input type="checkbox" name="swallowing_aspiration" {{ !empty($data['swallowing_aspiration']) ? 'checked' : '' }}>Risk for aspiration &nbsp;&nbsp;

            Current status
            <input class="input-dotted input-medium" name="current_status"
                   value="{{ $data['current_status'] ?? '' }}" style="width: 420px;"><br>

            Neck control
            <input type="checkbox" name="neck_good" {{ !empty($data['neck_good']) ? 'checked' : '' }}>Good
            <input type="checkbox" name="neck_fair" {{ !empty($data['neck_fair']) ? 'checked' : '' }}>Fair
            <input type="checkbox" name="neck_poor" {{ !empty($data['neck_poor']) ? 'checked' : '' }}>Poor<br>

            Oral phase
            <input class="input-dotted input-medium" name="oral_phase"
                   value="{{ $data['oral_phase'] ?? '' }}" style="width: 320px;">

            Pharyngeal phase
            <input class="input-dotted input-medium" name="pharyngeal_phase"
                   value="{{ $data['pharyngeal_phase'] ?? '' }}" style="width: 310px;"><br>

            Comments
            <input class="input-dotted input-long" name="comments"
                   value="{{ $data['comments'] ?? '' }}" style="width: 740px;">
        </div>


        <div class="box-section">
            <strong>Perception and cognitive function</strong><br>

            Perception
            <input type="checkbox" name="perception_intact" {{ isset($data['perception_intact']) && $data['perception_intact'] == 'on' ? 'checked' : '' }}>Intact
            <input type="checkbox" name="perception_impaired" {{ isset($data['perception_impaired']) && $data['perception_impaired'] == 'on' ? 'checked' : '' }}>Impaired

            Attention
            <input type="checkbox" name="attention_intact" {{ isset($data['attention_intact']) && $data['attention_intact'] == 'on' ? 'checked' : '' }}>Intact
            <input type="checkbox" name="attention_impaired" {{ isset($data['attention_impaired']) && $data['attention_impaired'] == 'on' ? 'checked' : '' }}>Impaired<br>

            Memory
            <input type="checkbox" name="memory_intact" {{ isset($data['memory_intact']) && $data['memory_intact'] == 'on' ? 'checked' : '' }}>Intact
            <input type="checkbox" name="memory_impaired" {{ isset($data['memory_impaired']) && $data['memory_impaired'] == 'on' ? 'checked' : '' }}>Impaired

            Orientation
            <input type="checkbox" name="orientation_intact" {{ isset($data['orientation_intact']) && $data['orientation_intact'] == 'on' ? 'checked' : '' }}>Intact
            <input type="checkbox" name="orientation_impaired" {{ isset($data['orientation_impaired']) && $data['orientation_impaired'] == 'on' ? 'checked' : '' }}>Impaired<br>

            Executive function
            <input type="checkbox" name="executive_function_intact" {{ isset($data['executive_function_intact']) && $data['executive_function_intact'] == 'on' ? 'checked' : '' }}>Intact
            <input type="checkbox" name="executive_function_impaired" {{ isset($data['executive_function_impaired']) && $data['executive_function_impaired'] == 'on' ? 'checked' : '' }}>Impaired<br>

            Splint requirement
            <input class="input-dotted input-long" name="splint_requirement" style="width: 700px;" value="{{ $data['splint_requirement'] ?? '' }}"><br>

            Fall risk assessment
            <input type="checkbox" name="fall_risk_low" {{ isset($data['fall_risk_low']) && $data['fall_risk_low'] == 'on' ? 'checked' : '' }}>Low
            <input type="checkbox" name="fall_risk_high" {{ isset($data['fall_risk_high']) && $data['fall_risk_high'] == 'on' ? 'checked' : '' }}>High
        </div>


        <div class="box-section">
            <div style="display: flex; justify-content: space-between;">
                <div style="flex: 1; margin-right: 10px;">
                    <strong>Pain assessment</strong><br>
                    Does patient have pain?
                    <input type="checkbox" name="pain_no" {{ isset($data['pain_no']) && $data['pain_no'] == 'on' ? 'checked' : '' }}>No
                    <input type="checkbox" name="pain_yes" {{ isset($data['pain_yes']) && $data['pain_yes'] == 'on' ? 'checked' : '' }}>Yes<br>

                    Score <input class="input-dotted input-short" name="pain_score" style="width: 50px;" value="{{ $data['pain_score'] ?? '' }}">
                    Pain location <input class="input-dotted input-medium" name="pain_location" style="width: 50px;" value="{{ $data['pain_location'] ?? '' }}">
                    Duration <input class="input-dotted input-medium" name="pain_duration" style="width: 50px;" value="{{ $data['pain_duration'] ?? '' }}">
                    Characteristic <input class="input-dotted input-medium" name="pain_characteristic" style="width: 50px;" value="{{ $data['pain_characteristic'] ?? '' }}">
                    Frequency <input class="input-dotted input-medium" name="pain_frequency" style="width: 70px;" value="{{ $data['pain_frequency'] ?? '' }}"><br>

                    OT diagnosis is <input class="input-dotted input-long" name="ot_diagnosis" style="width: 505px;" value="{{ $data['ot_diagnosis'] ?? '' }}"><br>
                    OT program <input class="input-dotted input-long" name="ot_program" style="width: 520px;" value="{{ $data['ot_program'] ?? '' }}"><br>

                    Goal of treatment<br>
                    Short term goal <input class="input-dotted input-long" name="goal_short" style="width: 500px;" value="{{ $data['goal_short'] ?? '' }}"><br>
                    Long term goal <input class="input-dotted input-long" name="goal_long" style="width: 500px;" value="{{ $data['goal_long'] ?? '' }}"><br>
                    Patient and family education <input class="input-dotted input-long" name="education" style="width: 430px;" value="{{ $data['education'] ?? '' }}"><br>
                    Occupational Therapist's name <input class="input-dotted input-medium" name="ot_name" style="width: 412px;" value="{{ $data['ot_name'] ?? '' }}"><br>
                    Date <input class="input-dotted input-short" name="date" style="width: 120px;" value="{{ $data['date'] ?? '' }}">
                    Time <input class="input-dotted input-short" name="time" style="width: 120px;" value="{{ $data['time'] ?? '' }}">
                </div>

                <div class="pain-tool-box">
                    <strong>Pain assessment tool</strong><br>
                    <input type="checkbox" name="tool_nips" {{ isset($data['tool_nips']) && $data['tool_nips'] == 'on' ? 'checked' : '' }}>&lt;1 year (NIPS)<br>
                    <input type="checkbox" name="tool_flacc_1_3" {{ isset($data['tool_flacc_1_3']) && $data['tool_flacc_1_3'] == 'on' ? 'checked' : '' }}>1–3 years (FLACC)<br>
                    <input type="checkbox" name="tool_flacc_3_8" {{ isset($data['tool_flacc_3_8']) && $data['tool_flacc_3_8'] == 'on' ? 'checked' : '' }}>&gt;3–8 years (FLACC)<br>
                    <input type="checkbox" name="tool_nrs" {{ isset($data['tool_nrs']) && $data['tool_nrs'] == 'on' ? 'checked' : '' }}>&gt;8 years (NRS)<br>
                    <input type="checkbox" name="tool_bps" {{ isset($data['tool_bps']) && $data['tool_bps'] == 'on' ? 'checked' : '' }}>BPS (impaired cognition / elder)
                </div>
            </div>
        </div>



        <div class="col-lg-12">
            <button type="submit" class="custom-grey-button">
                Update Prescription
            </button>
        </div>
    </form>

  </div>
</body>
</html>
