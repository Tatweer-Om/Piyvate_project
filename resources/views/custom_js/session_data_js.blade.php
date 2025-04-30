<script>


    $(document).ready(function() {


        $('#all_session_data').DataTable({
    "sAjaxSource": "{{ url('show_session_data') }}",
    "bFilter": true,
    'pagingType': 'numbers',
    "ordering": true,
    "order": [

        [3, 'asc'],

        [4, 'desc']
    ],
    "columnDefs": [
        {
            "targets": [3, 4],
            "type": "date"
        }
    ]
});


    });


    function edit(id, source) {
        showPreloader();
        before_submit();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            dataType: 'JSON',
            url: "{{ url('edit_ind_session2') }}",
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
                    $("#session_cat").val(fetch.session_cat);
                    $('#session_cat').selectpicker('refresh');

                    $("#doctor").val(fetch.doctor);
                    $("#session_primary_id").val(fetch.session_primary_id);
                    $('#doctor').selectpicker('refresh');


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
        $('#editSessionForm2').on('submit', function(event) {
            event.preventDefault();

            // Get the form data
            var id = $("#session_primary_id").val(); // Get the session ID
            var patient_id = $("#patient_primary_id").val(); // Get the session ID


            var patient_name = $("#patient_name").val();
            var session_date = $("#inputDate").val();
            var session_time = $("#inputTime").val();
            var doctor = $("#doctor").val();
            var session_cat = $("#session_cat").val();
            // CSRF token for security
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Make the AJAX request to update the session
            $.ajax({
                url: "{{ url('update_ind_session2') }}", // Adjust URL as needed
                method: "POST",
                data: {
                    id: id,
                    patient_id: patient_id,
                    session_date: session_date,
                    session_time: session_time,
                    session_cat: session_cat,
                    doctor: doctor,
                    _token: csrfToken
                },
                success: function(response) {

                    if(response.status==3){
                        show_notification('error', '<?php echo trans('messages.doctor_is_busy_lang', [], session('locale')); ?>');
                        return
                    }


                    $('#editSessionModal2').modal('hide');


                    show_notification('success', '<?php echo trans('messages.data_add_success_lang', [], session('locale')); ?>');
                    $('#all_session_data').DataTable().ajax.reload();


                },
                error: function(xhr) {
                    // Handle error, show error message
                    show_notification('error', '<?php echo trans('messages.data_add_failed_lang', [], session('locale')); ?>');
                }
            });
        });
    });



</script>
