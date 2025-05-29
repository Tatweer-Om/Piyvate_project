<script>
$(document).ready(function() {


    // $('#all_patients_contract').DataTable({
    //     processing: true,
    //     serverSide: false, // since Laravel is just returning all data, no server-side paging
    //     ajax: {
    //         url: "{{ url('show_all_contract') }}",
    //         data: {
    //             mini_id: $('#mini_id').val()
    //         },
    //         dataSrc: '' // because Laravel returns a plain array, not wrapped under 'data'
    //     },
    //     columns: [
    //         { data: null, render: (data, type, row, meta) => meta.row + 1 }, // Sr.No
    //         { data: 'appointment_no' },
    //         { data: null, render: function(data, type, row) {
    //             let sessionInfo = '';
    //             if (row.type === 'appointment') {
    //                 sessionInfo = `<span class="badge bg-info">Appointment Sessions</span>`;
    //             } else if (row.type === 'session') {
    //                 sessionInfo = `<span class="badge bg-success">Direct Sessions</span>`;
    //             } else {
    //                 sessionInfo = 'N/A';
    //             }
    //             return `
    //                 ${sessionInfo} <br>
    //                 Taken: ${row.taken_session} <br>
    //                 Pending: ${row.pending} <br>
    //                 OT: ${row.ot} <br>
    //                 PT: ${row.pt}
    //             `;
    //         }},
    //         { data: null, render: function(data, type, row) {
    //             let paymentDetails = `
    //                 <span>Fee: ${row.fee}</span> <br>
    //                 <span>Payment: ${row.paid_amount}</span> <br>
    //             `;
    //             if (row.account_amounts && Object.keys(row.account_amounts).length > 0) {
    //                 paymentDetails += Object.entries(row.account_amounts)
    //                     .map(([name, amount]) => `${name}: ${amount}`)
    //                     .join('<br>') + '<br>';
    //             }
    //             if (row.voucher_codes && row.voucher_codes.length > 0) {
    //                 paymentDetails += `<span>Vouchers: ${row.voucher_codes.join(', ')}</span><br>`;
    //             }
    //             if (row.voucher_amounts && row.voucher_amounts.length > 0) {
    //                 paymentDetails += `<span>Voucher Amount: ${row.voucher_amounts.reduce((sum, val) => sum + val, 0)}</span>`;
    //             } else if (row.total_voucher_amount) {
    //                 paymentDetails += `<span>Voucher Amount: ${row.total_voucher_amount}</span>`;
    //             }
    //             return paymentDetails;
    //         }},
    //         { data: null, render: function(data, type, row) {
    //             return `
    //                 Sessions: ${row.session_count} <br>
    //                 Payment Type: ${row.payment_type} <br>
    //                 ${row.name}
    //             `;
    //         }},
    //         { data: 'single_session_fee' }
    //     ],
    //     columnDefs: [
    //         { targets: [0, 1, 2, 3, 4, 5], className: 'align-middle' }
    //     ],
    //     pagingType: 'numbers'
    // });

    $.ajax({
                 url: "{{ url('show_all_contract') }}",
            data: {
                mini_id: $('#mini_id').val()
            },                method: 'GET',
                success: function(response) {
                    var tableBody = $('#all_patients_contract tbody');
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
                                    <td>${sessionInfo} <br> Taken:${item.taken_session} <br> Pending: ${item.pending} <br> OT: ${item.ot} <br>PT: ${item.pt}</td>
                                    <td>
                                        <span>Fee: ${item.fee}</span> <br>
                                        <span> Payment: ${item.paid_amount}</span> <br>

                                        ${
                                            (item.account_amounts && Object.keys(item.account_amounts).length > 0)
                                            ? `<span> ${
                                                Object.entries(item.account_amounts)
                                                    .map(([name, amount]) => `${name}: ${amount}`)
                                                    .join('<br>')
                                            }</span> <br>`
                                            : ''
                                        }

                                        ${
                                            (item.voucher_codes && item.voucher_codes.length > 0)
                                            ? `<span>Vouchers: ${item.voucher_codes.join(', ')}</span> <br>`
                                            : ''
                                        }

                                        ${
                                            (item.voucher_amounts && item.voucher_amounts.length > 0)
                                            ? `<span>Voucher Amount: ${
                                                item.voucher_amounts.reduce((sum, val) => sum + val, 0)
                                            }</span> `
                                            : (item.total_voucher_amount
                                                ? `<span>Voucher Amount: ${item.total_voucher_amount}</span> `
                                                : ''
                                            )
                                        }
                                    </td>
                                    <td>
                                    sessions:    ${item.session_count} <br>
                                    Payment Type:    ${item.payment_type} <br>
                                        ${item.name}
                                    </td>
                                    <td>${item.single_session_fee}</td>
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
});




</script>
