<script>

    $(document).ready(function() {
    $('#add_speciality_modal').on('hidden.bs.modal', function() {
                $(".add_speciality")[0].reset();
                $('.speciality_id').val('');
    });

            $('#all_speciality').DataTable({
                "sAjaxSource": "{{ url('show_speciality') }}",
                "bFilter": true,
                'pagingType': 'numbers',
                "ordering": true,
            });

        $('#add_speciality_modal').off().on('submit', function (e) {
        e.preventDefault();

        var formdatas = new FormData($('.add_speciality')[0]);
        formdatas.append('_token', '{{ csrf_token() }}');
        var title = $('.speciality_name').val();
        var id = $('.speciality_id').val();

        // Validation
        if (title === "") {
            show_notification('error', '<?php echo trans('messages.add_speciality_name_lang',[],session('locale')); ?>');
            return false;
        }



        showPreloader();
        before_submit();

        $.ajax({
            type: "POST",
            url: id ? "{{ url('update_speciality') }}" : "{{ url('add_speciality') }}",
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
                $('#add_speciality_modal').modal('hide');
                $('#all_speciality').DataTable().ajax.reload();
                if (!id) $(".add_speciality")[0].reset();
            },
            error: function (data) {
                hidePreloader();
                after_submit();
                show_notification('error', id ?
                    '<?php echo trans('messages.data_update_failed_lang',[],session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>'
                );
                $('#all_speciality').DataTable().ajax.reload();
                console.log(data);
            }
        });
    });

        });
        function edit(id){
            showPreloader();
            before_submit();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax ({
                dataType:'JSON',
                url : "{{ url('edit_speciality') }}",
                method : "POST",
                data :   {id:id,_token: csrfToken},
                success: function(fetch) {
                                hidePreloader();

                    after_submit();
                    if(fetch!=""){

                        $(".speciality_name").val(fetch.speciality_name);

                        $(".notes").val(fetch.notes);
                        $(".speciality_id").val(fetch.speciality_id);
                        $(".modal-title").html('<?php echo trans('messages.update_lang',[],session('locale')); ?>');
                    }
                },
                error: function(html)
                {
                 hidePreloader();

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
                    showPreloader();
                    before_submit();
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ url('delete_speciality') }}",
                        type: 'POST',
                        data: {id: id,_token: csrfToken},
                        error: function () {
                                        hidePreloader();

                            after_submit();
                            show_notification('error', '<?php echo trans('messages.delete_failed_lang',[],session('locale')); ?>');
                        },
                        success: function (data) {
                                        hidePreloader();

                            after_submit();
                            $('#all_speciality').DataTable().ajax.reload();
                            show_notification('success', '<?php echo trans('messages.delete_success_lang',[],session('locale')); ?>');
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    show_notification('success', '<?php echo trans('messages.safe_lang',[],session('locale')); ?>');
                }
            });
        }



    </script>
