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

            <form method="POST" action="{{ url('add_otatp_ortho') }}">
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
                    BP(mmHg)<input type="text" class="input-line" name="bp">
                    PR(bpm)<input type="text" class="input-line" name="pr">
                    RR(rpm)<input type="text" class="input-line" name="rr">
                    T(°C)<input type="text" class="input-line" name="temperature">
                    O2sat(%)<input type="text" class="input-line" name="oxygen">
                    BW(kg)<input type="text" class="input-line" name="bw">
                </div>

                <div class="row">Chief complaint:<input type="text" class="input-line" style="width: 500px;" name="chief_complaint"></div>

                <div class="row">History of illness:<input type="text" class="input-line" name="history_1" style="width: 800px;"></div>
                <div class="row"><input type="text" class="input-line" name="history_2" style="width: 800px;"></div>

                <div class="row"><input type="text" class="input-line" name="history_3" style="width: 800px;"></div>

                <div class="row"><input type="text" class="input-line" name="history_4" style="width: 800px;"></div>

                <div class="row"><input type="text" class="input-line" name="history_5" style="width: 800px;"></div>

                <div class="row"><input type="text" class="input-line" name="history_6" style="width: 800px;"></div>

                <div class="row">Precaution:<input type="text" class="input-line" style="width: 740px;" name="precaution"></div>
                <div class="row">Operation:<input type="text" class="input-line" style="width: 745px;" name="operation"></div>
                <div class="row">Laboratory/Radiology Results:<input type="text" class="input-line" style="width: 640px;" name="lab_results"></div>
                <div class="row">Observation:<input type="text" class="input-line" style="width: 732px;" name="observation"></div>
                <div class="row">Palpation:<input type="text" class="input-line" style="width: 745px;" name="palpation"></div>

                <div class="form-row">
                    <div class="form-left">
                        <label>
                            AROM:
                            <input type="checkbox" name="arom_normal"> Normal
                            <input type="checkbox" name="arom_limit"> Limit at
                            <input type="text" class="input-line" name="arom_limit_value">
                        </label>

                        <label>
                            PROM:
                            <input type="checkbox" name="prom_normal"> Normal
                            <input type="checkbox" name="prom_limit"> Limit at
                            <input type="text" class="input-line" name="prom_limit_value">
                        </label>

                        <label>
                            Sensory:
                            <input type="checkbox" name="sensory_intact"> Intact
                            <input type="checkbox" name="sensory_impaired"> Impaired
                            <input type="checkbox" name="sensory_loss"> Loss at
                            <input type="text" class="input-line" name="sensory_loss_value">
                        </label>
                    </div>

                    <div class="form-right">
                        <div>
                            <canvas id="body-canvas" width="300" height="300"></canvas>
                            <input type="hidden" id="ticked-points" name="ticked_points">
                            <input type="hidden" id="canvas-image" name="canvas_image">
                        </div>
                    </div>
                </div>
                <div class="row">Others:<input type="text" class="input-line" name="others_1" style="width: 750px;"></div>
                <div class="row"><input type="text" class="input-line" name="others_2" style="width: 790px;"></div>
                <div class="row"><input type="text" class="input-line" name="others_3" style="width: 790px;"></div>
                <div class="row"><input type="text" class="input-line" name="others_4" style="width: 790px;"></di>
                <div class="row">
                    Fall risk assessment Total Score=<input type="text" class="input-short input-line" name="fall_score">
                    <input type="checkbox" name="fall_low">Low risks
                    <input type="checkbox" name="fall_high">High risks
                </div>

                <div class="row">
                    Pain assessment Does patient have pain?
                    <input type="checkbox" name="pain_no">No
                    <input type="checkbox" name="pain_yes">Yes
                    Location<input type="text" class="input-line" name="pain_location">
                </div>

                <div class="row">
                    Duration:
                    <input type="checkbox" name="duration_intermittent">Intermittent
                    <input type="checkbox" name="duration_constant">Constant
                    Characteristic of pain:
                    <input type="checkbox" name="pain_prick">Prick
                    <input type="checkbox" name="pain_sharp">Sharp
                    <input type="checkbox" name="pain_dull">Dull
                    <input type="checkbox" name="pain_burning">Burning
                    <input type="checkbox" name="pain_collic">Collic
                    <input type="checkbox" name="pain_others">Others
                    <input type="text" class="input-line" name="pain_others_value">
                </div>

                <div class="row">
                    Frequency:
                    <input type="checkbox" name="freq_less_daily">Less than daily
                    <input type="checkbox" name="freq_daily">Daily
                    <input type="checkbox" name="freq_all_time">All the time
                    Pain re-assessment score<input type="text" class="input-line" name="pain_score">
                </div>

                <div class="row">
                    Assessment tool:
                    <input type="checkbox" name="tool_nips">NIPS
                    <input type="checkbox" name="tool_flacc">FLACC
                    <input type="checkbox" name="tool_faces">FACES
                    <input type="checkbox" name="tool_nrs">NRS
                    <input type="text" class="input-line" name="tool_other">
                </div>

                <div class="row">PT diagnosis/Impression:<input type="text" class="input-line" style="width: 664px;" name="pt_diagnosis"></div>

                <div class="row">Goal of treatment: Long term goal<input type="text" class="input-line" style="width: 620px;" name="goal_long"></div>
                <div class="row">Short term goal<input type="text" class="input-line" style="width: 716px;" name="goal_short"></div>

                <div class="row">Plan of treatment and procedure<input type="text" name="treatment_1" class="input-line"
                    style="width: 630px;"></div>
                <div class="row"><input type="text" class="input-line" name="treatment_2" style="width: 800px;"></div>
                <div class="row"><input type="text" class="input-line" name="treatment_3" style="width: 800px;"></div>
                <div class="row"><input type="text" class="input-line" name="treatment_4" style="width: 800px;"></div>
                <div class="row"><input type="text" class="input-line" name="treatment_5" style="width: 800px;"></div>

                <div class="row">Instruction:<input type="text" class="input-line" name="instruction"></div>

                <div class="row"><input type="checkbox" name="instruction_given">Patient and/or family was given and understood about instruction and plan of treatment</div>
                <div class="row"><input type="checkbox" name="need_reviewed">Need reviewed</div>

                <div class="signature-line">
                    PT's signature:<input type="text" class="input-line" name="pt_signature"> Date:<input type="text" class="input-line" name="date"> Time:<input type="text" class="input-line" name="time">
                </div>
                <div class="col-lg-12">
                    <button type="submit" class="custom-grey-button">
                        Save Prescription
                    </button>
                </div>
            </form>

        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>

    <script>
        const canvas = new fabric.Canvas('body-canvas', {
            width: 260,
            height: 170
        });

        fabric.Image.fromURL("{{ asset('images/logo/model3.png') }}", function(img) {
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
