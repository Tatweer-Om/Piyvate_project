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

                if (response.length === 0) {
                    appointmentContainer.append('<p class="text-center">No appointments found.</p>');
                } else {
                    let rowHTML = '<div class="row g-3 mb-3">'; // Added 'mb-3' for row spacing

                    response.forEach(function(appointment, index) {
                        if (index % 2 === 0 && index !== 0) {
                            rowHTML += '</div><div class="row g-3 mb-3">'; // Close and start a new row with spacing
                        }

                        rowHTML += `
                          <div class="row g-3"> <!-- Bootstrap row with spacing -->
                            <div class="col-md-6 col-lg-4"> <!-- Responsive card sizing -->
                                <div class="timeline-panel bgl-dark border-0 p-3 rounded text-center shadow-sm">
                                    <p class="mb-1 fs-12 text-dark fw-bold">${appointment.appointment_no}</p>
                                    <p class="mb-1 fs-12 text-dark">${appointment.patient_name}</p>
                                    <small class="text-dark d-block fs-10">
                                        ${appointment.date}
                                        <br>
                                        <span class="d-block text-center fw-bold fs-9 me-2">${appointment.time}</span>
                                    </small>
                                </div>
                            </div>
                        </div>

                        `;
                    });

                    rowHTML += '</div>'; // Close last row
                    appointmentContainer.append(rowHTML);
                }
            },
            error: function() {
                console.log("Error loading appointments");
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
        "columns": [
            { "title": "Appointment No" },
            { "title": "Patient Name" },
            { "title": "Appointment Date" },
            { "title": "Status" }
        ],
        "pagingType": "numbers",
        "ordering": true,
        "order": [[2, "desc"]] // Ensure this column index matches your data
    });
});





</script>
