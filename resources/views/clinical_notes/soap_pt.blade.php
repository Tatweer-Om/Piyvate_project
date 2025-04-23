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
        <form action="{{ url('add_soap_pt') }}" method="POST">
            @csrf
            <div class="header">
                <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Center Logo" height="50">
                <div>
                    HN: <input type="text" class="input-line" name="hn" value="{{ $patient->HN ?? '' }}">
                    PT: <input type="text" class="input-line" name="pt" value="{{ old('pt', '') }}"><br>
                    Name: <input type="text" class="input-line" name="full_name"
                        value="{{ $patient->full_name ?? '' }}">
                    Age: <input type="text" class="input-short input-line" name="age"
                        value="{{ $patient->age ?? '' }}">
                    Gender:
                    <label>
                        <input type="checkbox" name="gender_m" value="male"
                            {{ $patient->gender == 'Male' ? 'checked' : '' }}> M
                    </label>
                    <label>
                        <input type="checkbox" name="gender_f" value="female"
                            {{ $patient->gender == 'Female' ? 'checked' : '' }}> F
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
                            Date <input type="text" class="input-line" name="date[]"
                                value="{{ old('date.0', optional($clinicalNotes->soap_sections[0] ?? null)->date) }}">
                            Time <input type="text" class="input-line" style="width:40px;" name="time[]"
                                value="{{ old('time.0', optional($clinicalNotes->soap_sections[0] ?? null)->time) }}">
                            V/S BP <input type="text" class="input-line" style="width:30px;" name="bp[]"
                                value="{{ old('bp.0', optional($clinicalNotes->soap_sections[0] ?? null)->bp) }}">
                            P <input type="text" class="input-line" style="width:40px;" name="pulse[]"
                                value="{{ old('pulse.0', optional($clinicalNotes->soap_sections[0] ?? null)->pulse) }}">
                            O2sat <input type="text" class="input-line" style="width:30px;" name="o2sat[]"
                                value="{{ old('o2sat.0', optional($clinicalNotes->soap_sections[0] ?? null)->o2sat) }}">
                            % T <input type="text" class="input-line" style="width:40px;" name="temp[]"
                                value="{{ old('temp.0', optional($clinicalNotes->soap_sections[0] ?? null)->temp) }}">
                            PS: <input type="text" class="input-line" style="width:30px;" name="ps[]"
                                value="{{ old('ps.0', optional($clinicalNotes->soap_sections[0] ?? null)->ps) }}"> /10
                        </div>

                        <div class="row">S:<br>
                            <textarea rows="2" name="s[]">{{ old('s.0', optional($clinicalNotes->soap_sections[0] ?? null)->s) }}</textarea>
                        </div>
                        <div class="row">O:<br>
                            <textarea rows="2" name="o[]">{{ old('o.0', optional($clinicalNotes->soap_sections[0] ?? null)->o) }}</textarea>
                        </div>
                        <div class="row">A:<br>
                            <textarea rows="2" name="a[]">{{ old('a.0', optional($clinicalNotes->soap_sections[0] ?? null)->a) }}</textarea>
                        </div>
                        <div class="row">P:<br>
                            <textarea rows="2" name="p[]">{{ old('p.0', optional($clinicalNotes->soap_sections[0] ?? null)->p) }}</textarea>
                        </div>

                        <div class="signature-line">
                            #<input type="text" class="input-line" name="number[]"
                                value="{{ old('number.0', optional($clinicalNotes->soap_sections[0] ?? null)->number) }}">
                            PT Signature <input type="text" class="input-line" name="signature[]"
                                value="{{ old('signature.0', optional($clinicalNotes->soap_sections[0] ?? null)->signature) }}">
                        </div>
                    </div>
                    <div class="anatomy-img-wrapper">
                        <canvas class="body-canvas"></canvas>
                        <input type="hidden" class="ticked-points" name="ticked_points[]">
                        <input type="hidden" class="canvas-image" name="canvas_image[]">
                    </div>
                </div>
            </div>

            <!-- Wrapper for dynamic sections -->
            <div id="sections-wrapper">
                <!-- JS will move the initial section here -->
                @foreach ($clinicalNotes->soap_sections ?? [] as $index => $section)
                    <div class="section">
                        <div class="soap-wrapper">
                            <div class="soap-content">
                                <div class="row">
                                    Date <input type="text" class="input-line" name="date[]"
                                        value="{{ old('date.' . $index, $section->date) }}">
                                    Time <input type="text" class="input-line" style="width:40px;" name="time[]"
                                        value="{{ old('time.' . $index, $section->time) }}">
                                    V/S BP <input type="text" class="input-line" style="width:30px;"
                                        name="bp[]" value="{{ old('bp.' . $index, $section->bp) }}">
                                    P <input type="text" class="input-line" style="width:40px;" name="pulse[]"
                                        value="{{ old('pulse.' . $index, $section->pulse) }}">
                                    O2sat <input type="text" class="input-line" style="width:30px;"
                                        name="o2sat[]" value="{{ old('o2sat.' . $index, $section->o2sat) }}">
                                    % T <input type="text" class="input-line" style="width:40px;" name="temp[]"
                                        value="{{ old('temp.' . $index, $section->temp) }}">
                                    PS: <input type="text" class="input-line" style="width:30px;" name="ps[]"
                                        value="{{ old('ps.' . $index, $section->ps) }}"> /10
                                </div>

                                <div class="row">S:<br>
                                    <textarea rows="2" name="s[]">{{ old('s.' . $index, $section->s) }}</textarea>
                                </div>
                                <div class="row">O:<br>
                                    <textarea rows="2" name="o[]">{{ old('o.' . $index, $section->o) }}</textarea>
                                </div>
                                <div class="row">A:<br>
                                    <textarea rows="2" name="a[]">{{ old('a.' . $index, $section->a) }}</textarea>
                                </div>
                                <div class="row">P:<br>
                                    <textarea rows="2" name="p[]">{{ old('p.' . $index, $section->p) }}</textarea>
                                </div>

                                <div class="signature-line">
                                    #<input type="text" class="input-line" name="number[]"
                                        value="{{ old('number.' . $index, $section->number) }}">
                                    PT Signature <input type="text" class="input-line" name="signature[]"
                                        value="{{ old('signature.' . $index, $section->signature) }}">
                                </div>
                            </div>
                            <div class="anatomy-img-wrapper">
                                <canvas class="body-canvas"></canvas>
                                <input type="hidden" class="ticked-points" name="ticked_points[]">
                                <input type="hidden" class="canvas-image" name="canvas_image[]">
                            </div>
                        </div>
                    </div>
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
                // Move template to wrapper
                const firstSection = document.getElementById('section-template');
                const wrapper = document.getElementById('sections-wrapper');
                wrapper.appendChild(firstSection);

                // Initialize all canvases on page load
                document.querySelectorAll('.body-canvas').forEach(canvas => {
                    initializeCanvas(canvas);
                });
            });

            function addSection() {
                const wrapper = document.getElementById('sections-wrapper');
                const template = document.getElementById('section-template');
                const clone = template.cloneNode(true);

                // Clear input values in the new section
                clone.querySelectorAll('input[type="text"], input[type="hidden"], textarea').forEach(input => {
                    input.value = '';
                });

                // Remove IDs from the cloned elements to prevent duplicates
                clone.querySelectorAll('[id]').forEach(element => {
                    element.removeAttribute('id');
                });

                // Append the cloned section to the DOM
                wrapper.appendChild(clone);

                // Initialize the new canvas for the appended section
                const newCanvas = clone.querySelector('.body-canvas');
                initializeCanvas(newCanvas); // Ensure the canvas is initialized after the section is appended
            }




            function removeSection(button) {
                const wrapper = document.getElementById('sections-wrapper');
                if (wrapper.children.length > 1) {
                    button.closest('.section').remove();
                } else {
                    alert("At least one section is required.");
                }
            }

            function initializeCanvas(canvasElement) {
                // Check if the canvas has already been initialized
                if (canvasElement.fabricCanvas) {
                    return; // Exit if already initialized
                }

                // Initialize the Fabric.js canvas
                const canvas = new fabric.Canvas(canvasElement, {
                    width: 300,
                    height: 200,
                    selection: false
                });

                // Store the reference of the Fabric canvas on the element
                canvasElement.fabricCanvas = canvas;

                // Load background image for the canvas
                fabric.Image.fromURL("{{ asset('images/logo/model3.png') }}", function(img) {
                    img.scaleToWidth(100);
                    img.scaleToHeight(200);
                    img.set({
                        left: 0,
                        top: 0,
                        selectable: false,
                        evented: false
                    });
                    canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
                    canvas.renderAll();
                });

                // Add a mouse:down event listener to mark on the canvas
                canvas.on('mouse:down', function(event) {
                    const pointer = canvas.getPointer(event.e);

                    // Remove existing tick if clicked on an existing one
                    if (event.target && event.target.type === 'text') {
                        canvas.remove(event.target);
                    } else {
                        // Add a new tick mark
                        const tick = new fabric.Text('✔', {
                            left: pointer.x,
                            top: pointer.y,
                            fontSize: 20,
                            fill: 'red',
                            selectable: false
                        });
                        canvas.add(tick);
                    }

                    // Update the hidden input for the new points
                    const section = canvasElement.closest('.section');
                    const points = canvas.getObjects('text').map(t => ({
                        x: t.left,
                        y: t.top
                    }));
                    section.querySelector('.ticked-points').value = JSON.stringify(points);
                });
            }

            // Handle form submission
            document.querySelector("form").addEventListener('submit', function(e) {
                // Update all canvas images before submit
                document.querySelectorAll('.body-canvas').forEach((canvas, index) => {
                    const section = canvas.closest('.section');
                    section.querySelector('.canvas-image').value = canvas.toDataURL();
                });
            });
        </script>
    </div>

</body>

</html>
