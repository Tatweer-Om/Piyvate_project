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
    <form action="{{ url('add_soap_ot') }}" method="POST">
        @csrf
        <div class="header">
            <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Center Logo" height="50">
            <div>
                HN:<input type="text" class="input-line" name="hn" value="{{ $patient->HN ?? '' }}">
                PT:<input type="text" class="input-line" name="pt"><br>
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
        <input type="hidden" value="{{ $session->id ?? '' }}" name="session_id">
        <input type="hidden" value="{{ $patient->id ?? '' }}" name="patient_id">
        <input type="hidden" value="{{ $apt->doctor_id ?? '' }}" name="doctor_id">

        <!-- Wrapper for all sections -->
        <div id="sections-wrapper">
            @if(!empty($clinicalNotes->soap_sections))
                @foreach ($clinicalNotes->soap_sections as $index => $section)
                    <div class="section" id="section-template">
                        <div class="section-header">
                            Occupational Therapy Follow up and Re-assessment
                            <button type="button" onclick="addSection()">+</button>
                            <button type="button" onclick="removeSection(this)">−</button>
                        </div>

                        <div class="row">
                            Date <input type="text" name="date[]" value="{{ $section->date ?? '' }}">
                            Time <input type="text" name="time[]" value="{{ $section->time ?? '' }}">
                            V/S BP <input type="text" name="bp[]" value="{{ $section->bp ?? '' }}">
                            P <input type="text" name="pulse[]" value="{{ $section->pulse ?? '' }}">
                            O2sat <input type="text" name="o2sat[]" value="{{ $section->o2sat ?? '' }}">
                            % T <input type="text" name="temp[]" value="{{ $section->temp ?? '' }}">
                            PS: <input type="text" name="ps[]" value="{{ $section->ps ?? '' }}">/10
                        </div>

                        <div class="row">S:<br><textarea rows="2" name="s[]">{{ $section->s ?? '' }}</textarea></div>
                        <div class="row">O:<br><textarea rows="2" name="o[]">{{ $section->o ?? '' }}</textarea></div>
                        <div class="row">A:<br><textarea rows="2" name="a[]">{{ $section->a ?? '' }}</textarea></div>
                        <div class="row">P:<br><textarea rows="2" name="p[]">{{ $section->p ?? '' }}</textarea></div>

                        <div class="signature-line">
                            #<input type="text" name="number[]" value="{{ $section->number ?? '' }}">
                            OT Signature <input type="text" name="signature[]" value="{{ $section->signature ?? '' }}">
                        </div>
                    </div>
                @endforeach
            @else
                {{-- Empty state: render one blank section --}}
                <div class="section" id="section-template">
                    <div class="section-header">
                        Occupational Therapy Follow up and Re-assessment
                        <button type="button" onclick="addSection()">+</button>
                        <button type="button" onclick="removeSection(this)">−</button>
                    </div>

                    <div class="row">
                        Date <input style="border: none;" type="date" name="date[]">
                        Time <input style="border: none;" type="time" name="time[]">
                        V/S BP <input type="text" name="bp[]">
                        P <input type="text" name="pulse[]">
                        O2sat <input type="text" name="o2sat[]">
                        % T <input type="text" name="temp[]">
                        PS: <input type="text" name="ps[]">/10
                    </div>

                    <div class="row">S:<br><textarea rows="2" name="s[]"></textarea></div>
                    <div class="row">O:<br><textarea rows="2" name="o[]"></textarea></div>
                    <div class="row">A:<br><textarea rows="2" name="a[]"></textarea></div>
                    <div class="row">P:<br><textarea rows="2" name="p[]"></textarea></div>

                    <div class="signature-line">
                        #<input type="text" name="number[]">
                        OT Signature <input type="text" name="signature[]">
                    </div>
                </div>
            @endif
        </div>




        <div class="col-lg-12 mt-3">
            <button type="submit" class="custom-grey-button">
                Save Prescription
            </button>
        </div>
    </form>

    <!-- JS for dynamic sections -->
    <script>
        // Function to add a new section
        function addSection() {
            const wrapper = document.getElementById('sections-wrapper');
            const firstSection = document.getElementById('section-template');
            const clone = firstSection.cloneNode(true);

            // Clear inputs inside the cloned section
            clone.querySelectorAll('input, textarea').forEach(input => {
                input.value = '';
            });

            // Append the cloned section
            wrapper.appendChild(clone);
        }

        // Function to remove a section
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
