<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Occupational Therapy Follow-Up Form</title>
    <link rel="stylesheet" href="{{ asset('css/soap_ot.css') }}">

</head>
<body>
<div class="page">
    <form action="{{ url('update_soap_ot/'.$note->id) }}" method="POST">
        @csrf
        <div class="header">
            <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Center Logo" height="50">
            <div>
                HN:<input type="text" class="input-line" name="hn" value="{{ $patient->HN ?? '' }}">
                PT:<input type="text" class="input-line" name="pt" value="{{ $data['pt'] ?? '' }}"><br>
                Name:<input type="text" class="input-line" name="full_name" value="{{ $patient->full_name ?? '' }}">
                Age:<input type="text" class="input-short input-line" name="age" value="{{ $patient->age ?? '' }}">
                Gender:
                <label>
                    <input type="checkbox" name="gender_m" value="male" {{ $patient->gender == 'Male' ? 'checked' : '' }}> M
                </label>
                <label>
                    <input type="checkbox" name="gender_f" value="female" {{ $patient->gender == 'Female' ? 'checked' : '' }}> F
                </label><br>
                Therapist:<input type="text" class="input-line" name="therapist" value="{{ $doctor ?? '' }}">
            </div>
        </div>

        <input type="hidden" value="{{ $apt->id ?? '' }}" name="appointment_id">
        <input type="hidden" value="{{ $patient->id ?? '' }}" name="patient_id">
        <input type="hidden" value="{{ $apt->doctor_id ?? '' }}" name="doctor_id">

     <!-- Add Buttons in the Corner of the First Section -->
        <div class="section" id="section-template">
            <div class="section-header" style="position: relative;">
                Occupational Therapy Follow up and Re-assessment

                <!-- Add and Remove Buttons -->
                <div style="position: absolute; top: 0; right: 0;">
                    <button type="button" onclick="addSection()" class="btn btn-success btn-sm">+</button>
                    <button type="button" onclick="removeSection(this)" class="btn btn-danger btn-sm">−</button>
                </div>
            </div>

            <div class="row">
                Date <input type="text" class="input-line" name="date[]">
                Time <input type="text" class="input-line" name="time[]">
                V/S BP <input type="text" class="input-line" name="bp[]">
                P <input type="text" class="input-line" name="pulse[]">
                O2sat <input type="text" class="input-line" name="o2sat[]">
                % T <input type="text" class="input-line" name="temp[]">
                PS: <input type="text" class="input-line" name="ps[]">/10
            </div>

            <div class="row">S:<br><textarea rows="2" name="s[]"></textarea></div>
            <div class="row">O:<br><textarea rows="2" name="o[]"></textarea></div>
            <div class="row">A:<br><textarea rows="2" name="a[]"></textarea></div>
            <div class="row">P:<br><textarea rows="2" name="p[]"></textarea></div>

            <div class="signature-line">
                #<input type="text" class="input-line" name="number[]">
                OT Signature <input type="text" class="input-line" name="signature[]">
            </div>
        </div>

        <div id="sections-wrapper">
            @foreach ($data['soap_sections'] ?? [] as $index => $section)
            <div class="section">
                <div class="section-header" style="position: relative;">
                    Occupational Therapy Follow up and Re-assessment

                    <!-- Add and Remove Buttons -->
                    <div style="position: absolute; top: 0; right: 0;">
                        <button type="button" onclick="addSection()" class="btn btn-success btn-sm">+</button>
                        <button type="button" onclick="removeSection(this)" class="btn btn-danger btn-sm">−</button>
                    </div>
                </div>

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
                    OT Signature <input type="text" class="input-line" name="signature[]" value="{{ $section['signature'] ?? '' }}">
                </div>
            </div>
            @endforeach
        </div>



        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const firstSection = document.getElementById('section-template');
                const wrapper = document.getElementById('sections-wrapper');
                wrapper.appendChild(firstSection);
            });

            function addSection() {
                const wrapper = document.getElementById('sections-wrapper');
                const firstSection = document.getElementById('section-template');
                const clone = firstSection.cloneNode(true);

                // Clear input values
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

<div class="col-lg-12">
    <button type="submit" class="custom-grey-button">
        update Prescription
    </button>
</div>

    </form>

</div>
</body>
</html>
