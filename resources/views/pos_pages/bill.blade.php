<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>BILL</title>

    <link rel="icon" type="image/png" sizes="16x16" href="">

    <style>
        @font-face {
            font-family: tahoma;
            /*src: url(../../assets/dist/fonts/Tajawal-Light.ttf);*/
        }

        body {
            font-family: tahoma;
        }

        #barcode {
            font-size: 20px;
            height: 80px;
            margin-left: -20px;
        }

        .outer_div {
            justify-content: center;
            height: 115px;
            width: 200px;
            text-align: center;
        }

        #brnd_name {
            margin: 10px;
            background: black;
            color: white;
            font-family: monospace;

        }
        
    </style>
    <style>
        @page {
            size: A3
        }
    </style>
    <!-- Custom styles for this document -->
    <style>
        body {
            /*          font-family: Tahoma;
*/
            font-size: 14px;
        }

        /* th {
            border-bottom: 1px solid black;
        }

        td {
            border-bottom: 1px solid black;
            font-size: 12px
        } */

        table {
            width: 100%;
            border-collapse: collapse;
            
        }
        td, th {
            padding: 0;
        }
        p {
            margin: 2px 0; /* Reduced margin to reduce space between paragraphs */
        }
    
        hr {
            margin: 5px 0; /* Reduced margin to shrink space around hr */
        }
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
    <style>
        @page {
            size: 80mm 100mm
        }

        /* output size */
        body.receipt .sheet {
            width: 80mm;
            height: auto;
            padding: 5mm;
        }

        /* sheet size */

        @media print {
            body.receipt {
                width: 80mm
            }
        }

        /* fix for Chrome */
    </style>

</head>

<body class="receipt">
    <section class="sheet">

        <p align="center"><img src="{{ asset('images/setting_images/' . $shop->invo_logo) }}"
                style="width:150px;height:50px; margin-right: 11px"></p>
        <p align="center">{{ $shop->company_address }} العنوان </p>
        <p align="center"> CR No: {{ $shop->cr_no }} </p>
        <p align="center"> Contact: {{ $invo->contact }} هاتف </p>
        <hr style="border-top: 1px dotted #000000;">

        <table style="border-collapse: collapse;" >
            <tr>
                <p align="center"> Invoice-الفاتورة</p>
                <td style="font-size: 13px;text-align: center; border: none;">Date/التاريخ</td>
                <td style="font-size: 13px;text-align: center; border: none;">Time/الوقت</td>
                <td style="font-size: 13px;text-align: center; border: none;">Invoice No./رقم الفاتورة</td>
            </tr>
            <tr>
                <td class="text-center" style="border: none;">{{ $order->created_at->format('d-m-y') }}</td>
                <td class="text-center" style="border: none;">{{ $order->created_at->format('h:i A') }}</td>
                <td class="text-center" style="border: none;">{{ $order->order_no }}</td>
            </tr>
        </table>
        <hr style="border-top: 1px dotted #000000;">
        <table>

            <tr>

                <th style="font-size: 13px;text-align: center;border: none;">#</th>
                <th style="font-size: 13px;text-align: center;border: none;">Item</th>
                <th style="font-size: 13px;text-align: center;border: none;">Qty</th>
                <th style="font-size: 13px;text-align: center;border: none;">UP</th>
                <th style="font-size: 13px;text-align: center;border: none;">AMT</th>
            </tr>

            <tr>
                <th style="font-size: 13px;text-align: center;border: none;"></th>
                <th style="font-size: 13px;text-align: center;border: none;">المنتج</th>
                <th style="font-size: 13px;text-align: center;border: none;">الكمية</th>
                <th style="font-size: 13px;text-align: center;border: none;">سعر الوحدة</th>
                <th style="font-size: 13px;text-align: center;border: none;">المجموع</th>
            </tr>

            @php
                $serial = 1;
                $total_price = 0;
            @endphp

            @foreach ($detail as $item)
                <tr>
                    <td class="text-center">{{ $serial++ }}</td>

                    <td class="text-center">
                        {{ $item->product->product_name_ar ? $item->product->product_name_ar : '' }}<br>
                        {{ $item->product->product_name }}<br>
                        {{ $item->item_barcode }}
                    </td>

                    <td class="text-center">{{ $item->item_quantity }}</td>
                    <td class="text-center">{{ $item->item_price }}</td>
                    <td class="text-center">{{ number_format($item->item_total, 3) }}</td>
                </tr>
            @endforeach
        </table>

        <hr style="border-top: 1px dotted #000000;">
        <table>

             <tr>
                <td colspan="3"></td>

                <td style="width:20%;text-align:right;border-bottom: 1px dotted #000;">{{ number_format($order->total_amount + $order->total_discount , 3) }} </td>
                <td style="width:35%;text-align:right;border-bottom: 1px dotted #000;">
                    <p style="margin-top: 2px;">المجموع</p>
                    <p>Total Amount</p>
                </td>
            </tr>
            <tr>
                <td colspan="3"></td>

                <td style="width:20%;text-align:right;border-bottom: 1px dotted #000;">{{ $order->total_discount }} </td>
                <td style="width:35%;text-align:right;border-bottom: 1px dotted #000;">
                    <p style="margin-top: 2px;">الخصم ر.ع</p>
                    <p>Discount</p>
                </td>
            </tr>
            <tr>
                <td colspan="3"></td>

                <td style="width:20%;text-align:right;border-bottom: 1px dotted #000;"> {{ number_format($order->total_amount, 3) }} </td>
                <td style="width:35%;text-align:right;border-bottom: 1px dotted #000;">
                    <p style="margin-top: 2px;">صافي المجموع</p>
                    <p>Net Amount</p>
                </td>
            </tr>
            <tr>
                <td colspan="3"></td>

                <td style="width:20%;text-align:right;border-bottom: 1px dotted #000;">{{ number_format($point_amount, 3) }} </td>
                <td style="width:35%;text-align:right;border-bottom: 1px dotted #000;">
                    <p style="margin-top: 2px;">المبلغ المذفوع بالىقاط</p>
                    <p>Payment by points </p>
                </td>
            </tr>
            <tr>
                <td colspan="3" ></td>

                <td style="twidth:20%;ext-align:right;border-bottom: 1px dotted #000;">{{ $account_name}}</td>
                <td  style="width:35%;text-align:right;border-bottom: 1px dotted #000;">
                    <p style="margin-top: 2px;">طريقة الدفع</p>
                    <p>Payment Method </p>
                </td>
            </tr>
            <tr>
                <td colspan="3" ></td>

                <td style="twidth:20%;ext-align:right;border-bottom: 1px dotted #000;">{{ number_format($order->cash_back, 3) }}</td>
                <td  style="width:35%;text-align:right;border-bottom: 1px dotted #000;">
                    <p style="margin-top: 2px;">المبلغ الباقي للعميل</p>
                    <p>Customer Balance </p>
                </td>
            </tr>
        </table><br>
        <p style="text-align: center">www.superelection.com</p>

        <center>
            <div id="barcode"></div>
        </center>
        {{-- <p align="center" style="font-size:12px; !important;">الرجاء االحتفاظ بالفاتورة لالستبدال شكرا لتسوقكم</p> --}}
        <p align="center" style="font-size:12px; !important; white-space:pre-line">{{ $invo->footer }}</p>


    </section>
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/jquery-barcode.js') }}"></script>


    <script type="text/javascript">
        $(document).ready(function() {
            // Barcode generation
            $("#barcode").barcode("{{ $order->order_no }}", "code128", { barWidth: 3 });


            window.print();


            setTimeout(function() {
                window.close();
            }, 2000);
        });
    </script>

</body>

</html>
