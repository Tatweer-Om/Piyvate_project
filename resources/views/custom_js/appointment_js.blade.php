<script>
    $(document).ready(function() {
        $('#all_appointments').DataTable({
            "sAjaxSource": "{{ url('show_appointment') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true,
            "order": [
                [7, "desc"]
            ]
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
            var doctor = $('#doctor').val();
            if (doctor === "") {
                show_notification('error',
                    '{{ trans('messages.add_doctor_name_lang', [], session('locale')) }}');
                return false;
            }
            var date = $('#appointment_date').val();
            if (date === "") {
                show_notification('error',
                    '{{ trans('messages.add_appointment_date_lang', [], session('locale')) }}');
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
                    if (response.status == 7) {
                        show_notification('error',
                            '{{ trans('messages.patient_appointment_already_booked_lang', [], session('locale')) }}'
                        );
                        return;
                    }
                    show_notification('success',
                        '{{ trans('messages.appointment_add_success_lang', [], session('locale')) }}'
                    );
                    $('.add_appointment')[0].reset();
                    window.location.href = "{{ url('/all_appointments') }}";
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

    $('.edit_appointment').submit(function(e) {
        e.preventDefault();

        var formdatas = new FormData($(this)[0]);
        formdatas.append('_token', '{{ csrf_token() }}');

        var title = $('#title').val();
        if (title === "") {
            show_notification('error',
                '{{ trans('messages.add_patient_name_lang', [], session('locale')) }}');
            return false;
        }
        var doctor = $('#doctor').val();
        if (doctor === "") {
            show_notification('error',
                '{{ trans('messages.add_doctor_name_lang', [], session('locale')) }}');
            return false;
        }
        var date = $('#appointment_date').val();
        if (date === "") {
            show_notification('error',
                '{{ trans('messages.add_appointment_date_lang', [], session('locale')) }}');
            return false;
        }

        showPreloader();
        before_submit();

        $.ajax({
            type: "POST", // Keep it POST like add_appointment
            url: "{{ url('update_appointment') }}", // Use the appropriate route
            data: formdatas,
            contentType: false,
            processData: false,
            success: function(response) {
                hidePreloader();
                after_submit();
                show_notification('success',
                    '{{ trans('messages.appointment_update_success_lang', [], session('locale')) }}'
                );

                $('.edit_appointment')[0].reset();
                $('#all_appointments').DataTable().ajax.reload();
                window.location.href = "{{ url('/all_appointments') }}";

            },
            error: function(response) {
                hidePreloader();
                after_submit();
                show_notification('error',
                    '{{ trans('messages.appointment_update_failed_lang', [], session('locale')) }}'
                );
                console.log(response);
            }
        });
    });


    function cancel(id) {
        Swal.fire({
            title: '{{ trans('messages.sure_lang', [], session('locale')) }}',
            text: '{{ trans('messages.cancel_lang', [], session('locale')) }}',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: '{{ trans('messages.cancel_it_lang', [], session('locale')) }}',
        }).then(function(result) {
            if (result.value) {
                showPreloader();
                before_submit();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{ url('cancel_appointment') }}",
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



    $(document).ready(function() {
        let mainForm = $('.add_appointment');
        let paymentForm = $('.add_payment');

        $('#open_payment_modal').click(function() {
            $('#payment_modal').modal('show');
        });

        $('#confirm_payment').click(function(e) {
            e.preventDefault();

            let selectedPayments = [];
            let totalAmount = 0;
            let isValid = false;

            $('.payment-method-checkbox:checked').each(function() {
                let accountId = $(this).val();
                let amount = parseFloat($('#amount_' + accountId).val()) || 0;
                let refNo = $('#ref_no_' + accountId).length ? $('#ref_no_' + accountId).val()
                    .trim() : null;

                if (amount > 0) {
                    selectedPayments.push({
                        accountId,
                        amount,
                        refNo
                    });
                    totalAmount += amount;
                    isValid = true;
                }
            });

            if (!isValid) {
                show_notification('error',
                    '{{ trans('messages.please_pay_the_fee_lang', [], session('locale')) }}');
                return;
            }

            let appointmentFee = parseFloat($('#total_amount').text().trim().replace('OMR', '')) || 0;

            if (totalAmount !== appointmentFee) {
                show_notification('error',
                    `{{ trans('messages.payment_mismatch_lang', [], session('locale')) }} (OMR ${totalAmount.toFixed(2)})`
                    );
                return;
            }

            selectedPayments.forEach(payment => {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'payment_methods[]',
                    value: payment.accountId
                }).appendTo(mainForm);

                $('<input>').attr({
                    type: 'hidden',
                    name: 'payment_amounts[' + payment.accountId + ']',
                    value: payment.amount
                }).appendTo(mainForm);

                if (payment.refNo) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'payment_ref_nos[' + payment.accountId + ']',
                        value: payment.refNo
                    }).appendTo(mainForm);
                }
            });

            $('#payment_modal').modal('hide');
            mainForm.submit();
        });
    });



    function session(appointment_id) {
        $.ajax({
            url: '/get-session-data/' + appointment_id, // API route
            type: 'GET',
            success: function(response) {
                $("#patient_id").val(response.patient_id);
                $("#patient_name").text(response.patient_name);
                $("#doctor_id").val(response.doctor_id);
                $("#doctor_name").text(response.doctor_name);
                $("#appointment_id").val(response.appointment_id);
                $(".appointment_id2").val(response.appointment_id);
                $(".payment_status").val(response.payment_status);
                $("#appointment_date").text(response.appointment_date);
                $("#sessions").text(response.sessions);
                $("#gap").text(response.gap);

                let sessionCount = response.sessions;
                let gap = response.gap;
                let startDate = new Date(response.appointment_date);
                let sessionRows = '<tr>';

                for (let i = 0; i < sessionCount; i++) {
                    let sessionDate = new Date(startDate);
                    sessionDate.setDate(startDate.getDate() + (gap * i));
                    let formattedDate = sessionDate.toISOString().split('T')[0];

                    if (i > 0 && i % 6 === 0) {
                        sessionRows += '</tr><tr>';
                    }

                    sessionRows += `
                    <td class="col-md-2 text-center">
                        <label class="session-label">Session ${i + 1}</label>
                        <input type="date" class="form-control form-control-sm session-date mt-1" data-index="${i}" value="${formattedDate}" />
                        <div class="input-group clockpicker mt-1">
                            <input type="text" class="form-control form-control-sm success_time" id="time_to_${i}" name="time_to" value="10:30">
                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                        </div>
                    </td>`;
                }

                sessionRows += '</tr>'; // Close last row

                $("#session_table tbody").empty().append(sessionRows);


                $('.clockpicker').clockpicker({
                    autoclose: true,
                    donetext: 'Done'
                });

                $("#sessionModal").modal('show');

                // ** Add Event Listener to the First Session Date Input **
                $(".session-date").first().on("change", function() {
                    let newStartDate = new Date($(this).val());
                    updateSessionDates(newStartDate, gap);
                });
            },
            error: function() {
                show_notification('error',
                    '{{ trans('messages.error_fetching_data', [], session('locale')) }}');
            }
        });
    }

    // ** Function to Update All Session Dates Dynamically **
    function updateSessionDates(startDate, gap) {
        $(".session-date").each(function(index) {
            if (index === 0) return; // Skip the first one (already set)

            let newDate = new Date(startDate);
            newDate.setDate(startDate.getDate() + (gap * index));
            let formattedDate = newDate.toISOString().split('T')[0];

            $(this).val(formattedDate);
        });
    }


   $(document).ready(function () {
    // Add Session
    $("#addSessionBtn").click(function () {
        let lastRow = $("#session_table tbody tr:last");
        let currentSessionCount = $("#session_table tbody td").length;
        let newSessionNumber = currentSessionCount + 1;
        let formattedDate = new Date().toISOString().split('T')[0];

        let sessionTd = `
        <td class="col-md-2 text-center">
            <label class="session-label">Session ${newSessionNumber}</label>
            <input type="date" class="form-control form-control-sm session-date mt-1"
                name="session_dates[]" value="${formattedDate}" />
            <div class="input-group clockpicker mt-1">
                <input type="text" class="form-control form-control-sm success_time"
                    name="session_times[]" value="10:30">
                <span class="input-group-text"><i class="fas fa-clock"></i></span>
            </div>
        </td>`;

        if (lastRow.length === 0 || lastRow.children("td").length >= 6) {
            $("#session_table tbody").append(`<tr>${sessionTd}</tr>`);
        } else {
            lastRow.append(sessionTd);
        }
    });

    // Remove Session
    $("#removeSessionBtn").click(function () {
        let lastRow = $("#session_table tbody tr:last");
        if (lastRow.length > 0) {
            let lastTd = lastRow.children("td:last");
            if (lastRow.children("td").length > 1) {
                lastTd.remove();
            } else {
                lastRow.remove();
            }
        }
    });

    // On form submit, collect sessions into JSON
    $("form").on("submit", function () {
        let dates = $("input[name='session_dates[]']");
        let times = $("input[name='session_times[]']");
        let sessions = [];

        for (let i = 0; i < dates.length; i++) {
            sessions.push({
                session_date: dates[i].value,
                session_time: times[i] ? times[i].value : ''
            });
        }

        $("#sessions_input").val(JSON.stringify(sessions));
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

    $(document).ready(function() {
        function fetchDetails(url, id, priceBadge, categoryBadge = null, hiddenInput = null) {
            if (id) {
                $.ajax({
                    url: url + '/' + id, // Use dynamic URL with ID
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        console.log("AJAX Response:", response); // Debugging

                        if (response.success) {
                            if ($(priceBadge).length) {
                                $(priceBadge).text("Price: OMR " + response.price);
                            } else {
                                console.warn("Price badge not found:", priceBadge);
                            }

                            // Assign value to hidden input
                            if (hiddenInput) {
                                $(hiddenInput).val(response.price);
                            }

                            // Check if categoryBadge exists and assign correct values
                            if (categoryBadge && $(categoryBadge).length) {
                                if (url.includes("getOfferDetails")) {
                                    $(categoryBadge).text("Total Sessions: " + response.sessions);
                                    $("#hiddenTotalSessions").val(response.sessions);
                                } else {
                                    $(categoryBadge).text("Session Category: " + response.category);
                                }
                            } else if (categoryBadge) {
                                console.warn("Category badge not found:", categoryBadge);
                            }
                        } else {
                            show_notification('error',
                                '{{ trans('messages.error_fetching_data', [], session('locale')) }}'
                                );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        show_notification('error',
                            '{{ trans('messages.error_fetching_data_try_again', [], session('locale')) }}'
                            );
                    }
                });
            } else {
                console.warn("Invalid ID provided:", id);
            }
        }

        // Ministry Selection Change Event
        $("#ministrySelect").change(function() {
            let ministryId = $(this).val();
            fetchDetails("{{ url('/getMinistryDetails') }}", ministryId, "#sessionPrice",
                "#sessionCategory", "#hiddenMinistryPrice");
        });

        // Offer Selection Change Event
        $("#offerSelect").change(function() {
            let offerId = $(this).val();
            fetchDetails("{{ url('/getOfferDetails') }}", offerId, "#offerPrice", "#session_count",
                "#hiddenOfferPrice");
        });

        // Normal Session Selection Change Event
        $("#sessionSelect").change(function() {
            let sessionId = $(this).val();
            fetchDetails("{{ url('/getsessionDetails') }}", sessionId, "#session_Price", null,
                "#hiddenSessionPrice");
        });

        // Show/Hide Input Fields Based on Radio Selection
        $("input[name='session_type']").change(function() {
            let selectedType = $(this).val();

            if (selectedType === "ministry") {
                $("#ministryOptions").show();
                $("#offerOptions").hide();
                $("#sessionOptions").hide();

            } else if (selectedType === "offer") {
                $("#offerOptions").show();
                $("#ministryOptions").hide();
                $("#sessionOptions").hide();

            } else if (selectedType === "normal") {
                $("#sessionOptions").show();
                $("#ministryOptions").hide();
                $("#offerOptions").hide();

            } else {
                $("#ministryOptions").hide();
                $("#offerOptions").hide();
                $("#sessionOptions").hide();
            }
        });

        // Trigger change event to set initial state
        $("input[name='session_type']:checked").trigger("change");

        $("#saveSessionBtn").click(function() {
            let sessionType = $("input[name='session_type']:checked").val();
            let totalSessions = $("#session_table tbody td").length; // Count sessions from the table
            let totalPrice = 0;

            // Calculate total price using hidden input values instead of extracting from spans
            if (sessionType === "ministry") {
                Price = parseFloat($("#hiddenMinistryPrice").val()) || 0;
                totalPrice = Price * totalSessions;
            } else if (sessionType === "offer") {
                let offerPrice = parseFloat($("#hiddenOfferPrice").val()) || 0;
                totalSessions = parseInt($("#hiddenTotalSessions").val()) || totalSessions;
                totalPrice = offerPrice;
            } else if (sessionType === "normal") {
                let sessionPrice = parseFloat($("#hiddenSessionPrice").val()) || 0;
                totalPrice = sessionPrice * totalSessions;
            }
            let ministryId = $("#ministrySelect").val();
            if (ministryId) {
                $(".payment_status").val(3);
            }
            let offerId = $("#offerSelect").val();
            let normalId = $("#sessionSelect").val();

            if (!ministryId && !offerId && !normalId) {
                show_notification('error',
                    '{{ trans('messages.Please_select_either_a_Ministry_Offe_or_Normal_Session_before_proceeding', [], session('locale')) }}'
                    );
                return;
            }

            let formData = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                session_type: sessionType,
                ministry_id: $("#ministrySelect").val() || null,
                offer_id: $("#offerSelect").val() || null,
                normal_id: $("#sessionSelect").val() || null,

                patient_id: $("#patient_id").val(),
                doctor_id: $("#doctor_id").val(),
                appointment_id: $("#appointment_id").val(),
                total_sessions: totalSessions,
                total_price: totalPrice.toFixed(2), // Formatting price
                sessions: []
            };

            $("#session_table tbody td").each(function() {
                let session_date = $(this).find(".session-date").val();
                let session_time = $(this).find(".success_time").val();

                if (session_date && session_time) {
                    formData.sessions.push({
                        session_date: session_date,
                        session_time: session_time
                    });
                }
            });

            $("#hiddenTotalPrice").val(totalPrice.toFixed(2));
            $("#total_amount").text(totalPrice.toFixed(2)); // Update before AJAX


            $.ajax({
                url: "/save_sessions",
                type: "POST",
                data: formData,
                success: function(response) {
                    $("#sessionModal").modal("hide");
                    $("#secondModal").modal("show");
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert("Error saving sessions.");
                }
            });
        });
    });


    $(document).ready(function() {
        $(".add_payment2").submit(function(event) {
            event.preventDefault();

            let paymentData = [];
            let totalPaid = 0;
            let paymentStatus = parseInt($(".payment_status").val()) || 0;
            let totalAmount = parseFloat($("#total_amount").text()) || 0;

            $(".payment-method-checkbox:checked").each(function() {
                let accountId = $(this).val();
                let amount = parseFloat($("#amount_" + accountId).val()) || 0;
                let refNoInput = $("#ref_no_" + accountId); // Reference the input field
                let refNo = refNoInput.length > 0 ? refNoInput.val().trim() : null;
                if (amount > 0) {
                    paymentData.push({
                        account_id: accountId,
                        amount: amount,
                        ref_no: refNo,
                        payment_status: paymentStatus
                    });
                    totalPaid += amount;
                }
            });

            if (paymentData.length === 0 && paymentStatus !== 3) {
                show_notification('error',
                    '{{ trans('messages.Please_select_atleast_a_payment_method', [], session('locale')) }}'
                    );
                return;
            }

            if (paymentStatus === 3 && paymentData.length === 0) {
                paymentData.push({
                    payment_status: 3
                });
            }

            $.ajax({
                url: "/save_session_payment", // Change URL if needed
                type: "POST",
                data: {
                    _token: $("input[name=_token]").val(), // CSRF Token
                    payment_methods: paymentData,
                    payment_status: paymentStatus,
                    appointment_id2: $(".appointment_id2").val(),
                    totalAmount: totalAmount
                },
                success: function(response) {
                    if (response.success) {
                        show_notification('success',
                            '{{ trans('messages.payment_added_success', [], session('locale')) }}'
                            );
                        $("#secondModal").modal("hide");
                        $('#all_appointments').DataTable().ajax.reload();
                    } else {
                        alert("Payment failed! " + response.message);
                    }
                },
                error: function(xhr) {
                    alert("Error occurred: " + xhr.responseText);
                }
            });
        });

        // Handle modal display and content update based on payment status
        $('#secondModal').on('show.bs.modal', function() {
            let paymentStatus = parseInt($(".payment_status").val()) || 0;
            if (paymentStatus === 3) {
                // If payment status is 3, show the pending payment alert and hide relevant content
                $("#pendingPaymentAlert").removeClass("d-none").addClass("d-block");
                $("#accountss").hide();
                $(".deta").hide();
            } else {
                // If payment status is not 3, hide the pending payment alert and show relevant content
                $("#pendingPaymentAlert").removeClass("d-block").addClass("d-none");
                $("#accountss").show();
                $(".deta").show();
            }
        });
    });




    //patinet acutocompelete

    $(document).ready(function() {
        $("#clinic_no").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "/search-patient",
                    type: "GET",
                    dataType: "json",
                    data: {
                        query: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: `
                                <div class="_autocomplete-item">
                                    <span class="_autocomplete-name">${item.first_name} ${item.second_name}</span>
                                    <span class="_autocomplete-info"> ${item.HN} | ${item.mobile}</span>
                                </div>
                            `,
                                value: item
                                .clinic_no, // Ensure only the clinic number is set in the input field
                                patient: item
                            };
                        }));
                    }
                });
            },
            minLength: 2,
            focus: function(event, ui) {
                event.preventDefault(); // Prevent default behavior
            },
            select: function(event, ui) {
                event.preventDefault(); // Prevent inserting the `label` (HTML) in the input field
                if (ui.item.patient) {
                    let patient = ui.item.patient;
                    $("#clinic_no").val(patient.clinic_no); // Set only clinic_no in the input field
                    $("#title").val(patient.title);
                    $('#title').selectpicker('refresh');
                    $("#first_name").val(patient.first_name);
                    $("#second_name").val(patient.second_name);
                    $("#mobile").val(patient.mobile);
                    $("#id_passport").val(patient.id_passport);
                    $("#dob").val(patient.dob);
                    $("#country").val(patient.country_id);
                    $('#country').selectpicker('refresh');
                    $('#doctor').selectpicker('refresh');
                    $("#doctor").val(patient.doctor_id);
                    $("#gender").val(patient.gender);
                    $("#age_input").val(patient.age);
                    $("#age_value").text(patient.age);
                    $("#age_badge").show();
                    $("#gender_value").text(patient.gender);
                    $(".gender").val(patient.gender);
                    $("#gender_badge").show();
                }
            }
        }).autocomplete("instance")._renderItem = function(ul, item) {
            return $("<li class='ui-menu-item'>")
                .append($(item.label)) // Use jQuery to parse HTML properly
                .appendTo(ul);
        };
    });



    $(document).ready(function() {
        // Calculate and display age in years and months when DOB is selected
        $("#dob").on("change", function() {
            let dob = new Date($(this).val());
            let today = new Date();
            let ageYears = today.getFullYear() - dob.getFullYear();
            let ageMonths = today.getMonth() - dob.getMonth();

            // Adjust if the birthday hasn't occurred yet this year
            if (today.getDate() < dob.getDate()) {
                ageMonths--;
            }

            if (ageMonths < 0) {
                ageYears--;
                ageMonths += 12;
            }

            if (!isNaN(ageYears) && ageYears >= 0) {
                let ageText = `${ageYears} years`;
                if (ageMonths > 0) {
                    ageText += ` ${ageMonths} months`;
                }

                $("#age_value").text(ageText);
                $("#age_input").val(`${ageYears} years ${ageMonths} months`);
                $("#age_badge").show();
            } else {
                $("#age_badge").hide();
            }
        });

        // Update gender based on selected title
        $("#title").on("change", function() {
            let title = $(this).val();
            let gender = "";

            if (title === "1") gender = "Female"; // Miss
            if (title === "2") gender = "Male"; // Mr.
            if (title === "3") gender = "Female"; // Mrs.

            if (gender) {
                $("#gender_value").text(gender);
                $("#gender_input").val(gender);
                $("#gender_badge").show();
            } else {
                $("#gender_badge").hide();
            }
        });
    });
</script>
