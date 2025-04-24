<script>
    $(document).ready(function() {
        var patientId = {{ $patient->id }};
        fetchAppointments(patientId);
        fetchAppointmentsAndSessions(patientId);

        $('a[href="#sessionsBody"]').click(function() {
            fetchSessions(patientId);
        });


        // Fetch payments on tab click
        $('#visits-tab').click(function() {
            fetchPayments(patientId);
        });

        function fetchAppointments(patientId) {
            $.ajax({
                url: '/patient/' + patientId + '/appointments',
                method: 'GET',
                success: function(response) {
                    var tableBody = $('#appointmentstable tbody');
                    tableBody.empty();

                    if (response.length > 0) {
                        response.forEach(function(appointment, index) {
                            tableBody.append(`
                        <tr>
                            <td>${appointment.appointment_no}</td>
                            <td>${appointment.appointment_date}</td>
                            <td>${appointment.doctor?.doctor_name ?? ''}</td>
                            <td>${appointment.status_badge}</td>
                            <td>
                                <!-- Action Buttons, for example: -->
                                <button class="btn btn-info btn-sm">View</button>
                                <button class="btn btn-danger btn-sm">Cancel</button>
                            </td>
                        </tr>
                    `);
                        });
                    } else {
                        tableBody.append(
                            '<tr><td colspan="5" class="text-center">No appointments found.</td></tr>'
                        );
                    }
                }
            });
        }

        function fetchAppointmentsAndSessions(patientId) {
            $.ajax({
                url: '/patient/' + patientId +
                    '/appointments-and-sessions', // Make sure the URL matches your route
                method: 'GET',
                success: function(response) {
                    var tableBody = $('#total_appt_session tbody');
                    tableBody.empty();

                    if (response.length > 0) {
                        response.forEach(function(item, index) {
                            let sessionCount = item.session_count;
                            let singleSessionFee = item.single_session_fee;

                            // If the type is 'appointment', add session-related info
                            let sessionInfo = '';

                            if (item.type === 'appointment') {
                                // Show the session count for appointments in a badge
                                sessionInfo = `
                            <span class="badge bg-info">
                                Appointment Sessions
                            </span>
                        `;
                            } else if (item.type === 'session') {
                                // Show "Sessions" for session type with a different color
                                sessionInfo = `
                            <span class="badge bg-success">
                               Direct Sessions
                            </span>
                        `;
                            } else {
                                // Default case, show 'N/A' for other types
                                sessionInfo = 'N/A';
                            }

                            // Append data to the table
                            tableBody.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.appointment_no}</td>
                            <td>${sessionInfo}</td>

                            <td>${item.fee}</td>
                            <td>${sessionCount}</td>
                            <td>${singleSessionFee}</td>
                        </tr>
                    `);
                        });
                    } else {
                        tableBody.append(
                            '<tr><td colspan="6" class="text-center">No appointments or sessions found.</td></tr>'
                        );
                    }
                }
            });
        }

        $('#all_patient_session_table').DataTable({
            "processing": true,
            "serverSide": false, // You're returning pre-built JSON from Laravel, no need for serverSide
            "ajax": {
                "url": "{{ url('show_all_sessions_by_patient') }}",
                "type": "GET",
                "data": function(d) {
                    d.patient_id = patientId;
                },
                "error": function(xhr, error, thrown) {
                    console.log("AJAX Error:", xhr.responseText);
                }
            },
            "columns": [{
                    "title": "S.No"
                },
                {
                    "title": "Session Date"
                },
                {
                    "title": "Doctor"
                },
                {
                    "title": "Session Time"
                },
                // {
                //     "title": "Session Fee"
                // },
                {
                    "title": "Status"
                },
                {
                    "title": "Actions"
                },


            ],
            "pagingType": "numbers",
            "ordering": true,
            "order": [
                [2, "desc"]
            ] // Default order by Session Date
        });



        // $('#payment_table').DataTable({
        //     processing: true,
        //     serverSide: false,
        //     ajax: {
        //         url: '{{ route('show_all_payment_by_patient') }}',
        //         type: 'GET',
        //         data: {
        //             patient_id: patientId,
        //             _token: '{{ csrf_token() }}'
        //         }
        //     },

        // });


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

        // Optional: AJAX submit logic
        $('#prescriptionForm').submit(function(e) {
            e.preventDefault();

            // Collect form data
            const formData = $('#prescriptionForm').serializeArray();

            // Collect the test recommendations (only if they exist)
            const testRecommendations = [];
            $('input[name="test_recommendation[]"]').each(function() {
                if ($(this).val()) {
                    testRecommendations.push($(this).val());
                }
            });

            // Prepare the data for submission
            const dataToSend = {
                prescription_type: $('input[name="prescription_type"]:checked')
            .val(), // Either 'appointment' or 'session'
                test_recommendations: testRecommendations.length > 0 ? testRecommendations :
                null, // Send test recommendations array if not empty
                ...formData.reduce((acc, field) => {
                    acc[field.name] = field.value;
                    return acc;
                }, {})
            };

            $.ajax({
                url: "{{ route('save_prescription') }}", // Your route
                method: "POST",
                data: dataToSend, // Sending the data as an object
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token
                },
                success: function(response) {
                    if (response.success) {
                        show_notification('success', response.message);
                        $('#rightPopup').offcanvas('hide');
                        $('#prescriptionForm')[0].reset();
                    } else {
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


    function toggleAmountInput(accountId, accountStatus) {
        const checkbox = document.getElementById("account_" + accountId);
        const amountInput = document.getElementById("amount_" + accountId);
        const refNoInput = document.getElementById("ref_no_" + accountId);

        // Update amount input visibility and requirement
        if (checkbox.checked) {
            amountInput.style.display = "block";
            amountInput.required = true;
        } else {
            amountInput.style.display = "none";
            amountInput.required = false;
            amountInput.value = "";
        }

        // Update ref_no visibility and requirement if account status is not 1
        if (accountStatus !== 1 && refNoInput) {
            if (checkbox.checked) {
                refNoInput.style.display = "block";
                refNoInput.required = true;
            } else {
                refNoInput.style.display = "none";
                refNoInput.required = false;
                refNoInput.value = "";
            }
        }
    }

    $('.contract_payment').on('click', function() {
        let totalAmountDue = parseFloat($('#total_amount').text());
        let totalEntered = 0;
        let valid = true;

        $('.payment-amount-input:visible').each(function() {
            let val = parseFloat($(this).val());
            if (!isNaN(val)) {
                totalEntered += val;
            }
        });

        // Compare with due amount
        if (totalEntered !== totalAmountDue) {
            show_notification('error', 'Total payment amount must equal OMR ' + totalAmountDue.toFixed(2));
            valid = false;
        }

        if (!valid) return;

        // Ensure all values are serialized correctly
        $('input[type="checkbox"]:not(:checked)').each(function() {
            let accountId = $(this).val();
            // Set the value to 0 for unchecked checkboxes to include them in the submission
            $('input[name="payment_amounts[' + accountId + ']"]').val(0);
            $('input[name="ref_nos[' + accountId + ']"]').val('');
        });

        var formData = $('#paymentForm').serialize();

        $.ajax({
            url: "{{ route('submit_contract_payment') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                show_notification('success', 'Payment data submitted successfully!');
                $('#paymentModal').modal('hide');
            },
            error: function(xhr) {
                show_notification('error', 'Something went wrong. Please try again.');
                console.log(xhr.responseText);
            }
        });
    });

    function edit(id, source) {
        showPreloader();
        before_submit();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            dataType: 'JSON',
            url: "{{ url('edit_ind_session') }}",
            method: "POST",
            data: {
                id: id,
                source: source,
                _token: csrfToken
            },
            success: function(fetch) {
                hidePreloader();

                after_submit();
                if (fetch != "") {

                    $("#patient_name").val(fetch.patient);
                    $("#patient_primary_id").val(fetch.patient_id);
                    $("#inputDate").val(fetch.date);
                    $("#inputTime").val(fetch.time);
                    $("#doctor").val(fetch.doctor);
                    $("#session_primary_id").val(fetch.session_primary_id);
                    $("#source").val(fetch.source);
                    $('#doctor').selectpicker('refresh');


                    $(".modal-title").html('<?php echo trans('messages.update_lang', [], session('locale')); ?>');
                }
            },
            error: function(html) {
                hidePreloader();

                after_submit();
                show_notification('error', '<?php echo trans('messages.edit_failed_lang', [], session('locale')); ?>');

                return false;
            }
        });
    }


    $(document).ready(function() {
        // When the form is submitted
        $('#editSessionForm').on('submit', function(event) {
            event.preventDefault();
            document.activeElement.blur();

            // Get the form data
            var id = $("#session_primary_id").val(); // Get the session ID
            var patient_id = $("#patient_primary_id").val(); // Get the session ID

            var source = $("#source").val(); // Get the source value

            var patient_name = $("#patient_name").val();
            var session_date = $("#inputDate").val();
            var session_time = $("#inputTime").val();
            var doctor = $("#doctor").val();

            // CSRF token for security
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Make the AJAX request to update the session
            $.ajax({
                url: "{{ url('update_ind_session') }}", // Adjust URL as needed
                method: "POST",
                data: {
                    id: id,
                    source: source,
                    patient_id: patient_id,
                    session_date: session_date,
                    session_time: session_time,
                    doctor: doctor,
                    _token: csrfToken
                },
                success: function(response) {

                    $('#editSessionModal').modal('hide');


                    show_notification('success', '<?php echo trans('messages.data_add_success_lang', [], session('locale')); ?>');
                    $('#all_patient_session_table').DataTable().ajax.reload();


                },
                error: function(xhr) {
                    // Handle error, show error message
                    show_notification('error', '<?php echo trans('messages.data_add_failed_lang', [], session('locale')); ?>');
                }
            });
        });
    });


    function transfer(id, source) {
        showPreloader();
        before_submit();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            dataType: 'JSON',
            url: "{{ url('transfer_ind_session') }}",
            method: "POST",
            data: {
                id: id,
                source: source,
                _token: csrfToken
            },
            success: function(fetch) {
                hidePreloader();

                after_submit();
                if (fetch != "") {

                    $("#source_patient").val(fetch.patient);
                    $("#patient_primary_id2").val(fetch.patient_id);
                    $("#ses_date").val(fetch.date);
                    $("#ses_time").val(fetch.time);
                    $("#session_primary_id2").val(fetch.session_primary_id);
                    $("#source2").val(fetch.source);

                }
            },
            error: function(html) {
                hidePreloader();

                after_submit();
                show_notification('error', '<?php echo trans('messages.transfer_failed_lang', [], session('locale')); ?>');

                return false;
            }
        });
    }

    $(document).ready(function() {
        // When the form is submitted
        $('#transferForm').on('submit', function(event) {
            event.preventDefault();
            document.activeElement.blur();

            // Get the form data
            var id = $("#session_primary_id2").val(); // Get the session ID
            var old_patient_id = $("#patient_primary_id2").val(); // Get the session ID

            var source = $("#source2").val(); // Get the source value

            var patient_name = $("#patient_name").val();
            var session_date = $("#ses_date").val();
            var session_time = $("#ses_time").val();
            var target_patient = $("#target_patient").val();

            // CSRF token for security
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Make the AJAX request to update the session
            $.ajax({
                url: "{{ url('update_transfer_ind_session') }}", // Adjust URL as needed
                method: "POST",
                data: {
                    id: id,
                    source: source,
                    old_patient_id: old_patient_id,
                    session_date: session_date,
                    session_time: session_time,
                    target_patient:target_patient,
                    _token: csrfToken
                },
                success: function(response) {

                    $('#transferModal').modal('hide');


                    show_notification('success', '<?php echo trans('messages.data_add_success_lang', [], session('locale')); ?>');
                    $('#all_patient_session_table').DataTable().ajax.reload();


                },
                error: function(xhr) {
                    // Handle error, show error message
                    show_notification('error', '<?php echo trans('messages.data_add_failed_lang', [], session('locale')); ?>');
                }
            });
        });
    });
</script>
