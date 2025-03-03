@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.purchase', [], session('locale')) }}</title>
    @endpush

    <div class="content-body">
        <div class="container mt-5">

            <!-- /add -->
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">{{ trans('messages.supplier_&_invoice_lang', [], session('locale')) }}</h5>
                    </div>
                    <form class="add_purchase_product" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="" class="total_product">
                        <div class="row ">
                            <div class="col-xl-3 ">
                                <div class="form-group">
                                    <label class="col-form-label">Invoice No:</label>
                                    <input type="text" class="form-control invoice_no" name="invoice_no">
                                    <span class="invoice_err text-danger small"></span>
                                </div>
                            </div>
                            <div class="col-xl-3">
                                <div class="form-group">
                                    <label class="col-form-label">Supplier:</label>
                                    <select class="supplier_id form-control" name="supplier_id_stk" id="supplier_id">
                                        <option value="">Choose</option>
                                        @foreach ($supplier as $supp)
                                            <option value="{{ $supp->id }}">{{ $supp->supplier_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-3 ">
                                <div class="form-group">
                                    <label class="col-form-label">Purchase Date:</label>
                                    <input type="date" class="form-control purchase_date datetimepicker"
                                        value="<?php echo date('Y-m-d'); ?>" name="purchase_date">
                                </div>
                            </div>

                            <div class="col-xl-3 ">
                                <div class="form-group">
                                    <label class="col-form-label">Invoice Price:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">OMR</span>
                                        <input type="text" class="form-control invoice_price isnumber"
                                            name="invoice_price">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-3 mb-3">
                                <div class="form-group">
                                    <label
                                        class="col-form-label">{{ trans('messages.total_price_lang', [], session('locale')) }}:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">OMR</span>
                                        <input type="text" class="form-control" id="total_price" value="0.000" readonly>
                                        <input type="hidden" id="total_price_input" name="total_price">
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-xl-3 mb-3">
                                        <div class="form-group">
                                            <label class="col-form-label">{{ trans('messages.total_tax_lang', [], session('locale')) }}:</label>
                                            <div class="input-group">
                                                <span class="input-group-text">OMR</span>
                                                <input type="text" class="form-control" id="total_tax" value="0.000" name="total_tax">
                                                <input type="hidden" id="total_tax_input" >
                                            </div>
                                        </div>
                                    </div> --}}
                            <div class="col-xl-3 mb-3">
                                <div class="form-group">
                                    <label
                                        class="col-form-label">{{ trans('messages.total_shipping_charges_lang', [], session('locale')) }}:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">OMR</span>
                                        <input type="text" class="form-control" id="total_shipping" value="0.000"
                                            name="total_shipping">
                                        <input type="hidden" id="total_shipping_input">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">


                            <div class="col-xl-3 d-none">
                                <div class="form-group">
                                    <label class="col-form-label">Tax Type:</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tax_type"
                                            id="tax_not_available" value="2" checked>
                                        <label class="form-check-label" for="tax_not_available">
                                            No Tax Available
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tax_type" id="tax_available"
                                            value="1">
                                        <label class="form-check-label" for="tax_available">
                                            Tax Available
                                        </label>
                                    </div>

                                    <!-- Tax Input (Initially Hidden) -->
                                    <div id="tax_input_div" class="mt-2 d-none">
                                        <label class="col-form-label">OMR Tax:</label>
                                        <input type="text" class="form-control" name="omr_tax" id="omr_tax"
                                            placeholder="Enter Tax Amount">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-4 position-relative">
                                    <div class="form-group">
                                        <label class="col-form-label">Invoice Receipt:</label>
                                        <div class="image-upload border rounded p-2 text-center"
                                            style="height: auto; position: relative;">
                                            <input type="file" name="receipt_file" class="d-none" id="receiptFile"
                                                accept="image/*,.pdf,.doc,.docx,.xls,.xlsx">

                                            <!-- Close (X) Button -->
                                            <span id="removeFile"
                                                class="position-absolute bg-danger text-white rounded-circle px-2"
                                                style="cursor: pointer; top: 5px; right: 5px; display: none; font-size: 16px;"
                                                onclick="removeFile()">&times;</span>

                                            <label for="receiptFile" class="cursor-pointer">
                                                <div class="image-uploads">
                                                    <img id="filePreview"
                                                        src="{{ asset('images/dummy_images/file.png') }}" alt="File"
                                                        style="width: 30px;">
                                                    <h4 id="fileName" class="text-muted" style="font-size: 14px;">Drag
                                                        and drop a file here</h4>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    document.getElementById('receiptFile').addEventListener('change', function(event) {
                                        let file = event.target.files[0];
                                        let preview = document.getElementById('filePreview');
                                        let fileNameDisplay = document.getElementById('fileName');
                                        let removeButton = document.getElementById('removeFile');

                                        if (file) {
                                            let fileName = file.name.toLowerCase();
                                            let fileType = file.type;

                                            // Set file name display
                                            fileNameDisplay.textContent = file.name;

                                            // Handle image preview
                                            if (fileType.startsWith('image')) {
                                                let reader = new FileReader();
                                                reader.onload = function(e) {
                                                    preview.src = e.target.result;
                                                };
                                                reader.readAsDataURL(file);
                                            } else {
                                                // Handle document previews
                                                if (fileName.endsWith('.pdf')) {
                                                    preview.src = "{{ asset('images/dummy_images/pdf.png') }}";
                                                } else if (fileName.endsWith('.doc') || fileName.endsWith('.docx')) {
                                                    preview.src = "{{ asset('images/dummy_images/word.jpeg') }}";
                                                } else if (fileName.endsWith('.xls') || fileName.endsWith('.xlsx')) {
                                                    preview.src = "{{ asset('images/dummy_images/excel.jpeg') }}";
                                                } else {
                                                    preview.src = "{{ asset('images/dummy_images/file.png') }}";
                                                }
                                            }

                                            // Show remove (×) button
                                            removeButton.style.display = 'block';
                                        }
                                    });

                                    function removeFile() {
                                        let preview = document.getElementById('filePreview');
                                        let fileNameDisplay = document.getElementById('fileName');
                                        let fileInput = document.getElementById('receiptFile');
                                        let removeButton = document.getElementById('removeFile');

                                        // Reset file input
                                        fileInput.value = '';

                                        // Reset preview and file name
                                        preview.src = "{{ asset('images/dummy_images/file.png') }}";
                                        fileNameDisplay.textContent = 'Drag and drop a file here';

                                        // Hide remove (×) button
                                        removeButton.style.display = 'none';
                                    }
                                </script>

                                <div class="col-xl-8">
                                    <div class="form-group">
                                        <label class="col-form-label">Note:</label>
                                        <textarea class="form-control notes" rows="3" name="notes"></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>



                        <div class="card-header">
                            <h5 class="card-title">Inventory_Detail</h5>
                        </div>
                        <div class="stocks_class stock_no_1">
                            <div class="row p-2">
                                <div class="col-md-12">
                                    <h4>stock 1</h4>
                                </div>
                            </div>

                            <div class="row p-2">
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <label class="col-form-label">stores:</label>
                                        <select class="store_id_1 form-control select2" id="store_id_1"
                                            name="store_id_stk[]">
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
                                        <select class="category_id_1 form-control select2" id="category_id_1"
                                            name="category_id_stk[]">
                                            <option value="">choose</option>
                                            @foreach ($categorys as $category)
                                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Product_name:</label>
                                        <input type="text" class="form-control product_name_1" name="product_name[]">
                                    </div>
                                </div>

                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Barcode Generator:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control barcodes barcode_1"
                                                onkeyup="search_barcode('1')" onchange="search_barcode('1')"
                                                name="barcode[]">

                                            <span class="input-group-text" onclick="get_rand_barcode(1)">
                                                <i class="fas fa-barcode"></i>
                                            </span>

                                        </div>
                                        <span class="barcode_err_1 text-danger small"></span>
                                    </div>
                                </div>

                            </div>

                            <div class="row p-2">
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Purchase Price:</label>
                                        <div class="input-group">
                                            <span class="input-group-text">OMR</span>
                                            <input type="text" class="form-control purchase_price_1 isnumber"
                                                onkeyup="get_profit_percent(1)" onkeyup="calculateTotalPurchasePrice(1)"
                                                name="purchase_price[]">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Sales Price:</label>
                                        <div class="input-group">
                                            <span class="input-group-text">OMR</span>
                                            <input type="text" class="form-control sale_price_1 isnumber" id="sale_price_1" onkeyup="updateSalesPrice(1)" name="sale_price[]">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Quantity:</label>
                                        <input type="text" class="form-control quantity_1 isnumber1" onkeyup="check_qty(1)" name="quantity[]">
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Tax:</label>
                                        <div class="input-group">
                                            <span class="input-group-text">%</span>
                                            <input type="text" class="form-control tax_1 isnumber" id="tax_1" onkeyup="updateSalesPriceWithTax(1)" name="tax[]">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <!-- Image Upload Section -->
                                <div class="col-xl-4 position-relative mt-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Upload Image</label>

                                        <!-- Hidden File Input -->
                                        <input type="file" class="d-none image" name="stock_image_1" id="stock_img_1"
                                            accept="image/*"
                                            onchange="previewImage(event, 'stock_img_tag_1', 'remove_stock_img')">

                                        <!-- Clickable Image Preview -->
                                        <label for="stock_img_1">
                                            <img src="{{ asset('images/dummy_images/no_image.jpg') }}"
                                                id="stock_img_tag_1" class="img-thumbnail mt-2"
                                                style="width: 100px; cursor: pointer;">
                                        </label>

                                        <!-- Remove Button (X) -->
                                        <span id="remove_stock_img"
                                            class="position-absolute top-0 end-0 bg-danger text-white rounded-circle px-2"
                                            style="cursor: pointer; display: none;"
                                            onclick="removeImage('stock_img_tag_1', 'stock_img_1', 'remove_stock_img')">
                                            &times;
                                        </span>
                                    </div>
                                </div>

                                <!-- Description Section -->
                                <div class="col-xl-5">
                                    <div class="form-group">
                                        <label class="col-form-label">Description:</label>
                                        <textarea class="form-control description_1" name="description[]" rows="3"></textarea>
                                    </div>
                                </div>

                                <!-- Product Type Radio Buttons -->
                                <div class="col-xl-3 mt-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Product Type:</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="product_type_1"
                                                id="product_sale_1" value="2">
                                            <label class="form-check-label" for="product_sale_1">Sale</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input product_clinic_1" type="radio"
                                                name="product_type_1" id="product_clinic_1" value="1">
                                            <label class="form-check-label" for="product_clinic_1">Clinic</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="more_stk"></div>
                        <div class="row">
                            <div class="col-xl-12 d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">
                                    {{ trans('messages.close_lang', [], session('locale')) }}
                                </button>
                                <a id="add_more_stk_btn" class="btn btn-secondary">
                                    {{ trans('messages.add_stock_lang', [], session('locale')) }}
                                </a>
                                <button type="submit" class="btn btn-primary submit_form">
                                    {{ trans('messages.add_data_lang', [], session('locale')) }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <!-- /add -->
        </div>
    </div>
    </div>



    @include('layouts.footer')
@endsection
