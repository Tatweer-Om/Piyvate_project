<script>

    $(document).ready(function() {
    $('#add_employee_modal').on('hidden.bs.modal', function() {
                $(".add_employee")[0].reset();
                $('.employee_id').val('');
    });

            $('#all_employee').DataTable({
                "sAjaxSource": "{{ url('show_employee') }}",
                "bFilter": true,
                'pagingType': 'numbers',
                "ordering": true,
                "order": [[6, "dsc"]]
            });

        $('#add_employee_modal').off().on('submit', function (e) {
        e.preventDefault();

        var formdatas = new FormData($('.add_employee')[0]);
        formdatas.append('_token', '{{ csrf_token() }}');
        var title = $('.employee_name').val();
        var phone = $('.phone').val();
        var joining = $('.joining_date').val();

        var password = $('.password').val();

        var branch_id = $('#branch_id').val();


        var id = $('.employee_id').val();

        if (title === "") {
            show_notification('error', '<?php echo trans('messages.add_employee_name_lang',[],session('locale')); ?>');
            return false;
        }
        if (password === "") {
            show_notification('error', '<?php echo trans('messages.provide_password_lang',[],session('locale')); ?>');
            return false;
        }
        if (phone === "") {
            show_notification('error', '<?php echo trans('messages.provide_contact_number',[],session('locale')); ?>');
            return false;
        }
        if (branch_id === "") {
            show_notification('error', '<?php echo trans('messages.provide_branch',[],session('locale')); ?>');
            return false;
        }
        if (joining === "") {
            show_notification('error', '<?php echo trans('messages.add_joining_date_lang',[],session('locale')); ?>');
            return false;
        }


        showPreloader();
        before_submit();

        $.ajax({
            type: "POST",
            url: id ? "{{ url('update_employee') }}" : "{{ url('add_employee') }}",
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
                $('#add_employee_modal').modal('hide');
                $('#all_employee').DataTable().ajax.reload();
                if (!id) $(".add_employee")[0].reset();
            },
            error: function (data) {
                hidePreloader();
                after_submit();
                show_notification('error', id ?
                    '<?php echo trans('messages.data_update_failed_lang',[],session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>'
                );
                $('#all_employee').DataTable().ajax.reload();
            }
        });
    });

        });
        function edit(id){
            $('#global-loader').show();
            before_submit();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax ({
                dataType:'JSON',
                url : "{{ url('edit_employee') }}",
                method : "POST",
                data :   {id:id,_token: csrfToken},
                success: function(fetch) {
                    $('#global-loader').hide();
                    after_submit();
                    if(fetch!=""){

                        $(".employee_name").val(fetch.employee_name);
                        console.log(fetch.employee_id);

                        $(".employee_id").val(fetch.employee_id);
                        $(".password").val(fetch.password);
                        $(".email").val(fetch.employee_email);
                        $(".phone").val(fetch.employee_phone);
                        $(".emergency_leaves").val(fetch.emergency_leaves);
                        $(".joining_date").val(fetch.joining_date);

                        $(".annual_leaves").val(fetch.annual_leaves);
                        $(".notes").val(fetch.notes);
                        $(".employee_image").attr("src", fetch.employee_image);
                        $("#branch_id").val(fetch.branch_id);
                        $('.default-select').selectpicker('refresh');
                        $("#role_id").val(fetch.role_id);
                        $('.default-select').selectpicker('refresh');

                        $(".modal-title").html('<?php echo trans('messages.update_lang',[],session('locale')); ?>');
                    }
                },
                error: function(html)
                {
                    $('#global-loader').hide();
                    after_submit();
                    show_notification('error','<?php echo trans('messages.edit_failed_lang',[],session('locale')); ?>');

                    return false;
                }
            });
        }


        function del(id) {
            Swal.fire({
                title:  '<?php echo trans('messages.sure_lang',[],session('locale')); ?>',
                text:  '<?php echo trans('messages.delete_lang',[],session('locale')); ?>',
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
                    $('#global-loader').show();
                    before_submit();
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ url('delete_employee') }}",
                        type: 'POST',
                        data: {id: id,_token: csrfToken},
                        error: function () {
                            $('#global-loader').hide();
                            after_submit();
                            show_notification('error', '<?php echo trans('messages.delete_failed_lang',[],session('locale')); ?>');
                        },
                        success: function (data) {
                            $('#global-loader').hide();
                            after_submit();
                            $('#all_employee').DataTable().ajax.reload();
                            show_notification('success', '<?php echo trans('messages.delete_success_lang',[],session('locale')); ?>');
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    show_notification('success', '<?php echo trans('messages.safe_lang',[],session('locale')); ?>');
                }
            });
        }


           document.getElementById('selectAll').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.permission-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        function triggerUpload() {
        document.getElementById("imageUpload").click();
    }

    function handleImageChange(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("imagePreview").src = e.target.result;
                document.getElementById("removeImage").style.display = "block";
            };
            reader.readAsDataURL(file);
        }
    }

    function removeSelectedImage() {
        document.getElementById("imagePreview").src = "{{ asset('images/dummy_images/cover-image-icon.png') }}";
        document.getElementById("imageUpload").value = "";
        document.getElementById("removeImage").style.display = "none";
    }
    </script>
