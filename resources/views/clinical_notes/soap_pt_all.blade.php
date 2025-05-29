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
        <div class="header">
            <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Center Logo" height="50">
            <div>
                HN:
                <input type="text" style="width:150px;" class="input-line" name="hn"
                    value="{{ $patient->HN ?? '' }}" readonly>

                PT:
                <input type="text" class="input-line" name="pt" value="{{ $pt ?? '' }}"><br> {{-- Editable --}}

                Name:
                <input type="text" style="width:150px;" class="input-line" name="full_name"
                    value="{{ $patient->full_name ?? '' }}" readonly>

                Age:
                <input type="text" class="input-short input-line" style="width:150px;" name="age"
                    value="{{ $patient->age ?? '' }}" readonly> <br>

                Gender:
                <label>
                    <input type="radio" name="gender" value="Male"
                        {{ ($patient->gender ?? '') == 'Male' ? 'checked' : '' }} onclick="return false;"> M
                </label>
                <label>
                    <input type="radio" name="gender" value="Female"
                        {{ ($patient->gender ?? '') == 'Female' ? 'checked' : '' }} onclick="return false;"> F
                </label>
                <br>

                Therapist:
                <input type="text" class="input-line" name="therapist" value="{{ $doctor ?? '' }}" readonly>
            </div>

        </div>

        <div class="section" id="section-template">
            <div class="section-header" style="position: relative;">
                Physical Therapy Follow up and Re-assessment
            </div>

            @foreach ($session_data as $index => $data)
                <div class="soap-wrapper">
                    <div class="soap-content">
                        <div class="row">
                            Date
                            <input type="date" class="input-line"
                                style="width:120px; border:none; border-bottom:1px solid #000;" name="date"
                                value="{{ $data->date }}">

                            Time
                            <input type="time" class="input-line"
                                style="width:100px; border:none; border-bottom:1px solid #000;" name="time"
                                value="{{ $data->time }}">

                            V/S BP <input type="text" class="input-line" style="width:30px;" name="bp"
                                value="{{ $data->bp }}">
                            P <input type="text" class="input-line" style="width:40px;" name="pulse"
                                value="{{ $data->pulse }}">
                            O2sat <input type="text" class="input-line" style="width:30px;" name="o2sat"
                                value="{{ $data->o2sat }}">
                            % T <input type="text" class="input-line" style="width:40px;" name="temp"
                                value="{{ $data->temp }}">
                            PS: <input type="text" class="input-line" style="width:30px;" name="ps"
                                value="{{ $data->ps }}"> /10
                        </div>

                        <div class="row">S:<br>
                            <textarea rows="2" name="s">{{ $data->s }}</textarea>
                        </div>
                        <div class="row">O:<br>
                            <textarea rows="2" name="o">{{ $data->o }}</textarea>
                        </div>
                        <div class="row">A:<br>
                            <textarea rows="2" name="a">{{ $data->a }}</textarea>
                        </div>
                        <div class="row">P:<br>
                            <textarea rows="2" name="p">{{ $data->p }}</textarea>
                        </div>

                        <div class="signature-line">
                            #<input type="text" class="input-line" name="number" value="{{ $data->number }}">
                            PT Signature <input type="text" class="input-line" name="signature"
                                value="{{ $data->signature }}">
                        </div>
                    </div>




                    <canvas class="body-canvas" style="margin-top: 50px;" id="canvas-{{ $index }}"  height="200"></canvas>
                    <input type="hidden" class="canvas-image" id="canvas-image-{{ $index }}" value="{{ asset($data->soap_image) }}">
                    <input type="hidden" class="ticked-points" id="ticked-points-{{ $index }}" value="{{ $data->ticked_points }}">

                </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.body-canvas').forEach((canvasElement, index) => {
                initializeCanvas(canvasElement, index);
            });

            document.querySelector("form")?.addEventListener('submit', function () {
                document.querySelectorAll('.body-canvas').forEach((canvasElement, index) => {
                    if (canvasElement.fabricCanvas) {
                        const canvasImageInput = document.getElementById(`canvas-image-${index}`);
                        canvasImageInput.value = canvasElement.fabricCanvas.toDataURL();
                    }
                });
            });
        });

        function initializeCanvas(canvasElement, index) {
            const canvas = new fabric.Canvas(canvasElement, {
                selection: false
            });
            canvasElement.fabricCanvas = canvas;

            const canvasImageInput = document.getElementById(`canvas-image-${index}`);
            const imageUrl = canvasImageInput?.value || "{{ asset('images/logo/model3.png') }}";

            fabric.Image.fromURL(imageUrl, function (img) {
                img.scaleToWidth(canvas.getWidth());
                img.scaleToHeight(canvas.getHeight());
                img.set({ selectable: false, evented: false });
                canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
            });

            // Load existing ticks
            const tickedPointsInput = document.getElementById(`ticked-points-${index}`);
            const savedTicks = JSON.parse(tickedPointsInput?.value || '[]');
            savedTicks.forEach(tick => {
                canvas.add(new fabric.Text('✔', {
                    left: tick.x,
                    top: tick.y,
                    fontSize: 20,
                    fill: 'red',
                    selectable: false
                }));
            });

            canvas.on('mouse:down', function (event) {
                if (event.target && event.target.type === 'text') {
                    canvas.remove(event.target);
                } else {
                    const pointer = canvas.getPointer(event.e);
                    canvas.add(new fabric.Text('✔', {
                        left: pointer.x,
                        top: pointer.y,
                        fontSize: 20,
                        fill: 'red',
                        selectable: false
                    }));
                }

                updateTickedPoints(canvas, tickedPointsInput);
            });
        }

        function updateTickedPoints(canvas, inputElement) {
            const points = canvas.getObjects('text').map(obj => ({
                x: obj.left,
                y: obj.top
            }));
            inputElement.value = JSON.stringify(points);
        }
        </script>


</body>


</html>
