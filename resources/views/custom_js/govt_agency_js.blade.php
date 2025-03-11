
<script>
$(document).ready(function() {
    // Reset form on modal close
    $('#add_govt_modal').on('hidden.bs.modal', function() {
        $(".add_govt")[0].reset();
        $('.govt_id').val('');
    });

    // Initialize DataTable
    $('#all_govt').DataTable({
        "sAjaxSource": "{{ url('show_govt') }}",
        "bFilter": true,
        'pagingType': 'numbers',
        "ordering": true,
    });

    // Handle form submission
    $('#add_govt_modal').off().on('submit', function (e) {
        e.preventDefault();

        var formdatas = new FormData($('.add_govt')[0]);
        formdatas.append('_token', '{{ csrf_token() }}');
        var name = $('.govt_name').val();
        var id = $('.govt_id').val();

        // Validation
        if (name === "") {
            show_notification('error', '<?php echo trans('messages.add_govt_name_lang',[],session('locale')); ?>');
            return false;
        }

        showPreloader();
        before_submit();

        $.ajax({
            type: "POST",
            url: id ? "{{ url('update_govt') }}" : "{{ url('add_govt') }}",
            data: formdatas,
            contentType: false,
            processData: false,
            success: function (data) {
                hidePreloader();
                after_submit();
                show_notification('success', id ?
                    '<?php echo trans('messages.data_update_success_lang',[],session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_success_lang',[],session('locale')); ?>'
                );
                $('#add_govt_modal').modal('hide');
                $('#all_govt').DataTable().ajax.reload();
                if (!id) $(".add_govt")[0].reset();
            },
            error: function (data) {
                hidePreloader();
                after_submit();
                show_notification('error', id ?
                    '<?php echo trans('messages.data_update_failed_lang',[],session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>'
                );
                $('#all_govt').DataTable().ajax.reload();
                console.log(data);
            }
        });
    });

});

// Edit function
function edit(id) {
    showPreloader();
    before_submit();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        dataType: 'JSON',
        url: "{{ url('edit_govt') }}",
        method: "POST",
        data: {id: id, _token: csrfToken},
        success: function(fetch) {
            hidePreloader();
            after_submit();
            if (fetch != "") {
                $(".govt_name").val(fetch.govt_name);
                $(".govt_phone").val(fetch.govt_phone);
                $(".govt_email").val(fetch.govt_email);
                $(".notes").val(fetch.notes);
                $(".govt_id").val(fetch.govt_id);
                $(".modal-title").html('<?php echo trans('messages.update_lang',[],session('locale')); ?>');
            }
        },
        error: function() {
            hidePreloader();
            after_submit();
            show_notification('error', '<?php echo trans('messages.edit_failed_lang',[],session('locale')); ?>');
        }
    });
}

// Delete function
function del(id) {
    Swal.fire({
        title: '<?php echo trans('messages.sure_lang',[],session('locale')); ?>',
        text: '<?php echo trans('messages.delete_lang',[],session('locale')); ?>',
        type: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: '<?php echo trans('messages.delete_it_lang',[],session('locale')); ?>',
        confirmButtonClass: "btn btn-primary",
        cancelButtonClass: "btn btn-danger ml-1",
        buttonsStyling: !1
    }).then(function (result) {
        if (result.value) {
            showPreloader();
            before_submit();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{ url('delete_govt') }}",
                type: 'POST',
                data: {id: id, _token: csrfToken},
                error: function () {
                    hidePreloader();
                    after_submit();
                    show_notification('error', '<?php echo trans('messages.delete_failed_lang',[],session('locale')); ?>');
                },
                success: function () {
                    hidePreloader();
                    after_submit();
                    $('#all_govt').DataTable().ajax.reload();
                    show_notification('success', '<?php echo trans('messages.delete_success_lang',[],session('locale')); ?>');
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            show_notification('success', '<?php echo trans('messages.safe_lang',[],session('locale')); ?>');
        }
    });
}
</script>
