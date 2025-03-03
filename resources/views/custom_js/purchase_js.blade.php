<script>
    $(document).ready(function() {

        $('.add_purchase_product').off().on('submit', function(e) {


            e.preventDefault();
            var formdatas = new FormData($('.add_purchase_product')[0]);
            var supplier_id = $('#supplier_id').val();
            var invoice_no = $('.invoice_no').val();
            var purchase_date = $('.purchase_date').val();
            var invoice_price = $('.invoice_price').val();


            if (invoice_no == "") {
                show_notification('error', '<?php echo trans('messages.provide_invoice#_lang', [], session('locale')); ?>');
                return false;
            }
            if (supplier_id == "") {
                show_notification('error', '<?php echo trans('messages.provide_supplier_first_lang', [], session('locale')); ?>');
                return false;
            }
            if (purchase_date == "") {
                show_notification('error', '<?php echo trans('messages.provide_purchase_date_lang', [], session('locale')); ?>');
                return false;
            }

            if (invoice_price == "") {
                show_notification('error', '<?php echo trans('messages.provide_invoice_price_lang', [], session('locale')); ?>');
                return false;
            }

            var stocks_class = $('.stocks_class').length;

            for (var i = 1; i <= stocks_class; i++) {

            if ($('#store_id_' + i).val() == "") {
            show_notification('error', i + ' ' + '<?php echo trans('messages.provide_store_lang', [], session('locale')); ?>');
            return false;
        }


        if ($('.product_name_' + i).val() == "" && $('.product_name_ar_' + i).val() == "") {
            show_notification('error', +i + '<?php echo trans('messages.provide_product_name_lang', [], session('locale')); ?>');
            return false;
        }
        if ($('.barcode_' + i).val() == "") {
            show_notification('error', +i + '<?php echo trans('messages.provide_barcode_lang', [], session('locale')); ?>');
            return false;
        }
        if ($('.purchase_price_' + i).val() == "") {
            show_notification('error', +i + '<?php echo trans('messages.provide_purchase_price_lang', [], session('locale')); ?>');
            return false;
        }

        if ($('.quantity_' + i).val() == "") {
            show_notification('error', +i + '<?php echo trans('messages.provide_quantity_lang', [], session('locale')); ?>');
            return false;
        }
        if ($('.notification_limit_' + i).val() == "") {
            show_notification('error', +i + '<?php echo trans('messages.provide_notification_limit_first_lang', [], session('locale')); ?>');
            return false;
        }


}


// Store entered barcodes in an object
var enteredBarcodes = {};
var duplicate_barcodes = '';
$('.barcodes').each(function() {
    var barcode = $(this).val();
    if (enteredBarcodes.hasOwnProperty(barcode)) {
        duplicate_barcodes = duplicate_barcodes + barcode + ', '
    } else {
        enteredBarcodes[barcode] = true;
    }
});

before_submit();
$('#global-loader').show();

if (duplicate_barcodes != "") {
    show_notification('error', duplicate_barcodes + '<?php echo trans('messages.duplicate barcode_lang', [], session('locale')); ?>');
    $('#global-loader').hide();
    after_submit();
    return false;
}




var str = $(".add_purchase_product").serialize();

$.ajax({
    type: "POST",
    url: "<?php echo url('add_purchase_product'); ?>",
    data: formdatas,
    contentType: false,
    processData: false,
    success: function(html) {
        $('#global-loader').hide();
        if (html.status == 2) {
            show_notification('error', '<?php echo trans('messages.invoice_no_already_exists_lang', [], session('locale')); ?>');
            after_submit();
            return false;
        }

        $(".add_purchase_product")[0].reset();
        $('#more_stk').html("");
        after_submit();
        show_notification('success', '<?php echo trans('messages.purchase_added_success_lang', [], session('locale')); ?>');
        // location.reload();
    },
    error: function(html) {
        show_notification('error', '<?php echo trans('messages.purchase_add_failed_lang', [], session('locale')); ?>');
        console.log(html);
    }
});
});

        $(document).on('click', '#add_more_stk_btn', function(e) {

            show_notification('success', '<?php echo trans('messages.new_stock_added_lang', [], session('locale')); ?>');
            var count = $('.stocks_class').length;
            count = count + 1;
            $('#more_stk').append(`
                        <hr>
                        <div class="stocks_class stock_no_${count}">
                                <div class="row">
                                    <div class="col-md-2">
                                        <h5> stock ${count}</h1>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="item_remove"><i class="fa fa-trash fa-3x"></i></a>
                                    </div>

                                </div>

                            <div class="row p-2">
                            <div class="col-xl-3">
                                <div class="form-group">
                                    <label class="col-form-label">stores:</label>
                                    <select class="form-control select2 store_id_${count}" id="store_id_${count}" name="store_id_stk[]">
                                        <option value="">choose</option>
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->branch_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                                <div class="col-xl-3">
                                <div class="form-group">
                                    <label class="col-form-label">Category:</label>
                                    <select class="category_id_${count} form-control select2" id="category_id_${count}" name="category_id_stk[]">
                                        <option value="">choose</option>
                                        @foreach ($categorys as $category)
                                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-3">
                                <div class="form-group">
                                    <label class="col-form-label">Product_name(en):</label>
                                    <input type="text" class="form-control product_name_${count}" name="product_name[]">
                                </div>
                            </div>

                            <div class="col-xl-3">
                                <div class="form-group">
                                    <label class="col-form-label">Barcode Generator:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control barcodes barcode_${count}"
                                            onkeyup="search_barcode('1')"
                                            onchange="search_barcode('1')"
                                            name="barcode[]"
                                            >

                                        <span class="input-group-text " >
                                            <a onclick="get_rand_barcode(${count})">
                                                <i class="fas fa-barcode"></i>
                                            </a>
                                        </span>
                                    </div>
                                    <span class="barcode_err_${count} text-danger small"></span>
                                </div>
                            </div>

                            </div>


                            <div class="row p-2">
                                <div class="col-xl-3">
                                <div class="form-group">
                                    <label class="col-form-label">Purchase Price:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">OMR</span>
                                        <input type="text" class="form-control purchase_price_${count} isnumber" onkeyup="calculateTotalPurchasePrice(${count})" onkeyup="get_profit_percent(1)" name="purchase_price[]">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Sales Price:</label>
                                        <div class="input-group">
                                            <span class="input-group-text">OMR</span>
                                            <input type="text" class="form-control sale_price_${count} isnumber"
                                                id="sale_price_${count}" onkeyup="updateSalesPrice(${count})"
                                                name="sale_price[]">
                                        </div>
                                    </div>
                                </div>

                            <div class="col-xl-3">
                                <div class="form-group">
                                    <label class="col-form-label">Quantity:</label>
                                    <input type="text" class="form-control quantity_${count} isnumber1" onkeyup="check_qty(1)" name="quantity[]">
                                </div>
                            </div>

                                <div class="col-xl-3">
                                <div class="form-group">
                                    <label class="col-form-label">Tax:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">%</span>
                                        <input type="text" class="form-control tax_${count} isnumber" id="tax_${count}" onkeyup="updateSalesPriceWithTax(${count})" name="tax[]">
                                    </div>
                                </div>
                            </div>

                            </div>

                            <div class="row ">
                            <div class="col-xl-4 position-relative mt-4">
                                <div class="form-group">
                                    <label class="col-form-label">upload_image</label>

                                    <!-- Hidden File Input -->
                                    <input type="file" class="d-none image" name="stock_image_${count}" id="stock_img_${count}" accept="image/*"
                                        onchange="previewImage(event, 'stock_img_tag_${count}', 'remove_stock_img')">

                                    <!-- Clickable Image Preview -->
                                    <label for="stock_img_${count}">
                                        <img src="{{ asset('images/dummy_images/no_image.jpg') }}" id="stock_img_tag_${count}"
                                            class="img-thumbnail mt-2" style="width: 100px; cursor: pointer;">
                                    </label>

                                    <!-- Remove Button (X) -->
                                    <span id="remove_stock_img" class="position-absolute top-0 end-0 bg-danger text-white rounded-circle px-2"
                                        style="cursor: pointer; display: none;" onclick="removeImage('stock_img_tag_${count}', 'stock_img_${count}', 'remove_stock_img')">
                                        &times;
                                    </span>
                                </div>
                            </div>
                            <div class="col-xl-5">
                                <div class="form-group">
                                    <label class="col-form-label">Description:</label>
                                    <textarea class="form-control description_${count}" name="description[]" rows="3"></textarea>
                                </div>
                            </div>

                                <div class="col-xl-3 mt-3">
                            <div class="form-group">
                                <label class="col-form-label">Product Type:</label>
                                <div class="form-check">
                                    <input class="form-check-input product_sale_${count}" type="radio" name="product_type_${count}" id="product_sale_${count}" value="2">
                                    <label class="form-check-label" for="product_sale_${count}">Sale</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input product_sale_${count}" type="radio" name="product_type_${count}" id="product_clinic_${count}" value="1">
                                    <label class="form-check-label" for="product_clinic_${count}">Clinic</label>
                                </div>
                            </div>
                            </div>


                            </div>
                            </div>`);





            // clone select boxes
            var last_count = count - 1;



            $('.store_id_' + count).select2();
            get_selected_new_data(count, 'store')
            var divs = $('.stocks_class');

            divs.each(function(index) {
                var h1 = $(this).find('.pro_number');
                h1.text('<?php echo trans('messages.stock_lang', [], session('locale')); ?> ' + (index + 1));
            });


        });
        $(document).on('click', '.item_remove', function(e) {
            e.preventDefault();
            $(this).closest('.stocks_class').remove();
            var divs = $('.stocks_class');
            // Assign numbers based on their order
            divs.each(function(index) {
                var h1 = $(this).find('.pro_number');
                h1.text('<?php echo trans('messages.stock_lang', [], session('locale')); ?>' + (index + 1));
            });
            $('.invoice_price').keyup()
            show_notification('success', '<?php echo trans('messages.stock_area_removed_lang', [], session('locale')); ?>');
        });


        function search_barcode(i) {
            var enteredBarcodes = {};
            var duplicate_barcodes = '';
            $('.barcodes').each(function() {
                var barcode = $(this).val();
                if (enteredBarcodes.hasOwnProperty(barcode)) {
                    duplicate_barcodes = duplicate_barcodes + barcode + ', '
                } else {
                    enteredBarcodes[barcode] = true;
                }
            });

            if (duplicate_barcodes != "") {
                show_notification('error', duplicate_barcodes + '<?php echo trans('messages.duplicate barcode_lang', [], session('locale')); ?>');
                return false;
            }
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $('.barcode_' + i).autocomplete({
                autoFocus: true,
                source: function(request, response) {
                    $.ajax({
                        url: "<?php echo url('search_barcode'); ?>",
                        dataType: 'json',
                        data: {
                            search: request.term,
                            _token: csrfToken
                        },
                        success: function(data) {
                            $('.barcode_err_' + i).html('');
                            response(data);
                        }
                    });
                },
                select: function(event, ui) {
                    $.ajax({
                        dataType: 'JSON',
                        url: "<?php echo url('get_product_data'); ?>",
                        method: "POST",
                        data: {
                            result: ui.item.value,
                            _token: csrfToken
                        },
                        success: function(data) {
                            if (data != "") {
                                setTimeout(function() {
                                    $('.category_id_' + i).val(data.category_id)
                                        .trigger(
                                            'change');
                                    $('.store_id_' + i).val(data.store_id)
                                        .trigger(
                                            'change');

                                }, 1000);
                                $(".product_name_" + i).val(data.product_name);
                                $(".barcode_" + i).val(data.barcode);
                                $(".purchase_price_" + i).val(data.purchase_price);
                                $(".sale_price_" + i).val(data.sale_price);
                                $(".tax_" + i).val(data.tax);

                                $('input[type="radio"][name="product_type_' + i +
                                    '"][value="' +
                                    data.product_type + '"]').prop('checked', true);


                                $(".quantity_old_" + i).val(data.quantity);
                                $(".sale_price_old_" + i).val(data.sale_price);
                                $(".description_" + i).val(data.description);
                                var imagePath = '<?php echo asset('images/dummy_image/no_image.png'); ?>';
                                $('#stock_img_tag_' + i).attr('src', imagePath);
                                if (data.stock_image != "") {
                                    imagePath = '<?php echo asset('images/product_images/'); ?>/' + data
                                        .stock_image;
                                    $('#stock_img_tag_' + i).attr('src', imagePath);
                                }

                                // get the total and total purchase
                                $('.all_purchase_price').trigger('keyup');

                            }
                        }
                    });
                }
            });
        }

        // $('.add_supplier').off().on('submit', function(e) {
        //     e.preventDefault();
        //     var formdatas = new FormData($('.add_supplier')[0]);
        //     var title = $('.supplier_name').val();
        //     var phone = $('.supplier_phone').val();
        //     var id = $('.supplier_id').val();

        //     if (id == '') {


        //         if (title == "") {
        //             show_notification('error', '<?php echo trans('messages.add_supplier_name_lang', [], session('locale')); ?>');
        //             return false;

        //         }
        //         if (phone == "") {
        //             show_notification('error', '<?php echo trans('messages.add_supplier_phone_lang', [], session('locale')); ?>');
        //             return false;
        //         }
        //         $('#global-loader').show();
        //         before_submit();
        //         var str = $(".add_supplier").serialize();
        //         $.ajax({
        //             type: "POST",
        //             url: "<?php echo url('add_supplier'); ?>",
        //             data: formdatas,
        //             contentType: false,
        //             processData: false,
        //             success: function(data) {
        //                 $('#global-loader').hide();
        //                 after_submit();
        //                 $('#all_supplier').DataTable().ajax.reload();
        //                 show_notification('success', '<?php echo trans('messages.data_add_success_lang', [], session('locale')); ?>');
        //                 $('#add_supplier_modal').modal('hide');
        //                 $(".add_supplier")[0].reset();
        //                 get_selected_new_data(1, 'supplier')
        //                 setTimeout(function() {
        //                     $('.supplier_id').val(data.supplier_id).trigger(
        //                         'change');
        //                 }, 2000);
        //                 return false;
        //             },
        //             error: function(data) {
        //                 $('#global-loader').hide();
        //                 after_submit();
        //                 show_notification('error', '<?php echo trans('messages.data_add_failed_lang', [], session('locale')); ?>');
        //                 $('#all_supplier').DataTable().ajax.reload();

        //                 return false;
        //             }
        //         });

        //     }

        // });
        // supplier
    });
    // stock_number
    function stock_number(i) {
        $('.stock_number').val(i);
    }
    //
    //get new selected values
    function get_selected_new_data(i, type) {
        $('#global-loader').show();
        before_submit();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "<?php echo url('get_selected_new_data'); ?>",
            method: "POST",
            data: {
                _token: csrfToken
            },
            success: function(data) {
                $('#global-loader').hide();
                after_submit();
                // Access data and populate select boxes

                if (type == "supplier") {
                    $('.supplier_id').html(data.suppliers);
                } else {
                    var total_stock = $('.stocks_class').length;
                    for (let index = 1; index <= total_stock; index++) {
                        if (type == "brand") {
                            var this_brand_id = $('.brand_id_' + index).val();
                            $('.brand_id_' + index).html(data.brands);
                            if (i != index) {
                                $('.brand_id_' + index).val(this_brand_id).trigger('change');
                            }
                        } else if (type == "category") {
                            var this_category_id = $('.category_id_' + index).val();
                            $('.category_id_' + index).html(data.categories);
                            if (i != index) {
                                $('.category_id_' + index).val(this_category_id).trigger('change');
                            }
                        } else if (type == "store") {
                            var this_store_id = $('.store_id_' + index).val();
                            $('.store_id_' + index).html(data.stores);
                            if (i != index) {
                                $('.store_id_' + index).val(this_store_id).trigger('change');
                            }
                        }
                    }
                }

            },
            error: function(data) {
                $('#global-loader').hide();
                after_submit();
                show_notification('error', '<?php echo trans('messages.get_data_failed_lang', [], session('locale')); ?>');

                return false;
            }
        });
    }
    // get barcode random
    function get_rand_barcode(i) {
        var randomNumber = Math.floor(100000 + Math.random() * 900000);
        $('.barcode_' + i).val(randomNumber);
    }
    //

    // check tax type
    $('input[name="tax_type"]').change(function() {
        var selectedTaxType = $('input[name="tax_type"]:checked').val();
        if (selectedTaxType == 1) {
            $('.refund_non_refund_tax_div').show();
            $('input[name="available_tax_type"][value="1"]').prop('checked', true);
            $('.bulk_tax').val('');
            $('.all_tax').val('');
            $('input[name="available_tax_type"]').trigger('change');
        } else {
            $('.refund_non_refund_tax_div').hide();
            $('input[name="available_tax_type"]').prop('checked', false);
            $('.bulk_tax').val('');
            $('.all_tax').val('');
        }
        $('.invoice_price').keyup();
    });
    //

    // check tax avaiable type type
    <?php if(isset($active_tax)){ ?>
    var no_active_tax = '<?php echo $active_tax; ?>';
    <?php } else { ?>
    var no_active_tax = 0;
    <?php } ?>
    $('input[name="available_tax_type"]').change(function() {
        var bulk_tax = $('.bulk_tax').val();
        var available_type = $('input[name="available_tax_type"]:checked').val();
        $('.all_tax').val(0)
        $('.all_tax').keyup()
        if (available_type == 1) {
            $('.invoice_price').keyup();
        } else {
            $('#global-loader').show();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "<?php echo url('check_tax_active'); ?>",
                method: "POST",
                data: {
                    _token: csrfToken
                },
                success: function(data) {
                    $('#global-loader').hide();
                    // Access data and populate select boxes
                    if (data.status == 1) {
                        $('.invoice_price').keyup();
                        no_active_tax = 1;
                    } else {
                        $('.all_tax').val(bulk_tax)
                        $('.all_tax').keyup()
                        no_active_tax = 2;
                    }
                },
                error: function(data) {
                    $('#global-loader').hide();
                    show_notification('error', '<?php echo trans('messages.get_data_failed_lang', [], session('locale')); ?>');

                    return false;
                }
            });

        }
    });
    //



    // check qty
    function check_qty(i) {
        if ($('.quantity_' + i).val() == "" || $('.quantity_' + i).val() <= 0) {
            $('.quantity_' + i).val(1);
            $('.quantity_' + i).trigger('keyup');
        }
    }
    //





    // get profit percent
    function get_profit_percent(i) {
        setTimeout(function() {
            var purchase_price = $('.total_purchase_price_' + i).val();
            if (purchase_price == "") {
                purchase_price = 0;
            }

            var sale_price = $('.sale_price_' + i).val();
            if (sale_price == "") {
                sale_price = 0;
            }

            // Convert to numeric values
            purchase_price = parseFloat(purchase_price);
            sale_price = parseFloat(sale_price);

            // Calculate profit percent
            var profit = sale_price - purchase_price;
            var profit_percent = 100 * profit / purchase_price;

            $('.profit_percent_' + i).val(three_digit_after_decimal(profit_percent));
        }, 2000);
    }

    // tags input
    if ($('.total_product').val() != "") {
        for (let im = 1; im <= $('.total_product').val(); im++) {
            $(".imei_no_" + im).tagsinput();
        }
    }
    $(".imei_no_1").tagsinput();




    // get total purchase price and total tax
    $('body').on('change , keyup',
        '.bulk_tax, .all_total_purchase_price, .all_purchase_price, .shipping_cost, .invoice_price, .all_tax, .all_qty',
        function() {
            var totalTax = 0;

            var shipping_final = 0;
            var totalPurchasePrice = 0;
            var total_qty = 0;
            var tax_type = $('input[name="tax_type"]:checked').val();
            var available_type = $('input[name="available_tax_type"]:checked').val();
            var bulk_tax = $('.bulk_tax').val();
            if (bulk_tax == "") {
                bulk_tax = 0;
            }
            get_pro_purchase();
            // Loop through all elements with class 'all_purchase_price'
            setTimeout(function() {
                $('.all_total_purchase_price').each(function() {

                    // Get the closest parent row of the purchase price input
                    var row = $(this).closest('.row');
                    // purchase price
                    var purchase_value = row.find('.all_purchase_price');
                    var purchase_price = parseFloat(purchase_value.val()) || 0;
                    console.log(purchase_price)
                    // old total purchase price
                    var total_purchase_old_value = row.find('.all_total_purchase_price_old');
                    var total_purchase_price_old = parseFloat(total_purchase_old_value.val()) || 0;

                    // old total purchase price
                    var sale_price_old_value = row.next('.row').find('.sale_price_old');
                    var old_sale_price = parseFloat(sale_price_old_value.val()) || 0;

                    // old total purchase price
                    var sale_price_new_value = row.next('.row').find('.all_sale_price');
                    var new_sale_price = parseFloat(sale_price_new_value.val()) || 0;

                    // Find the next row and get the value of all_qty
                    var qty_value = row.next('.row').find('.all_qty');
                    var total_qty = parseFloat(qty_value.val()) || 0;

                    // old qty
                    var qty_old_value = row.next('.row').find('.quantity_old');
                    var total_qty_old = parseFloat(qty_old_value.val()) || 0;

                    var inputValue = parseFloat($(this).val()) || 0;
                    totalPurchasePrice += inputValue * total_qty;
                    console.log(inputValue * total_qty)

                    // Find the corresponding tax input by going up to the parent row and then finding the tax input within the same row
                    var taxInput = row.find('.all_tax');


                    var taxValue = parseFloat(taxInput.val()) || 0;



                    var new_tax_expense = 0;
                    var before_shipping_purchase_price = 0;
                    if (tax_type == 1) {
                        if (available_type == 1) {
                            new_tax_expense = purchase_price / 100 * bulk_tax;
                            totalTax += three_digit_after_decimal(new_tax_expense) * total_qty;
                            $('.all_tax').val(0)
                            before_shipping_purchase_price = purchase_price + new_tax_expense;
                        } else if (available_type == 2 && no_active_tax == 1) {
                            new_tax_expense = purchase_price / 100 * bulk_tax;
                            totalTax += three_digit_after_decimal(new_tax_expense) * total_qty;
                            $('.all_tax').val(0)
                            before_shipping_purchase_price = purchase_price + new_tax_expense;
                        } else if (available_type == 2 && no_active_tax == 2) {

                            taxValue = purchase_price / 100 * taxValue;
                            totalTax += taxValue * total_qty;
                            $('.all_tax').val(bulk_tax)
                            before_shipping_purchase_price = purchase_price;
                        }
                    } else {
                        taxValue = 0;
                        totalTax = 0;
                        $('.all_tax').val(0)
                        before_shipping_purchase_price = purchase_price;
                    }


                    // calculate shipping percentage

                    var shippping_percentage = $('.shipping_percentage ').val();
                    if (shippping_percentage <= 0) {
                        shippping_percentage = 0;
                    }
                    if (inputValue > 0) {
                        if (isNaN(shippping_percentage)) {


                            shipping_final += 0;
                        } else {
                            console.log(before_shipping_purchase_price)

                            shipping_final_before = three_digit_after_decimal(
                                before_shipping_purchase_price) * two_digit_after_decimal(
                                shippping_percentage);
                            shipping_final += three_digit_after_decimal(shipping_final_before /
                                100) * total_qty;
                        }
                    } else {
                        if (isNaN(shippping_percentage)) {

                            shipping_final += 0;
                        } else {

                            shipping_final += 0;
                        }
                    }


                    // total purchase
                    var grand_purchase_price = row.find('.grand_purchase_price');
                    grand_purchase_price.text(three_digit_after_decimal(inputValue * total_qty))

                    // get average purchase and sale price

                    var final_qty = total_qty + total_qty_old;
                    average_purchase = (inputValue * total_qty) + (total_qty_old *
                        total_purchase_price_old);
                    var average_purchase_price = row.find('.average_purchase_price');
                    average_purchase_price.text(three_digit_after_decimal(average_purchase /
                        final_qty))

                    average_sale_price = (new_sale_price * total_qty) + (total_qty_old *
                        old_sale_price);
                    var average_sale_price_price = row.next('.row').find('.average_sale_price');
                    average_sale_price_price.text(three_digit_after_decimal(average_sale_price /
                        final_qty))


                });

                // Update the totals in the HTML
                $('#total_tax').text(totalTax.toFixed(3));
                $('#total_price').text(totalPurchasePrice.toFixed(3));
                $('#total_shipping').text(shipping_final.toFixed(3));
                $('#total_tax_input').val(totalTax.toFixed(3));
                $('#total_price_input').val(totalPurchasePrice.toFixed(3));
                $('#total_shipping_input').val(shipping_final.toFixed(3));

            }, 1000); // 1000 milliseconds = 1 second
        });

    // keyup shiping cost and invoice price
    function get_pro_purchase() {
        var tax_type = $('input[name="tax_type"]:checked').val();
        var available_type = $('input[name="available_tax_type"]:checked').val();
        var bulk_tax = $('.bulk_tax').val();
        if (bulk_tax == "") {
            bulk_tax = 0;
        }

        var count = $('div.stocks_class').length;

        var i = 0;
        for (var z = 0; z < count; z++) {
            i++;
            var purchase_price = $('.purchase_price_' + i).val();
            if (purchase_price == "") {
                purchase_price = 0;
            }

            var profit_percent = $('.profit_percent_' + i).val();
            if (profit_percent == "") {
                profit_percent = 0;
            }

            var tax = $('.tax_' + i).val();
            if (tax == "") {
                tax = 0;
            }

            var invoice_price = $('.invoice_price').val();
            if (invoice_price == "") {
                invoice_price = 0;
            }

            var shipping_cost = $('.shipping_cost').val();
            if (shipping_cost == "") {
                shipping_cost = 0;
            }



            // Convert to numeric values
            purchase_price = parseFloat(purchase_price);
            profit_percent = parseFloat(profit_percent);
            invoice_price = parseFloat(invoice_price);
            shipping_cost = parseFloat(shipping_cost);
            tax = parseFloat(tax);



            var final_purchase_price = 0;
            if (tax_type == 1) {
                if (available_type == 1) {
                    final_purchase_price = purchase_price / 100 * bulk_tax;
                } else if (available_type == 2 && no_active_tax == 1) {
                    final_purchase_price = purchase_price / 100 * bulk_tax;

                } else if (available_type == 2 && no_active_tax == 2) {

                    final_purchase_price = 0;

                }
            } else {
                final_purchase_price = 0;
            }


            if (isNaN(final_purchase_price)) {
                final_purchase_price = 0;
            }


            $('.total_purchase_price_' + i).val(three_digit_after_decimal(purchase_price + final_purchase_price));
            var total_purchase_price = $('.total_purchase_price_' + i).val();
            if (total_purchase_price == "") {
                total_purchase_price = 0;
            }

            // calculate shipping percentage
            var shipping_final = 0;
            var shippping_percentage = shipping_cost / invoice_price * 100;
            if (total_purchase_price > 0) {
                if (isNaN(shippping_percentage)) {
                    shippping_percentage = 0;
                    $('.shipping_percentage').val(two_digit_after_decimal(shippping_percentage));
                    shipping_final = 0;
                } else {
                    $('.shipping_percentage').val(two_digit_after_decimal(shippping_percentage));
                    shipping_final = total_purchase_price / 100 * two_digit_after_decimal(shippping_percentage);
                }
            } else {
                if (isNaN(shippping_percentage)) {
                    shippping_percentage = 0;
                    $('.shipping_percentage').val(two_digit_after_decimal(shippping_percentage));
                    shipping_final = 0;
                } else {
                    $('.shipping_percentage').val(two_digit_after_decimal(shippping_percentage));
                    shipping_final = 0;
                }
            }

            $('.total_purchase_price_' + i).val(three_digit_after_decimal(parseFloat(total_purchase_price) + parseFloat(
                shipping_final)));
            var total_purchase_price = $('.total_purchase_price_' + i).val();
            // Calculate profit percent
            get_profit_percent(i)

        }


    }

    //
    // add purchase product



    //

    // update purhase
    $('.update_purchase').off().on('submit', function(e) {

        e.preventDefault();
        var formdatas = new FormData($('.update_purchase')[0]);
        var supplier_id = $('.supplier_id').val();
        var invoice_no = $('.invoice_no').val();
        var purchase_date = $('.purchase_date').val();
        var shipping_cost = $('.shipping_cost').val();
        var invoice_price = $('.invoice_price').val();


        // invoice validation
        if (invoice_no == "") {
            show_notification('error', '<?php echo trans('messages.provide_invoice#_lang', [], session('locale')); ?>');
            return false;
        }
        if (supplier_id == "") {
            show_notification('error', '<?php echo trans('messages.provide_supplier_first_lang', [], session('locale')); ?>');
            return false;
        }
        if (purchase_date == "") {
            show_notification('error', '<?php echo trans('messages.provide_purchase_date_lang', [], session('locale')); ?>');
            return false;
        }
        if (shipping_cost == "") {
            show_notification('error', '<?php echo trans('messages.provide_shipping_cost_lang', [], session('locale')); ?>');
            return false;
        }
        if (invoice_price == "") {
            show_notification('error', '<?php echo trans('messages.provide_invoice_price_lang', [], session('locale')); ?>');
            return false;
        }

        // product validation
        var stocks_class = $('.stocks_class').length;
        // if(stocks_class <=0)
        // {
        //     show_notification('error','<?php echo trans('messages.please_add_product_in_list_lang', [], session('locale')); ?>');
        //     return false;
        // }
        for (var i = 1; i <= stocks_class; i++) {

            if ($('.store_id_' + i).val() == "") {
                show_notification('error', +i + '<?php echo trans('messages.provide_store_lang', [], session('locale')); ?>');
                return false;
            }
            if ($('.category_id_' + i).val() == "") {
                show_notification('error', +i + '<?php echo trans('messages.provide_category_lang', [], session('locale')); ?>');
                return false;
            }
            if ($('.brand_id_' + i).val() == "") {
                show_notification('error', +i + '<?php echo trans('messages.provide_brand_lang', [], session('locale')); ?>');
                return false;
            }
            if ($('.product_name_' + i).val() == "" && $('.product_name_ar_' + i).val() == "") {
                show_notification('error', +i + '<?php echo trans('messages.provide_product_name_lang', [], session('locale')); ?>');
                return false;
            }
            if ($('.barcode_' + i).val() == "") {
                show_notification('error', +i + '<?php echo trans('messages.provide_barcode_lang', [], session('locale')); ?>');
                return false;
            }
            if ($('.purchase_price_' + i).val() == "") {
                show_notification('error', +i + '<?php echo trans('messages.provide_purchase_price_lang', [], session('locale')); ?>');
                return false;
            }
            if ($('.profit_percent_' + i).val() == "") {
                show_notification('error', +i + '<?php echo trans('messages.provide_profit_percent_lang', [], session('locale')); ?>');
                return false;
            }
            if ($('.quantity_' + i).val() == "") {
                show_notification('error', +i + '<?php echo trans('messages.provide_quantity_lang', [], session('locale')); ?>');
                return false;
            }
            if ($('.notification_limit_' + i).val() == "") {
                show_notification('error', +i + '<?php echo trans('messages.provide_notification_limit_first_lang', [], session('locale')); ?>');
                return false;
            }
            if ($('input[name="warranty_type_' + i + '"]:checked').val() != 3) {
                if ($('.warranty_days_' + i).val() == "") {
                    show_notification('error', +i + '<?php echo trans('messages.provide_warranty_days_lang', [], session('locale')); ?>');
                    return false;
                }
            }
            if ($('#whole_sale_' + i).is(':checked')) {
                if ($('.bulk_quantity_' + i).val() == "") {
                    show_notification('error', +i + '<?php echo trans('messages.provide_bulk_quantity_lang', [], session('locale')); ?>');
                    return false;
                }
                if ($('.bulk_price_' + i).val() == "") {
                    show_notification('error', +i + '<?php echo trans('messages.provide_bulk_price_lang', [], session('locale')); ?>');
                    return false;
                }
            }

            if ($('#imei_check_' + i).is(':checked')) {
                if ($('.imei_no_' + i).val() == "") {
                    show_notification('error', ' ' + i + '<?php echo trans('messages.provide_imei_product_lang', [], session('locale')); ?>');
                    return false;
                }
            }

        }

        var allIMEIs = []; // Array to store all IMEIs
        var enteredIMEIs = {}; // Object to store entered IMEIs
        var duplicate_imeis = ''; // String to store duplicate IMEIs

        // Iterate through each tag input
        $('.tags').each(function() {
            var imeis = $(this).val().split(','); // Split the input value into an array of IMEIs

            // Iterate through each IMEI in the current tag input
            imeis.forEach(function(imei) {
                var trimmedIMEI = imei.trim();
                if (trimmedIMEI !== "") { // Skip empty strings
                    allIMEIs.push(trimmedIMEI); // Push IMEI to the array
                    if (enteredIMEIs.hasOwnProperty(trimmedIMEI)) {
                        enteredIMEIs[trimmedIMEI]++;
                    } else {
                        enteredIMEIs[trimmedIMEI] = 1;
                    }
                }
            });
        });

        // Iterate through all IMEIs to find duplicates
        for (var imei in enteredIMEIs) {
            if (enteredIMEIs.hasOwnProperty(imei) && enteredIMEIs[imei] > 1) {
                duplicate_imeis += imei + ', '; // Append duplicate IMEI to the string
            }
        }

        // If there are duplicate IMEIs, show error notification and return false
        if (duplicate_imeis !== "") {
            show_notification('error', duplicate_imeis + '<?php echo trans('messages.duplicate_imei_lang', [], session('locale')); ?>');
            $('#global-loader').hide(); // Hide loader
            after_submit(); // Call after_submit function
            return false; // Return false
        }

        // Store entered barcodes in an object
        var enteredBarcodes = {};
        var duplicate_barcodes = '';
        $('.barcodes').each(function() {
            var barcode = $(this).val();
            if (enteredBarcodes.hasOwnProperty(barcode)) {
                duplicate_barcodes = duplicate_barcodes + barcode + ', '
            } else {
                enteredBarcodes[barcode] = true;
            }
        });

        before_submit();
        $('#global-loader').show();

        if (duplicate_barcodes != "") {
            show_notification('error', duplicate_barcodes + '<?php echo trans('messages.duplicate barcode_lang', [], session('locale')); ?>');
            $('#global-loader').hide();
            after_submit();
            return false;
        }
        var str = $(".update_purchase").serialize();

        $.ajax({
            type: "POST",
            url: "<?php echo url('update_purchase'); ?>",
            data: formdatas,
            contentType: false,
            processData: false,
            success: function(html) {
                $('#global-loader').hide();

                if (html.status == 3) {
                    show_notification('error', html.duplicate_imeis + ' <?php echo trans('messages.duplicate_imei_lang', [], session('locale')); ?>');
                    after_submit();
                    return false;
                }
                after_submit();
                show_notification('success', '<?php echo trans('messages.purchase_update_success_lang', [], session('locale')); ?>');
                // location.reload();
            },
            error: function(html) {
                show_notification('error', '<?php echo trans('messages.purchase_update_failed_lang', [], session('locale')); ?>');
                console.log(html);
            }
        });
    });

    //

    // search invoice no
    $('.invoice_no').keyup(function() {
        var invoice_no = $('.invoice_no').val();
        $('.invoice_err').html(
            '<span class="text text-warning"> <?php echo trans('messages.checking_invoice#_lang', [], session('locale')); ?> <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span>'
        );
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "<?php echo url('search_invoice'); ?>",
            method: "POST",
            data: {
                search: $('.invoice_no').val(),
                _token: csrfToken
            },
            success: function(data) {
                if (data.error_code == 1) {
                    Swal.fire({
                        text: '<?php echo trans('messages.invoice_no_already_exists_lang', [], session('locale')); ?>',
                        type: "warning",
                        showCancelButton: !0,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: '<?php echo trans('messages.yes_lang', [], session('locale')); ?>',
                        confirmButtonClass: "btn btn-primary",
                        cancelButtonClass: "btn btn-danger ml-1",
                        buttonsStyling: !1
                    }).then(function(result) {
                        if (result.value) {
                            window.location.href = '<?php echo url('edit_purchase'); ?>' + '/' + data
                                .purchase_id;
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            $('.invoice_no').val('');
                            $('.invoice_no').keyup();
                        }
                    });
                    $('.invoice_err').html('<span class="text text-danger">' + data.error +
                        '</span>');
                    // $('.submit_form').attr('disabled',true);
                } else {
                    $('.invoice_err').html('');
                    $('.submit_form').attr('disabled', false);
                }

            },
            error: function(data) {
                $('#global-loader').hide();
                after_submit();
                show_notification('error', '<?php echo trans('messages.search_data_failed_lang', [], session('locale')); ?>');
                console.log(data);
                return false;
            }
        });
    });
    //

    // search barcode

    // view_purchase
    $('#all_purchase').DataTable({
        "sAjaxSource": "<?php echo url('show_purchase'); ?>",
        "bFilter": true,
        "sDom": 'fBtlpi',
        'pagingType': 'numbers',
        "ordering": true,
        "language": {
            search: ' ',
            sLengthMenu: '_MENU_',
            searchPlaceholder: '<?php echo trans('messages.search_lang', [], session('locale')); ?>',
            info: "_START_ - _END_ of _TOTAL_ items",
        },
        initComplete: (settings, json) => {
            $('.dataTables_filter').appendTo('#tableSearch');
            $('.dataTables_filter').appendTo('.search-input');
        },

    });

    // approve prodcuts
    function approved_products(id) {
        get_purchase_products(id);
    }
    // approve purchase

    function complete_purchase(id) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        Swal.fire({
            title: '<?php echo trans('messages.sure_lang', [], session('locale')); ?>',
            text: '<?php echo trans('messages.want_complete_purchase_lang', [], session('locale')); ?>',
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: '<?php echo trans('messages.complete_it_lang', [], session('locale')); ?>',
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: !1
        }).then(function(result) {
            if (result.value) {
                $('#global-loader').show();
                $.ajax({
                    url: "<?php echo url('complete_purchase'); ?>",
                    type: 'POST',
                    data: {
                        id: id,
                        _token: csrfToken
                    },
                    error: function() {
                        $('#global-loader').hide();
                        $('#all_purchase').DataTable().ajax.reload();
                        show_notification('error', '<?php echo trans('messages.complete_purchase_lang', [], session('locale')); ?>');
                    },
                    success: function(data) {
                        if (data.msg == 401) {
                            show_notification('error', data.error);
                            $('#global-loader').hide();
                            $('#all_purchase').DataTable().ajax.reload();
                            return false;
                        }
                        $('#global-loader').hide();
                        $('#all_purchase').DataTable().ajax.reload();
                        show_notification('success', '<?php echo trans('messages.complete_purchase_lang', [], session('locale')); ?>');
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                show_notification('success', '<?php echo trans('messages.safe_lang', [], session('locale')); ?>');
            }
        });

    }

    // select all
    $(document).on('click', '#all_select', function(e) {
        // Check if the "All" option is checked
        if ($(this).prop('checked')) {
            // If "All" is checked, select all select boxes with the class "all_products"
            $('.all_products').prop('checked', true);
        } else {
            // If "All" is not checked, deselect all select boxes with the class "all_products"
            $('.all_products').prop('checked', false);
        }
    });

    // get purchase payment
    function get_purchase_products(id) {
        $('#global-loader').show();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "<?php echo url('get_purchase_products'); ?>",
            method: "POST",
            data: {
                id: id,
                _token: csrfToken
            },
            success: function(data) {
                $('#global-loader').hide();
                if (data.msg == 1) {
                    $('#purchase_products_div').html(data.purchase_product_div);
                    $('.approve_purchase_id').val(id)
                    $('#purchase_product_modal').modal('show');
                } else {
                    show_notification('error', '<?php echo trans('messages.validation_no_purchase_product_lang', [], session('locale')); ?>');
                }

            },
            error: function(data) {
                $('#global-loader').hide();
                after_submit();
                show_notification('error', '<?php echo trans('messages.get_data_failed_lang', [], session('locale')); ?>');
                console.log(data);
                return false;
            }
        });
    }
    // add purchase payment
    $('.approved_purchase').off().on('submit', function(e) {
        e.preventDefault();
        var formdatas = new FormData($('.approved_purchase')[0]);
        if ($('.all_products:checked').length <= 0) {
            show_notification('error', '<?php echo trans('messages.at_least_one_pro_check_lang', [], session('locale')); ?>');
            return false;
        }

        $('#global-loader').show();
        before_submit();
        var str = $(".approved_purchase").serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo url('approved_purchase'); ?>",
            data: formdatas,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#global-loader').hide();
                after_submit();
                $('#all_purchase').DataTable().ajax.reload();
                show_notification('success', '<?php echo trans('messages.purchase_approved_lang', [], session('locale')); ?>');
                $('#purchase_product_modal').modal('hide');
                return false;
            },
            error: function(data) {
                $('#global-loader').hide();
                after_submit();
                show_notification('error', '<?php echo trans('messages.data_add_failed_lang', [], session('locale')); ?>');
                $('#all_purchase').DataTable().ajax.reload();
                console.log(data);
                return false;
            }
        });
    });

    // delete purchase
    function del_purchase(id) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        Swal.fire({
            title: '<?php echo trans('messages.sure_lang', [], session('locale')); ?>',
            text: '<?php echo trans('messages.delete_lang', [], session('locale')); ?>',
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: '<?php echo trans('messages.delete_it_lang', [], session('locale')); ?>',
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: !1
        }).then(function(result) {
            if (result.value) {
                $('#global-loader').show();
                $.ajax({
                    url: "<?php echo url('delete_purchase'); ?>",
                    type: 'POST',
                    data: {
                        id: id,
                        _token: csrfToken
                    },
                    error: function() {
                        $('#global-loader').hide();
                        show_notification('error', '<?php echo trans('messages.delete_failed_lang', [], session('locale')); ?>');
                    },
                    success: function(data) {
                        $('#global-loader').hide();
                        $('#all_purchase').DataTable().ajax.reload();
                        show_notification('success', '<?php echo trans('messages.delete_success_lang', [], session('locale')); ?>');
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                show_notification('success', '<?php echo trans('messages.safe_lang', [], session('locale')); ?>');
            }
        });
    }


    // get purchase payment
    function get_purchase_payment(id) {
        $('#global-loader').show();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "<?php echo url('get_purchase_payment'); ?>",
            method: "POST",
            data: {
                id: id,
                _token: csrfToken
            },
            success: function(data) {
                $('#global-loader').hide();
                if (data.remaining_price > 0) {
                    $('.grand_total').val(data.grand_total);
                    $('.remaining_price').val(data.remaining_price);
                    $('.bill_id').val(data.bill_id);
                    $('.purchase_id').val(id);
                    $('#purchase_payment_modal').modal('show');
                } else {
                    show_notification('error', '<?php echo trans('messages.purchase_payment_paid_lang', [], session('locale')); ?>');
                }

            },
            error: function(data) {
                $('#global-loader').hide();
                after_submit();
                show_notification('error', '<?php echo trans('messages.purchase_payment_failed_lang', [], session('locale')); ?>');
                console.log(data);
                return false;
            }
        });
    }

    // check paid amount
    $('.paid_amount').keyup(function() {
        var remaining_price = $('.remaining_price').val();
        if (parseFloat($(this).val()) > parseFloat(remaining_price)) {
            show_notification('error', '<?php echo trans('messages.paid_amount_cannot_be_greater_lang', [], session('locale')); ?>');
            $(this).val("")
            return false;
        }
    });
    //

    // check payment_method
    $('.payment_method').change(function() {
        var selectedOption = $(this).find(':selected');
        var status = selectedOption.attr('status');
        if (status != 1) {
            $('.payment_reference_no_div').show();
            $('.payment_reference_no').val("");
        } else {
            $('.payment_reference_no_div').hide();
            $('.payment_reference_no').val("");
        }
    });
    //

    // add purchase payment
    $('.add_purchase_payment').off().on('submit', function(e) {
        e.preventDefault();
        var formdatas = new FormData($('.add_purchase_payment')[0]);
        var paid_amount = $('.paid_amount').val();
        var payment_date = $('.payment_date').val();
        var purchase_id = $('.purchase_id').val();
        var payment_reference_no = $('.payment_reference_no').val();
        var payment_method_selected = $('.payment_method').find(':selected');
        var account_status = payment_method_selected.attr('status');

        if (paid_amount == "") {
            show_notification('error', '<?php echo trans('messages.add_paid_amount_lang', [], session('locale')); ?>');
            return false;

        }
        if (payment_date == "") {
            show_notification('error', '<?php echo trans('messages.add_payment_date_lang', [], session('locale')); ?>');
            return false;

        }
        if (account_status != 1) {
            if (payment_reference_no == "") {
                show_notification('error', '<?php echo trans('messages.add_payment_reference_no_lang', [], session('locale')); ?>');
                return false;
            }
        }

        $('#global-loader').show();
        before_submit();
        var str = $(".add_purchase_payment").serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo url('add_purchase_payment'); ?>",
            data: formdatas,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#purchase_payment_modal').modal('hide');
                $('#global-loader').hide();
                after_submit();
                $('#all_purchase').DataTable().ajax.reload();
                show_notification('success', '<?php echo trans('messages.data_add_payment_success', [], session('locale')); ?>');
                $(".add_purchase_payment")[0].reset();
                // get_purchase_payment(purchase_id)
                return false;
            },
            error: function(data) {
                $('#global-loader').hide();
                after_submit();
                show_notification('error', '<?php echo trans('messages.data_add_failed_lang', [], session('locale')); ?>');
                $('#all_purchase').DataTable().ajax.reload();
                console.log(data);
                return false;
            }
        });
    });
    //


    function previewImage(event, imgId, removeBtnId) {
        const file = event.target.files[0]; // Get the selected file
        const imgTag = document.getElementById(imgId);
        const removeBtn = document.getElementById(removeBtnId);

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imgTag.src = e.target.result; // Set uploaded image
                removeBtn.style.display = 'block'; // Show remove button
            };
            reader.readAsDataURL(file); // Convert file to base64
        }
    }


    function removeImage(imgId, inputId, removeBtnId) {
        document.getElementById(imgId).src = "{{ asset('images/dummy_images/no_image.jpg') }}"; // Restore default image
        document.getElementById(inputId).value = ""; // Clear file input
        document.getElementById(removeBtnId).style.display = 'none'; // Hide remove button
    }



    function updateSalesPriceWithTax(index) {
    // Get the sales price and tax input elements
    const salesPriceInput = document.getElementById(`sale_price_${index}`);
    const taxInput = document.getElementById(`tax_${index}`);

    // Retrieve the original price from data attribute (fallback to input value if missing)
    let originalSalesPrice = parseFloat(salesPriceInput.dataset.originalValue);

    // If not set, initialize it with the current input value
    if (!originalSalesPrice) {
        originalSalesPrice = parseFloat(salesPriceInput.value) || 0;
        salesPriceInput.dataset.originalValue = originalSalesPrice; // Store it
    }

    // Get tax percentage
    const taxPercentage = parseFloat(taxInput.value) || 0;

    // Calculate the new price with tax
    const taxAmount = (originalSalesPrice * taxPercentage) / 100;
    const inclusiveSalesPrice = originalSalesPrice + taxAmount;

    // Update the input with the new calculated price
    salesPriceInput.value = inclusiveSalesPrice.toFixed(2);
}

// Attach event listener to all tax inputs dynamically
document.querySelectorAll("[id^='tax_']").forEach(taxInput => {
    taxInput.addEventListener("input", function() {
        const index = this.id.split("_")[1]; // Extract the index
        updateSalesPriceWithTax(index);
    });
});

// Store original price when the page loads
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll("[id^='sale_price_']").forEach(input => {
        if (!input.dataset.originalValue) {
            input.dataset.originalValue = input.value; // Store original value
        }
    });
});





</script>
