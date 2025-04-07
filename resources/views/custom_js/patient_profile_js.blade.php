<script>
    $(document).ready(function() {
        var patientId = {{ $patient->id }}; // Patient ID passed from controller to view

        // Fetch appointments on page load
        fetchAppointments(patientId);
        fetchAppointmentsAndSessions(patientId);

        // Fetch sessions on tab click
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
          "columns": [
    {
        "data": null,
        "title": "S.No",
        "render": function(data, type, row, meta) {
            return meta.row + 1;
        }
    },
    {
        "data": "session_date",
        "title": "Session Date",
        "render": function(data) {
            return moment(data).format('DD-MM-YYYY');
        }
    },
    {
        "data": "doctor_name",
        "title": "Doctor"
    },
    {
        "data": "session_time",
        "title": "Session Time"
    },
    {
        "data": "status",
        "title": "Status"
    },
    {
        "data": "source",
        "title": "Source"
    }
]
,
            "pagingType": "numbers",
            "ordering": true,
            "order": [
                [2, "desc"]
            ] // Default order by Session Date
        });

$('#payment_table').DataTable({
    "processing": true,
    "serverSide": false, // You're returning pre-built JSON from Laravel, no need for serverSide
    "ajax": {
        "url": "{{ url('show_all_payment_by_patient') }}", // The URL endpoint
        "type": "GET",
        "data": function(d) {
            d.patient_id = patientId; // Add patient_id dynamically
        },
        "error": function(xhr, error, thrown) {
            console.log("AJAX Error:", xhr.responseText); // Handle any AJAX errors
        }
    },
    "columns": [
        {
            "data": null,
            "title": "S.No",
            "render": function(data, type, row, meta) {
                return meta.row + 1; // Display row number (1, 2, 3...)
            }
        },
        {
            "data": "session_date",
            "title": "Session Date",
            "render": function(data) {
                return moment(data).format('DD-MM-YYYY'); // Format the date
            }
        },
        {
            "data": "doctor_name",
            "title": "Doctor"
        },
        {
            "data": "session_time",
            "title": "Session Time"
        },
        {
            "data": "status",
            "title": "Status"
        },
        {
            "data": "source",
            "title": "Source"
        }

    ],
    "pagingType": "numbers", // Paging style (numeric)
    "ordering": true, // Enable sorting
    "order": [[1, "desc"]] // Default order by Session Date (index 1)
});


    });
</script>
