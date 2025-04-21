<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Physical Therapy Assessment and Treatment Plan</title>
    <link rel="stylesheet" href="{{ asset('css/neuro.css') }}">

</head>

<body>
    <div class="page_whole">

        <form action="{{ url('add_neuro_pedriatic') }}" method="POST">
            @csrf
            <div class="page">

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


                <div class="section-header">
                    Physical Therapy Assessment and Treatment Plan for <u>Neuro</u> and Pediatric
                </div>

                <p>
                    BP (<input type="text" class="input-line" name="bp" />)
                    PR (bpm) <input type="text" class="input-line" name="pr" />
                    RR (rpm) <input type="text" class="input-line" name="rr" />
                    T (°C) <input type="text" class="input-line" name="temperature" />
                    O<sub>2</sub>sat (%) <input type="text" class="input-line" name="o2sat" />
                    BW (Kg) <input type="text" class="input-line" name="body_weight" />
                </p>

                <p>Chief complaint <input type="text" class="input-line" name="chief_complaint"
                        style="width: 600px;" /></p>
                <p>History of illness <input type="text" class="input-line" name="history_of_illness"
                        style="width: 600px;" /></p>
                <p>
                    Underlying <input type="text" class="input-line" name="underlying" style="width: 150px;" />
                    Precaution <input type="text" class="input-line" name="precaution" style="width: 300px;" />
                </p>
                <p>Operation <input type="text" class="input-line" name="operation" style="width: 300px;" /></p>
                <p>Laboratory Radiology result <input type="text" class="input-line" name="lab_radiology_result"
                        style="width: 600px;" /></p>


                <div class="flex-row">
                    <div style="flex: 1;">
                        <p>Level of consciousness:
                            <span class="checkbox-group"><input type="checkbox" name="level_of_consciousness[]"
                                    value="alert"> Alert</span>
                            <span class="checkbox-group"><input type="checkbox" name="level_of_consciousness[]"
                                    value="drowsy"> Drowsy</span>
                            <span class="checkbox-group"><input type="checkbox" name="level_of_consciousness[]"
                                    value="stupor"> Stupor</span>
                            <span class="checkbox-group"><input type="checkbox" name="level_of_consciousness[]"
                                    value="semi_coma"> Semi coma</span>
                        </p>
                        <p>
                            <span class="checkbox-group"><input type="checkbox" name="level_of_consciousness[]"
                                    value="coma"> Coma</span>
                            Interpretered:
                            <span class="checkbox-group"><input type="checkbox" name="interpreted" value="no">
                                No</span>
                            <span class="checkbox-group"><input type="checkbox" name="interpreted" value="yes">
                                Yes</span>
                        </p>
                        <p>Observation <input type="text" class="input-line" name="observation"
                                style="width: 500px;" /></p>
                        <p>
                            Muscle tone <input type="text" class="input-line" name="muscle_tone"
                                style="width: 180px;" />
                            Muscle strength <input type="text" class="input-line" name="muscle_strength"
                                style="width: 180px;" />
                        </p>
                        <p>
                            Sensation <input type="text" class="input-line" name="sensation"
                                style="width: 120px;" /> ➝ ASIA
                            <input type="text" class="input-line" name="asia" style="width: 300px;" />
                        </p>
                        <p>
                            Bed mobility
                            <span class="checkbox-group"><input type="checkbox" name="bed_mobility[]"
                                    value="independent"> Independent</span>
                            <span class="checkbox-group">
                                <input type="checkbox" name="bed_mobility[]" value="dependent"> Dependent with
                                <input type="text" class="input-line" name="bed_mobility_assist"
                                    style="width: 100px;" />
                            </span>
                        </p>
                        <p>
                            Transfer
                            <span class="checkbox-group"><input type="checkbox" name="transfer[]"
                                    value="independent"> Independent</span>
                            <span class="checkbox-group">
                                <input type="checkbox" name="transfer[]" value="dependent"> Dependent with
                                <input type="text" class="input-line" name="transfer_assist"
                                    style="width: 100px;" />
                            </span>
                        </p>
                    </div>
                    <div>
                        <canvas id="body-canvas" width="300" height="300" style="border: none;"></canvas>
                        <input type="hidden" id="ticked-points" name="ticked_points">
                        <input type="hidden" id="canvas-image" name="canvas_image">
                    </div><br>
                </div>


                <div class="stream-section">


                    <table class="stream-table">
                        <tr>
                            <th>STREAM Score</th>
                            <th>Upper extremity</th>
                            <th>Lower extremity</th>
                            <th>Basic Mobility</th>
                            <th class="totl">Total</th>
                        </tr>
                        <tr>
                            <td>Score</td>
                            <td><input type="number" name="score_upper" /></td>
                            <td><input type="number" name="score_lower" /></td>
                            <td><input type="number" name="score_mobility" /></td>
                            <td class="totl"><input type="number" name="score_total" /></td>
                        </tr>
                        <tr>
                            <td>%</td>
                            <td><input type="number" name="percent_upper" step="0.01" /></td>
                            <td><input type="number" name="percent_lower" step="0.01" /></td>
                            <td><input type="number" name="percent_mobility" step="0.01" /></td>
                            <td class="totl"><input type="number" name="percent_total" step="0.01" /></td>
                        </tr>
                    </table>


                    <div class="stream-image-box"
                        style="position: relative; width: 150px; height: 100px; border: 1px solid #000; margin-top:20px;">
                        <!-- Horizontal line (X-axis) -->
                        <div
                            style="position: absolute; top: 50%; left: 0; width: 100%; height: 1px; background-color: #000;">
                        </div>

                        <!-- Vertical line (Y-axis) -->
                        <div
                            style="position: absolute; top: 0; left: 50%; width: 1px; height: 100%; background-color: #000;">
                        </div>

                        <!-- Checkboxes in each quadrant -->
                        <div
                            style="position: absolute; top: 0; left: 0; width: 50%; height: 50%; display: flex; justify-content: center; align-items: center;">
                            <label>
                                <input type="checkbox" name="checkbox_tl" value="1">
                            </label>
                        </div>

                        <div
                            style="position: absolute; top: 0; right: 0; width: 50%; height: 50%; display: flex; justify-content: center; align-items: center;">
                            <label>
                                <input type="checkbox" name="checkbox_tr" value="1">
                            </label>
                        </div>

                        <div
                            style="position: absolute; bottom: 0; left: 0; width: 50%; height: 50%; display: flex; justify-content: center; align-items: center;">
                            <label>
                                <input type="checkbox" name="checkbox_bl" value="1">
                            </label>
                        </div>

                        <div
                            style="position: absolute; bottom: 0; right: 0; width: 50%; height: 50%; display: flex; justify-content: center; align-items: center;">
                            <label>
                                <input type="checkbox" name="checkbox_br" value="1">
                            </label>
                        </div>

                        <!-- Original text at bottom -->
                        <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                            <p>Lt.</p>
                            <p>Rt.</p>
                        </div>
                    </div>

                </div>


                <!-- Berg Balance Scale -->
                <table>
                    <tr>
                        <th rowspan="5">Berg Balance Scale</th>
                        <td><label><input type="checkbox" name="sit_to_standing" /> Sit to standing</label></td>
                        <td><label><input type="checkbox" name="eyes_closed" /> Stand with eyes closed score</label>
                        </td>
                        <td><label><input type="checkbox" name="turning_360" /> Turning 360 degree</label></td>
                    </tr>
                    <tr>
                        <td><label><input type="checkbox" name="standing_unsupported" /> Standing unsupported</label>
                        </td>
                        <td><label><input type="checkbox" name="feet_together" /> Standing with feet together
                                score</label></td>
                        <td><label><input type="checkbox" name="foot_on_stool" /> Placing alternate foot on
                                stool</label></td>
                    </tr>
                    <tr>
                        <td><label><input type="checkbox" name="sitting_unsupported" /> Sitting unsupported</label>
                        </td>
                        <td><label><input type="checkbox" name="reaching_forward" /> Reaching forward with
                                outstretched arm</label></td>
                        <td><label><input type="checkbox" name="foot_in_front" /> Standing with foot in front</label>
                        </td>
                    </tr>
                    <tr>
                        <td><label><input type="checkbox" name="standing_to_sitting" /> Standing to sitting</label>
                        </td>
                        <td><label><input type="checkbox" name="retrieve_object" /> Retrieving object from
                                floor</label></td>
                        <td><label><input type="checkbox" name="one_foot" /> Standing on one foot</label></td>
                    </tr>
                    <tr>
                        <td><label><input type="checkbox" name="transfer" /> Transfer</label></td>
                        <td><label><input type="checkbox" name="look_behind" /> Turning to look behind</label></td>
                        <td class="totl">
                            <input type="number" class="total-input" name="berg_total_score" placeholder="Total" />
                        </td>
                    </tr>
                </table>

                <!-- GMFM -->
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
                        <td><input type="number" name="lying_rolling" /></td>
                        <td><input type="number" name="sitting" /></td>
                        <td><input type="number" name="crawling_kneeling" /></td>
                        <td><input type="number" name="standing_score" /></td>
                        <!-- renamed to avoid clash with BBS -->
                        <td><input type="number" name="walking_running_jumping" /></td>
                        <td class="totl"><input type="number" name="gmfm_total_score" /></td>
                    </tr>
                </table>



                <p>
                    Gait analysis
                    <input type="text" class="input-line" style="width: 600px;" name="gait_analysis" />
                </p>

                <!-- Others -->
                <p>
                    Others
                    <input type="text" class="input-line" style="width: 600px;" name="other_notes" />
                </p>

                <p>ADL Feeding
                    <span class="checkbox-group"><input type="checkbox" name="adl_feeding[]" value="dependent"> Dependent</span>
                    <span class="checkbox-group"><input type="checkbox" name="adl_feeding[]" value="independent"> Independent</span>

                    Bathing/Toileting
                    <span class="checkbox-group"><input type="checkbox" name="adl_bathing_toileting[]" value="dependent"> Dependent</span>
                    <span class="checkbox-group"><input type="checkbox" name="adl_bathing_toileting[]" value="independent"> Independent</span>
                </p>

                <p>Dressing
                    <span class="checkbox-group"><input type="checkbox" name="adl_dressing[]" value="dependent"> Dependent</span>
                    <span class="checkbox-group"><input type="checkbox" name="adl_dressing[]" value="independent"> Independent</span>

                    Sleeping
                    <span class="checkbox-group"><input type="checkbox" name="adl_sleeping[]" value="dependent"> Dependent</span>
                    <span class="checkbox-group"><input type="checkbox" name="adl_sleeping[]" value="independent"> Independent</span>

                    Carrying
                    <span class="checkbox-group"><input type="checkbox" name="adl_carrying[]" value="dependent"> Dependent</span>
                    <span class="checkbox-group"><input type="checkbox" name="adl_carrying[]" value="independent"> Independent</span>
                </p>

                <p>Fall risk assessment:
                    <span class="checkbox-group"><input type="checkbox" name="fall_risk[]" value="low"> Low risks</span>
                    <span class="checkbox-group"><input type="checkbox" name="fall_risk[]" value="high"> High risks</span>
                </p>

                <p>Pain assessment: Does patient have pain?
                    <span class="checkbox-group"><input type="checkbox" name="has_pain[]" value="no"> No</span>
                    <span class="checkbox-group"><input type="checkbox" name="has_pain[]" value="yes"> Yes</span>
                </p>

                <p>
                    Location
                    <input type="text" class="input-line" style="width: 500px;" name="pain_location" />
                </p>

                <p>Duration
                    <span class="checkbox-group"><input type="checkbox" name="pain_duration[]" value="intermittent"> Intermittent</span>
                    <span class="checkbox-group"><input type="checkbox" name="pain_duration[]" value="constant"> Constant</span>

                    Characteristic of pain:
                    <span class="checkbox-group"><input type="checkbox" name="pain_character[]" value="prick"> Prick</span>
                    <span class="checkbox-group"><input type="checkbox" name="pain_character[]" value="sharp"> Sharp</span>
                    <span class="checkbox-group"><input type="checkbox" name="pain_character[]" value="dull"> Dull</span>
                    <span class="checkbox-group"><input type="checkbox" name="pain_character[]" value="burning"> Burning</span>
                    <span class="checkbox-group"><input type="checkbox" name="pain_character[]" value="colic"> Colic</span>
                    <span class="checkbox-group"><input type="checkbox" name="pain_character[]" value="others"> Others</span>
                </p>

                <p>Frequency:
                    <span class="checkbox-group"><input type="checkbox" name="pain_frequency[]" value="less_than_daily"> Less than daily</span>
                    <span class="checkbox-group"><input type="checkbox" name="pain_frequency[]" value="daily"> Daily</span>
                    <span class="checkbox-group"><input type="checkbox" name="pain_frequency[]" value="all_the_time"> All the time</span>
                </p>

                <p>
                    Pain re-assessment score
                    <input type="text" class="input-line" style="width: 300px;" name="pain_reassessment_score" />
                </p>

                <p>Assessment tool:
                    <span class="checkbox-group"><input type="checkbox" name="assessment_tool[]" value="nips"> NIPS</span>
                    <span class="checkbox-group"><input type="checkbox" name="assessment_tool[]" value="flacc"> FLACC</span>
                    <span class="checkbox-group"><input type="checkbox" name="assessment_tool[]" value="faces"> FACES</span>
                    <span class="checkbox-group"><input type="checkbox" name="assessment_tool[]" value="nrs"> NRS</span>
                </p>

                <p>
                    PT diagnosis / Impression
                    <input type="text" class="input-line" style="width: 500px;" name="pt_diagnosis" />
                </p>

                <p>
                    Goal of treatment: Long term goal
                    <input type="text" class="input-line input-line-short" style="width: 500px;" name="long_term_goal" />
                </p>

                <p class="indented">
                    Short term goal
                    <input type="text" class="input-line input-line-short" style="width: 500px;" name="short_term_goal" />
                </p>

            </div>
            <div class="full-width-button-wrapper">
                <button type="submit" class="custom-grey-button">
                    Save Prescription
                </button>
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
