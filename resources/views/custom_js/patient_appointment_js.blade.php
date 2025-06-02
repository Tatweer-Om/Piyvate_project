<script>
    $(document).ready(function() {
        var appointmentId = {{ $appointment->id }};

        fetchSingleAppointmentDetail(appointmentId);

    function fetchSingleAppointmentDetail(appointmentId) {
    $.ajax({
        url: '/patient_appointment_detail/' + appointmentId, // Adjust endpoint as needed
        method: 'GET',
        success: function(appointment) {
            var tableBody = $('#singleappointmentsdetailtable tbody');
            tableBody.empty();

            if (appointment) {
                tableBody.append(`
                    <tr>
                        <td>
                            <span>${appointment.appointment_no}</span><br>
                            <small class="text-muted">${appointment.appointment_date}</small>
                        </td>

                        <td>
                            <div class="d-flex justify-content-center gap-3 mt-2">
                                <div class="text-center d-flex flex-column align-items-center">
                                    <span class="badge bg-success rounded-circle d-flex flex-column justify-content-center align-items-center" style="width: 25px; height: 25px;">
                                        <span style="font-size: 7px;">PT</span>
                                        <small>${appointment.pt_sessions}</small>
                                    </span>
                                </div>
                                <div class="text-center d-flex flex-column align-items-center">
                                    <span class="badge bg-warning text-dark rounded-circle d-flex flex-column justify-content-center align-items-center" style="width: 25px; height: 25px;">
                                        <span style="font-size: 7px;">OT</span>
                                        <small>${appointment.ot_sessions}</small>
                                    </span>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span>Taken: ${appointment.session_taken}</span><br>
                            <span>Remain: ${appointment.session_remain}</span>
                        </td>

                        <td>
                            ${(appointment.test_recommendations && appointment.test_recommendations.length > 0)
                                ? appointment.test_recommendations.join(', ')
                                : 'No test'}
                        </td>

                        <td>
                            ${(appointment.files && appointment.files.length > 0)
                                ? appointment.files.map(file => `
                                    <div class="d-flex align-items-start gap-2 mb-2 flex-column align-items-start">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="/images/dummy_images/file.png" alt="File Icon" width="20">
                                            <a href="/download/${file.file_id}" class="btn btn-sm btn-link text-decoration-none p-0" title="${file.file_name}">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                `).join('')
                                : '<span class="text-muted">No file</span>'
                            }
                        </td>

                        <td>
                            <!-- Prescription Notes -->
                            <button
                                class="btn btn-sm btn-outline-info p-1 rounded-circle me-2"
                                data-bs-toggle="modal"
                                data-bs-target="#notesModal"
                                data-notes="${appointment.prescription_notes}"
                                title="View Prescription Notes">
                                <i class="fas fa-sticky-note" style="font-size: 14px;"></i>
                            </button>

                            <!-- Appointment Notes -->
                            <button
                                class="btn btn-sm btn-outline-warning p-1 rounded-circle"
                                data-bs-toggle="modal"
                                data-bs-target="#appointmentNotesModal"
                                data-notes="${appointment.notes}"
                                title="View Appointment Notes">
                                <i class="fas fa-file-alt" style="font-size: 14px;"></i>
                            </button>
                             <a href="/apt_invoice/${appointment.id}"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary p-1 rounded-circle"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Print Invoice">
                                <i class="fas fa-print" style="font-size: 14px;"></i>
                            </a>
                        </td>
                    <td style="font-size: 10px; padding: 4px;">
                    ${
                        appointment.clinical_notes.map(note => `
                        <div class="d-flex align-items-center justify-content-between mb-1" style="gap: 4px;">
                            <div class="d-flex flex-column align-items-center text-center" style="width: 40px;">
                                <img src="${note.icon}" alt="icon" width="24" height="24" class="rounded shadow-sm mb-1" style="object-fit: cover;">
                                <div class="text-muted" style="font-size: 8px; line-height: 1;">${note.form_type}</div>
                            </div>
                            <div class="flex-grow-1 text-end">
                                <a href="${note.view_url}" target="_blank" class="me-1 text-primary" title="View" style="font-size: 10px;">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="${note.edit_url}" class="text-warning" title="Edit" style="font-size: 10px;">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                        `).join('')
                    }
                </td>

                    </tr>
                `);
            } else {
                tableBody.append('<tr><td colspan="10" class="text-center">Appointment not found.</td></tr>');
            }
        },
        error: function(xhr) {
            $('#singleappointmentsdetailtable tbody').html('<tr><td colspan="10" class="text-danger text-center">Error fetching data.</td></tr>');
        }
    });
}

    });
    $(document).ready(function() {
        // Function to toggle session and test inputs with smooth animations
        function toggleSessionInputs() {
            const selected = $('input[name="prescription_type"]:checked').val();
            if (selected === 'session') {
                $('#sessionInputs').slideDown();
                $('#testInputs').slideUp();
            } else if (selected === 'test') {
                $('#testInputs').slideDown();
                $('#sessionInputs').slideUp();
            } else {
                $('#sessionInputs').slideUp();
                $('#testInputs').slideUp();
            }
        }

        // Initial toggle on load
        toggleSessionInputs();

        // Change event for prescription type
        $('input[name="prescription_type"]').change(function() {
            toggleSessionInputs();
        });

        // Add new test input dynamically
        $('#testList').on('click', '.addTestInput', function() {
            const newTestInput = `
            <div class="row mb-2">
                <div class="col-lg-4">
                    <input type="text" class="form-control" name="test_recommendation[]" placeholder="Enter test name">
                </div>
                <div class="col-lg-2">
                    <button type="button" class="btn btn-outline-danger removeTestInput">×</button>
                </div>
            </div>
        `;
            $('#testList').append(newTestInput);
        });

        // Remove test input field
        $('#testList').on('click', '.removeTestInput', function() {
            $(this).closest('.row').remove();
        });

        $('#prescriptionForm').submit(function(e) {
            e.preventDefault();

            // Collect form data
            const formData = $('#prescriptionForm').serialize(); // ✅ serialize, not serializeArray

            $.ajax({
                url: "{{ route('save_prescription') }}", // Your route
                method: "POST",
                data: formData, // ✅ send the correct serialized data
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token
                },
                success: function(response) {
                    // Check if session gap is missing
                    if (response.status == 2) {
                        show_notification('error',
                        'Session Gap is required!'); // Clearer message
                    }
                    // Success case (when response.success is true)
                    else if (response.success) {
                        show_notification('success', response.message);
                        $('#rightPopup').offcanvas('hide');
                        $('#prescriptionForm')[0].reset();
                        location.reload();

                    }
                    // Error case for other failures
                    else {
                        show_notification('error', 'Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    show_notification('error', errorMessage);
                }
            });
        });

    });





    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('file_upload');
        const filePreview = document.getElementById('file-preview');
        const fileTrigger = document.getElementById('filePreview');

        let selectedFiles = [];

        // Trigger file input when clicking the preview area
        fileTrigger.addEventListener('click', () => fileInput.click());

        // When files are selected, add them to the selectedFiles array and render previews
        fileInput.addEventListener('change', (event) => {
            const newFiles = Array.from(event.target.files);

            newFiles.forEach(file => {
                selectedFiles.push(file);
            });

            renderPreviews();
        });

        // Render previews for the selected files
        function renderPreviews() {
            filePreview.innerHTML = ''; // Clear previous previews

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                const fileName = file.name.toLowerCase();

                const item = document.createElement('div');
                item.className =
                    'file-preview-item col-auto position-relative'; // Ensure positioning for remove button

                const img = document.createElement('img');
                img.className = 'file-preview-img';
                img.style.maxWidth = '50px'; // Adjust image size

                // Create a remove button for each file preview
                const removeBtn = document.createElement('button');
                removeBtn.className = 'remove-btn btn btn-danger btn-sm position-absolute top-0 end-0';
                removeBtn.innerHTML = '&times;';
                removeBtn.onclick = () => {
                    selectedFiles.splice(index, 1); // Remove file from selectedFiles array
                    renderPreviews(); // Re-render previews
                };

                // Handle image files
                if (file.type.startsWith('image/')) {
                    reader.onload = (e) => {
                        img.src = e.target.result;
                        item.appendChild(removeBtn);
                        item.appendChild(img);
                        filePreview.appendChild(item);
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Handle non-image files (e.g., PDF, DOC, XLS)
                    if (fileName.endsWith('.pdf')) {
                        img.src = "{{ asset('images/dummy_images/pdf.png') }}";
                    } else if (fileName.endsWith('.doc') || fileName.endsWith('.docx')) {
                        img.src = "{{ asset('images/dummy_images/word.jpeg') }}";
                    } else if (fileName.endsWith('.xls') || fileName.endsWith('.xlsx')) {
                        img.src = "{{ asset('images/dummy_images/excel.jpeg') }}";
                    } else {
                        img.src = "{{ asset('images/dummy_images/file.png') }}";
                    }

                    item.appendChild(removeBtn);
                    item.appendChild(img);
                    filePreview.appendChild(item);
                }

                // Add the file name below the preview
                const fileLabel = document.createElement('div');
                fileLabel.className = 'small text-truncate mt-1';
                fileLabel.style.maxWidth = '100px';
                fileLabel.title = file.name; // Tooltip if the name is too long
                fileLabel.textContent = file.name;
                item.appendChild(fileLabel); // Add file name to preview
            });
        }

        // Optional: Prepare FormData and submit manually (when form is submitted)
        document.getElementById('labReportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData();

            // Add selected files to the FormData object
            selectedFiles.forEach(file => {
                formData.append('lab_reports[]', file);
            });

            // Add additional form data (like patient_id, if needed)
            formData.append('patient_id', "{{ $patient->id ?? '' }}");
            formData.append('appoint_id', "{{ $apt_id ?? '' }}");


            // Send the files via AJAX
            fetch("{{ route('lab_reports_upload') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        show_notification('success', data.message);
                        selectedFiles = []; // Clear selected files
                        filePreview.innerHTML = ''; // Clear preview area
                        document.getElementById('labReportForm').reset(); // Reset form

                        // Hide the offcanvas using Bootstrap's built-in mechanism
                        $('#leftPopup').offcanvas('hide');
                        location.reload();

                    } else {
                        show_notification('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Upload failed:', error);
                    show_notification('error', 'Something went wrong');
                });
        });
    });


    $(document).ready(function() {
        $('.session-checkbox').change(function() {
            if ($('#checkbox_ot').is(':checked')) {
                $('#ot_sessions_box').show();
            } else {
                $('#ot_sessions_box').hide();
                $('#ot_sessions').val('');
            }

            if ($('#checkbox_pt').is(':checked')) {
                $('#pt_sessions_box').show();
            } else {
                $('#pt_sessions_box').hide();
                $('#pt_sessions').val('');
            }
        });
    });


    $(document).ready(function() {
      var appointmentId = {{ $appointment->id }};

      $('#allappointmentsessiontable').DataTable({
            "processing": true,
            "serverSide": false,
            "ajax": {
                "url": "{{ url('show_all_sessions_under_appointment') }}",
                "type": "GET",
                "data": function(d) {
                    d.main_id = appointmentId;

                },
                "error": function(xhr, error, thrown) {
                    console.log("AJAX Error:", xhr.responseText);
                }
            },
            "columns": [{
                    "title": "S.No"
                },
                {
                    "title": "Date"
                },
                {
                    "title": "Doctor"
                },
                {
                    "title": "Time"
                },
                {
                    "title": "Type"
                },
                {
                    "title": "Status"
                },



            ],
            "pagingType": "numbers",
            "ordering": true,
            "order": [
                [1, "desc"]
            ] // Default order by Session Date
        });

    });



      document.addEventListener('DOMContentLoaded', function () {
        // Prescription Notes Modal
        const notesModal = document.getElementById('notesModal');
        notesModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const notes = button.getAttribute('data-notes') || 'No notes available.';
            document.getElementById('notesContent').textContent = notes;
        });

        // Appointment Notes Modal
        const appointmentNotesModal = document.getElementById('appointmentNotesModal');
        appointmentNotesModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const notes = button.getAttribute('data-notes') || 'No notes available.';
            document.getElementById('appointmentNotesContent').textContent = notes;
        });
    });
</script>
