<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Physical Therapy Assessment and Treatment Plan</title>
    <link rel="stylesheet" href="{{ asset('css/neuro.css') }}">

</head>

<body>
    <div class="page_whole">
        <form  method="POST">
            @csrf

            <div class="page">
                <!-- Header Section -->
                <div class="header" style="display: flex; align-items: flex-start; padding: 10px;">
                    <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Center Logo" height="50"
                        style="margin-right: 20px;">
                    <div style="text-align: justify;">
                        <div>
                            HN:<input type="text" class="input-line" value="{{ $patient->HN ?? '' }}"
                                name="hn" /> PT:<input type="text" class="input-line" name="pt" />
                        </div>
                        <div>
                            Name:<input type="text" class="input-line" value="{{ $patient->full_name ?? '' }}" />
                            Age:<input type="text" value="{{ $patient->age ?? '' }}" class="input-line" />
                        </div>
                        <div>
                            Gender:
                            <label>
                                <input type="checkbox" name="gender_m" value="male"
                                    {{ $patient->gender == 'Male' ? 'checked' : '' }}> M
                            </label>
                            <label>
                                <input type="checkbox" name="gender_f" value="female"
                                    {{ $patient->gender == 'Female' ? 'checked' : '' }}> F
                            </label>
                        </div>
                        <div>
                            Birth Date:<input type="text" class="input-line" value="{{ $patient->dob ?? '' }}" />
                            Therapist:<input type="text" class="input-line" value="{{ $doctor ?? '' }}" />
                        </div>
                    </div>
                </div>
                <input type="hidden" value="{{ $apt->id ?? '' }}" name="appointment_id" class="appointment_id">
                <input type="hidden" value="{{ $patient->id ?? '' }}" name="patient_id" class="patient_id">
                <input type="hidden" value="{{ $apt->doctor_id ?? '' }}" name="doctor_id" class="doctor_id">


                <!-- Section Header -->
                <div class="section-header" style="margin: 20px 0;">
                    Physical Therapy Assessment and Treatment Plan for <u>Neuro</u> and Pediatric
                </div>

                <!-- Vitals -->
                <p>
                    BP (<input type="text" class="input-line" name="bp" value="{{ $data['bp'] ?? '' }}" />)
                    PR (bpm)
                    <input type="text" class="input-line" name="pr" value="{{ $data['pr'] ?? '' }}" />
                    RR (rpm)
                    <input type="text" class="input-line" name="rr" value="{{ $data['rr'] ?? '' }}" />
                    T (°C)
                    <input type="text" class="input-line" name="temperature" value="{{ $data['temperature'] ?? '' }}" />
                    O<sub>2</sub>sat (%)
                    <input type="text" class="input-line" name="o2sat" value="{{ $data['o2sat'] ?? '' }}" />
                    BW (Kg)
                    <input type="text" class="input-line" name="body_weight" value="{{ $data['body_weight'] ?? '' }}" />
                </p>

                <!-- Chief Complaint and History -->
                <p>
                    Chief complaint
                    <input type="text" class="input-line" name="chief_complaint" style="width:600px;"
                        value="{{ $data['chief_complaint'] ?? '' }}" />
                </p>
                <p>
                    History of illness
                    <input type="text" class="input-line" name="history_of_illness" style="width:600px;"
                        value="{{ $data['history_of_illness'] ?? '' }}" />
                </p>

                <!-- Other Details -->
                <p>
                    Underlying
                    <input type="text" class="input-line" name="underlying" style="width:150px;"
                        value="{{ $data['underlying'] ?? '' }}" />
                    Precaution
                    <input type="text" class="input-line" name="precaution" style="width:300px;"
                        value="{{ $data['precaution'] ?? '' }}" />
                </p>
                <p>
                    Operation
                    <input type="text" class="input-line" name="operation" style="width:300px;"
                        value="{{ $data['operation'] ?? '' }}" />
                </p>
                <p>
                    Laboratory Radiology result
                    <input type="text" class="input-line" name="lab_radiology_result" style="width:600px;"
                        value="{{ $data['lab_radiology_result'] ?? '' }}" />
                </p>

                <!-- Level of Consciousness -->
                <div style="margin: 15px 0;">
                    <p>Level of consciousness:
                        <label><input type="checkbox" name="level_of_consciousness[]" value="alert"
                                {{ isset($data['level_of_consciousness']) && in_array('alert', $data['level_of_consciousness']) ? 'checked' : '' }}>
                            Alert</label>
                        <label><input type="checkbox" name="level_of_consciousness[]" value="drowsy"
                                {{ isset($data['level_of_consciousness']) && in_array('drowsy', $data['level_of_consciousness']) ? 'checked' : '' }}>
                            Drowsy</label>
                        <label><input type="checkbox" name="level_of_consciousness[]" value="stupor"
                                {{ isset($data['level_of_consciousness']) && in_array('stupor', $data['level_of_consciousness']) ? 'checked' : '' }}>
                            Stupor</label>
                        <label><input type="checkbox" name="level_of_consciousness[]" value="semi_coma"
                                {{ isset($data['level_of_consciousness']) && in_array('semi_coma', $data['level_of_consciousness']) ? 'checked' : '' }}>
                            Semi coma</label>
                        <label><input type="checkbox" name="level_of_consciousness[]" value="coma"
                                {{ isset($data['level_of_consciousness']) && in_array('coma', $data['level_of_consciousness']) ? 'checked' : '' }}>
                            Coma</label>
                    </p>
                    <p>
                        Interpretered:
                        <label><input type="checkbox" name="interpreted" value="no"
                                {{ isset($data['interpreted']) && $data['interpreted'] === 'no' ? 'checked' : '' }}> No</label>
                        <label><input type="checkbox" name="interpreted" value="yes"
                                {{ isset($data['interpreted']) && $data['interpreted'] === 'yes' ? 'checked' : '' }}> Yes</label>
                    </p>
                    <p>
                        Observation
                        <input type="text" class="input-line" name="observation" style="width:500px;"
                            value="{{ $data['observation'] ?? '' }}" />
                    </p>
                    <p>
                        Muscle tone
                        <input type="text" class="input-line" name="muscle_tone" style="width:180px;"
                            value="{{ $data['muscle_tone'] ?? '' }}" />
                        Muscle strength
                        <input type="text" class="input-line" name="muscle_strength" style="width:180px;"
                            value="{{ $data['muscle_strength'] ?? '' }}" />
                    </p>
                    <p>
                        Sensation
                        <input type="text" class="input-line" name="sensation" style="width:120px;"
                            value="{{ $data['sensation'] ?? '' }}" /> ➝ ASIA
                        <input type="text" class="input-line" name="asia" style="width:300px;"
                            value="{{ $data['asia'] ?? '' }}" />
                    </p>
                </div>

                <!-- Canvas Section for Image -->
                <div class="flex-row" style="margin: 20px 0;">
                    <div style="flex: 1;">
                        <!-- Other fields or canvases can be added here -->
                    </div>
                    <div>
                        @if(isset($data['image_path']))
                            <p>Current Image:</p>
                            <img src="{{ asset($data['image_path']) }}" alt="Marked Image" style="max-width:300px;">
                        @endif
                        <p>Update Image (if needed):</p>
                        <canvas id="body-canvas" width="300" height="300" style="border: 1px solid #000;"></canvas>
                        <!-- Hidden values to be populated by canvas interaction -->
                        <input type="hidden" id="ticked-points" name="ticked_points">
                        <input type="hidden" id="canvas-image" name="canvas_image">
                    </div>
                </div>
                <table>
                    <tr>
                        <th rowspan="5">Berg Balance Scale</th>
                        <td><label><input type="checkbox" name="sit_to_standing" value="1" {{ isset($data['sit_to_standing']) && $data['sit_to_standing'] == 'on' ? 'checked' : '' }} /> Sit to standing</label></td>
                        <td><label><input type="checkbox" name="eyes_closed"  {{ isset($data['eyes_closed']) && $data['eyes_closed'] == 'on' ? 'checked' : '' }} /> Stand with eyes closed score</label></td>
                        <td><label><input type="checkbox" name="turning_360"  {{ isset($data['turning_360']) && $data['turning_360'] == 'on' ? 'checked' : '' }} /> Turning 360 degree</label></td>
                    </tr>
                    <tr>
                        <td><label><input type="checkbox" name="standing_unsupported" {{ isset($data['standing_unsupported']) && $data['standing_unsupported'] == 'on' ? 'checked' : '' }} /> Standing unsupported</label></td>
                        <td><label><input type="checkbox" name="feet_together"  {{ isset($data['feet_together']) && $data['feet_together'] == 'on' ? 'checked' : '' }} /> Standing with feet together score</label></td>
                        <td><label><input type="checkbox" name="foot_on_stool"  {{ isset($data['foot_on_stool']) && $data['foot_on_stool'] == 'on' ? 'checked' : '' }} /> Placing alternate foot on stool</label></td>
                    </tr>
                    <tr>
                        <td><label><input type="checkbox" name="sitting_unsupported"  {{ isset($data['sitting_unsupported']) && $data['sitting_unsupported'] == 'on' ? 'checked' : '' }} /> Sitting unsupported</label></td>
                        <td><label><input type="checkbox" name="reaching_forward"  {{ isset($data['reaching_forward']) && $data['reaching_forward'] == 'on' ? 'checked' : '' }} /> Reaching forward with outstretched arm</label></td>
                        <td><label><input type="checkbox" name="foot_in_front"  {{ isset($data['foot_in_front']) && $data['foot_in_front'] == 'on' ? 'checked' : '' }} /> Standing with foot in front</label></td>
                    </tr>
                    <tr>
                        <td><label><input type="checkbox" name="standing_to_sitting"  {{ isset($data['standing_to_sitting']) && $data['standing_to_sitting'] == 'on' ? 'checked' : '' }} /> Standing to sitting</label></td>
                        <td><label><input type="checkbox" name="retrieve_object"  {{ isset($data['retrieve_object']) && $data['retrieve_object'] == 'on' ? 'checked' : '' }} /> Retrieving object from floor</label></td>
                        <td><label><input type="checkbox" name="one_foot"  {{ isset($data['one_foot']) && $data['one_foot'] == 'on' ? 'checked' : '' }} /> Standing on one foot</label></td>
                    </tr>
                    <tr>
                        <td><label><input type="checkbox" name="transfer"  {{ isset($data['transfer']) && $data['transfer'] == 'on' ? 'checked' : '' }} /> Transfer</label></td>
                        <td><label><input type="checkbox" name="look_behind"  {{ isset($data['look_behind']) && $data['look_behind'] == 'on' ? 'checked' : '' }} /> Turning to look behind</label></td>
                        <td class="totl">
                            <input type="number" class="total-input" name="berg_total_score" placeholder="Total" value="{{ isset($data['berg_total_score']) ? $data['berg_total_score'] : '' }}" />
                        </td>
                    </tr>
                </table>




                <table class="gmfm-table">
                    <tr>
                        <th colspan="7">GMFM (for Pediatric)</th>
                    </tr>
                    <tr>
                        <th>Dimension</th>
                        <td>A. Lying & Rolling</td>
                        <td>B. Sitting</td>
                        <td>C. Crawling & Kneeling</td>
                        <td>D. Standing</td>
                        <td>E. Walking, Running and Jumping</td>
                        <td class="totl">Total Score</td>
                    </tr>
                    <tr>
                        <td>Score</td>
                        <td><input type="number" name="lying_rolling" value="{{ $data['lying_rolling'] ?? '' }}" /></td>
                        <td><input type="number" name="sitting" value="{{ $data['sitting'] ?? '' }}" /></td>
                        <td><input type="number" name="crawling_kneeling" value="{{ $data['crawling_kneeling'] ?? '' }}" /></td>
                        <td><input type="number" name="standing_score" value="{{ $data['standing_score'] ?? '' }}" /></td>
                        <td><input type="number" name="walking_running_jumping" value="{{ $data['walking_running_jumping'] ?? '' }}" /></td>
                        <td class="totl"><input type="number" name="gmfm_total_score" value="{{ $data['gmfm_total_score'] ?? '' }}" /></td>
                    </tr>
                </table>


                <p>
                    Gait analysis
                    <input type="text" class="input-line" name="gait_analysis" style="width:600px;"
                        value="{{ $data['gait_analysis'] ?? '' }}" />
                </p>
                <p>
                    Others
                    <input type="text" class="input-line" name="other_notes" style="width:600px;"
                        value="{{ $data['other_notes'] ?? '' }}" />
                </p>


                <p>ADL Feeding
                    <span class="checkbox-group">
                        <input type="checkbox" name="adl_feeding[]" value="dependent" {{ in_array('dependent', $adl_feeding ?? []) ? 'checked' : '' }}> Dependent
                    </span>
                    <span class="checkbox-group">
                        <input type="checkbox" name="adl_feeding[]" value="independent" {{ in_array('independent', $adl_feeding ?? []) ? 'checked' : '' }}> Independent
                    </span>

                    Bathing/Toileting
                    <span class="checkbox-group">
                        <input type="checkbox" name="adl_bathing_toileting[]" value="dependent" {{ in_array('dependent', $data->adl_bathing_toileting ?? []) ? 'checked' : '' }}> Dependent
                    </span>
                    <span class="checkbox-group">
                        <input type="checkbox" name="adl_bathing_toileting[]" value="independent" {{ in_array('independent', $data->adl_bathing_toileting ?? []) ? 'checked' : '' }}> Independent
                    </span>
                </p>

                <p>Dressing
                    <span class="checkbox-group">
                        <input type="checkbox" name="adl_dressing[]" value="dependent" {{ in_array('dependent', $data->adl_dressing ?? []) ? 'checked' : '' }}> Dependent
                    </span>
                    <span class="checkbox-group">
                        <input type="checkbox" name="adl_dressing[]" value="independent" {{ in_array('independent', $data->adl_dressing ?? []) ? 'checked' : '' }}> Independent
                    </span>

                    Sleeping
                    <span class="checkbox-group">
                        <input type="checkbox" name="adl_sleeping[]" value="dependent" {{ in_array('dependent', $data->adl_sleeping ?? []) ? 'checked' : '' }}> Dependent
                    </span>
                    <span class="checkbox-group">
                        <input type="checkbox" name="adl_sleeping[]" value="independent" {{ in_array('independent', $data->adl_sleeping ?? []) ? 'checked' : '' }}> Independent
                    </span>

                    Carrying
                    <span class="checkbox-group">
                        <input type="checkbox" name="adl_carrying[]" {{ in_array('dependent', $data->adl_carrying ?? []) ? 'checked' : '' }}> Dependent
                    </span>
                    <span class="checkbox-group">
                        <input type="checkbox" name="adl_carrying[]"  {{ in_array('independent', $data->adl_carrying ?? []) ? 'checked' : '' }}> Independent
                    </span>
                </p>

                <p>Fall risk assessment:
                    <span class="checkbox-group">
                        <input type="checkbox" name="fall_risk[]" value="low" {{ in_array('low', $data->fall_risk ?? []) ? 'checked' : '' }}> Low risks
                    </span>
                    <span class="checkbox-group">
                        <input type="checkbox" name="fall_risk[]" value="high" {{ in_array('high', $data->fall_risk ?? []) ? 'checked' : '' }}> High risks
                    </span>
                </p>

                <p>Pain assessment: Does patient have pain?
                    <span class="checkbox-group">
                        <input type="checkbox" name="has_pain[]" value="no" {{ in_array('no', $data->has_pain ?? []) ? 'checked' : '' }}> No
                    </span>
                    <span class="checkbox-group">
                        <input type="checkbox" name="has_pain[]" value="yes" {{ in_array('yes', $data->has_pain ?? []) ? 'checked' : '' }}> Yes
                    </span>
                </p>



                <p>
                    Location
                    <input type="text" class="input-line" style="width: 500px;" name="pain_location" value="{{ $data['pain_location'] ?? '' }}" />
                </p>

                <p>Duration
                    <span class="checkbox-group">
                        <input type="checkbox" name="pain_duration[]" value="intermittent" {{ in_array('intermittent', $data->pain_duration ?? []) ? 'checked' : '' }}> Intermittent
                    </span>
                    <span class="checkbox-group">
                        <input type="checkbox" name="pain_duration[]" value="constant" {{ in_array('constant', $data->pain_duration ?? []) ? 'checked' : '' }}> Constant
                    </span>

                    Characteristic of pain:
                    <span class="checkbox-group">
                        <input type="checkbox" name="pain_character[]" value="prick" {{ in_array('prick', $data->pain_character ?? []) ? 'checked' : '' }}> Prick
                    </span>
                    <span class="checkbox-group">
                        <input type="checkbox" name="pain_character[]" value="sharp" {{ in_array('sharp', $data->pain_character ?? []) ? 'checked' : '' }}> Sharp
                    </span>
                    <span class="checkbox-group">
                        <input type="checkbox" name="pain_character[]" value="dull" {{ in_array('dull', $data->pain_character ?? []) ? 'checked' : '' }}> Dull
                    </span>
                    <span class="checkbox-group">
                        <input type="checkbox" name="pain_character[]" value="burning" {{ in_array('burning', $data->pain_character ?? []) ? 'checked' : '' }}> Burning
                    </span>
                    <span class="checkbox-group">
                        <input type="checkbox" name="pain_character[]" value="colic" {{ in_array('colic', $data->pain_character ?? []) ? 'checked' : '' }}> Colic
                    </span>
                    <span class="checkbox-group">
                        <input type="checkbox" name="pain_character[]" value="others" {{ in_array('others', $data->pain_character ?? []) ? 'checked' : '' }}> Others
                    </span>
                </p>

                <p>Frequency:
                    <span class="checkbox-group">
                        <input type="checkbox" name="pain_frequency[]" value="less_than_daily" {{ in_array('less_than_daily', $data->pain_frequency ?? []) ? 'checked' : '' }}> Less than daily
                    </span>
                    <span class="checkbox-group">
                        <input type="checkbox" name="pain_frequency[]" value="daily" {{ in_array('daily', $data->pain_frequency ?? []) ? 'checked' : '' }}> Daily
                    </span>
                    <span class="checkbox-group">
                        <input type="checkbox" name="pain_frequency[]" value="all_the_time" {{ in_array('all_the_time', $data->pain_frequency ?? []) ? 'checked' : '' }}> All the time
                    </span>
                </p>

                <p>
                    Pain re-assessment score
                    <input type="text" class="input-line" style="width: 300px;" name="pain_reassessment_score" value="{{ $data->pain_reassessment_score ?? '' }}" />
                </p>

                <p>Assessment tool:
                    <span class="checkbox-group">
                        <input type="checkbox" name="assessment_tool[]" value="nips" {{ in_array('nips', $data->assessment_tool ?? []) ? 'checked' : '' }}> NIPS
                    </span>
                    <span class="checkbox-group">
                        <input type="checkbox" name="assessment_tool[]" value="flacc" {{ in_array('flacc', $data->assessment_tool ?? []) ? 'checked' : '' }}> FLACC
                    </span>
                    <span class="checkbox-group">
                        <input type="checkbox" name="assessment_tool[]" value="faces" {{ in_array('faces', $data->assessment_tool ?? []) ? 'checked' : '' }}> FACES
                    </span>
                    <span class="checkbox-group">
                        <input type="checkbox" name="assessment_tool[]" value="nrs" {{ in_array('nrs', $data->assessment_tool ?? []) ? 'checked' : '' }}> NRS
                    </span>
                </p>

                <p>
                    PT diagnosis / Impression
                    <input type="text" class="input-line" style="width: 500px;" name="pt_diagnosis" value="{{ $data->pt_diagnosis ?? '' }}" />
                </p>

                <p>
                    Goal of treatment: Long term goal
                    <input type="text" class="input-line input-line-short" style="width: 500px;" name="long_term_goal" value="{{ $data->long_term_goal ?? '' }}" />
                </p>

                <p class="indented">
                    Short term goal
                    <input type="text" class="input-line input-line-short" style="width: 500px;" name="short_term_goal" value="{{ $data->short_term_goal ?? '' }}" />
                </p>

                <!-- Submit Button -->
                <div class="col-lg-12" style="margin-top: 20px;">
                    <button type="submit" class="custom-grey-button">Update Prescription</button>
                </div>
            </div>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>

    <script>
        const canvas = new fabric.Canvas('body-canvas', {
            width: 300,
            height: 300
        });

        fabric.Image.fromURL("{{ asset('images/logo/model.png') }}", function(img) {
            img.scaleToWidth(250);
            img.scaleToHeight(250);
            img.set({
                left: 0,
                top: 0,
                selectable: false,
                evented: false,
                hoverCursor: 'default'
            });

            img.hasBorders = false;
            img.hasControls = false;

            canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
        });

        canvas.on('mouse:down', function(event) {
            const clickedObject = event.target;

            if (clickedObject && clickedObject.type === 'text') {
                canvas.remove(clickedObject);
            } else {
                const pointer = canvas.getPointer(event.e);
                const tick = new fabric.Text('✔', {
                    left: pointer.x - 5,
                    top: pointer.y - 5,
                    fontSize: 25,
                    fill: 'green',
                    selectable: false
                });
                canvas.add(tick);
            }

            updateTickedPoints();
        });

        function updateTickedPoints() {
            const points = canvas.getObjects('text').map(t => ({
                x: t.left,
                y: t.top
            }));
            document.getElementById('ticked-points').value = JSON.stringify(points);
        }

        // 🟡 Capture canvas image and set it before form submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector("form"); // adjust selector if needed
            const imageInput = document.getElementById("canvas-image");

            form.addEventListener('submit', function(e) {
                const imageData = canvas.toDataURL("image/png");
                imageInput.value = imageData;
            });
        });
    </script>


</body>

</html>
