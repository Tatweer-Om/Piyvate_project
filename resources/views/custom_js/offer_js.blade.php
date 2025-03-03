<script>

    $(document).ready(function() {
    $('#add_offer_modal').on('hidden.bs.modal', function() {
                $(".add_offer")[0].reset();
                $('.offer_id').val('');
    });

            $('#all_offer').DataTable({
                "sAjaxSource": "{{ url('show_offer') }}",
                "bFilter": true,
                'pagingType': 'numbers',
                "ordering": true,
                "order": [[6, "dsc"]]
            });

        $('#add_offer_modal').off().on('submit', function (e) {
        e.preventDefault();

        var formdatas = new FormData($('.add_offer')[0]);
        formdatas.append('_token', '{{ csrf_token() }}');
        var title = $('.offer_name').val();
        var id = $('.offer_id').val();

        // Validation
        if (title === "") {
            show_notification('error', '<?php echo trans('messages.add_offer_name_lang',[],session('locale')); ?>');
            return false;
        }



        showPreloader();
        before_submit();

        $.ajax({
            type: "POST",
            url: id ? "{{ url('update_offer') }}" : "{{ url('add_offer') }}",
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
                $('#add_offer_modal').modal('hide');
                $('#all_offer').DataTable().ajax.reload();
                if (!id) $(".add_offer")[0].reset();
            },
            error: function (data) {
                hidePreloader();
                after_submit();
                show_notification('error', id ?
                    '<?php echo trans('messages.data_update_failed_lang',[],session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>'
                );
                $('#all_offer').DataTable().ajax.reload();
                console.log(data);
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
                url : "{{ url('edit_offer') }}",
                method : "POST",
                data :   {id:id,_token: csrfToken},
                success: function(fetch) {
                    $('#global-loader').hide();
                    after_submit();
                    if(fetch!=""){

                        $(".offer_name").val(fetch.offer_name);
                        $(".sessions").val(fetch.sessions);
                        $(".offer_price").val(fetch.offer_price);
                        $(".branch_id").val(fetch.branch_id).trigger('change');
                        $('.default-select').selectpicker('refresh');
                        $(".notes").val(fetch.notes);
                        $(".offer_id").val(fetch.offer_id);
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
                        url: "{{ url('delete_offer') }}",
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
                            $('#all_offer').DataTable().ajax.reload();
                            show_notification('success', '<?php echo trans('messages.delete_success_lang',[],session('locale')); ?>');
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    show_notification('success', '<?php echo trans('messages.safe_lang',[],session('locale')); ?>');
                }
            });
        }



    </script>
