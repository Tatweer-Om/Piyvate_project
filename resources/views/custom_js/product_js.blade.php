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

        // show all qty audit
        $('#all_qty_audit').DataTable({
            "ajax": {
                "url": "<?php echo url('show_qty_audit'); ?>",
                "type": "GET",
                "data": function (d) {
                    // Pass additional data to the server
                    d.start_date = $('.start_date').val();
                    d.end_date = $('.end_date').val();
                    d.product_id = $('.product_id').val();
                }
            },
            "bFilter": true,
            "sDom": 'fBtlpi',
            'pagingType': 'numbers',
            "ordering": true,
            "order": [[11, "asc"]],
            "language": {
                search: ' ',
                sLengthMenu: '_MENU_',
                searchPlaceholder: "'<?php echo trans('messages.search_lang',[],session('locale')); ?>'",
                info: "_START_ - _END_ of _TOTAL_ items",
            },
            initComplete: (settings, json)=>{
                $('.dataTables_filter').appendTo('#tableSearch');
                $('.dataTables_filter').appendTo('.search-input');
            },
        });

        //

        // check damage qty
        $('.damage_qty').keyup(function() {
            var current_qty = $('.current_qty').val();
            if(parseFloat($(this).val())>parseFloat(current_qty))
            {
                show_notification('error', '<?php echo trans('messages.damage_quantity_lang',[],session('locale')); ?>');
                $(this).val("")
                return false;
            }
        });
        //

        // add damage qty
        $('.add_damage_qty').off().on('submit', function(e){
            e.preventDefault();
            var formdatas = new FormData($('.add_damage_qty')[0]);
            var reason=$('.reason').val();
            var product_id=$('.product_id').val();
            var stock_type=$('.stock_type').val();

            if(stock_type==1)
            {
                var damage_qty=$('.damage_qty').val();
                if(damage_qty=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_damage_qty_lang',[],session('locale')); ?>'); return false;

                }
            }
            else
            {
                var imei_checked = $('.all_imeis:checked').length;
                // If no checkbox is checked, display an alert message
                if (imei_checked === 0) {
                    show_notification('error','<?php echo trans('messages.check_atleast_one_imei_lang',[],session('locale')); ?>'); return false;
                }
            }
            if(reason=="" )
            {
                show_notification('error','<?php echo trans('messages.add_reason_lang',[],session('locale')); ?>'); return false;

            }

            $('#global-loader').show();
            before_submit();
            var str = $(".add_damage_qty").serialize();
            $.ajax({
                type: "POST",
                url: "<?php echo url('add_damage_qty');?>",
                data: formdatas,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#global-loader').hide();
                    after_submit();
                    $('#all_product').DataTable().ajax.reload();
                    show_notification('success','<?php echo trans('messages.data_add_damage_qty_success',[],session('locale')); ?>');
                    get_product_qty(product_id)
                    $(".add_damage_qty")[0].reset();
                    return false;
                    },
                error: function(data)
                {
                    $('#global-loader').hide();
                    after_submit();
                    show_notification('error','<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>');
                    $('#all_product').DataTable().ajax.reload();
                    console.log(data);
                    return false;
                }

            });
        });
        //

        // add damage qty
        $('.add_undo_damage_qty').off().on('submit', function(e){
            e.preventDefault();
            var formdatas = new FormData($('.add_undo_damage_qty')[0]);
            var reason=$('.undo_reason').val();
            var product_id=$('.product_id').val();
            var stock_type=$('.undo_stock_type').val();

            var imei_checked = $('.single_damage_qty:checked').length;
            // If no checkbox is checked, display an alert message
            if (imei_checked === 0) {
                show_notification('error','<?php echo trans('messages.check_atleast_one_stock_lang',[],session('locale')); ?>'); return false;
            }

            if(reason=="" )
            {
                show_notification('error','<?php echo trans('messages.add_reason_lang',[],session('locale')); ?>'); return false;

            }

            $('#global-loader').show();
            before_submit();
            var str = $(".add_undo_damage_qty").serialize();
            $.ajax({
                type: "POST",
                url: "<?php echo url('add_undo_damage_qty');?>",
                data: formdatas,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#global-loader').hide();
                    after_submit();
                    $("#undo_damage_qty_modal").modal("hide");
                    $('#all_product').DataTable().ajax.reload();
                    show_notification('success','<?php echo trans('messages.data_add_undo_damage_qty_success',[],session('locale')); ?>');
                    $(".add_undo_damage_qty")[0].reset();
                    return false;
                },
                error: function(data)
                {
                    $('#global-loader').hide();
                    after_submit();
                    show_notification('error','<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>');
                    $('#all_product').DataTable().ajax.reload();
                    console.log(data);
                    return false;
                }
            });
        });
        //

        // check and uncehcekd damage undo products one time
        $(document).on('click', '.single_damage_qty', function(e) {
            if($(".single_damage_qty").length == $(".single_damage_qty:checked").length) {
                $(".all_damge_requests").prop("checked", true);
            }
            else
            {
                $(".all_damge_requests").prop("checked", false);
            }
        });
        $(document).on('click', '.all_damge_requests', function(e) {
            var checkAll = $(".all_damge_requests").prop('checked');
            if (checkAll) {
                $(".single_damage_qty").prop("checked", true);
            } else {
                $(".single_damage_qty").prop("checked", false);
            }

        });
    });

    // get purchase payment
    function get_product_qty(id)
    {
        $('#global-loader').show();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "<?php echo url('get_product_qty'); ?>",
            method: "POST",
            data: {
                id:id,
                _token: csrfToken
            },
            success: function(data) {
                $('#global-loader').hide();
                if(data.qty_status==1)
                {
                    $('#damag_qty_div').html(data.qty_div);
                    $('#damage_qty_modal').modal('show');
                }
                else
                {
                    show_notification('error', '<?php echo trans('messages.stock_out_lang',[],session('locale')); ?>');
                }

            },
            error: function(data) {
                $('#global-loader').hide();
                after_submit();
                show_notification('error', 'get data failed');
                console.log(data);
                return false;
            }
        });
    }

    // get purchase payment
    function undo_damage_product(id)
    {
        $('#global-loader').show();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "<?php echo url('undo_damage_product'); ?>",
            method: "POST",
            data: {
                id:id,
                _token: csrfToken
            },
            success: function(data) {
                $('#global-loader').hide();
                if(data.qty_status==1)
                {
                    $('#undo_damag_qty_div').html(data.qty_div);
                    $('#undo_damage_qty_modal').modal('show');
                }
                else
                {
                    show_notification('error', 'This product do not have damage quantity');
                }

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

     //new

    $(document).ready(function() {
    $('.add_product').off().on('submit', function(e){
        e.preventDefault();
        var formdatas = new FormData($('.add_product')[0]);
        var title = $('.product_name').val();
        var title_ar = $('.product_name_ar').val();
        var title = $('.category_id').val();
        var title = $('.brand_id').val();
        var title = $('.sale_price').val();
        var cost = $('.min_sale_price').val();
        var quick_sale = $('.quick_sale').prop('checked') ? 0 : 1;

        $('#global-loader').show();
        before_submit();
        var str = $(".add_product").serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo url('update_product'); ?>",
            data: formdatas,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#global-loader').hide();
                after_submit();
                show_notification('success','<?php echo trans('messages.data_update_success_lang',[],session('locale')); ?>');
                $('#add_product_modal').modal('hide');
                $('#all_product').DataTable().ajax.reload();
                return false;
            },
            error: function(data) {
                $('#global-loader').hide();
                after_submit();
                show_notification('error','<?php echo trans('messages.data_update_failed_lang',[],session('locale')); ?>');
                $('#all_product').DataTable().ajax.reload();
                console.log(data);
                return false;
            }
        });
    });


    $('.add_replace_product_form').off().on('submit', function(e){
        e.preventDefault();
        var formdatas = new FormData($('.add_replace_product_form')[0]);
        var current_imei = $('.current_imei').val();
        var new_imei = $('.new_imei').val();
        var order_no = $('.order_no').val();
        var replace_notes = $('.replace_notes').val();
        if(current_imei == "")
        {
            show_notification('error','<?php echo trans('messages.validation_current_imei_lang',[],session('locale')); ?>');
            return false;
        }
        if(new_imei == "")
        {
            show_notification('error','<?php echo trans('messages.validation_new_imei_lang',[],session('locale')); ?>');
            return false;
        }
        if(replace_notes == "")
        {
            show_notification('error','<?php echo trans('messages.validation_notes_lang',[],session('locale')); ?>');
            return false;
        }

        $('#global-loader').show();
        before_submit();
        var str = $(".add_replace_product_form").serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo url('add_replace_product'); ?>",
            data: formdatas,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#global-loader').hide();
                after_submit();
                if(data.status == 1)
                {
                    show_notification('success','<?php echo trans('messages.replaced_successfully_lang',[],session('locale')); ?>');
                    $('#add_replace_pro_modal').modal('hide');
                    $('#all_product').DataTable().ajax.reload();
                    return false;
                }
                else if(data.status == 2)
                {
                    show_notification('error','<?php echo trans('messages.imei_not_exist_lang',[],session('locale')); ?>');
                    return false;
                }
                else if(data.status == 3)
                {
                    show_notification('error','<?php echo trans('messages.imei_duplicate_lang',[],session('locale')); ?>');
                     return false;
                }
            },
            error: function(data) {
                $('#global-loader').hide();
                after_submit();
                show_notification('error','<?php echo trans('messages.data_update_failed_lang',[],session('locale')); ?>');
                $('#all_product').DataTable().ajax.reload();
                console.log(data);
                return false;
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
            url : "<?php echo url('edit_product'); ?>",
            method : "POST",
            data :   {id:id,_token: csrfToken},
            success: function(fetch) {
                $('#global-loader').hide();
                after_submit();
                if(fetch!=""){

                    $(".product_name").val(fetch.product_name);
                    $(".product_id").val(id);
                    $(".product_name_ar").val(fetch.product_name_ar);
                    $(".brand_id").val(fetch.brand_id);
                    $(".category_id").val(fetch.category_id);
                    $(".sale_price").val(fetch.sale_price);
                    $(".min_sale_price").val(fetch.min_sale_price);
                    if (fetch.quick_sale==1) {
                        $(".quick_sale").prop('checked', true);
                    } else {
                        $(".quick_sale").prop('checked', false);
                    }

                    $(".modal-title").html('<?php echo trans('messages.update_lang',[],session('locale')); ?>');
                }
            },
            error: function(html)
            {
                $('#global-loader').hide();
                after_submit();
                show_notification('error','<?php echo trans('messages.edit_failed_lang',[],session('locale')); ?>');
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
