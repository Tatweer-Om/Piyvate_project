<?php
	$locale = session('locale');
	if($locale=="ar")
	{
		$dir="dir='rtl'";
	}
	else
	{
		$dir="dir='ltr'";
	}
?>
<!DOCTYPE html>
<html lang="en" <?php echo $dir; ?>>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		<meta name="description" content="POS - Bootstrap Admin Template">
		<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
		<meta name="author" content="Dreamguys - Bootstrap Admin Template">
		<meta name="robots" content="noindex, nofollow">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @stack('title')



		<!-- Favicon -->
		<link rel="shortcut icon" type="image/x-icon" href="{{asset('img/favicon.png')}}">

		<!-- Bootstrap CSS -->
		<?php if($locale=="ar"){ ?>
			<link rel="stylesheet" href="{{asset('css/rtl/bootstrap.rtl.min.css')}}">
		<?php } else {?>
			<link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
		<?php }?>
        {{-- datapicker --}}
        <link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}">

		<!-- Animation CSS -->
		<link rel="stylesheet" href="{{asset('css/rtl/animate.css')}}">

        <!-- Select2 CSS -->
		<link rel="stylesheet" href="{{asset('css/select2.min.css')}}">

		<!-- Datatable CSS -->
		{{-- <link rel="stylesheet" href="{{asset('css/rtl/dataTables.bootstrap4.min.css')}}"> --}}
		{{-- <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap5.min.css')}}"> --}}


		<!-- Fontawesome CSS -->
		<link rel="stylesheet" href="{{asset('fonts/css/all.min.css')}}">


        {{-- toastr css --}}
        <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.css')}}">

		<!-- Main CSS -->
		<?php if($locale=="ar"){ ?>
			<link rel="stylesheet" href="{{asset('css/rtl/style.css')}}">
			{{-- <link rel="stylesheet" href="{{asset('css/style_setting_rtl.css')}}"> --}}
			<link rel="stylesheet" href="{{asset('css/rtl/dataTables.bootstrap4.min.css')}}">
		<?php } else {?>
			<link rel="stylesheet" href="{{asset('css/style.css')}}">
			{{-- <link rel="stylesheet" href="{{asset('css/style_setting.css')}}"> --}}
			<link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}">

		<?php }?>

{{-- qout --}}
        <link href="{{ asset('css/invoice_css/success.css') }}" rel="stylesheet" type="text/css" />

		{{-- tags input css --}}
		<link rel="stylesheet" href="{{asset('css/tags_css/bootstrap-tagsinput.css')}}">

		<!-- jQuery UI CSS -->
		<link rel="stylesheet" href="{{asset('css/jquery-ui.min.css')}}">

		{{-- carousel css --}}
		<link rel="stylesheet" href="{{asset('plugins/owlcarousel/owl.carousel.min.css')}}">

        {{-- custom css --}}

		<link rel="stylesheet" href="{{asset('css/custom.css')}}">

        <link rel="stylesheet" href="{{asset('css/summer/summernote-bs4.min.css')}}">

        <style type="text/css">
            body
            {
                font-family: Tajawal;
                font-size: 16px!important;

                /*font-weight: bold*/
            }
            .bold
            {
                font-weight: bold;
            }
            .header_td
            {
               border-top:0px!important;
               font-weight: bold;
            }
            /* table td, th
            {
               border: 1px solid;
            } */
            .data_tr > td
            {
                border-top: 0px;
                border-bottom: 0px;
            }
           /* .item_table td
            {
                text-align: left;
            }*/
            .bold-header
            {
                font-weight: bolder;
            }
            table {
                font-size: 24px; /* Increase the font size to your desired value */
            }
        </style>

	</head>

	<body>
 <div class="main-wrapper">
     <div class="container-fluid" style="background-color: #fff">
         <div class="row">
             <div class="col-md-12 text-center">
            <img src="{{ asset('images/setting_images/' . $shop->invo_logo) }}" style="width: 10%;">
             </div>
         </div>
         <div class="row">
             <div class="col-md-4"></div>
             <div class="col-md-4 text-center">
                 <h1 style="font-size: 40px;font-weight: bold;">Sell Bill- فاتورة بيع </h1>

             </div>
             <div class="col-md-4 text-right">
                 <h1 style="margin-right: 65px;font-size: 40px;font-weight: bold;"></h1>
             </div>
         </div>
         <div class="row" style="padding: 52px;">
            <table width="100%" border="1" style="border-collapse: collapse;">
                <tr>
                    <td style="padding: 10px; border: 1px solid #d3cccc5e;">
                        <div >Invoice No:</div>
                    </td>
                    <td style="padding: 10px; border: 1px solid #d3cccc5e;">
                        {{ $order_no ?? 'N/A' }}
                    </td>
                    <td style="padding: 10px; border: 1px solid #d3cccc5e;">
                        <div >Customer Name:</div>
                    </td>
                    <td style="padding: 10px; border: 1px solid #d3cccc5e;">
                        {{ $customer_name ?? 'N/A' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px; border: 1px solid #d3cccc5e;">
                        <div >Purchase Date:</div>
                    </td>
                    <td style="padding: 10px; border: 1px solid #d3cccc5e;">
                        {{ $created_at ? $created_at->format('d-m-Y') : 'N/A' }}
                    </td>


                    <td style="padding: 10px; border: 1px solid #d3cccc5e;">
                        <div >Customer Phone:</div>
                    </td>
                    <td style="padding: 10px; border: 1px solid #d3cccc5e;">
                        {{ $customer_phone ?? 'N/A' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px; border: 1px solid #d3cccc5e;">
                        <div >Branch:</div>
                    </td>
                    <td style="padding: 10px; border: 1px solid #d3cccc5e;">
                        {{ $stor ?? 'N/A' }}
                    </td>
                    <td style="padding: 10px; border: 1px solid #d3cccc5e;">
                        <div >Customer ID:</div>
                    </td>
                    <td style="padding: 10px; border: 1px solid #d3cccc5e;">
                        {{ $customer_no ?? 'N/A' }}
                    </td>
                </tr>
            </table>
            <div style="height: 20px;"></div>
            <table width="100%" style="border-collapse: collapse;">
                <tr>
                    <th class="bold-header" style="width: 5%; border: 1px solid #d3cccc5e; text-align: center;">SR #</th>
                    <th class="bold-header" style="width: 15%; border: 1px solid #d3cccc5e; text-align: center;">BARCODE</th>
                    <th class="bold-header" style="width: 45%; border: 1px solid #d3cccc5e; text-align: center;">DESCRIPTION</th>
                    <th class="bold-header" style="width: 5%; border: 1px solid #d3cccc5e; text-align: center;">QTY</th>
                    <th class="bold-header" style="width: 5%; border: 1px solid #d3cccc5e; text-align: center;">UNIT PRICE</th>
                    <th class="bold-header" style="width: 5%; border: 1px solid #d3cccc5e; text-align: center;">AMOUNT (R.0)</th>
                </tr>
                @php $serial = 1; $total_price = 0; @endphp
                @foreach ($detail as $item)
                <tr>
                    <td style="padding: 5px; border: 1px solid #d3cccc5e; text-align: center;">{{ $serial++ }}</td>
                    <td style="padding: 5px; border: 1px solid #d3cccc5e; text-align: center;">

                        {{ $item->item_barcode }}
                    </td>
                    <td style="padding: 5px; border: 1px solid #d3cccc5e; text-align: center;">
                        <span>
                            {{ $item->product->product_name }}
                            {{ $item->product->product_name_ar ? $item->product->product_name_ar : '' }}

                        </span>
                    </td>
                    <td style="padding: 5px; border: 1px solid #d3cccc5e; text-align: center;">
                        <span>{{ $item->item_quantity }}</span>
                    </td>
                    <td style="padding: 5px; border: 1px solid #d3cccc5e; text-align: center;">
                        <span>
                            {{ $item->item_price }}
                        </span>
                    </td>
                    <td style="padding: 5px; border: 1px solid #d3cccc5e; text-align: center;">{{ number_format($item->item_total, 3) }}</td>
                </tr>
                @php $total_price += $item->item_total ?? 0; @endphp
                @endforeach

                <tr>
                    <td style="border: 1px solid #d3cccc5e;"></td>
                    <td style="border: 1px solid #d3cccc5e;"></td>
                    <td style="border: 1px solid #d3cccc5e;"></td>
                    <td style="border: 1px solid #d3cccc5e;"></td>
                    <td style="border: 1px solid #d3cccc5e;"></td>
                    <td style="border: 1px solid #d3cccc5e;"></td>
                </tr>

                <tr>
                    <td  class="bold-header" style=" text-align: right;"></td>
                    <td  class="bold-header" style=" text-align: right;"></td>
                    <td  class="bold-header" style=" text-align: right;"></td>
                    <td  class="bold-header" style=" text-align: right;"></td>
                    <td  class="bold-header" style="border: 1px solid #d3cccc5e; text-align: right;">Total Price</td>
                    <td style="padding: 5px; border: 1px solid #d3cccc5e; text-align: center;">{{ number_format($total_price, 3) }}</td>

                </tr>
                <tr>
                    <td  class="bold-header" style=" text-align: right;"></td>
                    <td  class="bold-header" style=" text-align: right;"></td>
                    <td  class="bold-header" style=" text-align: right;"></td>
                    <td  class="bold-header" style=" text-align: right;"></td>
                    <td  class="bold-header" style="border: 1px solid #d3cccc5e; text-align: right;">DISCOUNT</td>
                    <td style="padding: 5px; border: 1px solid #d3cccc5e; text-align: center;">{{ number_format( $order->total_discount , 3) }} </td>

                </tr>
                <tr>
                    <td  class="bold-header" style=" text-align: right;"></td>
                    <td  class="bold-header" style=" text-align: right;"></td>
                    <td  class="bold-header" style=" text-align: right;"></td>
                    <td  class="bold-header" style=" text-align: right;"></td>
                    <td  class="bold-header" style="border: 1px solid #d3cccc5e; text-align: right;">NET AMOUNT</td>
                    <td style="padding: 5px; border: 1px solid #d3cccc5e; text-align: center;">{{ number_format($total_price - $order->total_discount, 3) }} </td>

                </tr>
            </table>

        </div>

        {{-- <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4 text-center">
                <h1 style="font-size: 40px;font-weight: bold;">Sell Bill- فاتورة بيع</h1>

            </div>
            <div class="col-md-4 text-right">
                <h1 style="margin-right: 65px;font-size: 40px;font-weight: bold;"></h1>
            </div>
        </div>

        <div class="row" style="padding-left: 250px;">
            <div class="row">
                <div class="col-lg-1">
                </div>
                <div class="col-lg-2">
                    <div style="padding: 10px; padding-right:2px;">
                        <div>Invoice No:</div>
                        <div>Purchase Date:</div>
                        <div>Branch:</div>

                    </div>
                </div>
                <div class="col-lg-2">
                    <div style="padding: 10px;">
                        <div>{{ $order_no ?? 'N/A' }}</div>
                        <div>{{ $created_at ? $created_at->format('d-m-Y') : 'N/A' }}</div>
                        <div>{{ $stor ?? 'N/A' }}</div>


                    </div>
                </div>
                <div class="col-lg-2">
                    <div style="padding: 10px;">
                        <div>Customer Name:</div>
                        <div>Customer Phone:</div>
                        <div>Customer ID:</div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div style="padding: 10px;">
                        <div>{{ $customer_name ?? 'N/A' }}</div>
                        <div>{{ $customer_phone ?? 'N/A' }}</div>
                        <div>{{ $customer_no ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>



        <div style="height: 20px;"></div>


        <div class="row">
            <div class="col-lg-12" style="text-align: center;">
                <div class="bold-header" style="display: inline-block; width: 5%;">Sr #</div>
                <div class="bold-header" style="display: inline-block; width: 15%;">BARCODE</div>
                <div class="bold-header" style="display: inline-block; width: 45%;">DESCRIPTION</div>
                <div class="bold-header" style="display: inline-block; width: 5%;">QTY</div>
                <div class="bold-header" style="display: inline-block; width: 10%;">UNIT PRICE</div>
                <div class="bold-header" style="display: inline-block; width: 10%;">AMOUNT (R.0)</div>
            </div>
        </div>

        @php $serial = 1; $total_price = 0; @endphp
        @foreach ($detail as $item)
        <div class="row">
            <div class="col-lg-12" style="text-align: center;">
                <div style="display: inline-block; width: 5%;">{{ $serial++ }}</div>
                <div style="display: inline-block; width: 15%;">{{ $item->item_barcode }}</div>
                <div style="display: inline-block; width: 45%;">
                    <span>
                        {{ $item->product->product_name_ar ? $item->product->product_name_ar : '' }}<br>
                        {{ $item->product->product_name }}<br>

                    </span>
                </div>
                <div style="display: inline-block; width: 5%;">{{ $item->item_quantity }}</div>
                <div style="display: inline-block; width: 10%;">
                    <span>
                        {{ $item->item_price }}
                    </span>
                </div>
                <div style="display: inline-block; width: 10%;">{{ number_format($item->item_total, 3) }}</div>
            </div>
        </div><br><br><br><br>
        @php $total_price += $item->item_total ?? 0; @endphp
        @endforeach

        <!-- Total Price -->
        <div class="row">
            <div class="col-lg-7" style="text-align: right; margin-top: 10px;">
                <div class="bold-header">Total Price</div>
            </div>
            <div class="col-lg-2" style="text-align: right; margin-top: 10px;">
                {{ number_format($total_price, 3) }}
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7" style="text-align: right; margin-top: 10px;">
                <div class="bold-header">DISCOUNT</div>
            </div>
            <div class="col-lg-2" style="text-align: right; margin-top: 10px;">
                {{ number_format($order->total_discount, 3) }}
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7" style="text-align: right; margin-top: 10px;">
                <div class="bold-header">NET AMOUNT</div>
            </div>
            <div class="col-lg-2" style="text-align: right; margin-top: 10px;">
                {{ number_format($total_price - $order->total_discount, 3) }}
            </div>
        </div>

     </div>
 </div> --}}


 <script src="{{  asset('js/jquery-3.6.0.min.js')}}"></script>


 <!-- Feather Icon JS -->
 <script src="{{  asset('js/feather.min.js')}}"></script>

 <!-- Slimscroll JS -->
 <script src="{{  asset('js/jquery.slimscroll.min.js')}}"></script>

 <!-- Datatable JS -->
  <script src="{{  asset('js/jquery.dataTables.min.js')}}"></script>
 <script src="{{  asset('js/dataTables.bootstrap5.min.js')}}"></script>
 {{-- <script src="{{  asset('js/dataTables.bootstrap4.min.js')}}"></script> --}}
  {{-- <script src="{{  asset('js/jquery_repair.dataTables.min.js')}}"></script> --}}

 <!-- Bootstrap Core JS -->
 <script src="{{  asset('js/bootstrap.bundle.min.js')}}"></script>

 <!-- Select2 JS -->
 <script src="{{  asset('js/select2.min.js')}}"></script>
 <script src="{{  asset('js/select_repair.min.js')}}"></script>

 <script src="{{  asset('plugins/select2/js/custom-select.js')}}"></script>

 <!-- Datetimepicker JS -->
 <script src="{{  asset('js/moment.min.js')}}"></script>
 <script src="{{  asset('js/bootstrap-datetimepicker.min.js')}}"></script>

 <!-- Mask JS -->
 <script src="{{  asset('js/jquery.maskedinput.min.js')}}"></script>

 <!-- Chart JS -->
 <script src="{{  asset('js/apexcharts.min.js')}}"></script>
 <script src="{{  asset('js/chart-data.js')}}"></script>

 {{-- image js --}}
 <script src="{{  asset('plugins/fileupload/fileupload.min.js') }}"></script>

 {{-- toastr js --}}
 <script src="{{  asset('plugins/toastr/toastr.min.js')}}"></script>
 <script src="{{  asset('plugins/toastr/toastr.js')}}"></script>

 {{-- tags js --}}
 <script src="{{  asset('js/tags_js/bootstrap-tagsinput.min.js')}}"></script>
 <script src="{{  asset('js/tags_js/typeahead.bundle.min.js')}}"></script>

 {{-- barcode js --}}
 <script src="{{  asset('js/JsBarcode.all.min.js')}}"></script>


 <!-- jQuery UI library -->
 <script src="{{  asset('js/jquery-ui.min.js')}}"></script>

 {{-- caousel js --}}
 <script src="{{  asset('plugins/owlcarousel/owl.carousel.min.js') }}"></script>

  <!-- Sweetalert 2 -->
 <script src="{{  asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
 <script src="{{  asset('plugins/sweetalert/sweetalerts.min.js')}}"></script>
 <script src="{{  asset('plugins/summer/summernote-bs4.min.js')}}"></script>

 {{-- //settingsjs --}}
 <script src="{{  asset('js/ResizeSensor.js')}}"></script>
 <script src="{{  asset('js/theia-sticky-sidebar.js')}}"></script>




 {{-- multiple select --}}
 <script src="{{  asset('select2_js/select2.min.js')}}"></script>



 <!-- Custom JS -->
 <script src="{{  asset('js/script.js')}}"></script>
 @include('custom_js.custom_js')


 <script type="text/javascript">
    $(document).ready(function() {

        window.print();


        setTimeout(function() {
            window.close();
        }, 2000);
    });
</script>
</body>
</html>
