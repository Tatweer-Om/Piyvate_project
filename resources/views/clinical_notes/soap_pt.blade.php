<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Physical Therapy Follow-Up Form</title>
    <link rel="stylesheet" href="{{ asset('css/soap_pt.css') }}">


</head>

<body>
    @if (session('error'))
    <div id="error-alert" class="alert alert-danger">
        {{ session('error') }}
        <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'" aria-label="Close"></button>
    </div>
@endif


    <div class="page">
        <form action="{{ url('add_soap_pt') }}" method="POST">
            @csrf

            <div class="header">
                <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Center Logo" height="50">
                <div>
                    HN: <input type="text" style="width:150px;" class="input-line" name="hn" value="{{ $patient->HN ?? '' }}">
                    PT: <input type="text" class="input-line" name="pt" value="{{ old('pt', $session_data->pt ?? '') }}"><br>
                    Name: <input type="text"  style="width:150px; class="input-line" name="full_name"
                        value="{{ $patient->full_name ?? '' }}">
                    Age: <input type="text" class="input-short input-line" style="width:150px; name="age"
                        value="{{ $patient->age ?? '' }}"> <br>
                    Gender:
                    <label>
                        <input type="radio" name="gender" value="Male"
                            {{ ($patient->gender ?? '') == 'Male' ? 'checked' : '' }}> M
                    </label>
                    <label>
                        <input type="radio" name="gender" value="Female"
                            {{ ($patient->gender ?? '') == 'Female' ? 'checked' : '' }}> F
                    </label>
                    <br>
                    Therapist: <input type="text" class="input-line" name="therapist" value="{{ $doctor ?? '' }}">
                </div>
            </div>

            <input type="hidden" name="session_id" value="{{ $session_data->id ?? '' }}">
            <input type="hidden" value="{{ $session_data->main_session_id ?? '' }}" name="main_session_id">
            <input type="hidden" value="{{ $session_data->main_appointment_id ?? '' }}" name="main_appointment_id">
            <input type="hidden" value="{{ $session_data->patient_id ?? '' }}" name="patient_id">
            <input type="hidden" value="{{ $session_data->doctor_id ?? '' }}" name="doctor_id">

            <div class="section" id="section-template">
                <div class="section-header" style="position: relative;">
                    Physical Therapy Follow up and Re-assessment

                </div>

                <div class="soap-wrapper">
                    <div class="soap-content">
                        <div class="row">
                            Date
                            <input type="date" class="input-line" style="width:120px; border:none; border-bottom:1px solid #000;" name="date"
                                value="{{ old('date', $soap->date ?? '') }}">

                            Time
                            <input type="time" class="input-line" style="width:100px; border:none; border-bottom:1px solid #000;" name="time"
                                value="{{ old('time', $soap->time ?? '') }}">

                            V/S BP <input type="text" class="input-line" style="width:30px;" name="bp"
                                value="{{ old('bp', $soap->bp ?? '') }}">
                            P <input type="text" class="input-line" style="width:40px;" name="pulse"
                                value="{{ old('pulse', $soap->pulse ?? '') }}">
                            O2sat <input type="text" class="input-line" style="width:30px;" name="o2sat"
                                value="{{ old('o2sat', $soap->o2sat ?? '') }}">
                            % T <input type="text" class="input-line" style="width:40px;" name="temp"
                                value="{{ old('temp', $soap->temp ?? '') }}">
                            PS: <input type="text" class="input-line" style="width:30px;" name="ps"
                                value="{{ old('ps', $soap->ps ?? '') }}"> /10
                        </div>

                        <div class="row">S:<br>
                            <textarea rows="2" name="s">{{ old('s', $soap->s ?? '') }}</textarea>
                        </div>
                        <div class="row">O:<br>
                            <textarea rows="2" name="o">{{ old('o', $soap->o ?? '') }}</textarea>
                        </div>
                        <div class="row">A:<br>
                            <textarea rows="2" name="a">{{ old('a', $soap->a ?? '') }}</textarea>
                        </div>
                        <div class="row">P:<br>
                            <textarea rows="2" name="p">{{ old('p', $soap->p ?? '') }}</textarea>
                        </div>

                        <div class="signature-line">
                            #<input type="text" class="input-line" name="number"
                                value="{{ old('number', $soap->number ?? '') }}">
                            PT Signature <input type="text" class="input-line" name="signature"
                                value="{{ old('signature', $soap->signature ?? '') }}">
                        </div>
                    </div>

                    <div class="anatomy-img-wrapper">
                        <canvas class="body-canvas"></canvas>
                        <input type="hidden" class="ticked-points" name="ticked_points" value="{{ $soap->ticked_points ?? '' }}">
                        <input type="hidden" class="canvas-image" name="canvas_image" value="{{ $soap->canvas_image ?? '' }}">
                    </div>
                </div>
            </div>

            <div id="sections-wrapper">
                <!-- Additional dynamic sections can be handled here -->
            </div>

            <div class="col-lg-12">
                <button type="submit" class="custom-grey-button">
                    {{ isset($session_data) ? 'Update' : 'Save' }} Prescription
                </button>
            </div>
        </form>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>

        <!-- 💡 JavaScript for dynamic section handling -->
        <script>
          document.addEventListener('DOMContentLoaded', () => {
    const canvasElement = document.querySelector('.body-canvas');
    if (canvasElement) {
        initializeCanvas(canvasElement);
    }
});

function initializeCanvas(canvasElement) {
    if (canvasElement.fabricCanvas) return;

    const canvas = new fabric.Canvas(canvasElement, {
        width: 300,
        height: 200,
        selection: false
    });

    canvasElement.fabricCanvas = canvas;

    // Load the saved image if it exists
    const savedImage = document.querySelector('.canvas-image').value;
    if (savedImage) {
        fabric.Image.fromURL(savedImage, function(img) {
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
    } else {
        // If no saved image, use the default background image
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
    }

    // Load saved ticked points (if any)
    const savedTicks = JSON.parse(document.querySelector('.ticked-points').value || '[]');
    savedTicks.forEach(tick => {
        const tickMark = new fabric.Text('✔', {
            left: tick.x,
            top: tick.y,
            fontSize: 20,
            fill: 'red',
            selectable: false
        });
        canvas.add(tickMark);
    });

    // Handle the canvas interaction to draw ticks
    canvas.on('mouse:down', function(event) {
        if (event.target && event.target.type === 'text') {
            canvas.remove(event.target); // Remove tick if clicked
        } else {
            const pointer = canvas.getPointer(event.e);
            const tick = new fabric.Text('✔', {
                left: pointer.x,
                top: pointer.y,
                fontSize: 20,
                fill: 'red',
                selectable: false
            });
            canvas.add(tick);
        }

        updateHiddenPoints(canvas, canvasElement);
    });
}

function updateHiddenPoints(canvas, canvasElement) {
    const points = canvas.getObjects('text').map(t => ({
        x: t.left,
        y: t.top
    }));
    const section = canvasElement.closest('.section');
    section.querySelector('.ticked-points').value = JSON.stringify(points);
}

document.querySelector("form").addEventListener('submit', function() {
    const canvas = document.querySelector('.body-canvas');
    if (canvas && canvas.fabricCanvas) {
        const section = canvas.closest('.section');
        section.querySelector('.canvas-image').value = canvas.fabricCanvas.toDataURL();
    }
});

        </script>



    </div>

</body>

</html>



