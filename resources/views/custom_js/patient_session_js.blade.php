<script>

$(document).ready(function() {

 $('.selectpicker').selectpicker();


 var patientId = @json($patient->id);

    fetchsessiontransfer(patientId);

      let source = @json($source);
        let main_id = @json($main_id);
        $('#all_patient_session_table').DataTable({
            "processing": true,
            "serverSide": false,
            "ajax": {
                "url": "{{ url('show_all_sessions_by_patient') }}",
                "type": "GET",
                "data": function(d) {
                    d.source = source;
                    d.main_id = main_id;

                },
                "error": function(xhr, error, thrown) {
                    console.log("AJAX Error:", xhr.responseText);
                }
            },
            "columns": [{
                    "title": "S.No"
                },
                {
                    "title": "Date"
                },
                {
                    "title": "Doctor"
                },
                {
                    "title": "Time"
                },
                {
                    "title": "Type"
                },
                {
                    "title": "Status"
                },
                {
                    "title": "Actions"
                },


            ],
            "pagingType": "numbers",
            "ordering": true,
            "order": [
                [1, "desc"]
            ] // Default order by Session Date
        });


        function fetchsessiontransfer(patientId) {
                $.ajax({
                    url: '/patient/' + patientId + '/session_transfer',
                    method: 'GET',
                    success: function(response) {
                        var tableBody = $('#sessiontransfer tbody');
                        tableBody.empty();

                        if (response.length > 0) {
                            response.forEach(function(session_transfer, index) {
                                tableBody.append(`
                                    <tr>
                                        <td>
                                            ${session_transfer.session_date}<br>
                                            ${session_transfer.session_time}
                                        </td>
                                        <td>
                                          ${session_transfer.old_patient_name}<br>

                                        </td>
                                         <td>

                                            ${session_transfer.new_patient_name}
                                        </td>
                                        <td>${session_transfer.created_at_date}</td>
                                        <td>${session_transfer.added_by_name}</td>
                                    </tr>
                                `);
                            });
                        } else {
                            tableBody.append(
                                '<tr><td colspan="5" class="text-center">No session transfers found.</td></tr>'
                            );
                        }
                    }
                });
            }


    });




    function edit(id, source) {
        showPreloader();
        before_submit();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            dataType: 'JSON',
            url: "{{ url('edit_ind_session') }}",
            method: "POST",
            data: {
                id: id,
                source: source,
                _token: csrfToken
            },
            success: function(fetch) {
                hidePreloader();

                after_submit();
                if (fetch != "") {

                    $("#patient_name").val(fetch.patient);
                    $("#patient_primary_id").val(fetch.patient_id);
                    $("#inputDate").val(fetch.date);
                    $("#inputTime").val(fetch.time);
                    $("#doctor").val(fetch.doctor);
                    $("#session_cat").val(fetch.session_cat);
                    $("#session_primary_id").val(fetch.session_primary_id);
                    $("#source").val(fetch.source);
                    $('#doctor').selectpicker('refresh');
                    $('#session_cat').selectpicker('refresh');


                    $(".modal-title").html('<?php echo trans('messages.update_lang', [], session('locale')); ?>');
                }
            },
            error: function(html) {
                hidePreloader();

                after_submit();
                show_notification('error', '<?php echo trans('messages.edit_failed_lang', [], session('locale')); ?>');

                return false;
            }
        });
    }


    $(document).ready(function() {
        // When the form is submitted
        $('#editSessionForm').on('submit', function(event) {
            event.preventDefault();
            document.activeElement.blur();

            // Get the form data
            var id = $("#session_primary_id").val(); // Get the session ID
            var patient_id = $("#patient_primary_id").val(); // Get the session ID

            var source = $("#source").val(); // Get the source value

            var patient_name = $("#patient_name").val();
            var session_date = $("#inputDate").val();
            var session_time = $("#inputTime").val();
            var doctor = $("#doctor").val();
            var session_cat = $("#session_cat").val();


            // CSRF token for security
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Make the AJAX request to update the session
            $.ajax({
                url: "{{ url('update_ind_session') }}", // Adjust URL as needed
                method: "POST",
                data: {
                    id: id,
                    source: source,
                    patient_id: patient_id,
                    session_date: session_date,
                    session_time: session_time,
                    doctor: doctor,
                    session_cat: session_cat,

                    _token: csrfToken
                },
                success: function(response) {



                    $('#editSessionModal').modal('hide');


                    show_notification('success', '<?php echo trans('messages.data_add_success_lang', [], session('locale')); ?>');
                    $('#all_patient_session_table').DataTable().ajax.reload();


                },
                error: function(xhr) {
                    // Handle error, show error message
                    show_notification('error', '<?php echo trans('messages.data_add_failed_lang', [], session('locale')); ?>');
                }
            });
        });
    });


    function transfer(id, source) {
        showPreloader();
        before_submit();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            dataType: 'JSON',
            url: "{{ url('transfer_ind_session') }}",
            method: "POST",
            data: {
                id: id,
                source: source,
                _token: csrfToken
            },
            success: function(fetch) {
                hidePreloader();

                after_submit();
                if (fetch != "") {

                    $("#source_patient").val(fetch.patient);
                    $("#patient_primary_id2").val(fetch.patient_id);
                    $("#ses_date").val(fetch.date);
                    $("#ses_time").val(fetch.time);
                    $("#session_primary_id2").val(fetch.session_primary_id);
                    $("#source2").val(fetch.source);

                }
            },
            error: function(html) {
                hidePreloader();

                after_submit();
                show_notification('error', '<?php echo trans('messages.transfer_failed_lang', [], session('locale')); ?>');

                return false;
            }
        });
    }

    $(document).ready(function() {
        // When the form is submitted
        $('#transferForm').on('submit', function(event) {
            event.preventDefault();
            document.activeElement.blur();

            // Get the form data
            var id = $("#session_primary_id2").val(); // Get the session ID
            var old_patient_id = $("#patient_primary_id2").val(); // Get the session ID

            var source = $("#source2").val(); // Get the source value

            var patient_name = $("#patient_name").val();
            var session_date = $("#ses_date").val();
            var session_time = $("#ses_time").val();
            var target_patient = $("#target_patient").val();

            // CSRF token for security
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Make the AJAX request to update the session
            $.ajax({
                url: "{{ url('update_transfer_ind_session') }}", // Adjust URL as needed
                method: "POST",
                data: {
                    id: id,
                    source: source,
                    old_patient_id: old_patient_id,
                    session_date: session_date,
                    session_time: session_time,
                    target_patient: target_patient,
                    _token: csrfToken
                },
                success: function(response) {
                    if (response.status == 9) {
                        show_notification('error', '{{ trans('messages.same_patient_issue') }}');
                        return;
                    }

                    $('#transferModal').modal('hide');


                    show_notification('success', '<?php echo trans('messages.data_add_success_lang', [], session('locale')); ?>');
                    $('#all_patient_session_table').DataTable().ajax.reload();


                },
                error: function(xhr) {
                    // Handle error, show error message
                    show_notification('error', '<?php echo trans('messages.data_add_failed_lang', [], session('locale')); ?>');
                }
            });
        });
    });
</script>
