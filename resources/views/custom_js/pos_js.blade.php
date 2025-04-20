<script>

    // let reloadAttempted = sessionStorage.getItem('reloadAttempted');

    // $.ajaxSetup({
    //     timeout: 7000, // 5 seconds
    //     error: function(xhr, textStatus) {
    //         if (textStatus === 'timeout') {
    //             show_notification('error', 'Please check your internet connection.');
    //             if (!reloadAttempted) {
    //                 sessionStorage.setItem('reloadAttempted', 'true');
    //                 setTimeout(() => {

    //                 }, 7000);
    //             }
    //             return false;
    //         } else if (textStatus === 'error') {
    //             show_notification('error', 'An error occurred: ' + xhr.status + ' ' + xhr.statusText);
    //         }
    //     }
    // });

    // // Clear reload flag when the page loads successfully
    // $(document).ready(function() {
    //     sessionStorage.removeItem('reloadAttempted');
    // });

        // disabled btn after and before
        function before_submit_pos() {
            $('.submit_form').attr('disabled', true);
        }

        function after_submit_pos() {
            $('.submit_form').attr('disabled', false);
        }
        $(document).ready(function() {

            // digit validation
            // three digit after decimal
            function three_digit_after_decimal(number) {
                if (!isNaN(number)) {
                    return Math.floor(number * 1000) / 1000;
                }
            }
            // two digit
            function two_digit_after_decimal(number) {
                if (!isNaN(number)) {
                    return Math.floor(number * 100) / 100;
                }
            }
            // only number allow
            function isNumber(evt, element) {
                var charCode = (evt.which) ? evt.which : event.keyCode
                if ((charCode != 45 || $(element).val().indexOf('-') != -1) && (charCode != 46 || $(element).val().indexOf(
                        '.') != -1) && ((charCode < 48 && charCode != 8) || charCode > 57)) {
                    return false;
                } else {
                    return true;
                }
            }

            function isNumber_qty(evt, element) {
                var charCode = (evt.which) ? evt.which : event.keyCode;

                // Allow only digits
                if (charCode < 48 || charCode > 57) {
                    return false;
                } else {
                    return true;
                }
            }

            function convertToEnglishDigits(inputField) {
                // Replace Arabic digits with English digits
                inputField.value = inputField.value.replace(/[٠١٢٣٤٥٦٧٨٩]/g, function(match) {
                    return String.fromCharCode(match.charCodeAt(0) - '٠'.charCodeAt(0) + '0'.charCodeAt(0));
                });

                // Remove any non-digit characters
                inputField.value = inputField.value.replace(/\D/g, '');
            }

            function isNumber1(evt, element) {
                var charCode = (evt.which) ? evt.which : event.keyCode;

                // Allow digits (0-9), backspace (8), and minus sign (45)
                if ((charCode >= 48 && charCode <= 57) || charCode == 8 || charCode == 45) {
                    // Check if the minus sign is not the first character
                    if (charCode == 45 && $(element).val().indexOf('-') !== -1) {
                        return false;
                    }
                    return true;
                } else {
                    return false;
                }
            }

            //Number with decimal only
            $(document).on('keypress', '.isnumber', function(e) {
                return isNumber(e, this);
            });
            // only english digit
            $(document).on('input', '.isnumber_qty', function() {
                convertToEnglishDigits(this);
            });
            //Number without decimal only
            $(document).on('keypress', '.isnumber1', function(e) {
                return isNumber1(e, this);
            });

            // fa for focus barcode input
            $(document).keydown(function(event) {
                // Check if the pressed key is F2 (key code 113)
                if (event.which == 113) {
                    // Set focus to the desired input element
                    $('.product_input ').focus();
                }
            });

            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // seach paid ordr
            // $('.order-details').hide();

            // focus on product list
            $('.product_input ').focus();
            // catregory carusel
            // POS Category Slider
            var dirValue = $('html').attr('dir');
            if(dirValue=='rtl')
            {
                if($('.pos-category').length > 0) {
                    $('.pos-category').owlCarousel({
                        rtl : true,
                        items: 6,
                        loop:false,
                        margin:8,
                        nav:true,
                        dots: false,
                        autoplay:false,
                        smartSpeed: 1000,
                        navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
                        responsive:{
                            0:{
                                items:2
                            },
                            500:{
                                items:3
                            },
                            768:{
                                items:4
                            },
                            991:{
                                items:5
                            },
                            1200:{
                                items:6
                            },
                            1401:{
                                items:6
                            }
                        }
                    })
                }
            }
            else
            {
                if($('.pos-category').length > 0) {
                    $('.pos-category').owlCarousel({
                        ltr : true,
                        items: 6,
                        loop:false,
                        margin:8,
                        nav:true,
                        dots: false,
                        autoplay:false,
                        smartSpeed: 1000,
                        navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
                        responsive:{
                            0:{
                                items:2
                            },
                            500:{
                                items:3
                            },
                            768:{
                                items:4
                            },
                            991:{
                                items:5
                            },
                            1200:{
                                items:6
                            },
                            1401:{
                                items:6
                            }
                        }
                    })
                }
            }

            // on open payment modal
            $('#payment_modal_id').on('click', function (e) {

                var tbody = $('#order_list');
                if (tbody.find('tr').length > 0) {
                    $('#payment_modal').modal('show');
                    var grand_total = $('.grand_total').text();
                    $('.cash_payment').val(grand_total);
                    $('.remaining_point_amount').text(grand_total);
                    $('.paid_point_amount').text("");
                    $('.get_point_amount').val("");
                    $('.payment_methods_value').val("");
                    $('.payment_methods_value').attr('readonly',true);
                    $('.payment_methods').prop('checked',false);
                    if($('.payment_customer_point_amount').val() <= 0 ||  $('.payment_customer_point_amount').val() == "")
                    {
                        $('.get_point_amount').attr('readonly',true);
                        $('#get_total_point_value').attr('disabled',true);
                    }
                    else
                    {
                        $('.get_point_amount').attr('readonly',false);
                        $('#get_total_point_value').attr('disabled',false);
                    }
                    total_calculation();
                }
                else
                {
                    show_notification('error', '<?php echo trans('messages.please_add_product_in_list_lang', [], session('locale')); ?>');

                }
            });
            // add pos order
            var isSubmitting_pos = false;
            $('#add_pos_order').click(function() {

                $(this).attr('disabled',true);
                $(this).removeClass('btn-success');
                $(this).addClass('btn-danger');
                if (isSubmitting_pos) {
                    return; // Do nothing if a request is already in progress
                }

                isSubmitting_pos = true;
                var action_type = ($(this).attr('id') === 'hold') ? 'hold' : 'add';
                var item_count = $('.count').text();
                var grand_total = $('.grand_total').text();
                var sub_total = $('.sub_total').text();
                var offer_id = $('.offer_id').val();
                var offer_discount = $('.offer_discount').val();
                // var payment_gateway= $('.payment_gateway_all').val();
                // var discount_by = $('.discount_by').val();
                var discount_by = "";
                var cash_payment = $('.cash_payment').val();
                if(cash_payment==''){
                    show_notification('error', '<?php echo trans('messages.please_pay_cash_payment_lang', [], session('locale')); ?>');
                }
                var discount_type = 1;
                // if ($('.discount_check').is(':checked')) {
                //     var discount_type = 2;
                // }
                var total_tax = $('.total_tax').text();
                var total_discount = $('.grand_discount').text();
                // var cash_back = $('.cash_back').text();
                var cash_back = $('.remaining_point_amount').text();
                var customer_id = "";
                if($('.pos_customer_id').val()!="")
                {
                    customer_id = $('.pos_customer_id').val();
                }


                var point_omr = $('.get_point_amount').val();
                if(point_omr == "")
                {
                    point_omr =0;
                }
                var sum = 0;
                var cash_sum = 0;
                $('.payment_methods_value').each(function() {
                    var value = parseFloat($(this).val());
                    var cash_type = $(this).attr('cash-type');
                    if(cash_type == 1)
                    {
                        if (!isNaN(value)) {
                            cash_sum = value;
                        }
                    }
                    else
                    {
                        if (!isNaN(value)) {
                            sum += value;
                        }
                    }

                });

                var final_omr = $('.grand_total').text();
                if(final_omr == "")
                {
                    final_omr =0;
                }
                final_without_cash = parseFloat(sum) + parseFloat(point_omr);
                final_with_cash = parseFloat(cash_sum) + parseFloat(sum) + parseFloat(point_omr);

                if(cash_sum <= 0 &&  final_without_cash > final_omr)
                {
                    $(this).attr('disabled',false);
                    $(this).removeClass('btn-danger');
                    $(this).addClass('btn-success');
                    isSubmitting_pos = false; // Reset the flag
                    show_notification('error','<?php echo trans('messages.validation_amount_greater_than_lang',[],session('locale')); ?>');
                    return false;
                }

                if(cash_sum <= 0 && final_without_cash < final_omr)
                {
                    $(this).attr('disabled',false);
                    $(this).removeClass('btn-danger');
                    $(this).addClass('btn-success');
                    isSubmitting_pos = false; // Reset the flag
                    show_notification('error','<?php echo trans('messages.validation_amount_less_than_lang',[],session('locale')); ?>');
                    return false;
                }

                if(cash_sum > 0 && final_without_cash > final_omr)
                {
                    $(this).attr('disabled',false);
                    $(this).removeClass('btn-danger');
                    $(this).addClass('btn-success');
                    isSubmitting_pos = false; // Reset the flag
                    show_notification('error','<?php echo trans('messages.validation_amount_greater_than_lang',[],session('locale')); ?>');
                    return false;
                }

                if(cash_sum + final_without_cash < final_omr)
                {
                    $(this).attr('disabled',false);
                    $(this).removeClass('btn-danger');
                    $(this).addClass('btn-success');
                    isSubmitting_pos = false; // Reset the flag
                    show_notification('error','<?php echo trans('messages.validation_amount_less_than_lang',[],session('locale')); ?>');
                    return false;
                }

                if(cash_sum == "")
                {
                    cash_sum = 0;
                }
                if(final_without_cash == "")
                {
                    final_without_cash = 0;
                }
                let final_paid_amount = final_without_cash + cash_sum;
                // get payment method
                var payment_method = [];

                // Iterate over each checked checkbox
                $('.payment_methods:checked').each(function() {
                    var checkboxValue = $(this).val(); // Get the value of the checked checkbox

                    var inputValue = $('#payment_methods_value_id' + checkboxValue).val();
                    var cash_type = $('#payment_methods_value_id' + checkboxValue).attr('cash-type');
                    if(inputValue > 0)
                    {
                        if(cash_type == 1)
                        {
                            cash_acc = 1;
                        }
                        else
                        {
                            cash_acc = "";
                        }
                        console.log(inputValue)
                        // Push both checkbox and input values as an object to the values array
                        payment_method.push({ checkbox: checkboxValue, input: inputValue,cash_data : cash_acc});
                    }
                });
                // Add the "point" checkbox value and the input value "10" outside the loop
                if(point_omr != "")
                {
                    var pointValue = 0;
                    var inputValue10 = point_omr;
                    payment_method.push({ checkbox: pointValue, input: inputValue10,cash_data : ""});

                }


                var product_id = [];
                $('.stock_ids').each(function() {
                    product_id.push($(this).val());
                });
                if(product_id.length===0)
                {
                    $(this).attr('disabled',false);
                    $(this).removeClass('btn-danger');
                    $(this).addClass('btn-success');
                    isSubmitting_pos = false; // Reset the flag
                    show_notification('error', '<?php echo trans('messages.please_add_product_in_list_lang', [], session('locale')); ?>');
                    return false;
                }
                var item_barcode = [];
                $('.barcode').each(function() {
                    item_barcode.push($(this).val());
                });

                var item_tax = [];
                $('.tax').each(function() {
                    item_tax.push($(this).val());
                });

                var item_imei = [];
                $('.imei').each(function() {
                    if($(this).val() == 'undefined' || $(this).val()=="")
                    {
                        imei_one = ""
                    }
                    else
                    {
                        imei_one = $(this).val()
                    }
                    item_imei.push(imei_one);
                });
                if (item_imei!="") {
                    if(customer_id=="")
                    {
                        $(this).attr('disabled',false);
                        $(this).removeClass('btn-danger');
                        $(this).addClass('btn-success');
                        isSubmitting_pos = false; // Reset the flag
                        show_notification('error', '<?php echo trans('messages.please_select_customer_lang', [], session('locale')); ?>');
                        return false;
                    }
                }
                var item_quantity = [];
                $('.qty-input').each(function() {
                    item_quantity.push($(this).val());
                });
                var item_price = [];
                $('.price').each(function() {
                    item_price.push($(this).val());
                });

                var item_total = [];
                $('.total_price').each(function() {
                    item_total.push($(this).text());
                });
                var item_discount = [];
                $('.discount').each(function() {
                    item_discount.push($(this).val());
                });

                var offer_discount_percent = [];
                $('.offer_discount_percent').each(function() {
                    offer_discount_percent.push($(this).val());
                });

                var offer_discount_amount = [];
                $('.offer_discount_amount').each(function() {
                    offer_discount_amount.push($(this).val());
                });

                var form_data = new FormData();
                form_data.append('item_count', item_count);
                // form_data.append('payment_gateway', payment_gateway);
                form_data.append('action_type', action_type);
                form_data.append('offer_id', offer_id);
                form_data.append('offer_discount', offer_discount);
                form_data.append('grand_total', grand_total);
                form_data.append('cash_payment', cash_payment);
                form_data.append('discount_type', discount_type);
                form_data.append('discount_by', discount_by);
                form_data.append('total_tax', total_tax);
                form_data.append('total_discount', total_discount);
                form_data.append('cash_back', cash_back);
                form_data.append('payment_method', JSON.stringify(payment_method));
                form_data.append('product_id', JSON.stringify(product_id));
                form_data.append('item_barcode', JSON.stringify(item_barcode));
                form_data.append('item_tax', JSON.stringify(item_tax));
                form_data.append('item_imei', JSON.stringify(item_imei));
                form_data.append('item_quantity', JSON.stringify(item_quantity));
                form_data.append('item_discount', JSON.stringify(item_discount));
                form_data.append('item_price', JSON.stringify(item_price));
                form_data.append('item_total', JSON.stringify(item_total));
                form_data.append('offer_discount_percent', JSON.stringify(offer_discount_percent));
                form_data.append('offer_discount_amount', JSON.stringify(offer_discount_amount));
                form_data.append('customer_id', customer_id);
                form_data.append('_token', csrfToken);

                $.ajax({
                    url: "{{ url('add_pos_order') }}",
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    data: form_data,
                    success: function(response) {

                        if(response.not_available > 0)
                        {

                            show_notification('error', response.finish_name + ' <?php echo trans('messages.product_stock_not_available_lang', [], session('locale')); ?>');
                            return false;
                        }
                        else
                        {
                            show_notification('success', '<?php echo trans('messages.data_add_success_lang', [], session('locale')); ?>');
                            $('#payment_modal').modal('hide');
                            $('#payment-completed').modal('show');
                            $('#last_cash_back').text(cash_back);
                            $('#last_final_amount').text(grand_total);
                            $('#last_paid_amount').text(final_paid_amount);
                            let orderUrl = `pos_bill/${response.order_no}`;
                            window.open(orderUrl, '_blank');
                            $('#pos_order_no').text(response.order_no);
                            $('#customer_input_data').val('');
                            $('.pos_customer_id').val('');
                            // location.reload();
                        }
                        $(this).attr('disabled',false);
                        $(this).removeClass('btn-danger');
                        $(this).addClass('btn-success');
                        isSubmitting_pos = false; // Reset the flag
                    }
                });
            });

            $('#payment-completed').on('hidden.bs.modal', function() {
                location.reload();



            });

            // cat_products('all');
            var totalQuantity = 0;

            $(document).on('click', '.inc', function() {
                var $qtyInput = $(this).siblings('.qty-input');
                var productBarcode = $(this).closest('tr').find('.barcode').val();
                var count = parseInt($qtyInput.val());
                product_quantity(productBarcode, count + 1, $qtyInput, 1);
            });

            function product_quantity(productBarcode, count, $qtyInput, qty_type) {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "POST",
                    url: "{{ url('order_list') }}",
                    data: {
                        product_barcode: productBarcode,
                        quantity: count,
                        _token: csrfToken
                    },
                    success: function(response) {

                        $('.price_' + productBarcode).val(response.product_price);
                        $('.show_pro_price_' + productBarcode).html(' ' + response.product_price);

                        if (response.error_code == 2) {
                            show_notification('error', '<?php echo trans('messages.product_stock_not_available_lang', [], session('locale')); ?>');
                            count--;
                            $qtyInput.val(count)

                        } else {

                            if (qty_type == 1) {
                                // count++;
                                $qtyInput.val(count);
                                totalQuantity++;
                                total_calculation();
                            } else {
                                if (count != 0) {

                                    $qtyInput.val(count);
                                    totalQuantity--;
                                    total_calculation();
                                }
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            $(document).on('click', '.dec', function() {
                var $qtyInput = $(this).siblings('.qty-input');
                var productBarcode = $(this).closest('tr').find('.barcode').val();
                var count = parseInt($qtyInput.val());
                product_quantity(productBarcode, count - 1, $qtyInput, 2);
            });

            $('#order_list').on('click', '#delete-item', function() {

                var $productItem = $(this).closest('tr');
                $productItem.remove();
                total_calculation();
            });

            $('#clear_list').click(function() {
                $('#order_list').empty();
                totalQuantity = 0;
                total_calculation();
            });

        });







        // scroll to bottom of order_list
        function order_list_bottom()
        {
            $('.product-wrap').animate({ scrollTop: $('.product-wrap')[0].scrollHeight }, 'slow');
        }
        function order_list(product_barcode, imei) {

            var quantity = 0;
            if ($('#order_list').find('tr.list_' + product_barcode).length > 0) {
                var old_quantity = $('tr.list_' + product_barcode + ' .qty-input').val();
                var quantity = parseFloat(old_quantity) + 1;
            } else {
                var quantity = 1;
            }


            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "POST",
                url: "{{ url('order_list') }}",
                data: {
                    quantity: quantity,
                    imei: imei,
                    product_barcode: product_barcode,
                    _token: csrfToken
                },
                success: function(response) {

                    if (response.error_code == 2) {
                        show_notification('error', '<?php echo trans('messages.product_stock_not_available_lang', [], session('locale')); ?>');
                        var audio = new Audio('/sounds/qty.mp3'); // Adjust the filename as per your audio file
                        audio.play();
                    }

                    else {


                        if ($('#order_list').find('tr.list_' + product_barcode).length > 0 && (typeof imei === 'undefined')) {

                            if (response.is_bulk == 1) {
                                $('.price_' + product_barcode).val(response.product_price);
                                $('.show_pro_price_' + product_barcode).html(response.product_price);
                            }

                            var $existingProduct = $('#order_list').find('tr.list_' + product_barcode);
                            var $qtyInput = $existingProduct.find('.qty-input');
                            var count = parseInt($qtyInput.val());
                            count++;
                            $qtyInput.val(count);
                            show_notification('success', '<?php echo trans('messages.item_add_to_list_lang', [], session('locale')); ?>');
                            var audio = new Audio('/sounds/test.mp3'); // Adjust the filename as per your audio file
                            audio.play();

                        }

                     else {
                        var className = 'imei_' + imei;
                        var element = $('#order_list').find('*').filter(function() {
                            return $(this).hasClass(className);
                        });


                        if(element.length > 0 && (typeof imei != 'undefined')){
                            show_notification('error', '<?php echo trans('messages.product_already_added_with_same_emei_lang', [], session('locale')); ?>');
                            var audio = new Audio('/sounds/horn.mp3'); // Adjust the filename as per your audio file
                            audio.play();
                        }

                            else{
                        var rowCount = $('#order_list tr').length;
                        rowCount = rowCount+1;
                        var pro_image = "{{ asset('images/dummy_image/no_image.png') }}";
                        if (response.product_image && response.product_image !== '') {
                            pro_image = "{{ asset('images/product_images/') }}" + response.product_image;
                        }

                        var warranty_type = "";
                        if(response.warranty_type!="")
                        {
                            warranty_type =  `<br><span class="badge badge-success"> ${response.warranty_type}</span> `;
                        }
                        var show_imei="";
                        var qty_input = "";
                        if (typeof imei !== 'undefined' && imei !== "") {
                            qty_input = '<div class="qty-item text-center"><input type="text" class="form-control text-center qty-input" readonly name="product_quantity" value="1"></div>';
                            show_imei = `<br>${response.imei_serial} : <span class="badge badge-warning">${imei}</span>`;
                        }
                        else
                        {
                            qty_input = `<div class="qty-item text-center">
                                                <a href="javascript:void(0);" class="dec d-flex justify-content-center align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="minus"><i class="fas fa-minus-circle"></i></a>

                                                <input type="text" class="form-control text-center qty-input" readonly name="product_quantity" value="1">

                                                <a href="javascript:void(0);" class="inc d-flex justify-content-center align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="plus"><i class="fas fa-plus-circle"></i></a>
                                            </div>`;
                        }
                        var final_name = "";
                        if(response.title_name != "")
                        {
                            final_name = response.title_name;
                        }
                        if(final_name == "")
                        {
                            final_name = response.title_name_ar;
                        }
                        else if(final_name!= "" && response.title_name_ar != "")
                        {
                            final_name = final_name;
                        }



                        // <a  href="#" data-bs-toggle="modal" onclick="edit_product(${response.product_barcode})" data-bs-target="#edit-product"><i class="fas fa-edit"></i></a>
                            var orderHtml = `
                            <tr class="list_${product_barcode}">
                                <th class="text-center">${rowCount}</th>
                                <th>${final_name}
                                    <input type="hidden" name="stock_ids" value="${response.id}" class="stock_ids product_id_${response.id}">
                                    <input type="hidden" name="product_tax" value="${response.product_tax}" class="tax tax_${response.product_barcode}">
                                    <input type="hidden" value="${response.product_min_price}" class="min_price min_price_${response.product_barcode}">
                                    <input type="hidden" value="${response.product_name}" class="product_name product_name_${response.product_barcode}">
                                    <input type="hidden" name="product_barcode" value="${response.product_barcode}" class="barcode barcode_${response.product_barcode}">

                                    <br>
                                    <span class="badge badge-warning"> ${response.product_barcode}</span>

                                </th>
                                <th class="text-center">
                                    <input type="text" readonly  value="${response.product_price}" class="price price_${response.product_barcode} text-center pos_item_td">
                                </th>
                                <th class="text-center">
                                    <div style="padding:15px" class="product-list item_list d-flex align-items-center justify-content-between">

                                        <div class="d-flex align-items-center product-info" data-bs-toggle="modal" data-bs-target="#products">
                                            ${qty_input}
                                        </div>
                                    </div>
                                </th>
                                <th class="text-center"><span style="font-size:16px" class="total_price total_price_${response.product_barcode}"></span></th>

                                <th class="text-center">
                                    <input type="text"  name="product_discount" value="0" class="isnumber text-center pos_item_td discount discount_${response.product_barcode}">
                                </th>
                                <th class="text-center">
                                    <span style="font-size:16px" class="grand_price grand_price_${response.product_barcode}"></span>
                                </th>
                                <th class="text-center">

                                    <a id="delete-item" href="javascript:void(0);"><i class="fas fa-2x fa-trash"></i></a>
                                </th>
                            </tr>

                    `;

                            $('#order_list').append(orderHtml);
                            show_notification('success', '<?php echo trans('messages.item_add_to_list_lang', [], session('locale')); ?>');
                            var audio = new Audio('/sounds/test.mp3'); // Adjust the filename as per your audio file
                            audio.play();
                        }
                    }
                    }
                    total_calculation();
                    $('#hold_order').modal('hide');
                    setTimeout(function() {
                        $('.product_input ').val('');
                        $('.product_input ').focus();
                    }, 1000);
                    setTimeout(order_list_bottom, 100);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }




        //auto complete

        $(".product_input").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "{{ url('product_autocomplete') }}",
                method: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    term: request.term
                },

                success: function(data) {
                    response(data.slice(0, 10));

                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        },

    }).autocomplete("search", "");

    //chek_imei
    $('.product_input, #enter').on('keypress click', function(event) {
        if ((event.which === 13 && event.target.tagName !== 'A') || (event.target.id === 'enter' && event.type === 'click')) {
            var product_input = $('.product_input').val();
            var parts = product_input.split('+');
            var barcode = "";

            // Check if there's a '+' in the product_input
            if (parts.length > 1) {
                // If yes, assign the part after '+' to barcode
                barcode = parts[0];
            } else {
                // If not, assign the original product_input to barcode
                barcode = product_input;
            }
            $.ajax({
                url: "{{ url('get_product_type') }}",
                method: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    barcode: barcode
                },

                success: function(data) {
                    if (data.check_imei == 1) {
                        get_pro_imei(barcode)
                        return false;
                    }
                    else if(data.check_imei == 3)
                    {

                        order_list(data.barcode , data.imei)
                        return false;
                    }
                    else
                    {
                        order_list(barcode)
                        return false;
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });

        }
    });





    //end check_imei


        //end autocomplete
        var discount_type = 1;

        function switch_discount_type() {
            discount_type = $('.discount_check').is(':checked') ? 2 : 1;
            total_calculation();
        }
        // discount change
        $(document).on('keyup', '.discount', function(event) {
            if($(this).val() == "")
            {
                $(this).val(0)
            }
            total_calculation();
        });
        $(document).on('click', '.discount', function(event) {
            $(this).select();
        })
        function total_calculation() {
    var total_price = 0;
    var total_qty = 0;
    var total_tax = 0;
    var total_discount = 0;
    var cash_payment = parseFloat($('.cash_payment').val()) || 0;
    var cash_back = 0;

    $('.barcode').each(function() {
        var $row = $(this).closest('tr');
        var product_id = $row.find('.stock_ids').val();
        var $qtyInput = $row.find('.qty-input');
        var qty = parseFloat($qtyInput.val()) || 0;
        total_qty += qty;

        var price = parseFloat($row.find('.price').val()) || 0;
        var product_cost = qty * price;

        // Calculate tax
        var tax = parseFloat($row.find('.tax').val()) || 0;
        var tax_amount = product_cost * (tax / 100);
        total_tax += tax_amount;

        var barcode = $(this).val();
        $row.find('.total_price').text(product_cost.toFixed(3));
        total_price += product_cost;

        // Calculate discount
        var discount = parseFloat($row.find('.discount').val()) || 0;
        var min_price = parseFloat($row.find('.min_price').val()) || 0;

        var discount_total_price, final_discount;
        if (discount_type == 1) {
            discount_total_price = product_cost - discount;
            final_discount = discount;
        } else {
            var discounted_price = product_cost * (discount / 100);
            discount_total_price = product_cost - discounted_price;
            final_discount = discounted_price;
        }

        var final_after_discount = discount_total_price / qty;
        if (final_after_discount < min_price) {
            show_notification('error', '<?php echo trans('messages.total_price_cannot_exceed_min_price_lang', [], session('locale')); ?>');
            total_discount += 0;
            final_discount = 0;
            $row.find('.discount').val(0);
        } else {
            total_discount += final_discount;
        }

        var final_total = (product_cost + tax_amount) - final_discount;
        $row.find('.grand_price').text(final_total.toFixed(3));
    });

    var grand_total = (total_price + total_tax) - total_discount;
    cash_back = grand_total - cash_payment;
    if (cash_back == grand_total) {
        cash_back = 0;
    }

    $('.sub_total').text(total_price.toFixed(3));
    $('.total_tax').html(total_tax.toFixed(3));
    $('.count').text(total_qty);
    $('.grand_discount').text(total_discount.toFixed(3));
    $('.grand_total').text(grand_total.toFixed(3));
    $('.sub_total_show').text(total_price.toFixed(3));
    $('.total_tax_show').html(total_tax.toFixed(3));
    $('.grand_discount_show').text(total_discount.toFixed(3));
    $('.grand_total_show').text(grand_total.toFixed(3));
}


        $('.cash_payment').on('input', function() {
            total_calculation();
        });
        //customer_js








        // get customer data
        function get_customer_data(customer_number)
        {

            $.ajax({
                url: "{{ url('get_customer_data') }}",
                method: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),

                },
                data: {
                    'customer_number':customer_number
                },
                success: function(data) {
                    let draw_name = data.draw_name+' ('+data.get_draw_price+')'
                    $('.customer_draw').val(data.draw_name)
                    $('.customer_draw_price').val(data.get_draw_price)
                    $('.payment_customer_name').val(data.customer_name)
                    $('.payment_customer_point').val(data.points)
                    $('.customer_point').val(data.points)
                    let total_point_amount = 0;
                    if (parseFloat(data.points_amount) > 0) {
                        total_point_amount = parseFloat(data.points_amount).toFixed(3);
                    }
                    $('.payment_customer_point_amount').val(total_point_amount)
                    $('.payment_customer_point_from').val(data.points_from)
                    $('.payment_customer_amount_to').val(data.amount_to)
                    $('.customer_offer').val(data.offer_name)
                    $('.offer_pros').val(data.offer_pros)
                    $('.offer_discount').val(data.offer_discount)
                    $('.offer_id').val(data.offer_id)
                    total_calculation();
                    // response(data);
                }
            });
        }
        // check customer type
        function check_customer() {
            var customer_type = $(".customer_type:checked").val();

            if (customer_type == 1) {
                $(".student_detail").show();
                $(".teacher_detail").hide();
                $(".employee_detail").hide();
            } else if (customer_type == 2) {
                $(".student_detail").hide();
                $(".teacher_detail").show();
                $(".employee_detail").hide();

            } else if (customer_type == 3) {
                $(".student_detail").hide();
                $(".teacher_detail").hide();
                $(".employee_detail").show();

            } else if (customer_type == 4) {
                $(".student_detail").hide();
                $(".teacher_detail").hide();
                $(".employee_detail").hide();

            }
        }
        check_customer();

        $('.pos_customer_id').val('');
        $('.customer_point').val('');
        $('.customer_offer').val('');
        $('.offer_pros').val('');
        $('.offer_discount').val('');
        $('.offer_id').val('');
        $('.customer_draw').val('');
        $('.customer_draw_price').val('');
        //customer autocomplete
        $("#customer_input_data").on('keyup', function() {
            // Check if the input is empty
            if ($(this).val().trim() === '') {
                // If it's empty, clear other inputs
                $('.pos_customer_id').val('');
                $('.customer_point').val('');
                $('.customer_offer').val('');
                $('.offer_pros').val('');
                $('.offer_discount').val('');
                $('.offer_id').val('');
                $('.customer_draw').val('');
                $('.customer_draw_price').val('');
            }
        });
        $(".add_customer").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ url('customer_autocomplete') }}",
                    method: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);

                    }
                });

            },
            // minLength: 2,


            select: function(event, ui) {
                console.log(ui.item);
                order_list(ui.item.phone);
                var customer_id = ui.item.value;
                var customer_number = customer_id.split(':')[0].trim();
                $('.pos_customer_id').val(customer_number)

                get_customer_data(customer_number)

            }
        }).autocomplete("search", "");

        $('.payment-anchor').click(function() {
            var accountId = $(this).data('account-id');
            var radio = $('#payment_gateway' + accountId);

            radio.prop('checked', !radio.prop('checked'));
        });



        $('#nextOrderButton').click(function() {
        window.location.href = "{{ url('pos') }}";
    });

    function get_rand_barcode(i) {
            var randomNumber = Math.floor(100000 + Math.random() * 900000);
            $('.barcode_' + i).val(randomNumber);
        }

    // return item
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $('.return_order_no').on('keypress', function(event) {
        if (event.which === 13) {
            $('#return_data').empty();
            var order_no = $(this).val();
            var return_type = $('.return:checked').val();
            $.ajax({
                url: "{{ url('get_return_items') }}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    order_no: order_no,
                    return_type: return_type,
                },
                success: function(response) {
                    if (response.status == 2) {
                        $('.repairing_data').empty();
                        show_notification('error','<?php echo trans('messages.no_record_found_lang',[],session('locale')); ?>');
                    }
                    else{
                        show_notification('success','<?php echo trans('messages.record_found_lang',[],session('locale')); ?>');
                        $('#return_data').html(response.return_data);
                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

    });

    // replace item
    $(document).on('click', '#replace_item_btn', function(e) {
        var order_no = $('.replace_reference_no').val();
        var replaced_imei = $('.replaced_imei').val();
        var old_product_id = $('.old_product_id').val();
        var old_imei = $('.old_imei').val();

        $.ajax({
            url: "{{ url('add_replace_item') }}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                order_no: order_no,
                replaced_imei: replaced_imei,
                old_product_id: old_product_id,
                old_imei: old_imei,
            },
            success: function(response) {
                if (response.status == 2) {
                    show_notification('error','<?php echo trans('messages.item_not_found_lang',[],session('locale')); ?>');
                    return false;
                }
                else{
                    show_notification('success','<?php echo trans('messages.item_replace_successfully_lang',[],session('locale')); ?>');
                    $('#return_data').empty();
                    $('.return_order_no').val('');
                    $('#return_modal').hide();
                    window.location.reload();



                    return false;
                }

            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });

    // restore items
    $('.restore_order_no').on('keypress', function(event) {
        if (event.which === 13) {
            $('#restore_data').empty();
            var order_no = $(this).val();
            var restore_type = $('.restore_type:checked').val();
            $.ajax({
                url: "{{ url('get_restore_items') }}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    order_no: order_no,
                    restore_type: restore_type,
                },
                success: function(response) {
                    if (response.status == 2) {
                        $('.repairing_data').empty();
                        show_notification('error','<?php echo trans('messages.no_record_found_lang',[],session('locale')); ?>');
                    }
                    else{
                        show_notification('success','<?php echo trans('messages.record_found_lang',[],session('locale')); ?>');
                        $('#restore_data').html(response.restore_data);
                        $('.restore_order_nos').val(response.order_no);
                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

    });


    // add patient customer
    $('.add_patient').submit(function(e) {
        e.preventDefault();

        var formData = new FormData($('.add_patient')[0]);
        formData.append('_token', '{{ csrf_token() }}');
        var firstName = $('#first_name').val();
        var mobile = $('#mobile').val();
        var id = $('.patient_id').val();

        if (firstName === "") {
            show_notification('error', '<?php echo trans('messages.add_patient_name_lang',[],session('locale')); ?>');
            return false;
        }
        if (mobile === "") {
            show_notification('error', '<?php echo trans('messages.provide_mobile_lang',[],session('locale')); ?>');
            return false;
        }

        showPreloader();
        before_submit();

        $.ajax({
            type: "POST",
            url: "{{ url('add_pos_patient') }}",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                hidePreloader();
                after_submit();
                show_notification('success', id ?
                    '<?php echo trans('messages.data_update_success_lang',[],session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_success_lang',[],session('locale')); ?>'
                );
                $('#add_patient').modal('hide'); 
                if (!id) $(".add_patient")[0].reset();
            },
            error: function(response) {
                hidePreloader();
                after_submit();
                show_notification('error', id ?
                    '<?php echo trans('messages.data_update_failed_lang',[],session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>'
                );
                $('#all_patient').DataTable().ajax.reload();
            }
        });
    });



    // key up

    $(document).on('keyup', '.return_qty', function(e) {

        var $row = $(this).closest('tr');
        var maxQty = parseFloat($row.find('.real_qty').text());
        var returnQty = parseFloat($(this).val());

        if (returnQty > maxQty) {
            show_notification('error','<?php echo trans('messages.validation_rtn_qty_grt_lang',[],session('locale')); ?>');
             $(this).val(maxQty > 0 ? maxQty : 1); // Revert to maxQty if valid, otherwise 1
        }
        else if(returnQty <= 0)
        {
            show_notification('error','<?php echo trans('messages.validation_rtn_qty_zero_lang',[],session('locale')); ?>');

            $(this).val(maxQty > 0 ? maxQty : 1);
        }
    });
    // get order_dewtail
    function get_order_items(order_no)
    {
        $('#restore_data').empty()
        $('input.restore_type[value="2"]').prop('checked', true);
        $('.restore_order_no').val(order_no)
        $('.restore_order_no').focus()
        $('.restore_order_no').trigger($.Event('keypress', { keyCode: 13, which: 13 })); // Trigger the Enter key press event

    }

    // check restore checkboxes

    $(document).on('click', '.all_restore_item', function(e) {
        var isChecked = $(this).is(':checked');
        $('.restore_item').prop('checked', isChecked);
    });

    // When any "restore_item" checkbox is clicked

    $(document).on('click', '.restore_item', function(e) {
        var allChecked = $('.restore_item').length === $('.restore_item:checked').length;
        $('.all_restore_item').prop('checked', allChecked);
    });

    // replace item
    // Replace item
    $(document).on('click', '#restore_item_btn', function(e) {
        e.preventDefault(); // Prevent the default action

        var order_no = $('.restore_order_nos').val();
        var restore_item = [];

        var restore_return_qty = [];

        $('.restore_item:checked').each(function() {
            var itemValue = $(this).val();
            var returnQty = $(this).closest('tr').find('.return_qty').val();
            restore_item.push(itemValue);
            restore_return_qty.push(returnQty);
        });

        // Check if no checkboxes are checked
        if (restore_item.length === 0) {
            show_notification('error', '{{ trans('messages.select_item_before_proceed_lang', [], session('locale')) }}');
            return false;
        }
        $.ajax({
            url: "{{ url('add_restore_item') }}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                order_no: order_no,
                restore_item: restore_item,
                restore_return_qty: restore_return_qty
            },
            success: function(response) {
                if (response.status == 2) {
                    show_notification('error', '{{ trans('messages.item_not_found_lang', [], session('locale')) }}');
                } else {
                    show_notification('success', '{{ trans('messages.item_return_successfully_lang', [], session('locale')) }}');
                    $('#restore_data').empty();

                    let orderUrl = `pos_bill/${response.new_bill}`;
                    window.open(orderUrl, '_blank');
                    $('.restore_order_no').val('');
                    $('#return_modal').hide();
                    window.location.reload();




                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });



    //pending order
        var isSubmitting = false; // Flag to track if an AJAX request is in progress
        $('#hold').click(function() {
            var holdButton = $(this); // Cache the button element
            holdButton.attr('disabled', true); //
            if (isSubmitting) {
                return; // Do nothing if a request is already in progress
            }
            isSubmitting = true;
            var item_count = $('.count').text();
            var grand_total = $('.grand_total').text();
            var discount_by = 1;
            var discount_type = 1;
            // if ($('.discount_check').is(':checked')) {
            //     var discount_type = 2;
            // }
            var total_tax = $('.total_tax').text();
            var total_discount = $('.grand_discount').text();
            var customer_id = "";
            if($('.pos_customer_id').val()!="")
            {
                customer_id = $('.pos_customer_id').val();
            }

            var product_id = [];
            $('.stock_ids').each(function() {
                product_id.push($(this).val());
            });
            if(product_id.length===0)
            {
                $(this).attr('disabled',false);;
                show_notification('error', '<?php echo trans('messages.please_add_product_in_list_lang', [], session('locale')); ?>');
                return false;
            }
            var item_barcode = [];
            $('.barcode').each(function() {
                item_barcode.push($(this).val());
            });

            var item_tax = [];
            $('.tax').each(function() {
                item_tax.push($(this).val());
            });

            var item_imei = [];
            $('.imei').each(function() {
                if($(this).val() == 'undefined' || $(this).val()=="")
                {
                    imei_one = ""
                }
                else
                {
                    imei_one = $(this).val()
                }
                item_imei.push(imei_one);
            });
            if (item_imei!="") {
                if(customer_id=="")
                {
                    $(this).attr('disabled',false);;
                    show_notification('error', '<?php echo trans('messages.please_select_customer_lang', [], session('locale')); ?>');
                    return false;
                }
            }
            var item_quantity = [];
            $('.qty-input').each(function() {
                item_quantity.push($(this).val());
            });
            var item_price = [];
            $('.price').each(function() {
                item_price.push($(this).val());
            });

            var item_total = [];
            $('.total_price').each(function() {
                item_total.push($(this).text());
            });
            var item_discount = [];
            $('.discount').each(function() {
                item_discount.push($(this).val());
            });



            var form_data = new FormData();
            form_data.append('item_count', item_count);
            form_data.append('grand_total', grand_total);
            form_data.append('discount_type', discount_type);
            form_data.append('discount_by', discount_by);
            form_data.append('total_tax', total_tax);
            form_data.append('total_discount', total_discount);
            form_data.append('product_id', JSON.stringify(product_id));
            form_data.append('item_barcode', JSON.stringify(item_barcode));
            form_data.append('item_tax', JSON.stringify(item_tax));
            form_data.append('item_imei', JSON.stringify(item_imei));
            form_data.append('item_quantity', JSON.stringify(item_quantity));
            form_data.append('item_discount', JSON.stringify(item_discount));
            form_data.append('item_price', JSON.stringify(item_price));
            form_data.append('item_total', JSON.stringify(item_total));
            form_data.append('customer_id', customer_id);
            form_data.append('_token', csrfToken);

            $.ajax({
                url: "{{ url('add_pending_order') }}",
                type: 'POST',
                processData: false,
                contentType: false,
                data: form_data,
                success: function(response) {

                    if (response.status == 1) {
                        $('.sub_total_show').text('')
                        $('.grand_discount_show').text('')
                        $('.total_tax_show').text('')
                        $('.grand_total_show').text('')
                        $('#customer_input_data').val('')
                        $('.pos_customer_id').val('')
                        get_customer_data($('.pos_customer_id').val())
                        $('#order_list').empty();
                        show_notification('success','<?php echo trans('messages.pending_record_added_lang',[],session('locale')); ?>');
                    }
                    else{
                        show_notification('error','<?php echo trans('messages.data_added_failed_lang',[],session('locale')); ?>');

                    }
                    $('.count').text('');
                    get_pending_data();
                    holdButton.attr('disabled',false);
                    isSubmitting = false; // Reset the flag
                }
            });
        });
        get_pending_data()
        function get_pending_data()
        {
            $.ajax({
                url: "{{ url('hold_orders') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processData: false,
                contentType: false,
                success: function(response) {
                    var hold_list = response.hold_list;

                    $('#hold_data').html(hold_list);

                }
            });

        }



    $(document).on('click', '#btn_hold', function() {
        var orderId = $(this).data('order-id');
        $.ajax({
            url: "{{ url('get_hold_data') }}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                order_id: orderId
            },
            success: function(response) {
                $('#orders').modal('hide');
                var orderList = response.order_list;
                var customer = response.customer_data;
                $('#customer_input_data').val(customer);
                $('.pos_customer_id').val(response.customer_number);
                $('#order_list').html(orderList);
                total_calculation();
                get_pending_data()
                get_customer_data(response.customer_number)
                setTimeout(order_list_bottom, 100);
            },

            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
    // next oder
    $(document).on('click', '#next_order_btn', function() {
        location.reload();




    })


    // calcualtor js

    // Select all the from document using queryselectAll
    var keys = document.querySelectorAll('#calculator span');


    // Variable to store the reference to the last focused input field
    var lastFocusedInput = null;

    // Event listener to track focus on input fields
    document.querySelectorAll('.payment_methods_value').forEach(function(input) {
        input.addEventListener('focus', function() {
            // Update the last focused input field
            lastFocusedInput = this;
        });
    });

    // Event listener for digit spans
    document.querySelectorAll('.digit').forEach(function(span) {
        span.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default action of the click event

            console.log('Span clicked'); // Debugging: Check if click event is fired

            // Check if there is a last focused input field
            if (lastFocusedInput) {
                // Get the digit from the clicked span
                var digit = this.innerHTML;
                console.log('Digit:', digit); // Debugging: Check if correct digit is retrieved

                // Append the digit to the last focused input value
                lastFocusedInput.value += digit;
                console.log('Updated value:', lastFocusedInput.value); // Debugging: Check if input value is updated

                // Set focus back to the last focused input field
                lastFocusedInput.focus();
                payment_modal_calculation()
            }
        });
    });

    // Event listener for the backspace button
    document.querySelector('.back_space').addEventListener('click', function(event) {
        event.preventDefault();
        console.log('Backspace button clicked'); // Check if the event listener is triggered

        // Check if there is a last focused input field
        if (lastFocusedInput) {
            // Remove the last character from the input value
            lastFocusedInput.value = lastFocusedInput.value.slice(0, -1);
            console.log('Updated value:', lastFocusedInput.value); // Check if input value is updated

            // Set focus back to the last focused input field
            lastFocusedInput.focus();
            payment_modal_calculation(); // Assuming this function handles calculations after input changes
        }
    });











    // gety point amount
    $(document).on('click', '#get_total_point_value', function() {
         var payment_customer_point = $('.payment_customer_point_amount').val();
         $('.get_point_amount').val(payment_customer_point);
         payment_modal_calculation()

    });
    $(document).on('keyup', '.get_point_amount', function() {
        payment_modal_calculation()
    });

    $(document).on('keyup', '.payment_methods_value', function() {
        payment_modal_calculation()
    });
    // check payment modal calculations
    function payment_modal_calculation()
    {
        // point omr
        var total_points = $('.payment_customer_point_amount').val();
        var point_omr = $('.get_point_amount').val();
        if(point_omr == "")
        {
            point_omr =0;
        }
        if(parseFloat(point_omr) > parseFloat(total_points))
        {
            show_notification('error','<?php echo trans('messages.validation_amount_greater_than_lang',[],session('locale')); ?>');
            $('.get_point_amount').val("");
            point_omr = $('.get_point_amount').val();
            if(point_omr == "")
            {
                point_omr =0;
            }
        }
        // payment methods
        var sum = 0;
        var cash_sum = 0;
        $('.payment_methods_value').each(function() {
            var value = parseFloat($(this).val());
            var cash_type = $(this).attr('cash-type');
            if(cash_type == 1)
            {
                if (!isNaN(value)) {
                    cash_sum = value;
                }
            }
            else
            {
                if (!isNaN(value)) {
                    sum += value;
                }
            }

        });

        var final_omr = $('.grand_total').text();
        if(final_omr == "")
        {
            final_omr =0;
        }
        final_without_cash = parseFloat(sum) + parseFloat(point_omr);
        final_with_cash = parseFloat(cash_sum) + parseFloat(sum) + parseFloat(point_omr);

        if(cash_sum <= 0 &&  final_without_cash > final_omr)
        {
            show_notification('error','<?php echo trans('messages.validation_amount_greater_than_lang',[],session('locale')); ?>');
            $('.payment_methods_value').val('');
            $('.paid_point_amount').text('');
            $('.get_point_amount').val('');
            $('.remaining_point_amount_input').val(parseFloat(final_omr).toFixed(3));
            $('.remaining_point_amount').text(parseFloat(final_omr).toFixed(3));
            return false;
        }



        if(cash_sum > 0 && final_without_cash > final_omr)
        {
            show_notification('error','<?php echo trans('messages.validation_amount_greater_than_lang',[],session('locale')); ?>');
            $('.payment_methods_value').val('');
            $('.paid_point_amount').text('');
            $('.get_point_amount').val('');
            $('.remaining_point_amount_input').val(parseFloat(final_omr).toFixed(3));
            $('.remaining_point_amount').text(parseFloat(final_omr).toFixed(3));
            return false;
        }

        var remaining_omr = final_omr - final_with_cash;
        $('.paid_point_amount_input').val(point_omr);
        $('.paid_point_amount').text(point_omr);
        $('.remaining_point_amount_input').val(parseFloat(remaining_omr).toFixed(3));
        $('.remaining_point_amount').text(parseFloat(remaining_omr).toFixed(3));
    }

    function add_payment_method(id) {
        if ($('#'+id+'_acc').prop('checked')) {
            $('#payment_methods_value_id'+id).prop('readonly', false);
            $('#payment_methods_value_id'+id).val('');
            var status = $('#payment_methods_value_id'+id).attr('cash-type');
            if(status!=1)
            {
                $('#payment_methods_value_id'+id).val($('.grand_total').text());
            }
        } else {
            $('#payment_methods_value_id'+id).prop('readonly', true);
            $('#payment_methods_value_id'+id).val('');
        }

        payment_modal_calculation();
    }





    // search imei
    function searchImei() {
        var input, filter, div, a, imei, i, txtValue;
        input = document.getElementById('search_imei');
        filter = input.value.toUpperCase();
        div = document.getElementById("all_pro_imei");
        a = div.getElementsByTagName('a');

        var matchFound = false;

        for (i = 0; i < a.length; i++) {
            imei = a[i].textContent || a[i].innerText;
            txtValue = imei.toUpperCase();
            if (txtValue.indexOf(filter) > -1) {
                a[i].closest('.col-sm-2').style.display = "";
                matchFound = true;
            } else {
                a[i].closest('.col-sm-2').style.display = "none";
            }
        }

        // Show or hide the modal body based on match found
        var modalBody = document.querySelector('.modal-body');
        if (matchFound) {
            modalBody.style.display = "block";
        } else {
            modalBody.style.display = "none";
        }
    }


    //university






    </script>
