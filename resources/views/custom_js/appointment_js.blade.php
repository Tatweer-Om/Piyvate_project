<script>
    $(document).ready(function() {
        $('#all_appointments').DataTable({
            "sAjaxSource": "{{ url('show_appointment') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true,
        });

        $('.add_appointment').submit(function(e) {
            e.preventDefault();

            var formdatas = new FormData($(this)[0]);
            formdatas.append('_token', '{{ csrf_token() }}');

            var title = $('#title').val();
            if (title === "") {
                show_notification('error',
                    '{{ trans('messages.add_patient_name_lang', [], session('locale')) }}');
                return false;
            }
            var title = $('#doctor').val();
            if (title === "") {
                show_notification('error',
                    '{{ trans('messages.add_doctor_name_lang', [], session('locale')) }}');
                return false;
            }

            showPreloader();
            before_submit();

            $.ajax({
                type: "POST",
                url: "{{ url('add_appointment') }}",
                data: formdatas,
                contentType: false,
                processData: false,
                success: function(response) {
                    hidePreloader();
                    after_submit();
                    show_notification('success',
                        '{{ trans('messages.appointment_add_success_lang', [], session('locale')) }}'
                        );
                    $('.add_appointment')[0].reset();
                    $('#all_appointments').DataTable().ajax.reload();
                },
                error: function(response) {
                    hidePreloader();
                    after_submit();
                    show_notification('error',
                        '{{ trans('messages.appointment_add_failed_lang', [], session('locale')) }}'
                        );
                    console.log(response);
                }
            });
        });
    });

    function deleteAppointment(id) {
        Swal.fire({
            title: '{{ trans('messages.sure_lang', [], session('locale')) }}',
            text: '{{ trans('messages.delete_lang', [], session('locale')) }}',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: '{{ trans('messages.delete_it_lang', [], session('locale')) }}',
        }).then(function(result) {
            if (result.value) {
                showPreloader();
                before_submit();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{ url('delete_appointment') }}",
                    type: 'POST',
                    data: {
                        id: id,
                        _token: csrfToken
                    },
                    error: function() {
                        hidePreloader();
                        after_submit();
                        show_notification('error',
                            '{{ trans('messages.delete_failed_lang', [], session('locale')) }}'
                            );
                    },
                    success: function() {
                        hidePreloader();
                        after_submit();
                        $('#all_appointments').DataTable().ajax.reload();
                        show_notification('success',
                            '{{ trans('messages.delete_success_lang', [], session('locale')) }}'
                            );
                    }
                });
            }
        });
    }



    function session(appointment_id) {
        $.ajax({
            url: '/get-session-data/' + appointment_id, // API route
            type: 'GET',
            success: function(response) {
                    $("#patient_name").text(response.patient_name);
                    $("#doctor_name").text(response.doctor_name);
                    $("#appointment_date").text(response.appointment_date);

                    let sessionCount = response.sessions;
                    let gap = response.gap;
                    let startDate = new Date(response.appointment_date);
                    let sessionRows = '<tr>';

                    for (let i = 0; i < sessionCount; i++) {
                        let sessionDate = new Date(startDate);
                        sessionDate.setDate(startDate.getDate() + (gap * i));
                        let formattedDate = sessionDate.toISOString().split('T')[0];

                        // Start a new row if needed
                        if (i > 0 && i % 6 === 0) {
                            sessionRows += '</tr><tr>';
                        }

                        sessionRows += `
                                        <td class="col-md-2 text-center">
                                            <label class="session-label">Session ${i + 1}</label>
                                            <input type="date" class="form-control form-control-sm session-date mt-1" value="${formattedDate}" />
                                            <div class="input-group clockpicker mt-1">
                                                <input type="text" class="form-control form-control-sm success_time" id="time_to_${i}" name="time_to" value="10:30">
                                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                            </div>
                                        </td>`;
                    }

                    // Close the last row
                    sessionRows += '</tr>';

                    $("#session_table tbody").empty().append(sessionRows);

                    // Reinitialize clockpicker for dynamically added elements
                    $('.clockpicker').clockpicker({
                        autoclose: true,
                        donetext: 'Done'
                    });

                    $("#sessionModal").modal('show');
                }


                ,

            error: function() {
                show_notification('error',
                    '{{ trans('messages.error_fetching_data', [], session('locale')) }}');
            }
        });
    }



    $(document).ready(function() {
        $("#addSessionBtn").click(function() {
            let lastRow = $("#session_table tbody tr:last");
            let currentSessionCount = $("#session_table tbody td").length;
            let newSessionNumber = currentSessionCount + 1; // Next session number

            let formattedDate = new Date().toISOString().split('T')[0];

            let sessionTd = `
            <td class="col-md-2 text-center">
                <label class="session-label">Session ${newSessionNumber}</label>
                <input type="date" class="form-control form-control-sm session-date mt-1" value="${formattedDate}" />
                <div class="input-group clockpicker mt-1">
                    <input type="text" class="form-control form-control-sm success_time" id="time_to_${newSessionNumber}" name="time_to" value="10:30">
                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                </div>
            </td>`;

            // If no row exists or last row already has 6 <td>, create a new row
            if (lastRow.length === 0 || lastRow.children("td").length >= 6) {
                $("#session_table tbody").append(`<tr>${sessionTd}</tr>`);
            } else {
                lastRow.append(sessionTd);
            }
        });

        $("#removeSessionBtn").click(function() {
            let lastRow = $("#session_table tbody tr:last");

            if (lastRow.length > 0) {
                let lastTd = lastRow.children("td:last");

                if (lastRow.children("td").length > 1) {
                    // Remove last session cell from the row
                    lastTd.remove();
                } else {
                    // If only one session left in the row, remove the whole row
                    lastRow.remove();
                }
            }
        });
    });





    document.addEventListener("DOMContentLoaded", function() {
        const sessionToggle = document.getElementById("session_toggle");
        const sessionSelectBox = document.getElementById("session_select_box");

        sessionToggle.addEventListener("click", function() {
            if (sessionSelectBox.style.display === "none") {
                sessionSelectBox.style.display = "block";
                sessionToggle.textContent = "Close";
                sessionToggle.classList.remove("bg-primary");
                sessionToggle.classList.add("bg-danger");
            } else {
                sessionSelectBox.style.display = "none";
                sessionToggle.textContent = "Session";
                sessionToggle.classList.remove("bg-danger");
                sessionToggle.classList.add("bg-primary");
            }
        });
    });


    $(document).ready(function () {
    $('input[name="sessionType"]').change(function () {
        if ($('#offer').is(':checked')) {
            $('#offerFields').show();
            $('#pactFields').hide();
            $('#extraFields').show();
        } else if ($('#pact').is(':checked')) {
            $('#offerFields').hide();
            $('#pactFields').show();
            $('#extraFields').show();
        } else {
            $('#offerFields').hide();
            $('#pactFields').hide();
            $('#extraFields').hide();
        }
    });
});


document.addEventListener('DOMContentLoaded', function () {
        const sessionTypeRadios = document.querySelectorAll('input[name="session_type"]');
        const offerSelectBox = document.getElementById('offer_select_box');
        const ministrySelectBox = document.getElementById('ministry_select_box');

        sessionTypeRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.value === 'offer') {
                    offerSelectBox.style.display = 'block';
                    ministrySelectBox.style.display = 'none';
                } else if (this.value === 'pact') {
                    ministrySelectBox.style.display = 'block';
                    offerSelectBox.style.display = 'none';
                } else {
                    offerSelectBox.style.display = 'none';
                    ministrySelectBox.style.display = 'none';
                }
            });
        });
    });
</script>
