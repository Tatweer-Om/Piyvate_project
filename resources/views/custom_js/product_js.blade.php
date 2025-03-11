<script>
    $(document).ready(function() {
        // JsBarcode(".barcode").init();
        // show all products
        $('#all_product').DataTable({
            "sAjaxSource": "{{ url('show_product') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true,
        });


    });



     $(document).ready(function () {
    $('.add_product').off().on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this); // Using `this` to reference the form directly
        let csrfToken = $('meta[name="csrf-token"]').attr('content');

        $('#global-loader').show();
        before_submit();

        $.ajax({
            type: "POST",
            url: "<?php echo url('update_product'); ?>",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': csrfToken // Ensure CSRF token is included
            },
            success: function (response) {
                $('#global-loader').hide();
                after_submit();

                show_notification('success', '<?php echo trans('messages.data_update_success_lang', [], session('locale')); ?>');

                $('#add_product_modal').modal('hide');
                $('#all_product').DataTable().ajax.reload();
            },
            error: function (error) {
                $('#global-loader').hide();
                after_submit();

                show_notification('error', '<?php echo trans('messages.data_update_failed_lang', [], session('locale')); ?>');

                console.log(error);
            }
        });
    });
});

function previewImage(event, imgTagId, removeBtnId) {
    const input = event.target;
    const imgTag = document.getElementById(imgTagId);
    const removeBtn = document.getElementById(removeBtnId);

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imgTag.src = e.target.result;  // Set image preview
            removeBtn.style.display = 'block'; // Show remove button
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage(imgTagId, inputId, removeBtnId) {
    document.getElementById(imgTagId).src = "{{ asset('images/dummy_images/no_image.jpg') }}"; // Reset image
    document.getElementById(inputId).value = ''; // Clear input
    document.getElementById(removeBtnId).style.display = 'none'; // Hide remove button
}



    // $('.add_replace_product_form').off().on('submit', function(e){
    //     e.preventDefault();
    //     var formdatas = new FormData($('.add_replace_product_form')[0]);
    //     var current_imei = $('.current_imei').val();
    //     var new_imei = $('.new_imei').val();
    //     var order_no = $('.order_no').val();
    //     var replace_notes = $('.replace_notes').val();
    //     if(current_imei == "")
    //     {
    //         show_notification('error','<?php echo trans('messages.validation_current_imei_lang',[],session('locale')); ?>');
    //         return false;
    //     }
    //     if(new_imei == "")
    //     {
    //         show_notification('error','<?php echo trans('messages.validation_new_imei_lang',[],session('locale')); ?>');
    //         return false;
    //     }
    //     if(replace_notes == "")
    //     {
    //         show_notification('error','<?php echo trans('messages.validation_notes_lang',[],session('locale')); ?>');
    //         return false;
    //     }

    //     $('#global-loader').show();
    //     before_submit();
    //     var str = $(".add_replace_product_form").serialize();
    //     $.ajax({
    //         type: "POST",
    //         url: "<?php echo url('add_replace_product'); ?>",
    //         data: formdatas,
    //         contentType: false,
    //         processData: false,
    //         success: function(data) {
    //             $('#global-loader').hide();
    //             after_submit();
    //             if(data.status == 1)
    //             {
    //                 show_notification('success','<?php echo trans('messages.replaced_successfully_lang',[],session('locale')); ?>');
    //                 $('#add_replace_pro_modal').modal('hide');
    //                 $('#all_product').DataTable().ajax.reload();
    //                 return false;
    //             }
    //             else if(data.status == 2)
    //             {
    //                 show_notification('error','<?php echo trans('messages.imei_not_exist_lang',[],session('locale')); ?>');
    //                 return false;
    //             }
    //             else if(data.status == 3)
    //             {
    //                 show_notification('error','<?php echo trans('messages.imei_duplicate_lang',[],session('locale')); ?>');
    //                  return false;
    //             }
    //         },
    //         error: function(data) {
    //             $('#global-loader').hide();
    //             after_submit();
    //             show_notification('error','<?php echo trans('messages.data_update_failed_lang',[],session('locale')); ?>');
    //             $('#all_product').DataTable().ajax.reload();
    //             console.log(data);
    //             return false;
    //         }
    //     });
    // });


function edit(id) {
    $('#global-loader').show();
    before_submit();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        dataType: 'JSON',
        url: "<?php echo url('edit_product'); ?>",
        method: "POST",
        data: { id: id, _token: csrfToken },
        success: function(fetch) {
            $('#global-loader').hide();
            after_submit();

            if (fetch) {
                // Assign values to inputs
                $(".product_name").val(fetch.product_name);
                $(".product_id").val(id);
                $(".quantity").val(fetch.quantity);
                $(".sale_price").val(fetch.sale_price);
                $(".tax").val(fetch.tax);
                $(".barcode").val(fetch.barcode);
                $(".purchase_price").val(fetch.purchase_price);
                $(".description").val(fetch.notes);
                $(".store_id").val(fetch.branch_id);
                $(".category_id").val(fetch.category_id);
                $('.store_id').selectpicker('refresh');

                $('.category_id').selectpicker('refresh');
                $("#stock_img_tag").attr("src", fetch.stock_image);
                if (fetch.product_type == 1) {
                    $(".product_clinic").prop('checked', true);
                    $(".product_sale").prop('checked', false);
                } else if (fetch.product_type == 2) {
                    $(".product_sale").prop('checked', true);
                    $(".product_clinic").prop('checked', false);
                }

                $(".modal-title").html('<?php echo trans('messages.update_lang',[],session('locale')); ?>');
            }
        },
        error: function(html) {
            $('#global-loader').hide();
            after_submit();
            show_notification('error', '<?php echo trans('messages.edit_failed_lang',[],session('locale')); ?>');
            console.log(html);
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
                    url: "<?php echo  url('delete_product') ?>",
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
                        $('#all_product').DataTable().ajax.reload();
                        show_notification('success', '<?php echo trans('messages.delete_success_lang',[],session('locale')); ?>');
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                show_notification('success',  '<?php echo trans('messages.safe_lang',[],session('locale')); ?>' );
            }
        });
    }

    // replace product
    // get purchase payment
    function replace_pro_imei(id)
    {
        $('#global-loader').show();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "<?php echo url('replace_pro_imei'); ?>",
            method: "POST",
            data: {
                id:id,
                _token: csrfToken
            },
            success: function(data) {
                $('#global-loader').hide();
                $('.order_no').val(data.order_no);
                $('.replace_product_id').val(id);
                $('#add_replace_pro_modal').modal('show');

            },
            error: function(data) {
                $('#global-loader').hide();
                after_submit();
                show_notification('error', '<?php echo trans('messages.get_quantity_failed_lang',[],session('locale')); ?>' );
                console.log(data);
                return false;
            }
        });
    }

        //endnew
    // send item back
    function send_item_back(id) {
        Swal.fire({
            title:  '<?php echo trans('messages.sure_lang',[],session('locale')); ?>',
            text:  '<?php echo trans('messages.send_item_to_purchase_lang',[],session('locale')); ?>',
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: '<?php echo trans('messages.yes_lang',[],session('locale')); ?>',
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: !1
        }).then(function (result) {
            if (result.value) {
                $('#global-loader').show();
                before_submit();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "<?php echo  url('send_item_back') ?>",
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
                        if(data.status == 1)
                        {
                            $('#all_product').DataTable().ajax.reload();
                            show_notification('success', '<?php echo trans('messages.item_send_back_success_lang',[],session('locale')); ?>');
                            return false;
                        }
                        else if(data.status == 2)
                        {
                            show_notification('error', '<?php echo trans('messages.purchase_complated_lang',[],session('locale')); ?>');
                            return false;
                        }
                        else if(data.status == 3)
                        {
                            show_notification('error', '<?php echo trans('messages.product_sold_already_lang',[],session('locale')); ?>');
                            return false;
                        }
                        else if(data.status == 4)
                        {
                            show_notification('error', '<?php echo trans('messages.old_product_lang',[],session('locale')); ?>');
                            return false;
                        }
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                show_notification('success',  '<?php echo trans('messages.safe_lang',[],session('locale')); ?>' );
            }
        });
    }
</script>
