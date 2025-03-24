<script>
 $(document).ready(function() {
    $('#add_patient').on('hidden.bs.modal', function() {
            $(".add_patient")[0].reset();
            $('.patient_id').val('');
        });


        $('#all_patient').DataTable({
            "sAjaxSource": "{{ url('show_patient') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true,
            "order": [[6, "desc"]]
        });


    });

    $('.add_patient').submit(function(e) {
    e.preventDefault();

    var formData = new FormData($('.add_patient')[0]);
    formData.append('_token', '{{ csrf_token() }}');
    var firstName = $('#first_name').val();
    var mobile = $('#mobile').val();
    var id = $('.patient_id').val();

    if (firstName === "") {
        show_notification('error', '<?php echo trans('messages.add_patient_name_lang',[],session('locale')); ?>');
        return false;
    }
    if (mobile === "") {
        show_notification('error', '<?php echo trans('messages.provide_mobile_lang',[],session('locale')); ?>');
        return false;
    }

    showPreloader();
    before_submit();

    $.ajax({
        type: "POST",
        url: id ? "{{ url('update_patient') }}" : "{{ url('add_patient') }}",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            hidePreloader();
            after_submit();
            show_notification('success', id ?
                '<?php echo trans('messages.data_update_success_lang',[],session('locale')); ?>' :
                '<?php echo trans('messages.data_add_success_lang',[],session('locale')); ?>'
            );
            $('#add_patient').modal('hide');
            $('#all_patient').DataTable().ajax.reload();
            if (!id) $(".add_patient")[0].reset();
        },
        error: function(response) {
            hidePreloader();
            after_submit();
            show_notification('error', id ?
                '<?php echo trans('messages.data_update_failed_lang',[],session('locale')); ?>' :
                '<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>'
            );
            $('#all_patient').DataTable().ajax.reload();
        }
    });
});

function edit(id) {
    $('#global-loader').show();
    before_submit();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        dataType: 'JSON',
        url: "{{ url('edit_patient') }}",
        method: "POST",
        data: { id: id, _token: csrfToken },
        success: function(patient) {
            $('#global-loader').hide();
            after_submit();
            if (patient != "") {
                $(".patient_id").val(patient.patient_id);
                $("#title").val(patient.title);
                $('#title').selectpicker('refresh');
                $("#first_name").val(patient.first_name);
                $("#second_name").val(patient.second_name);
                $("#mobile").val(patient.mobile);
                $("#id_passport").val(patient.id_passport);
                $("#dob").val(patient.dob);
                $("#country").val(patient.country);
                $(".country").selectpicker('refresh');

                $("#details").val(patient.details);
                $(".modal-title").html('<?php echo trans('messages.update_lang',[],session('locale')); ?>');
            }
        },
        error: function() {
            $('#global-loader').hide();
            after_submit();
            show_notification('error', '<?php echo trans('messages.edit_failed_lang',[],session('locale')); ?>');
            return false;
        }
    });
}

function del(id) {
    Swal.fire({
        title: '<?php echo trans('messages.sure_lang',[],session('locale')); ?>',
        text: '<?php echo trans('messages.delete_lang',[],session('locale')); ?>',
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: '<?php echo trans('messages.delete_it_lang',[],session('locale')); ?>',
        confirmButtonClass: "btn btn-primary",
        cancelButtonClass: "btn btn-danger ml-1",
        buttonsStyling: false
    }).then(function(result) {
        if (result.value) {
            $('#global-loader').show();
            before_submit();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ url('delete_patient') }}",
                type: 'POST',
                data: { id: id, _token: csrfToken },
                error: function() {
                    $('#global-loader').hide();
                    after_submit();
                    show_notification('error', '<?php echo trans('messages.delete_failed_lang',[],session('locale')); ?>');
                },
                success: function() {
                    $('#global-loader').hide();
                    after_submit();
                    $('#all_patient').DataTable().ajax.reload();
                    show_notification('success', '<?php echo trans('messages.delete_success_lang',[],session('locale')); ?>');
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            show_notification('success', '<?php echo trans('messages.safe_lang',[],session('locale')); ?>');
        }
    });
}


</script>
