
<script>

    $(document).ready(function() {
    $('#add_supplier_modal').on('hidden.bs.modal', function() {
                $(".add_supplier")[0].reset();
                $('.supplier_id').val('');
    });

            $('#all_supplier').DataTable({
                "sAjaxSource": "{{ url('show_supplier') }}",
                "bFilter": true,
                'pagingType': 'numbers',
                "ordering": true,
                "order": [[6, "dsc"]]
            });

        $('#add_supplier_modal').off().on('submit', function (e) {
        e.preventDefault();

        var formdatas = new FormData($('.add_supplier')[0]);
        formdatas.append('_token', '{{ csrf_token() }}');
        var title = $('.supplier_name').val();
        var phone = $('.phone').val();


        var id = $('.supplier_id').val();

        if (title === "") {
            show_notification('error', '<?php echo trans('messages.add_supplier_name_lang',[],session('locale')); ?>');
            return false;
        }
        if (phone === "") {
            show_notification('error', '<?php echo trans('messages.add_supplier_phone_lang',[],session('locale')); ?>');
            return false;
        }



        showPreloader();
        before_submit();

        $.ajax({
            type: "POST",
            url: id ? "{{ url('update_supplier') }}" : "{{ url('add_supplier') }}",
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
                $('#add_supplier_modal').modal('hide');
                $('#all_supplier').DataTable().ajax.reload();
                if (!id) $(".add_supplier")[0].reset();
            },
            error: function (data) {
                hidePreloader();
                after_submit();
                show_notification('error', id ?
                    '<?php echo trans('messages.data_update_failed_lang',[],session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>'
                );
                $('#all_supplier').DataTable().ajax.reload();
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
                url : "{{ url('edit_supplier') }}",
                method : "POST",
                data :   {id:id,_token: csrfToken},
                success: function(fetch) {
                    $('#global-loader').hide();
                    after_submit();
                    if(fetch!=""){

                        $(".supplier_name").val(fetch.supplier_name);

                        $(".supplier_id").val(fetch.supplier_id);

                        $(".phone").val(fetch.supplier_phone);
                        $(".notes").val(fetch.notes);

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
                        url: "{{ url('delete_supplier') }}",
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
                            $('#all_supplier').DataTable().ajax.reload();
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
    </script>
