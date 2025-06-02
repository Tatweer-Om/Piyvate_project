<script>
    $(document).ready(function() {
        var patientId = {{ $patient->id }};
        // fetchAppointments(patientId);
        fetchAppointmentsdetail(patientId);
        fetchAppointmentsAndSessions(patientId);
        fetchPayments(patientId);


        $('a[href="#sessionsBody"]').click(function() {
            fetchSessions(patientId);
        });


        // Fetch payments on tab click
        $('#payment').click(function() {
            fetchPayments(patientId);
        });

        function fetchAppointmentsdetail(patientId) {
            $.ajax({
                url: '/patient/' + patientId + '/appointmentsdetail',
                method: 'GET',
                success: function(response) {
                    var tableBody = $('#appointmentsdetailtable tbody');
                    tableBody.empty();

                    if (response.length > 0) {
                        response.forEach(function(appointment) {
                            tableBody.append(`
                        <tr>
                         <td>
                        <a href="/patient_appointment/${appointment.id}" class="text-decoration-none text-primary small">
                            ${appointment.appointment_no}
                        </a><br>
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
                            <span >
                                Taken: ${appointment.session_taken}
                            </span> <br>
                            <span >
                                Remain: ${appointment.session_remain}
                            </span>
                        </td>

                        <td>${(appointment.test_recommendations && appointment.test_recommendations.length > 0) ? appointment.test_recommendations.join(', ') : 'No test'}</td>

                      <td>
                            ${
                                (appointment.files && appointment.files.length > 0)
                                    ? appointment.files.map(file => `
                                        <div class="d-flex align-items-start gap-2 mb-2 flex-column align-items-start">
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="/images/dummy_images/file.png" alt="File Icon" width="20">
                                                <a
                                                    href="/download/${file.file_id}"
                                                    class="btn btn-sm btn-link text-decoration-none p-0"
                                                    title="${file.file_name}"
                                                >
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    `).join('')
                                    : '<span class="text-muted">No file</span>'
                            }
                        </td>


                           <td>
                            <!-- Button for Prescription Notes -->
                            <button
                                class="btn btn-sm btn-outline-info p-1 rounded-circle me-2"
                                data-bs-toggle="modal"
                                data-bs-target="#notesModal"
                                data-notes="${appointment.prescription_notes}"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="View Prescription Notes">
                                <i class="fas fa-sticky-note" style="font-size: 14px;"></i>
                            </button>

                            <!-- Button for Appointment Notes -->
                            <button
                                class="btn btn-sm btn-outline-warning p-1 rounded-circle"
                                data-bs-toggle="modal"
                                data-bs-target="#appointmentNotesModal"
                                data-notes="${appointment.notes}"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
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
                        });
                    } else {
                        tableBody.append(
                            '<tr><td colspan="10" class="text-center">No appointments found.</td></tr>'
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
                            let badgeLabel = '';
                            let badgeClass = '';
                            let invoice = '';
                            let source = '';

                            if (item.type === 'appointment') {
                                badgeLabel = 'Appointment Sessions';
                                badgeClass = 'bg-info';
                                source = 10;
                                invoice = `<a href="/apt_session_invoice/${item.id}" style="text-decoration: none;" target="_blank"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Print Invoice">
                                    <i class="fas fa-print text-primary" style="font-size: 16px;"></i>
                                </a>`;
                            } else if (item.type === 'session') {
                                badgeLabel = 'Direct Sessions';
                                badgeClass = 'bg-success';
                                source = 11;
                                invoice = `<a href="/dir_session_invoice/${item.id}" style="text-decoration: none;" target="_blank"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Print Invoice">
                                    <i class="fas fa-print text-primary" style="font-size: 16px;"></i>
                                </a>`;
                            }


                            let badgeHtml = `
                                <a href="/patient_session/${item.id}?source=${source}" class="badge ${badgeClass}" style="text-decoration: none;">
                                    ${badgeLabel}
                                </a>
                            `;


                            // Append data to the table
                            tableBody.append(`
                                <tr>
                                    <td>
                                        ${item.appointment_no} <br>
                                        ${badgeHtml}
                                    </td>
                                    <td> Taken:${item.taken_session} <br> Pending: ${item.pending} <br> OT: ${item.ot} <br>PT: ${item.pt}</td>

                                    <td>
                                    sessions:    ${item.session_count} <br>
                                    Payment Type:    ${item.payment_type} <br>
                                        ${item.name}
                                    </td>
                                    <td>${parseFloat(item.single_session_fee).toFixed(3)}</td>
                                    <td>${invoice}</td>

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

        function fetchPayments(patientId) {
            $.ajax({
                url: '/patient/' + patientId +
                    '/payment_history', // Make sure the URL matches your route
                method: 'GET',
                success: function(response) {
                    var tableBody = $('#payment_history tbody');
                    tableBody.empty();

                    if (response.length > 0) {
                        response.forEach(function(item, index) {
                            let sessionCount = item.session_count;
                            let singleSessionFee = item.single_session_fee;

                            // Append data to the table
                            tableBody.append(`
                                <tr>
                                    <td>
                                        ${item.appointment_no} <br>

                                    </td>
                                  <td class="small">
                                        <div class="mb-1">
                                            <span class="badge bg-success rounded-pill d-inline-block mb-1">Taken: ${item.taken_session}</span><br>
                                            <span class="badge bg-warning text-dark rounded-pill d-inline-block mb-1">Pending: ${item.pending}</span>
                                        </div>
                                        <div class="d-flex justify-content-center gap-3 mt-2">
                                    <div class="text-center d-flex flex-column align-items-center">
                                        <span class="badge bg-success rounded-circle d-flex flex-column justify-content-center align-items-center" style="width: 25px; height: 25px;">
                                            <span style="font-size: 7px;">PT</span>
                                            <small>${item.pt}</small>
                                        </span>
                                    </div>
                                    <div class="text-center d-flex flex-column align-items-center">
                                        <span class="badge bg-warning text-dark rounded-circle d-flex flex-column justify-content-center align-items-center" style="width: 25px; height: 25px;">
                                            <span style="font-size: 7px;">OT</span>
                                            <small>${item.ot}</small>
                                        </span>
                                    </div>
                                </div>

                                    </td>

                                    <td class="small">
                                        <div class="mb-1">
                                            <span class="badge bg-secondary rounded-pill d-inline-block mb-1">Fee: ${item.fee}</span><br>
                                            <span class="badge bg-success rounded-pill d-inline-block mb-1">Paid: ${item.paid_amount}</span>
                                        </div>

                                        ${
                                            (item.account_amounts && Object.keys(item.account_amounts).length > 0)
                                            ? `
                                                <div class="mb-1">
                                                ${Object.entries(item.account_amounts)
                                                    .map(([name, amount]) =>
                                                        `<span class="badge bg-dark rounded-pill d-block mb-1">${name}: ${amount}</span>`
                                                    ).join('')
                                                }
                                                </div>
                                            `
                                            : ''
                                        }

                                        ${
                                            (item.voucher_codes && item.voucher_codes.length > 0)
                                            ? `<div class="mb-1"><span class="badge bg-warning text-dark rounded-pill d-inline-block">Vouchers: ${item.voucher_codes.join(', ')}</span></div>`
                                            : ''
                                        }

                                        ${
                                            (item.voucher_amounts && item.voucher_amounts.length > 0)
                                            ? `<div class="mb-1"><span class="badge bg-info text-dark rounded-pill d-inline-block">Voucher Amount: ${
                                                item.voucher_amounts.reduce((sum, val) => sum + val, 0)
                                            }</span></div>`
                                            : (item.total_voucher_amount
                                                ? `<div class="mb-1"><span class="badge bg-info text-dark rounded-pill d-inline-block">Voucher Amount: ${item.total_voucher_amount}</span></div>`
                                                : ''
                                            )
                                        }
                                    </td>

                                    <td class="small">
                                        <div class="mb-1">
                                            <span class="badge bg-primary rounded-pill d-inline-block mb-1">Sessions: ${item.session_count}</span><br>
                                            <span class="badge bg-secondary rounded-pill d-inline-block mb-1">Payment: ${item.payment_type}</span><br>
                                            <span class="badge bg-light text-dark border d-inline-block mb-1">${item.name}</span>
                                        </div>
                                    </td>

                                    <td>${item.single_session_fee}</td>
                                    <td>${item.appointment_fee_paid !== undefined ? item.appointment_fee_paid : '0'} <br>
                                        ${(item.appointment_account_amounts && Object.keys(item.appointment_account_amounts).length > 0)
                                        ? `
                                        <div class="mb-1">
                                            ${Object.entries(item.appointment_account_amounts)
                                                .map(([name, amount]) =>
                                                    `<span class="badge bg-primary rounded-pill d-block mb-1">${name}: ${amount}</span>`
                                                ).join('')
                                            }
                                        </div>
                                        `
                                        : ''
                                         }
                                    </td>
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
                location.reload();
            },
            error: function(xhr) {
                show_notification('error', 'Something went wrong. Please try again.');
                console.log(xhr.responseText);
            }
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
