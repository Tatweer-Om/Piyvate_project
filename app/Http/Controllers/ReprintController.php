<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Store;
use App\Models\Account;
use App\Models\Product;
use App\Models\Customer;
use App\Models\PosOrder;
use App\Models\PosPayment;
use App\Models\Posinvodata;
use App\Models\Settingdata;
use App\Models\Product_imei;
use Illuminate\Http\Request;
use App\Models\PaymentExpense;
use App\Models\PosOrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ReprintController extends Controller
{
    public function index(){

        $user = Auth::user();
        $permit = User::find($user->id)->permit_type;
        $permit_array = json_decode($permit, true);
        if ($permit_array && in_array('12', $permit_array)){

            return view ('pos_pages.reprint', compact('permit_array'));
        } else {

            return redirect()->route('home');
        }


    }

    public function show_order()
        {
            $sno=0;

            $orders= PosOrder::all();
            if(count($orders)>0)
            {
                foreach($orders as $value)
                {
                    $payment= PosPayment::where('order_no', $value->order_no)->first();
                    $customer= Customer::where('id', $value->customer_id)->first();
                    $customer_name = $customer ? '<a href="javascript:void(0);">' . $customer->customer_name . '</a>' : '';
                    $order_no='<a href="javascript:void(0);">'.$value->order_no.'</a>';
                    $customer='<a href="javascript:void(0);">'.$customer_name.'</a>';
                    $total_amount='<a href="javascript:void(0);">'.$value->total_amount.'</a>';
                    $discount='<a href="javascript:void(0);">'.$value->total_discount.'</a>';
                    $paid_amount='<a href="javascript:void(0);">'.$value->paid_amount.'</a>';
                    $remaining_amount = $payment ? '<a href="javascript:void(0);">' . $payment->remaining_amount . '</a>' : '<a href="javascript:void(0);">N/A</a>';
                    $date = '<a href="javascript:void(0);">'.Carbon::parse($value->created_at)->format('d-m-Y h:i A').'</a>';
                    $modal = '
                  <a class="me-3 " href="'.url('pos_bill').'/'.$value->order_no.'">
                  <i class="fas fa-print"> </i>
                  </a>
                  <a class="me-3 " href="'.url('a5_print').'/'.$value->order_no.'">
                  <i class="fas fa-receipt"></i>
                  </a>
                  <a class="me-3 confirm-text delete" href="'.url('delete_order').'/'.$value->order_no.'">
                  <i class="fas fa-trash"></i>
                  </a>';

                    $add_data=get_date_only($value->created_at);

                    $sno++;
                    $json[]= array(
                                $sno,
                                $order_no,
                                $customer,
                                $total_amount,
                                $discount,
                                $paid_amount,
                                $remaining_amount,
                                $date,
                                $modal
                            );
                }
                $response = array();
                $response['success'] = true;
                $response['aaData'] = $json;
                echo json_encode($response);
            }
            else
            {
                $response = array();
                $response['sEcho'] = 0;
                $response['iTotalRecords'] = 0;
                $response['iTotalDisplayRecords'] = 0;
                $response['aaData'] = [];
                echo json_encode($response);
            }
        }


public function delete_order($order_no)
{

    $user_id = Auth::id();
    $data= User::where('id', $user_id)->first();
    $user= $data->username;

    DB::beginTransaction();

    try {

        $PosOrder = PosOrder::where('order_no', $order_no)->first();


        if($PosOrder){
            $items = PosOrderDetail::where('order_no', $order_no)->get();


            PosOrderDetail::where('order_id', $PosOrder->id)->delete();
            PosPayment::where('order_id', $PosOrder->id)->delete();
            PaymentExpense::where('order_id', $PosOrder->id)->delete();
            $PosOrder->delete();

            foreach($items as $item) {
                $product = Product::where('id', $item->product_id)->first();
                if($product){
                    $product->quantity += $item->item_quantity;
                    $product->save();
                }
                if(!empty($item->item_imei) && $item->item_imei!="undefined")
                {
                    $product_imei = new Product_imei();

                    $product_imei->product_id=$item->product_id;
                    $product_imei->barcode=$product->barcode;
                    $product_imei->imei=$item->item_imei;
                    $product_imei->added_by = $user;
                    $product_imei->user_id = $user_id;
                    $product_imei->save();
                }
            }
            DB::commit();

            Session::flash('success', trans('messages.order_deleted_success_lang', [], session('locale')));
        } else {
            Session::flash('error', trans('messages.order_not_found_lang', [], session('locale')));
        }
    } catch (\Exception $e) {
        DB::rollback();
        Session::flash('error', trans('messages.error_occurred_lang', [], session('locale')));
    }

    return redirect('reprint');
}



public function a5_print($order_no)
{

    $order = PosOrder::where('order_no', $order_no)->first();
    $created_at= $order->created_at;
    $order_no = $order->order_no;
    $store= Store::first();
    $stor= $store->store_name;
    $payment = PosPayment::where('order_no', $order_no)->first();
    $payment_method = $payment->account_id;
    $account = Account::where('id', $payment_method )->first();
    $account_name = $account ? $account->account_name : null;
    $customer = Customer::find($order->customer_id);

        $customer_name = $customer->customer_name ?? 'N/A';
        $customer_phone = $customer->customer_phone ?? 'N/A';
        $national_id = $customer->national_id ?? 'N/A';
        $customer_no = $customer->customer_number ?? 'N/A';

    $detail = PosOrderDetail::where('order_no', $order_no)
    ->with('product')
    ->get();
    $shop = Settingdata::first();
    $invo = Posinvodata::first();
    $payment= PosPayment::where('order_no', $order_no)->first();
    $account_id= $payment->account_id;
    $acc= Account::where('account_id', $account_id)->first();
    if($acc)
    {
        $acc_name= $acc->account_name;

    }
    else{
        $acc_name= null;
    }
    $user = User::where('id', $order->user_id)->first();

    return view('pos_pages.a5', compact('order', 'created_at', 'shop', 'payment', 'invo','detail',
     'payment','acc_name','user','customer_name', 'stor','customer', 'order_no', 'customer_phone','national_id', 'customer_no', 'account_name' ));
}

}
