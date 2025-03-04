@extends('layouts.header')

@section('main')
    @push('title')
        <title> {{ trans('messages.products_lang', [], session('locale')) }}</title>
    @endpush
    <style>
    /* Make button full-width on small screens */
    .form-head .add-staff {
        width: auto;
    }

    .search-area {
        max-width: 250px;
        width: 100%;
    }

    /* Adjust table font size and padding for smaller screens */
    @media (max-width: 767px) {
        .form-head {
            flex-direction: column;
            align-items: flex-start;
        }

        .form-head .add-staff {
            width: 100%;
            margin-bottom: 10px;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .table th, .table td {
            padding: 10px 8px;
            font-size: 12px;
        }

        .table {
            font-size: 12px;
        }

        .checkbox {
            padding: 0;
        }
    }

    /* Adjust the padding for the form fields */
    .input-group {
        max-width: 300px;
    }
</style>
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class=""><a href="javascript:void(0)">Dashboard/</a></li>
                <li class="active"><a href="javascript:void(0)">product</a></li>
            </ol>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="all_product" class="table table-striped patient-list mb-4 dataTablesCard fs-14">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>product Name</th>
                                        <th>Category</th>
                                        <th>Barcode</th>
                                        <th>Purchase Price </th>
                                        <th>Added Quantity </th>
                                        <th>Total Purchase  </th>
                                        <th>Sales Price </th>
                                        <th>Product Type </th>
                                        <th>Branch</th>
                                        <th>Added By</th>
                                        <th>Added On </th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <<div class="modal fade" id="add_product_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">product Model</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            <form class="add_product">
                @csrf

                <div class="row ">
                    <input type="hidden" class="product_id" name="product_id">
                        <div class="col-xl-4">
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
                        <div class="col-xl-4">
                            <div class="form-group">
                                <label class="col-form-label">Category:</label>
                                <select class="category_id_1 form-control select2" id="category_id_1"
                                    name="category_id_stk[]">
                                    <option value="">choose</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="form-group">
                                <label class="col-form-label">Product_name:</label>
                                <input type="text" class="form-control product_name_1" name="product_name[]">
                            </div>
                        </div>


                    </div>

                    <div class="row">

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
                        <div class="col-xl-3">
                            <div class="form-group">
                                <label class="col-form-label">Purchase Price:</label>
                                <div class="input-group">
                                    <span class="input-group-text">OMR</span>
                                    <input type="text" class="form-control purchase_price_1 isnumber"
                                        onkeyup="calculateTotalPurchasePrice(1)"
                                        name="purchase_price[]">
                                        <input type="text" class="form-control purchase_price_old_1"
                                        name="purchase_price_old[]" hidden>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="form-group">
                                <label class="col-form-label">Sales Price:</label>
                                <div class="input-group">
                                    <span class="input-group-text">OMR</span>
                                    <input type="text" class="form-control sale_price_1 isnumber" id="sale_price_1" onkeyup="updateSalesPrice(1)" name="sale_price[]">
                                    <input type="text" class="form-control sale_price_old_1"
                                    name="sale_price_old[]" hidden>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="form-group">
                                <label class="col-form-label">Quantity:</label>
                                <input type="text" class="form-control quantity_1 isnumber1" onkeyup="check_qty(1)" name="quantity[]">
                                <input type="text" class="form-control quantity_old_1"
                                name="quantity_old[]" hidden>
                            </div>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-xl-3">
                            <div class="form-group">
                                <label class="col-form-label">Tax:</label>
                                <div class="input-group">
                                    <span class="input-group-text">%</span>
                                    <input type="text" class="form-control tax_1 isnumber" id="tax_1" onkeyup="updateSalesPriceWithTax(1)" name="tax[]">
                                </div>
                            </div>
                        </div>
                        <!-- Image Upload Section -->
                        <div class="col-xl-5 position-relative mt-4">
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
                        <div class="col-xl-4 mt-4">
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

                        <!-- Description Section -->
                        <div class="col-xl-12">
                            <div class="form-group">
                                <label class="col-form-label">Description:</label>
                                <textarea class="form-control description_1" name="description[]" rows="3"></textarea>
                            </div>
                        </div>

                        <!-- Product Type Radio Buttons -->

                    </div>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Data</button>
                  </div>
            </form>
          </div>

        </div>
      </div>
    </div>
</div>

@include('layouts.footer')
@endsection
