<script>

$(document).ready(function () {

    $('#all_sessions').DataTable({
            "sAjaxSource": "{{ url('show_sessions') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true,
            "order": [[6, "desc"]]
        });

    $(".add_session").submit(function (e) {

        e.preventDefault(); // Prevent form from refreshing
        let sessionDate = $("#session_date").val();
        let title = $("#title").val();
        let firstName = $("#first_name").val();
        let doctor = $("#doctor").val();
        let sessionType = [];
$('#session_select_box .session-checkbox:checked').each(function() {
    sessionType.push($(this).val());
});
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


    if (sessionType.length === 0) {
    show_notification('error', '<?php echo trans('messages.session_type_required', [], session('locale')); ?>');
    return;
}

        let formData = $(this).serialize(); // Serialize form data

        $.ajax({
            url: "{{ route('add_session') }}", // Laravel route for storing data
            method: "POST",
            data: formData,
            success: function (response) {
        if(response.status == 3){
            show_notification('error', '<?php echo trans('messages.please_select_session_type', [], session('locale')); ?>');
            return
        }
        if(response.status == 4){
            show_notification('error', '<?php echo trans('messages.offer_sessions_must_be_equal_to_total_sessions_type', [], session('locale')); ?>');
            return
        }
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
                show_notification('error', '<?php echo trans('messages.check_the_fields_lang', [], session('locale')); ?>');
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
                    $("#session_fee").html("OMR " + parseFloat(response.session_price).toFixed(2));

                    // If response has predefined offer sessions, update input field
                    if (response.offer_sessions) {
                    $("#no_of_sessions").val(parseInt(response.offer_sessions));
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
        $("#addSessionBtn").click(function () {
    let lastRow = $("#session_table tbody tr:last");
    let currentSessionCount = $("#session_table tbody td").length;
    let newSessionNumber = currentSessionCount + 1;

    // Generate a random number of days between 1 and 60
    let randomDays = Math.floor(Math.random() * 60) + 1;
    let currentDate = new Date();
    currentDate.setDate(currentDate.getDate() + randomDays);
    let formattedDate = currentDate.toISOString().split('T')[0];

    let sessionTd = `
        <td class="col-md-3 text-center">
            <label class="session-label">Session ${newSessionNumber}</label>
            <input type="date" class="form-control form-control-sm session-date mt-1" value="${formattedDate}" />
            <div class="input-group clockpicker mt-1">
                <input type="text" class="form-control form-control-sm success_time" id="time_to_${newSessionNumber}" name="time_to" value="10:30">
                <span class="input-group-text"><i class="fas fa-clock"></i></span>
            </div>
        </td>`;

    if (lastRow.length === 0 || lastRow.children("td").length >= 4) {
        $("#session_table tbody").append(`<tr>${sessionTd}</tr>`);
    } else {
        lastRow.append(sessionTd);
    }

    // Re-initialize clockpicker for new elements
    $('.clockpicker').clockpicker({
        autoclose: true,
        donetext: 'Done'
    });
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



    $(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    $("#addSessionForm").submit(function (e) {
        e.preventDefault(); // Prevent form submission

        let formData = $(this).serializeArray(); // Serialize non-table form data

        // Collect session details dynamically
        let sessionData = [];
        $("#session_table tbody tr td").each(function (index) {
            let sessionDate = $(this).find(".session-date").val();
            let sessionTime = $(this).find(".success_time").val();

            if (sessionDate && sessionTime) {
                sessionData.push({
                    date: sessionDate,
                    time: sessionTime
                });
            }
        });

        // Append sessionData as JSON
        formData.push({ name: "sessions", value: JSON.stringify(sessionData) });

        $.ajax({
            url: "{{ route('add_session_detail') }}", // Laravel route for storing sessions
            method: "POST",
            data: formData,
            success: function (response) {
                if (response.success) {
                    show_notification('success', '{{ trans("messages.data_add_success_lang", [], session("locale")) }}');
                    $("#addSessionForm")[0].reset(); // Reset form
                    $(".session_id").val(response.session_id);
                    $("#secondModal2").modal("show");
                    if(response.session_type == 1)
                    {
                        $('#voucher_div').show();
                        $('#voucher_discount_div').show();
                        $('#after_discount_div').show();
                    }
                } else {
                    show_notification('error', '{{ trans("messages.data_add_failed_lang", [], session("locale")) }}');
                }
            },
            error: function (xhr) {
                show_notification('error', '{{ trans("messages.check_required_failed_lang", [], session("locale")) }}');
                console.log(xhr.responseText);
            }
        });
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
    $("#clinic_no").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "/search-patient",
                type: "GET",
                dataType: "json",
                data: { query: request.term },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            label: `
                                <div class="_autocomplete-item">
                                    <span class="_autocomplete-name">${item.first_name} ${item.second_name}</span>
                                    <span class="_autocomplete-info"> ${item.HN} | ${item.mobile}</span>
                                </div>
                            `,
                            value: item.clinic_no, // Ensure only the clinic number is set in the input field
                            patient: item
                        };
                    }));
                }
            });
        },
        minLength: 2,
        focus: function (event, ui) {
            event.preventDefault(); // Prevent default behavior
        },
        select: function (event, ui) {
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
    }).autocomplete("instance")._renderItem = function (ul, item) {
        return $("<li class='ui-menu-item'>")
            .append($(item.label)) // Use jQuery to parse HTML properly
            .appendTo(ul);
    };
});


document.addEventListener("DOMContentLoaded", function () {
            let sessionFeeDiv = document.getElementById("session_fee");
            let sessionFeeInput = document.getElementById("session_fee_input");

            // Function to update the hidden input field
            function updateSessionFeeInput() {
                sessionFeeInput.value = sessionFeeDiv.textContent.trim().replace("OMR ", "");
            }

            // Run the function initially
            updateSessionFeeInput();

            // Observe changes to the session fee div
            let observer = new MutationObserver(updateSessionFeeInput);
            observer.observe(sessionFeeDiv, { childList: true, subtree: true });
        });




$(document).ready(function () {
    $(".add_payment3").submit(function (event) {
        event.preventDefault();

        let paymentData = [];
        let totalPaid = 0;
        let paymentStatus = parseInt($("#payment_status").val()) || 0;
        let totalAmount = parseFloat($("#total_amount").text()) || 0;
        let final_amount = parseFloat($("#total_amount_after_discount").val()) || 0;
        let voucher_code = $('#voucher_code').val();
        let voucher_amount  = $('#total_amount_discount').val();
        $(".payment-method-checkbox:checked").each(function () {
            let accountId = $(this).val();
            let amount = parseFloat($("#amount_" + accountId).val()) || 0;
            let refNoInput = $("#ref_no_" + accountId); // Reference the input field
            let refNo = refNoInput.length > 0 ? refNoInput.val().trim() : null;
            if (amount > 0) {
                paymentData.push({
                    account_id: accountId,
                    amount: amount,
                    ref_no:refNo,
                    payment_status:paymentStatus
                });
                totalPaid += amount;
            }
        });

        if (paymentData.length === 0 && paymentStatus !== 3) {
            show_notification('error',
            '{{ trans('messages.Please_select_atleast_a_payment_method', [], session('locale')) }}');
            return;
        }
        if(parseFloat(totalPaid) != final_amount && paymentStatus !== 3)
        {
            show_notification('error',
            '{{ trans('messages.paid_amount_less_greater_total_amount_lang', [], session('locale')) }}');
            return;
        }

        $.ajax({
            url: "/save_session_payment2", // Change URL if needed
            type: "POST",
            data: {
                _token: $("input[name=_token]").val(), // CSRF Token
                payment_methods: paymentData,
                payment_status: paymentStatus, // Send payment_status to backend
                session_id: $(".session_id2").val(),
                totalAmount: totalAmount,
                voucher_code: voucher_code,
                voucher_amount: voucher_amount,
            },
            success: function (response) {
                if (response.success) {
                    show_notification('success',
                    '{{ trans('messages.payment_added_success', [], session('locale')) }}');
                      $("#secondModal2").modal("hide");
                      window.location.href = "/all_sessions";
                    $('#all_sessions').DataTable().ajax.reload();

                } else {
                    alert("Payment failed! " + response.message);
                }
            },
            error: function (xhr) {
                alert("Error occurred: " + xhr.responseText);
            }
        });
    });
});




$(document).ready(function () {
    $('#secondModal2').on('show.bs.modal', function () {
        let paymentStatus = parseInt($("#payment_status").val()) || 0;

        if (paymentStatus === 3) {
            $("#pendingPaymentAlert").removeClass("d-none").addClass("d-block");
            $("#accountss").hide();
            $("#voucher_div").hide();
            $(".deta").hide();
            $(".select_payment").hide();

        }
        else if (paymentStatus === 2) {
            $("#voucher_div").hide();
        }
        else {
            $("#pendingPaymentAlert").removeClass("d-block").addClass("d-none");
        }
    });
});



$(document).ready(function () {
    // Calculate Age and Show Badge with Years and Months
    $("#dob").on("change", function () {
        let dob = new Date($(this).val());
        if (!isNaN(dob)) {
            let today = new Date();
            let ageYears = today.getFullYear() - dob.getFullYear();
            let ageMonths = today.getMonth() - dob.getMonth();
            let dayDiff = today.getDate() - dob.getDate();

            // Adjust age if birthday hasn't occurred yet this year
            if (dayDiff < 0) {
                ageMonths--;
            }
            if (ageMonths < 0) {
                ageYears--;
                ageMonths += 12; // Convert negative months into positive by adjusting year
            }

            $("#age_badge").text("Age: " + ageYears + " years, " + ageMonths + " months").show();
        } else {
            $("#age_badge").hide();
        }
    });

    // Detect Gender from Title and Show Badge
    $("#title").on("change", function () {
        let title = $(this).val();
        let genderText = "Unknown";
        let genderIcon = "fas fa-question-circle";

        if (title == "1") {
            genderText = "Female";
            genderIcon = "fas fa-female";
        } else if (title == "2") {
            genderText = "Male";
            genderIcon = "fas fa-male";
        } else if (title == "3") {
            genderText = "Female";
            genderIcon = "fas fa-female";
        }

        $("#gender_badge").html('<i class="' + genderIcon + '"></i> Gender: ' + genderText).show();
    });


    $("#check_voucher").on("click", function () {
        let code = $('#voucher_code').val();
        $.ajax({
            url: "{{ route('check_voucher') }}", // Your Laravel route
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                code: code,
            },
            success: function (response) {
                if (response.success == 1) {
                    show_notification('success',
                    '{{ trans('messages.success_voucher_amount_lang', [], session('locale')) }}');

                    let discount = parseFloat(response.discount_price);
                    let total_amount = parseFloat($('#total_amount_input').val());
                    let after_discount = parseFloat(total_amount) - parseFloat(response.discount_price);

                    $('#voucher_discount').text(response.discount_price);
                    $('#after_discount').text(after_discount.toFixed(3));

                    $('#total_amount_discount').val(discount.toFixed(3));
                    $('#total_amount_after_discount').val(after_discount.toFixed(3));

                    $('#voucher_code').attr('readonly', true);
                    $('#check_voucher').attr('disabled', true);

                }
                else if (response.success == 2) {
                    show_notification('error',
                    '{{ trans('messages.failed_voucher_already_used_lang', [], session('locale')) }}');
                    let discount = 0.000;
                    let total_amount = $('#total_amount_input').val();
                    let after_discount = total_amount;
                    $('#voucher_discount').text(discount.toFixed(3));
                    $('#after_discount').text(after_discount.toFixed(3));

                    $('#total_amount_discount').val(discount.toFixed(3));
                    $('#total_amount_after_discount').val(after_discount.toFixed(3));

                }
                else if (response.success == 2) {
                    show_notification('error',
                    '{{ trans('messages.failed_voucher_wrong_used_lang', [], session('locale')) }}');
                    let discount = 0.000;
                    let total_amount = $('#total_amount_input').val();
                    let after_discount = total_amount;
                    $('#voucher_discount').text(discount.toFixed(3));
                    $('#after_discount').text(after_discount.toFixed(3));

                    $('#total_amount_discount').val(discount.toFixed(3));
                    $('#total_amount_after_discount').val(after_discount.toFixed(3));

                }
            },
            error: function () {
                show_notification('error',
                    '{{ trans('messages.failed_process_lang', [], session('locale')) }}');
            }
        });
    });
});

function toggleAmountInput(accountId) {
    var checkbox = document.getElementById("account_" + accountId);
    var amountInput = document.getElementById("amount_" + accountId);
    var refNoInput = document.getElementById("ref_no_" + accountId);

    if (checkbox.checked) {
        amountInput.style.display = "block";
        refNoInput.style.display = "block";
        amountInput.required = true;
    } else {
        amountInput.style.display = "none";
        refNoInput.style.display = "none";
        amountInput.required = false;
        amountInput.value = "";
        refNoInput.value = "";
    }
}


$(document).ready(function() {
  // When checkbox is clicked
$('.session-checkbox').on('change', function() {
    // Show/hide session input boxes
    if ($('#checkbox_ot').is(':checked')) {
        $('#ot_sessions_box').show();
    } else {
        $('#ot_sessions_box').hide();
        $('#ot_sessions').val(0); // Reset value if unchecked
    }

    if ($('#checkbox_pt').is(':checked')) {
        $('#pt_sessions_box').show();
    } else {
        $('#pt_sessions_box').hide();
        $('#pt_sessions').val(0); // Reset value if unchecked
    }

    // Update total sessions
    updateNoOfSessions();
    fetchSessionPrice();

});

// Update the total number of sessions automatically
function updateNoOfSessions() {
    let otSessions = parseInt($('#ot_sessions').val()) || 0;
    let ptSessions = parseInt($('#pt_sessions').val()) || 0;
    let totalSessions = otSessions + ptSessions;

    $('#no_of_sessions').val(totalSessions);
}

// When user types in session number fields, update the total
$('#ot_sessions, #pt_sessions').on('input', function() {
    updateNoOfSessions();
    fetchSessionPrice();
});

});

    </script>



   <script>
document.addEventListener("DOMContentLoaded", function () {
    const addBtn = document.getElementById("addSessionBtn");
    const removeBtn = document.getElementById("removeSessionBtn");

    const totalSessionsSpan = document.getElementById("total_sessions");
    const totalFeeSpan = document.getElementById("total_fee");
    const totalAmountSpan = document.getElementById("total_amount");
    const afterDiscountSpan = document.getElementById("after_discount");

    const sessionFee = parseFloat(document.querySelector("input[name='session_fee']").value) || 0;

    let sessionCount = parseInt(document.querySelector("input[name='no_of_sessions']").value) || 0;

    function updateDisplay() {
        const totalFee = sessionCount * sessionFee;

        // Fixed discount (example: 10%)

        const discountInput = document.getElementById(" voucher_discount");
        const afterDiscount = totalFee - discountInput;

        totalSessionsSpan.textContent = sessionCount;
        totalFeeSpan.textContent = totalFee.toFixed(2);
        totalAmountSpan.textContent = totalFee.toFixed(2);
        afterDiscountSpan.textContent = afterDiscount.toFixed(2);

        document.querySelector("input[name='no_of_sessions']").value = sessionCount;
    }

    addBtn.addEventListener("click", function () {
        sessionCount++;
        updateDisplay();
    });

    removeBtn.addEventListener("click", function () {
        if (sessionCount > 0) {
            sessionCount--;
            updateDisplay();
        }
    });

    updateDisplay();
});
</script>



