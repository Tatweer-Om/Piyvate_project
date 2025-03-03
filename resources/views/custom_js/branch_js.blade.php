<script>

    $(document).ready(function() {
    $('#add_branch_modal').on('hidden.bs.modal', function() {
                $(".add_branch")[0].reset();
                $('.branch_id').val('');
    });

            $('#all_branch').DataTable({
                "sAjaxSource": "{{ url('show_branch') }}",
                "bFilter": true,
                'pagingType': 'numbers',
                "ordering": true,
                "order": [[6, "dsc"]]
            });

        $('#add_branch_modal').off().on('submit', function (e) {
        e.preventDefault();

        var formdatas = new FormData($('.add_branch')[0]);
        formdatas.append('_token', '{{ csrf_token() }}');
        var title = $('.branch_name').val();
        var id = $('.branch_id').val();

        // Validation
        if (title === "") {
            show_notification('error', '<?php echo trans('messages.add_branch_name_lang',[],session('locale')); ?>');
            return false;
        }



        showPreloader();
        before_submit();

        $.ajax({
            type: "POST",
            url: id ? "{{ url('update_branch') }}" : "{{ url('add_branch') }}",
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
                $('#add_branch_modal').modal('hide');
                $('#all_branch').DataTable().ajax.reload();
                if (!id) $(".add_branch")[0].reset();
            },
            error: function (data) {
                hidePreloader();
                after_submit();
                show_notification('error', id ?
                    '<?php echo trans('messages.data_update_failed_lang',[],session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>'
                );
                $('#all_branch').DataTable().ajax.reload();
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
                url : "{{ url('edit_branch') }}",
                method : "POST",
                data :   {id:id,_token: csrfToken},
                success: function(fetch) {
                                hidePreloader();

                    after_submit();
                    if(fetch!=""){

                        $(".branch_name").val(fetch.branch_name);
                        $(".branch_email").val(fetch.branch_email);
                        $(".branch_phone").val(fetch.branch_phone);
                        $(".notes").val(fetch.notes);
                        $(".branch_id").val(fetch.branch_id);
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
                        url: "{{ url('delete_branch') }}",
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
                            $('#all_branch').DataTable().ajax.reload();
                            show_notification('success', '<?php echo trans('messages.delete_success_lang',[],session('locale')); ?>');
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    show_notification('success', '<?php echo trans('messages.safe_lang',[],session('locale')); ?>');
                }
            });
        }



    </script>
