<script>
    $(document).ready(function() {
    $('#add_doctor_modal').on('hidden.bs.modal', function() {
        $(".add_doctor")[0].reset();
        $('.doctor_id').val('');
    });

    $('#all_doctors').DataTable({
        "sAjaxSource": "{{ url('show_doctors') }}",
        "bFilter": true,
        'pagingType': 'numbers',
        "ordering": true,
        "order": [[6, "desc"]]
    });

    $('#add_doctor_modal').off().on('submit', function(e) {
        e.preventDefault();

        var formdatas = new FormData($('.add_doctor')[0]);
        formdatas.append('_token', '{{ csrf_token() }}');
        var name = $('.doctor_name').val();
        var password = $('.password').val();
        var id = $('.doctor_id').val();

        if (name === "") {
            show_notification('error', '<?php echo trans('messages.add_doctor_name_lang',[],session('locale')); ?>');
            return false;
        }
        if (password === "") {
            show_notification('error', '<?php echo trans('messages.provide_password_lang',[],session('locale')); ?>');
            return false;
        }

        showPreloader();
        before_submit();

        $.ajax({
            type: "POST",
            url: id ? "{{ url('update_doctor') }}" : "{{ url('add_doctor') }}",
            data: formdatas,
            contentType: false,
            processData: false,
            success: function(data) {
                hidePreloader();
                after_submit();
                show_notification('success', id ?
                    '<?php echo trans('messages.data_update_success_lang',[],session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_success_lang',[],session('locale')); ?>'
                );
                $('#add_doctor_modal').modal('hide');
                $('#all_doctors').DataTable().ajax.reload();
                if (!id) $(".add_doctor")[0].reset();
            },
            error: function(data) {
                hidePreloader();
                after_submit();
                show_notification('error', id ?
                    '<?php echo trans('messages.data_update_failed_lang',[],session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>'
                );
                $('#all_doctors').DataTable().ajax.reload();
            }
        });
    });
});

function edit(id) {
    $('#global-loader').show();
    before_submit();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        dataType: 'JSON',
        url: "{{ url('edit_doctor') }}",
        method: "POST",
        data: { id: id, _token: csrfToken },
        success: function(fetch) {
            $('#global-loader').hide();
            after_submit();
            if (fetch != "") {
                $(".doctor_name").val(fetch.doctor_name);
                $(".doctor_id").val(fetch.doctor_id);
                $(".user_name").val(fetch.user_name);
                $(".password").val(fetch.password);
                $(".email").val(fetch.email);
                $(".phone").val(fetch.phone);
                $(".notes").val(fetch.notes);
                $(".doctor_image").attr("src", fetch.doctor_image);
                $(".branch_id").val(fetch.branch_id).trigger('change');
                $('.branch_id').selectpicker('refresh');
                $(".speciality").val(fetch.specialization).trigger('change');
                $('.speciality').selectpicker('refresh');
                $('#checked_html').html(fetch.checked_html);
                $(".modal-title").html('<?php echo trans('messages.update_lang',[],session('locale')); ?>');
            }
        },
        error: function(html) {
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
        type: "warning",
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
                url: "{{ url('delete_doctor') }}",
                type: 'POST',
                data: { id: id, _token: csrfToken },
                error: function() {
                    $('#global-loader').hide();
                    after_submit();
                    show_notification('error', '<?php echo trans('messages.delete_failed_lang',[],session('locale')); ?>');
                },
                success: function(data) {
                    $('#global-loader').hide();
                    after_submit();
                    $('#all_doctors').DataTable().ajax.reload();
                    show_notification('success', '<?php echo trans('messages.delete_success_lang',[],session('locale')); ?>');
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            show_notification('success', '<?php echo trans('messages.safe_lang',[],session('locale')); ?>');
        }
    });
}


document.addEventListener("DOMContentLoaded", function () {
    let imagePreview = document.getElementById("imagePreview");
    let imageUpload = document.getElementById("imageUpload");
    let removeImage = document.getElementById("removeImage");

    // When clicking the image, trigger the file input
    imagePreview.addEventListener("click", function () {
        imageUpload.click();
    });

    // Handle image selection
    imageUpload.addEventListener("change", function (event) {
        let file = event.target.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                removeImage.style.display = "block"; // Show remove button
            };
            reader.readAsDataURL(file);
        }
    });

    // Handle remove image
    removeImage.addEventListener("click", function () {
        imagePreview.src = "{{ asset('images/dummy_images/cover-image-icon.png') }}"; // Reset to default image
        imageUpload.value = ""; // Clear file input
        removeImage.style.display = "none"; // Hide remove button
    });
});


</script>
