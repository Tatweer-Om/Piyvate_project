<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="content-width=device-width, initial-scale=1.0">
    <title>Physical Therapy Assessment and Treatment Plan</title>
    <link rel="stylesheet" href="{{ asset('css/ortho.css') }}">

</head>

<body>
    <div class="page_whole">
        <div class="page">

            <form method="POST" action="{{ url('update_otatp_ortho/' . $note->id) }}">
                @csrf
                <div class="header" style="display: flex; align-items: flex-start; padding: 10px;">
                    <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Center Logo" height="50" style="margin-right: 20px;">
                    <div style="text-align: justify;">
                        <div>
                            HN:<input type="text" class="input-line" value="{{ $patient->HN ?? '' }}" name="hn" />
                            PT:<input type="text" class="input-line" name="pt" />
                        </div>
                        <div>
                            Name:<input type="text" class="input-line" value="{{ $patient->full_name ?? '' }}" name="full_name" />
                            Age:<input type="text" value="{{ $patient->age ?? '' }}" class="input-line" name="age" />
                        </div>
                        <div>
                            Gender:
                            <label>
                                <input type="checkbox" name="gender_m" value="male" {{ $patient->gender == 'Male' ? 'checked' : '' }}> M
                            </label>
                            <label>
                                <input type="checkbox" name="gender_f" value="female" {{ $patient->gender == 'Female' ? 'checked' : '' }}> F
                            </label>
                        </div>
                        <div>
                            Birth Date:<input type="text" class="input-line" value="{{ $patient->dob ?? '' }}" name="dob" />
                            Therapist:<input type="text" class="input-line" value="{{ $doctor ?? '' }}" name="therapist" />
                        </div>
                    </div>
                </div>

                <input type="hidden" value="{{ $apt->id ?? '' }}" name="appointment_id" class="appointment_id">
                <input type="hidden" value="{{ $patient->id ?? '' }}" name="patient_id" class="patient_id">
                <input type="hidden" value="{{ $apt->doctor_id ?? '' }}" name="doctor_id" class="doctor_id">

                <div class="section-header">PHYSICAL THERAPY ASSESSMENT AND TREATMENT PLAN FOR ORTHOPEDIC</div>

                <div class="row">
                    BP (mmHg)
                    <input type="text" class="input-line" name="bp" value="{{ old('bp', $data['bp'] ?? '') }}">

                    PR (bpm)
                    <input type="text" class="input-line" name="pr" value="{{ old('pr', $data['pr'] ?? '') }}">

                    RR (rpm)
                    <input type="text" class="input-line" name="rr" value="{{ old('rr', $data['rr'] ?? '') }}">

                    T (°C)
                    <input type="text" class="input-line" name="temperature" value="{{ old('temperature', $data['temperature'] ?? '') }}">

                    O2sat (%)
                    <input type="text" class="input-line" name="oxygen" value="{{ old('oxygen', $data['oxygen'] ?? '') }}">

                    BW (kg)
                    <input type="text" class="input-line" name="bw" value="{{ old('bw', $data['bw'] ?? '') }}">
                </div>


                <div class="row">
                    Chief complaint:
                    <input type="text" class="input-line" style="width: 500px;" name="chief_complaint" value="{{ old('chief_complaint', $data['chief_complaint'] ?? '') }}">
                </div>

                <div class="row">
                    History of illness:
                    <input type="text" class="input-line" name="history_1" style="width: 800px;"
                           value="{{ old('history_1', $data['history_1'] ?? '') }}">
                </div>

                <div class="row">
                    <input type="text" class="input-line" name="history_2" style="width: 800px;"
                           value="{{ old('history_2', $data['history_2'] ?? '') }}">
                </div>

                <div class="row">
                    <input type="text" class="input-line" name="history_3" style="width: 800px;"
                           value="{{ old('history_3', $data['history_3'] ?? '') }}">
                </div>

                <div class="row">
                    <input type="text" class="input-line" name="history_4" style="width: 800px;"
                           value="{{ old('history_4', $data['history_4'] ?? '') }}">
                </div>

                <div class="row">
                    <input type="text" class="input-line" name="history_5" style="width: 800px;"
                           value="{{ old('history_5', $data['history_5'] ?? '') }}">
                </div>

                <div class="row">
                    <input type="text" class="input-line" name="history_6" style="width: 800px;"
                           value="{{ old('history_6', $data['history_6'] ?? '') }}">
                </div>


                <div class="row">
                    Precaution:
                    <input type="text" class="input-line" style="width: 740px;" name="precaution" value="{{ old('precaution', $data['precaution'] ?? '') }}">
                </div>

                <div class="row">
                    Operation:
                    <input type="text" class="input-line" style="width: 745px;" name="operation" value="{{ old('operation', $data['operation'] ?? '') }}">
                </div>

                <div class="row">
                    Laboratory/Radiology Results:
                    <input type="text" class="input-line" style="width: 640px;" name="lab_results" value="{{ old('lab_results', $data['lab_results'] ?? '') }}">
                </div>

                <div class="row">
                    Observation:
                    <input type="text" class="input-line" style="width: 732px;" name="observation" value="{{ old('observation', $data['observation'] ?? '') }}">
                </div>

                <div class="row">
                    Palpation:
                    <input type="text" class="input-line" style="width: 745px;" name="palpation" value="{{ old('palpation', $data['palpation'] ?? '') }}">
                </div>

                <div class="form-row">
                    <div class="form-left">
                        <label>
                            AROM:
                            <input type="checkbox" name="arom_normal" {{ old('arom_normal', $data['arom_normal'] ?? false) ? 'checked' : '' }}> Normal
                            <input type="checkbox" name="arom_limit" {{ old('arom_limit', $data['arom_limit'] ?? false) ? 'checked' : '' }}> Limit at
                            <input type="text" class="input-line" name="arom_limit_value" value="{{ old('arom_limit_value', $data['arom_limit_value'] ?? '') }}">
                        </label>

                        <label>
                            PROM:
                            <input type="checkbox" name="prom_normal" {{ old('prom_normal', $data['prom_normal'] ?? false) ? 'checked' : '' }}> Normal
                            <input type="checkbox" name="prom_limit" {{ old('prom_limit', $data['prom_limit'] ?? false) ? 'checked' : '' }}> Limit at
                            <input type="text" class="input-line" name="prom_limit_value" value="{{ old('prom_limit_value', $data['prom_limit_value'] ?? '') }}">
                        </label>

                        <label>
                            Sensory:
                            <input type="checkbox" name="sensory_intact" {{ old('sensory_intact', $data['sensory_intact'] ?? false) ? 'checked' : '' }}> Intact
                            <input type="checkbox" name="sensory_impaired" {{ old('sensory_impaired', $data['sensory_impaired'] ?? false) ? 'checked' : '' }}> Impaired
                            <input type="checkbox" name="sensory_loss" {{ old('sensory_loss', $data['sensory_loss'] ?? false) ? 'checked' : '' }}> Loss at
                            <input type="text" class="input-line" name="sensory_loss_value" value="{{ old('sensory_loss_value', $data['sensory_loss_value'] ?? '') }}">
                        </label>
                    </div>

                    <div class="form-right">
                        <div>
                            <canvas id="body-canvas" width="300" height="300"></canvas>
                            <input type="hidden" id="ticked-points" name="ticked_points" value="{{ old('ticked_points', $data['ticked_points'] ?? '') }}">
                            <input type="hidden" id="canvas-image" name="canvas_image" value="{{ old('canvas_image', $data['canvas_image'] ?? '') }}">
                        </div>
                    </div>
                </div>


                <div class="row">
                    Others:
                    <input type="text" class="input-line" name="others_1" style="width: 750px;"
                           value="{{ old('others_1', $data['others_1'] ?? '') }}">
                </div>

                <div class="row">
                    <input type="text" class="input-line" name="others_2" style="width: 790px;"
                           value="{{ old('others_2', $data['others_2'] ?? '') }}">
                </div>

                <div class="row">
                    <input type="text" class="input-line" name="others_3" style="width: 790px;"
                           value="{{ old('others_3', $data['others_3'] ?? '') }}">
                </div>

                <div class="row">
                    <input type="text" class="input-line" name="others_4" style="width: 790px;"
                           value="{{ old('others_4', $data['others_4'] ?? '') }}">
                </div>

                <div class="row">
                    Fall risk assessment Total Score=
                    <input type="text" class="input-short input-line" name="fall_score" value="{{ old('fall_score', $data['fall_score'] ?? '') }}">
                    <input type="checkbox" name="fall_low" {{ old('fall_low', $data['fall_low'] ?? false) ? 'checked' : '' }}> Low risks
                    <input type="checkbox" name="fall_high" {{ old('fall_high', $data['fall_high'] ?? false) ? 'checked' : '' }}> High risks
                </div>


                <div class="row">
                    Pain assessment Does patient have pain?
                    <input type="checkbox" name="pain_no" {{ old('pain_no', $data['pain_no'] ?? false) ? 'checked' : '' }}> No
                    <input type="checkbox" name="pain_yes" {{ old('pain_yes', $data['pain_yes'] ?? false) ? 'checked' : '' }}> Yes
                    Location <input type="text" class="input-line" name="pain_location" value="{{ old('pain_location', $data['pain_location'] ?? '') }}">
                </div>

                <div class="row">
                    Duration:
                    <input type="checkbox" name="duration_intermittent" {{ old('duration_intermittent', $data['duration_intermittent'] ?? false) ? 'checked' : '' }}> Intermittent
                    <input type="checkbox" name="duration_constant" {{ old('duration_constant', $data['duration_constant'] ?? false) ? 'checked' : '' }}> Constant
                    Characteristic of pain:
                    <input type="checkbox" name="pain_prick" {{ old('pain_prick', $data['pain_prick'] ?? false) ? 'checked' : '' }}> Prick
                    <input type="checkbox" name="pain_sharp" {{ old('pain_sharp', $data['pain_sharp'] ?? false) ? 'checked' : '' }}> Sharp
                    <input type="checkbox" name="pain_dull" {{ old('pain_dull', $data['pain_dull'] ?? false) ? 'checked' : '' }}> Dull
                    <input type="checkbox" name="pain_burning" {{ old('pain_burning', $data['pain_burning'] ?? false) ? 'checked' : '' }}> Burning
                    <input type="checkbox" name="pain_collic" {{ old('pain_collic', $data['pain_collic'] ?? false) ? 'checked' : '' }}> Collic
                    <input type="checkbox" name="pain_others" {{ old('pain_others', $data['pain_others'] ?? false) ? 'checked' : '' }}> Others
                    <input type="text" class="input-line" name="pain_others_value" value="{{ old('pain_others_value', $data['pain_others_value'] ?? '') }}">
                </div>

                <div class="row">
                    Frequency:
                    <input type="checkbox" name="freq_less_daily" {{ old('freq_less_daily', $data['freq_less_daily'] ?? false) ? 'checked' : '' }}> Less than daily
                    <input type="checkbox" name="freq_daily" {{ old('freq_daily', $data['freq_daily'] ?? false) ? 'checked' : '' }}> Daily
                    <input type="checkbox" name="freq_all_time" {{ old('freq_all_time', $data['freq_all_time'] ?? false) ? 'checked' : '' }}> All the time
                    Pain re-assessment score <input type="text" class="input-line" name="pain_score" value="{{ old('pain_score', $data['pain_score'] ?? '') }}">
                </div>

                <div class="row">
                    Assessment tool:
                    <input type="checkbox" name="tool_nips" {{ old('tool_nips', $data['tool_nips'] ?? false) ? 'checked' : '' }}> NIPS
                    <input type="checkbox" name="tool_flacc" {{ old('tool_flacc', $data['tool_flacc'] ?? false) ? 'checked' : '' }}> FLACC
                    <input type="checkbox" name="tool_faces" {{ old('tool_faces', $data['tool_faces'] ?? false) ? 'checked' : '' }}> FACES
                    <input type="checkbox" name="tool_nrs" {{ old('tool_nrs', $data['tool_nrs'] ?? false) ? 'checked' : '' }}> NRS
                    <input type="text" class="input-line" name="tool_other" value="{{ old('tool_other', $data['tool_other'] ?? '') }}">
                </div>


                <div class="row">
                    PT diagnosis/Impression: <input type="text" class="input-line" style="width: 664px;" name="pt_diagnosis" value="{{ old('pt_diagnosis', $data['pt_diagnosis'] ?? '') }}">
                </div>

                <div class="row">
                    Goal of treatment: Long term goal <input type="text" class="input-line" style="width: 620px;" name="goal_long" value="{{ old('goal_long', $data['goal_long'] ?? '') }}">
                </div>

                <div class="row">
                    Short term goal <input type="text" class="input-line" style="width: 716px;" name="goal_short" value="{{ old('goal_short', $data['goal_short'] ?? '') }}">
                </div>

                <div class="row">
                    Plan of treatment and procedure
                    <input type="text" name="treatment_1" class="input-line" style="width: 630px;"
                           value="{{ old('treatment_1', $data['treatment_1'] ?? '') }}">
                </div>

                <div class="row">
                    <input type="text" class="input-line" name="treatment_2" style="width: 800px;"
                           value="{{ old('treatment_2', $data['treatment_2'] ?? '') }}">
                </div>

                <div class="row">
                    <input type="text" class="input-line" name="treatment_3" style="width: 800px;"
                           value="{{ old('treatment_3', $data['treatment_3'] ?? '') }}">
                </div>

                <div class="row">
                    <input type="text" class="input-line" name="treatment_4" style="width: 800px;"
                           value="{{ old('treatment_4', $data['treatment_4'] ?? '') }}">
                </div>

                <div class="row">
                    <input type="text" class="input-line" name="treatment_5" style="width: 800px;"
                           value="{{ old('treatment_5', $data['treatment_5'] ?? '') }}">
                </div>


                <div class="row">
                    Instruction: <input type="text" class="input-line" name="instruction" value="{{ old('instruction', $data['instruction'] ?? '') }}">
                </div>

                <div class="row">
                    <input type="checkbox" name="instruction_given" {{ old('instruction_given', $data['instruction_given'] ?? false) ? 'checked' : '' }}> Patient and/or family was given and understood about instruction and plan of treatment
                </div>
                <div class="row">
                    <input type="checkbox" name="need_reviewed" {{ old('need_reviewed', $data['need_reviewed'] ?? false) ? 'checked' : '' }}> Need reviewed
                </div>

                <div class="signature-line">
                    PT's signature: <input type="text" class="input-line" name="pt_signature" value="{{ old('pt_signature', $data['pt_signature'] ?? '') }}">
                    Date: <input type="text" class="input-line" name="date" value="{{ old('date', $data['date'] ?? '') }}">
                    Time: <input type="text" class="input-line" name="time" value="{{ old('time', $data['time'] ?? '') }}">
                </div>

                <div class="col-lg-12">
                    <button type="submit" class="custom-grey-button">
                        Update Prescription
                    </button>
                </div>
            </form>

        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>

    <script>
        const canvas = new fabric.Canvas('body-canvas', {
            width: 260,
            height: 190
        });

        fabric.Image.fromURL("{{ asset($data['image_path']) }}", function(img) {
            img.scaleToWidth(200);
            img.scaleToHeight(180);
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
