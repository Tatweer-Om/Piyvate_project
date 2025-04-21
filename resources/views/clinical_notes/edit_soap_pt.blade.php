<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Physical Therapy Follow-Up Form</title>
    <link rel="stylesheet" href="{{ asset('css/soap_pt.css') }}">


</head>
<body>
<div class="page">
    <form action="{{ url('update_soap_pt/'.$note->id) }}" method="POST">
        @csrf
        <div class="header">
            <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Center Logo" height="50">
            <div>
                HN: <input type="text" class="input-line" name="hn" value="{{ $patient->HN ?? '' }}">
                PT: <input type="text" class="input-line" name="pt" value="{{ $data['pt'] ?? '' }}"><br>
                Name: <input type="text" class="input-line" name="full_name" value="{{ $patient->full_name ?? '' }}">
                Age: <input type="text" class="input-short input-line" name="age" value="{{ $patient->age ?? '' }}">
                Gender:
                <label>
                    <input type="checkbox" name="gender_m" value="male" {{ $patient->gender == 'Male' ? 'checked' : '' }}> M
                </label>
                <label>
                    <input type="checkbox" name="gender_f" value="female" {{ $patient->gender == 'Female' ? 'checked' : '' }}> F
                </label><br>
                Therapist: <input type="text" class="input-line" name="therapist" value="{{ $doctor ?? '' }}">
            </div>
        </div>

        <input type="hidden" value="{{ $apt->id ?? '' }}" name="appointment_id">
        <input type="hidden" value="{{ $patient->id ?? '' }}" name="patient_id">
        <input type="hidden" value="{{ $apt->doctor_id ?? '' }}" name="doctor_id">

        <!-- Template Section -->
        <div class="section" id="section-template">
            <div class="section-header" style="position: relative;">
                Physical Therapy Follow up and Re-assessment

                <!-- Add and Remove Buttons -->
                <div style="position: absolute; top: 0; right: 0;">
                    <button type="button" onclick="addSection()" class="btn btn-success btn-sm">+</button>
                    <button type="button" onclick="removeSection(this)" class="btn btn-danger btn-sm">−</button>
                </div>
            </div>

            <div class="soap-wrapper">
                <div class="soap-content">
                    <div class="row">
                        Date <input type="text" class="input-line" name="date[]">
                        Time <input type="text" class="input-line" style="width:40px;" name="time[]">
                        V/S BP <input type="text" class="input-line" style="width:30px;" name="bp[]">
                        P <input type="text" class="input-line" style="width:40px;" name="pulse[]">
                        O2sat <input type="text" class="input-line" style="width:30px;" name="o2sat[]">
                        % T <input type="text" class="input-line" style="width:40px;" name="temp[]">
                        PS: <input type="text" class="input-line" style="width:30px;" name="ps[]"> /10
                    </div>

                    <div class="row">S:<br><textarea rows="2" name="s[]"></textarea></div>
                    <div class="row">O:<br><textarea rows="2" name="o[]"></textarea></div>
                    <div class="row">A:<br><textarea rows="2" name="a[]"></textarea></div>
                    <div class="row">P:<br><textarea rows="2" name="p[]"></textarea></div>

                    <div class="signature-line">
                        #<input type="text" class="input-line" name="number[]">
                        PT Signature <input type="text" class="input-line" name="signature[]">
                    </div>
                </div>
                <div class="anatomy-img-wrapper">

                        <canvas id="body-canvas" ></canvas>
                        <input type="hidden" id="ticked-points" name="ticked_points">
                        <input type="hidden" id="canvas-image" name="canvas_image">

                </div>
            </div>
        </div>

        <!-- Wrapper for dynamic sections -->
        <div id="sections-wrapper">
            @foreach ($data['soap_sections'] ?? [] as $index => $section)
            <div class="section-header" style="position: relative;">
                Physical Therapy Follow up and Re-assessment

                <!-- Add and Remove Buttons -->
                <div style="position: absolute; top: 0; right: 0;">
                    <button type="button" onclick="addSection()" class="btn btn-success btn-sm">+</button>
                    <button type="button" onclick="removeSection(this)" class="btn btn-danger btn-sm">−</button>
                </div>
            </div>
            <div class="soap-wrapper">
                <div class="soap-content">
                    <div class="row">
                        Date <input type="text" class="input-line" name="date[]" value="{{ $section['date'] ?? '' }}">
                        Time <input type="text" class="input-line" name="time[]" value="{{ $section['time'] ?? '' }}">
                        V/S BP <input type="text" class="input-line" name="bp[]" value="{{ $section['bp'] ?? '' }}">
                        P <input type="text" class="input-line" name="pulse[]" value="{{ $section['pulse'] ?? '' }}">
                        O2sat <input type="text" class="input-line" name="o2sat[]" value="{{ $section['o2sat'] ?? '' }}">
                        % T <input type="text" class="input-line" name="temp[]" value="{{ $section['temp'] ?? '' }}">
                        PS: <input type="text" class="input-line" name="ps[]" value="{{ $section['ps'] ?? '' }}">/10
                    </div>
                    <div class="row">S:<br><textarea rows="2" name="s[]">{{ $section['s'] ?? '' }}</textarea></div>
                    <div class="row">O:<br><textarea rows="2" name="o[]">{{ $section['o'] ?? '' }}</textarea></div>
                    <div class="row">A:<br><textarea rows="2" name="a[]">{{ $section['a'] ?? '' }}</textarea></div>
                    <div class="row">P:<br><textarea rows="2" name="p[]">{{ $section['p'] ?? '' }}</textarea></div>

                    <div class="signature-line">
                        #<input type="text" class="input-line" name="number[]" value="{{ $section['number'] ?? '' }}">
                        PT Signature <input type="text" class="input-line" name="signature[]" value="{{ $section['signature'] ?? '' }}">
                    </div>
                </div>
                <div class="anatomy-img-wrapper">

                        <canvas id="body-canvas" ></canvas>
                        <input type="hidden" id="ticked-points" name="ticked_points">
                        <input type="hidden" id="canvas-image" name="canvas_image">

                </div>
            </div>
            <script>
                  const canvas = new fabric.Canvas('body-canvas', {
            width: 300,
            height: 300
        });

        fabric.Image.fromURL("{{ asset($section['image_path']) }}", function(img) {
            img.scaleToWidth(100);
            img.scaleToHeight(200);
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
            @endforeach

        </div>

        <div class="col-lg-12">
            <button type="submit" class="custom-grey-button">
                Save Prescription
            </button>
        </div>
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>


    <!-- 💡 JavaScript for dynamic section handling -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const firstSection = document.getElementById('section-template');
            const wrapper = document.getElementById('sections-wrapper');
            wrapper.appendChild(firstSection);
        });

        function addSection() {
            const wrapper = document.getElementById('sections-wrapper');
            const template = document.getElementById('section-template');
            const clone = template.cloneNode(true);

            // Clear input and textarea values
            clone.querySelectorAll('input, textarea').forEach(input => {
                input.value = '';
            });

            wrapper.appendChild(clone);
        }

        function removeSection(button) {
            const wrapper = document.getElementById('sections-wrapper');
            if (wrapper.children.length > 1) {
                button.closest('.section').remove();
            } else {
                alert("At least one section is required.");
            }
        }




    </script>


</div>
</body>
</html>
