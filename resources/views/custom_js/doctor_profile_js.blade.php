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
                        appointmentContainer.append(
                            '<p class="text-center">No appointments or sessions found.</p>');
                    } else {
                        let rowHTML = '';


                        response.appointments.forEach(function(appointment, index) {

                            if (index % 3 === 0) {
                                if (index !== 0) {
                                    rowHTML += '</div>'; // Close previous row
                                }
                                rowHTML += '<div class="row g-3 mb-3">';
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


                        rowHTML +=
                        '</div><div class="row g-3 mb-3">';
                        response.sessions.forEach(function(session) {
                            rowHTML += `
                    <div class="col-md-4">
                       <a href="/patient_session/${session.patient_id}"
                            class="text-decoration-none session-link"
                           >
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

                        rowHTML += '</div>';
                        appointmentContainer.append(rowHTML);
                    }
                },
                error: function() {
                    console.log("Error loading appointments and sessions");
                }
            });
        }



        loadAppointments();
        setInterval(loadAppointments, 10000);


        $('#all_patient_doctor').DataTable({
    "processing": true,
    "serverSide": false,
    "ajax": {
        "url": "{{ url('show_doctor_patients') }}",
        "type": "GET",
        "dataSrc": "aaData", // <-- This tells DataTables where to find the data
        "data": function(d) {
            d.doctor_id = doctorId;
        },
        "error": function(xhr, error, thrown) {
            console.log("AJAX Error:", xhr.responseText);
        }
    },
    "columns": [
        { "title": "Appointment No" },
        { "title": "Patient Name" },
        { "title": "Appointment Date" },
        { "title": "Status" }
    ],
    "pagingType": "numbers",
    "ordering": true,
    "order": [[2, "desc"]]
});

$('#all_session_table').DataTable({
    "processing": true,
    "serverSide": false,
    "ajax": {
        "url": "{{ url('show_all_sessions_by_doctor') }}",
        "type": "GET",
        "dataSrc": "aaData", // again, this is important
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
        { "title": "Source" },
        { "title": "Status" }
    ],
    "pagingType": "numbers",
    "ordering": true,
    "order": [[2, "desc"]]
});


        $('#appointmentSessionsTab, #allSessionsTab').on('shown.bs.tab', function(e) {
            var activeTab = $(e.target).attr('href');
            console.log("Active tab:", activeTab);
        });
    });



</script>
