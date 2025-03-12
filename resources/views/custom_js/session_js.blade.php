<script>

$(document).ready(function() {
    // Reset form when modal is closed
    $('#add_session_modal').on('hidden.bs.modal', function() {
        $(".add_session")[0].reset();
        $('.session_id').val('');
        $('#govt_select_div').hide(); // Ensure government select is hidden by default
        $('#session_name_div').show(); // Ensure session name is shown by default
    });

    // Initialize DataTable
    $('#all_session').DataTable({
        "sAjaxSource": "{{ url('show_session') }}",
        "bFilter": true,
        'pagingType': 'numbers',
        "ordering": true,
        "order": [[6, "desc"]]
    });

    // Handle form submission
    $('.add_session').off().on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append('_token', '{{ csrf_token() }}');

        // Get selected session type
        var sessionType = $('input[name="session_type"]:checked').val();
        if (!sessionType) {
            show_notification('error', 'Please select a session type.');
            return false;
        }
        formData.append('session_type', sessionType);

        // Handle session name or government selection based on session type
        var sessionName = $('.session_name').val();
        var government = $('select[name="government"]').val();

        if (sessionType === "ministry" && !government) {
            show_notification('error', 'Please select a government.');
            return false;
        } else if (sessionType === "normal" && !sessionName) {
            show_notification('error', 'Please enter a session name.');
            return false;
        }

        var id = $('.session_id').val();
        showPreloader();
        before_submit();

        $.ajax({
            type: "POST",
            url: id ? "{{ url('update_session') }}" : "{{ url('add_session') }}",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                hidePreloader();
                after_submit();
                show_notification('success', id ?
                    '<?php echo trans('messages.data_update_success_lang',[],session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_success_lang',[],session('locale')); ?>'
                );
                $('#add_session_modal').modal('hide');
                $('#all_session').DataTable().ajax.reload();
                if (!id) $(".add_session")[0].reset();
            },
            error: function(data) {
                hidePreloader();
                after_submit();
                show_notification('error', id ?
                    '<?php echo trans('messages.data_update_failed_lang',[],session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>'
                );
                $('#all_session').DataTable().ajax.reload();
                console.log(data);
            }
        });
    });

    // Edit session function


    // Delete session function
    function del(id) {
        Swal.fire({
            title: '<?php echo trans('messages.sure_lang',[],session('locale')); ?>',
            text: '<?php echo trans('messages.delete_lang',[],session('locale')); ?>',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: '<?php echo trans('messages.delete_it_lang',[],session('locale')); ?>'
        }).then((result) => {
            if (result.value) {
                $('#global-loader').show();
                before_submit();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "{{ url('delete_session') }}",
                    type: 'POST',
                    data: { id: id, _token: csrfToken },
                    success: function() {
                        $('#global-loader').hide();
                        after_submit();
                        $('#all_session').DataTable().ajax.reload();
                        show_notification('success', '<?php echo trans('messages.delete_success_lang',[],session('locale')); ?>');
                    },
                    error: function() {
                        $('#global-loader').hide();
                        after_submit();
                        show_notification('error', '<?php echo trans('messages.delete_failed_lang',[],session('locale')); ?>');
                    }
                });
            }
        });
    }

    // Toggle session fields based on session type selection
    $(".session_type").change(function() {
        if ($("#ministry").is(":checked")) {
            $("#govt_select_div").show();
        } else {
            $("#govt_select_div").hide();
            $("#session_name_div").show();
        }
    });
});


function edit(id) {
        $('#global-loader').show();
        before_submit();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            dataType: 'JSON',
            url: "{{ url('edit_session') }}",
            method: "POST",
            data: { id: id, _token: csrfToken },
            success: function(fetch) {
                $('#global-loader').hide();
                after_submit();
                if (fetch) {
                    $(".session_name").val(fetch.session_name);
                    $(".session_price").val(fetch.session_price);
                    $(".notes").val(fetch.notes);
                    $(".session_id").val(fetch.session_id);

                    // Set radio button and toggle fields
                    if (fetch.session_type === "ministry") {
                        $("#ministry").prop("checked", true);
                        $("#govt_select_div").show();
                    } else {
                        $("#normal").prop("checked", true);
                        $("#govt_select_div").hide();
                        $("#session_name_div").show();
                    }
                    $(".government").val(fetch.govt_id);
                    $('.government').selectpicker('refresh');


                    $(".modal-title").html('<?php echo trans('messages.update_lang',[],session('locale')); ?>');
                }
            },
            error: function() {
                $('#global-loader').hide();
                after_submit();
                show_notification('error', '<?php echo trans('messages.edit_failed_lang',[],session('locale')); ?>');
            }
        });
    }


    </script>
