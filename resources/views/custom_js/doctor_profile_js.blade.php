<script>
    $(document).ready(function() {
        let doctorId = "{{ $doctor->id }}";


        function loadAppointments() {
    $.ajax({
        url: `/doctor/${doctorId}/appointments`, // API route
        type: "GET",
        dataType: "json",
        success: function(response) {
            let appointmentContainer = $("#DZ_W_Todo2");
            appointmentContainer.empty();

            if (response.appointments.length === 0 && response.sessions.length === 0) {
                appointmentContainer.append('<p class="text-center">No appointments or sessions found.</p>');
            } else {
                let rowHTML = '';

                // Displaying Appointments
                response.appointments.forEach(function(appointment, index) {
                    // Start a new row every 3 appointments
                    if (index % 3 === 0) {
                        if (index !== 0) {
                            rowHTML += '</div>'; // Close previous row
                        }
                        rowHTML += '<div class="row g-3 mb-3">'; // Start new row
                    }

                    rowHTML += `
                    <div class="col-md-4">
                        <a href="/patient_profile/${appointment.patient_id}" class="text-decoration-none">
                            <div class="timeline-panel bgl-dark border-0 p-3 rounded text-center shadow-sm">
                                <p class="mb-1 fs-12 text-dark fw-bold">${appointment.appointment_no}</p>
                                <p class="mb-1 fs-12 text-dark">${appointment.patient_name}</p>
                                <small class="text-dark d-block fs-10">
                                    ${appointment.date}
                                    <br>
                                    <span class="d-block text-center fw-bold fs-9 me-2">${appointment.time}</span>
                                </small>
                            </div>
                        </a>
                    </div>
                    `;
                });

                // Displaying Sessions (with updated style)
                rowHTML += '</div><div class="row g-3 mb-3">'; // Start a new row for sessions
                response.sessions.forEach(function(session) {
                    rowHTML += `
                    <div class="col-md-4">
                        <a href="/patient_profile/${session.patient_id}" class="text-decoration-none">
                            <div class="timeline-panel bg-light border-0 p-2 rounded text-center shadow-sm">
                                <p class="mb-1 fs-10 text-dark fw-bold">Session</p>
                                <p class="mb-1 fs-10 text-dark">${session.patient_name}</p>
                                <small class="text-dark d-block fs-8">
                                    ${session.session_date}
                                    <br>
                                    <span class="d-block text-center fw-bold fs-8">
                                        <span class="badge badge-dark">${session.session_time}</span>
                                    </span>
                                </small>
                            </div>
                        </a>
                    </div>
                    `;
                });

                rowHTML += '</div>'; // Close the session row
                appointmentContainer.append(rowHTML);
            }
        },
        error: function() {
            console.log("Error loading appointments and sessions");
        }
    });
}



        loadAppointments(); // Load data on page load
        setInterval(loadAppointments, 10000); // Auto-refresh every 10 seconds


        $('#all_patient_doctor').DataTable({
            "processing": true,
            "serverSide": false,
            "ajax": {
                "url": "{{ url('show_doctor_patients') }}",
                "type": "GET",
                "data": function(d) {
                    d.doctor_id = doctorId;
                },
                "error": function(xhr, error, thrown) {
                    console.log("AJAX Error:", xhr.responseText); // Debugging
                }
            },
            "columns": [{
                    "title": "Appointment No"
                },
                {
                    "title": "Patient Name"
                },
                {
                    "title": "Appointment Date"
                },
                {
                    "title": "Status"
                },
                {
                    "title": "Action"
                }
            ],
            "pagingType": "numbers",
            "ordering": true,
            "order": [
                [2, "desc"]
            ] // Ensure this column index matches your data
        });

$('#all_session_table').DataTable({
    "processing": true,
    "serverSide": false, // You're returning pre-built JSON from Laravel, no need for serverSide
    "ajax": {
        "url": "{{ url('show_all_sessions_by_doctor') }}",
        "type": "GET",
        "data": function(d) {
            d.doctor_id = doctorId;
        },
        "error": function(xhr, error, thrown) {
            console.log("AJAX Error:", xhr.responseText);
        }
    },
    "columns": [
        { "title": "S.No" },
        { "title": "Session Date" },
        { "title": "Patient" },
        { "title": "Session Time" },
        { "title": "Status" },
        { "title": "Source" }
    ],
    "pagingType": "numbers",
    "ordering": true,
    "order": [[2, "desc"]] // Default order by Session Date
});

$('#appointmentSessionsTab, #allSessionsTab').on('shown.bs.tab', function (e) {
        var activeTab = $(e.target).attr('href'); // Get the target tab's href (ID)
        // Optional: Perform actions based on which tab is active
        console.log("Active tab:", activeTab);
    });
    });
</script>
