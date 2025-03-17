<script>

$(document).ready(function () {

    $(".add_session").submit(function (e) {

        e.preventDefault(); // Prevent form from refreshing
        let sessionDate = $("#session_date").val();
        let title = $("#title").val();
        let firstName = $("#first_name").val();
        let doctor = $("#doctor").val();
        let sessionType = $("#session_select_box select").val(); // Ministry selection

    // Validation check
    if (!title) {
        show_notification('error', '<?php echo trans('messages.title_required', [], session('locale')); ?>');
        return;
    }
    if (!firstName) {
        show_notification('error', '<?php echo trans('messages.first_name_required', [], session('locale')); ?>');
        return;
    }
    if (!doctor) {
        show_notification('error', '<?php echo trans('messages.doctor_required', [], session('locale')); ?>');
        return;
    }
    if (!sessionDate) {
        show_notification('error', '<?php echo trans('messages.session_date_required', [], session('locale')); ?>');
        return;
    }
    if (!sessionType) {
        show_notification('error', '<?php echo trans('messages.session_type_required', [], session('locale')); ?>');
        return;
    }

        let formData = $(this).serialize(); // Serialize form data

        $.ajax({
            url: "{{ route('add_session') }}", // Laravel route for storing data
            method: "POST",
            data: formData,
            success: function (response) {
                if (response.success) {
                    show_notification('success', '<?php echo trans('messages.data_add_success_lang', [], session('locale')); ?>');
                    $(".add_session")[0].reset(); // Reset form fields
                    $("#session_fee").html("OMR 0.00"); // Reset session fee display
                    window.location.href = "/session_detail/" + response.session_id;
                } else {
                    show_notification('error', '<?php echo trans('messages.data_add_failed_lang', [], session('locale')); ?>');
                }
            },
            error: function (xhr) {
                alert("Error saving session! Check required fields.");
                console.log(xhr.responseText);
            }
        });
    });
    // Function to fetch session price
    function fetchSessionPrice() {
        let sessionType = $("input[name='session_type']:checked").val();
        let noOfSessions = $("#no_of_sessions").val();
        let ministryId = $("#ministry_select_box select").val(); // Ministry selection
        let offerId = $("#offer_select_box select").val(); // Offer selection



        // Ensure sessionType is selected
        if (!sessionType) return;

        $.ajax({
            url: "{{ route('get.session.price') }}", // Your Laravel route
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                session_type: sessionType,
                no_of_sessions: noOfSessions,
                ministry_id: ministryId,
                offer_id: offerId
            },
            success: function (response) {
                if (response.success) {
                    $("#session_fee").html("OMR " + response.session_price.toFixed(2));

                    // If response has predefined offer sessions, update input field
                    if (response.offer_sessions) {
                        $("#no_of_sessions").val(response.offer_sessions);
                    }
                } else {
                    $("#session_fee").html("OMR 0.00"); // Reset if no data found
                }
            },
            error: function () {
                alert("Error fetching session price");
            }
        });
    }

    // Event Listeners
    $("input[name='session_type']").change(fetchSessionPrice); // Trigger when selecting session type
    $("#no_of_sessions").on("input", fetchSessionPrice); // Works for both typing & programmatic changes
    $("#ministry_select_box select").change(fetchSessionPrice); // Works for "pact" type
    $("#offer_select_box select").change(fetchSessionPrice); // Works for "offer" type
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
                } else if (this.value === 'ministry') {
                    ministrySelectBox.style.display = 'block';
                    offerSelectBox.style.display = 'none';
                } else {
                    offerSelectBox.style.display = 'none';
                    ministrySelectBox.style.display = 'none';
                }
            });
        });
    });


    function session_detail(session_id) {
        $.ajax({
            url: '/session_detail2/' + session_id, // API route
            type: 'GET',
            success: function(response) {
                    // $("#patient_name").text(response.patient_name);
                    // $("#doctor_name").text(response.doctor_name);

                    let sessionCount = response.sessions;
                    let gap = response.gap;
                    let startDate = new Date(response.appointment_date);
                    let sessionRows = '<tr>';

                    for (let i = 0; i < sessionCount; i++) {
                        let sessionDate = new Date(startDate);
                        sessionDate.setDate(startDate.getDate() + (gap * i));
                        let formattedDate = sessionDate.toISOString().split('T')[0];

                        // Start a new row if needed
                        if (i > 0 && i % 4 === 0) {
                            sessionRows += '</tr><tr>';
                        }

                        sessionRows += `
                                        <td class="col-md-3 text-center">
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
            <td class="col-md-3 text-center">
                <label class="session-label">Session ${newSessionNumber}</label>
                <input type="date" class="form-control form-control-sm session-date mt-1" value="${formattedDate}" />
                <div class="input-group clockpicker mt-1">
                    <input type="text" class="form-control form-control-sm success_time" id="time_to_${newSessionNumber}" name="time_to" value="10:30">
                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                </div>
            </td>`;

            // If no row exists or last row already has 6 <td>, create a new row
            if (lastRow.length === 0 || lastRow.children("td").length >= 4) {
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

    document.addEventListener("DOMContentLoaded", function () {
    let sessionFeeDiv = document.getElementById("session_fee");
    let sessionFeeInput = document.getElementById("session_fee_input");

    if (sessionFeeDiv && sessionFeeInput) {
        function updateSessionFee() {
            let feeText = sessionFeeDiv.innerText.trim().replace("OMR", "").trim(); // Remove "OMR" and spaces
            sessionFeeInput.value = feeText; // Update hidden input
            console.log("Session Fee Updated:", sessionFeeInput.value); // Debugging
        }

        // Run initially to set value
        updateSessionFee();

        // Detect changes if session fee updates dynamically
        let observer = new MutationObserver(updateSessionFee);
        observer.observe(sessionFeeDiv, { childList: true, subtree: true });

        // Also, manually trigger when clicking or focusing on the fee div (optional)
        sessionFeeDiv.addEventListener("DOMSubtreeModified", updateSessionFee);
    } else {
        console.error("Session Fee element not found!");
    }
});


    </script>
