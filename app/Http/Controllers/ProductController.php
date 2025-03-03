<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Brand;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product_imei;
use Illuminate\Http\Request;
use App\Models\Purchase_imei;
use App\Models\PosOrderDetail;
use App\Models\Purchase_detail;
use Illuminate\Support\Facades\DB;
use App\Models\Product_qty_history;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        $categories= Category::all();

        $user = Auth::user();
        $permit = User::find($user->id)->permit_type;
        $permit_array = json_decode($permit, true);

        // if ($permit_array && in_array('2', $permit_array)) {

            return view('purchase.products');
        // } else {

        //     return redirect()->route('home');
        // }

    }
    public function show_product()
    {
        $sno=0;
        $view_product= Product::all();
        if(count($view_product)>0)
        {
            foreach($view_product as $value)
            {
                // product_name
                $title=$value->product_name;

                $title='<a  href="'.url('product_detail').'/'.$value->id.'">'.$title.'</a>';

                $modal='';
                $modal.='<a class="me-3 confirm-text text-primary" target="_blank" href="'.url('product_view').'/'.$value->id.'"><i class="fas fa-eye"></i></a>
                <a class="me-3 confirm-text text-primary" data-bs-toggle="modal" data-bs-target="#add_product_modal" onclick=edit("'.$value->id.'")><i class="fas fa-edit"></i></a>
                <a class="me-3 confirm-text text-danger"  onclick=del("'.$value->id.'") ><i class="fas fa-trash"></i></a>';
                // qty button
                if($value->quantity>0)
                {
                    $modal.='<a class="me-3 confirm-text text-success" onclick=get_product_qty("'.$value->id.'")><i class="fab fa-stack-exchange"></i></a>
                    ';
                }

                // damage undo button
                // $product_qty_history_count = Product_qty_history::where('product_id', $value->id)
                //                                                 ->where('source', 'damage')
                //                                                 ->where('status', 1)
                //                                                 ->count();
                // if($product_qty_history_count>0)
                // {
                //     $modal.='<a class="me-3 confirm-text text-danger" onclick=undo_damage_product("'.$value->id.'")><i class="fas fa-undo"></i></a>';
                // }

                // if($value->check_imei==1)
                // {
                //     $modal.='<a class="me-3 confirm-text text-warning" onclick=replace_pro_imei("'.$value->id.'")><i class="fas fa-exchange-alt"></i></a>';
                // }

                // $pro_sold = PosOrderDetail::where('product_id', $value->id)
                //                                                 ->count();
                // if($pro_sold<=0)
                // {
                //     $purchase_invoice = Purchase_Detail::where('barcode', $value->barcode)
                //                     ->pluck('invoice_no')
                //                     ->unique();

                //     $invoice_count = $purchase_invoice->count();
                //     if ($invoice_count == 1) {
                //         $single_invoice_no = $purchase_invoice->first();
                //         $purchase_data = Purchase::where('invoice_no', $single_invoice_no)->first();
                //         if($purchase_data->status == 1)
                //         {
                //             $modal.='<a class="me-3 confirm-text text-danger" onclick=send_item_back("'.$value->id.'")><i class="fas fa-backspace"></i></a>';
                //         }
                //     }
                // }


                // check remaining
                $category = Category::where('id', $value->category_id)->value('category_name');
                $branch = Branch::where('id', $value->branch_id)->value('branch_name');
                $supplier = Supplier::where('id', $value->supplier_id)->value('supplier_name');



                $add_data=Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');

                $sno++;
                $json[]= array(
                            $sno,
                            $title,
                            $value->barcode,
                            $category,
                            $branch,
                            $value->quantity,
                            $value->sale_price,
                            $value->added_by,
                            $add_data,
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

    //new

    public function edit_product(Request $request){

        $product_id = $request->input('id');


        $product_data = Product::where('id', $product_id)->first();



        if (!$product_data) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.product_not_found', [], session('locale'))], 404);
        }

        $data = [
            'quick_sale'=>$product_data->quick_sale,
            'category_id' => $product_data->category_id,
            'brand_id' => $product_data->brand_id,
            'product_name' => $product_data->product_name,
            'product_name_ar' => $product_data->product_name_ar,
            'min_sale_price' => $product_data->min_sale_price,
            'sale_price' => $product_data->sale_price,
        ];

        return response()->json($data);
    }

    public function update_product(Request $request){
        $user_id = Auth::id();
        $data= User::where('id', $user_id)->first();
        $user= $data->username;
        $product_id = $request->input('product_id');
        $product = Product::where('id', $product_id)->first();
        if (!$product) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.product_not_found', [], session('locale'))], 404);
        }
        $quick_sale = $request->has('quick_sale') ? 0 : 1;



        $product->category_id = $request->input('category_id');
        $product->brand_id = $request->input('brand_id');
        $product->product_name = $request->input('product_name');
        $product->product_name_ar = $request->input('product_name_ar');
        $product->min_sale_price = $request->input('min_sale_price');
        $product->sale_price = $request->input('sale_price');
        $product->quick_sale= $quick_sale;
        $product->updated_by = $user;
        $product->save();
        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.product_update_lang', [], session('locale'))
        ]);
    }

    public function delete_product(Request $request){
        $product_id = $request->input('id');
        $product = Product::where('id', $product_id)->first();
        if (!$product) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.product_not_found', [], session('locale'))], 404);
        }
        $product->delete();

        //
        DB::table('product_imeis')->where('product_id', $product_id)->delete();

        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.product_deleted_lang', [], session('locale'))
        ]);
    }

    //new end

    //product view
    public function product_view($id){

        $user = Auth::user();
        $permit = User::find($user->id)->permit_type;
        $permit_array = json_decode($permit, true);

        $product_view = Product::where ('id', $id)->first();
        $category = getColumnValue('categories','id',$product_view->category_id,'category_name');
        $brand = getColumnValue('brands','id',$product_view->brand_id,'brand_name');
        $store = getColumnValue('stores','id',$product_view->store_id,'store_name');
        $supplier = getColumnValue('suppliers','id',$product_view->supplier_id,'supplier_name');

        // product type
        if($product_view->product_type==1)
        {
            $product_type=trans('messages.retail_lang', [], session('locale'));
        }
        else
        {
            $product_type=trans('messages.spare_parts_lang', [], session('locale'));
        }

        // warranty type
        if($product_view->warranty_type==1)
        {
            $warranty_type=trans('messages.shop_lang', [], session('locale'))." : ".$product_view->warranty_days." ".trans('messages.days_lang', [], session('locale'));
        }
        else if($product_view->warranty_type==2)
        {
            $warranty_type=trans('messages.days_lang', [], session('locale'))." : ".$product_view->warranty_days." ".trans('messages.days_lang', [], session('locale'));
        }
        else if($product_view->warranty_type==3)
        {
            $warranty_type=trans('messages.none_lang', [], session('locale'));
        }

        $user = Auth::user();
        $permit = User::find($user->id)->permit_type;
        $permit_array = json_decode($permit, true);

        if ($permit_array && in_array('2', $permit_array)) {

            return view ('stock.product_view', compact('permit_array','product_view','category','brand','store','supplier'
                    ,'product_type','warranty_type'));
        } else {

            return redirect()->route('home');
        }


    }

    // get_product_qty
    public function get_product_qty(Request $request){
        $id = $request->input('id');
        $product = Product::where('id', $id)->first();
        $qty_div="";
        if ($product->quantity<=0) {
            return response()->json(['qty_status' => 2, 'qty_div' => ""]);
        }
        else
        {
            if($product->check_imei==1)
            {
                $qty_div.='<input type="hidden" class="product_id" name="product_id" value="'.$id.'" >
                <input type="hidden" name="stock_type" class="stock_type" value="2" ><div class="row">';
                $product_imei = Product_imei::where('barcode', $product->barcode)->get();
                $uniqueImeis = [];
                foreach ($product_imei as $key => $imei) {
                    if (!in_array($imei->imei, $uniqueImeis)) {

                        $qty_div.='<div class="col-md-2 col-6">
                                    <label class="checkboxs">
                                        <input type="checkbox" class="all_imeis" name="all_imeis[]" value="'.$imei->id.'" id="'.$imei->id.'_qty">
                                        <span class="checkmarks" for="'.$imei->id.'_qty"></span>'.$imei->imei.'
                                    </label>
                                </div> ';
                                $uniqueImeis[] = $imei->imei;
                    }
                }
                $qty_div.='</div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 col-12">
                                <div class="form-group">
                                    <label>'.trans('messages.reason_lang', [], session('locale')).'</label>
                                    <textarea  class="form-control reason" rows="3" name="reason"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-submit me-2 submit_form">'.trans('messages.submit_lang', [], session('locale')).'</button>
                            <a class="btn btn-cancel" data-bs-dismiss="modal">'.trans('messages.cancel_lang', [], session('locale')).'</a>
                        </div>';
            }
            else
            {
                $qty_div.='<input type="hidden" class="product_id" name="product_id" value="'.$id.'" >
                <input type="hidden" name="stock_type" class="stock_type" value="1" ><div class="row">
                            <div class="col-lg-3 col-sm-6 col-6">
                                <div class="form-group">
                                    <label>'.trans('messages.current_qty_lang', [], session('locale')).'</label>
                                    <input class="form-control current_qty" name="current_qty" readonly value="'.$product->quantity.'">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-6">
                                <div class="form-group">
                                    <label>'.trans('messages.damage_qty_lang', [], session('locale')).'</label>
                                    <input class="form-control damage_qty" name="damage_qty"  value="">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 col-12">
                                <div class="form-group">
                                    <label>'.trans('messages.reason_lang', [], session('locale')).'</label>
                                    <textarea  class="form-control reason" rows="3" name="reason"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-submit me-2 submit_form">'.trans('messages.submit_lang', [], session('locale')).'</button>
                            <a class="btn btn-cancel" data-bs-dismiss="modal">'.trans('messages.cancel_lang', [], session('locale')).'</a>
                        </div>';
            }
            return response()->json(['qty_status' => 1, 'qty_div' => $qty_div]);
        }
    }
    //

    // add damage qty
    public function add_damage_qty (Request $request)
    {


        $user_id = Auth::id();
        $data= User::where('id', $user_id)->first();
        $user= $data->username;
        $reason = $request['reason'];
        $product_id = $request['product_id'];

        // get product data
        $product_data = Product::where ('id', $product_id)->first();

        if($request['stock_type']==1)
        {
            $current_qty = $request['current_qty'];
            $damage_qty = $request['damage_qty'];
            $new_qty = $current_qty - $damage_qty;
            // product qty history
            $product_qty_history = new Product_qty_history();

            $product_qty_history->order_no ="";
            $product_qty_history->product_id =$product_id;
            $product_qty_history->barcode=$product_data->barcode;
            $product_qty_history->source='damage';
            $product_qty_history->type=2;
            $product_qty_history->previous_qty=$current_qty;
            $product_qty_history->given_qty=$damage_qty;
            $product_qty_history->new_qty=$new_qty;
            $product_qty_history->notes=$reason;
            $product_qty_history->added_by = $user;
            $product_qty_history->user_id = $user_id;
            $product_qty_history->save();

            // update qty
            $product_data->quantity=$new_qty;
            $product_data->save();
        }
        else
        {
            $total_qty=0;
            $all_in_one="";
            $all_imeis = $request['all_imeis'];
            for ($i=0; $i < count($all_imeis) ; $i++) {

                $imei_data = Product_imei::where('id', $all_imeis[$i])->first();
                if($i==count($all_imeis)-1)
                {
                    $all_in_one.=$imei_data['imei'];
                }
                else
                {
                    $all_in_one.=$imei_data['imei'].', ';
                }

                // delete iemi
                if ($imei_data) {
                    $imei_data->delete();
                }
            }

            $current_qty = $product_data['quantity'];
            $damage_qty = count($all_imeis);
            $new_qty = $current_qty - $damage_qty;

            // product qty history
            $product_qty_history = new Product_qty_history();

            $product_qty_history->order_no ="";
            $product_qty_history->product_id =$product_id;
            $product_qty_history->barcode=$product_data->barcode;
            $product_qty_history->imei=$all_in_one;
            $product_qty_history->source='damage';
            $product_qty_history->type=2;
            $product_qty_history->previous_qty=$current_qty;
            $product_qty_history->given_qty=$damage_qty;
            $product_qty_history->new_qty=$new_qty;
            $product_qty_history->notes=$reason;
            $product_qty_history->added_by = $user;
            $product_qty_history->user_id = $user_id;
            $product_qty_history->save();

            // update qty

            $product_data->quantity=$new_qty;
            $product_data->save();
        }
    }
    //

    // get_product_qty
    public function undo_damage_product(Request $request){
        $id = $request->input('id');
        // product data
        $product_data = Product::where('id', $id)->first();

        $product_qty_history = Product_qty_history::where('product_id', $id)
                                         ->where('source', 'damage')
                                         ->where('status', 1)
                                         ->get();

        $qty_div="";
        if (count($product_qty_history)<0) {
            return response()->json(['qty_status' => 2, 'qty_div' => ""]);
        }
        else
        {
            $qty_div.='<div class="row">
                        <div class="col-lg-1 col-sm-6 col-12 pb-5">
                            <label class="checkboxs">
                                <input type="checkbox" class="all_damge_requests" id="all_damage_request">
                                <span class="checkmarks" for="all_damage_request"></span>
                            </label>
                        </div>

                        <div class="col-lg-5 col-sm-6 col-6">
                            <div class="form-group">
                                <label>'.trans('messages.imei_lang', [], session('locale')).'</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-6">
                            <div class="form-group">
                                <label>'.trans('messages.current_qty_lang', [], session('locale')).'</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-6">
                            <div class="form-group">
                                <label>'.trans('messages.damage_qty_lang', [], session('locale')).'</label>
                            </div>
                        </div>
                    </div>';
            foreach ($product_qty_history as $key => $qty_history) {
                $damage_imei="";
                if(!empty($qty_history->imei))
                {
                    $imeis=explode(',', $qty_history->imei);
                    for ($i=0; $i < count($imeis) ; $i++) {
                        $damage_imei.="<span class='badges bg-lightgreen'>".$imeis[$i]."</span> ";
                    }
                    $stk_type='<input type="hidden" name="stock_type" class="undo_stock_type" value="2" >';
                }
                else
                {
                    // product_name
                    $product_name = getColumnValue('products','id',$product_data->id,'product_name');
                    $product_name_ar = getColumnValue('products','id',$product_data->id,'product_name_ar');
                    $title=$product_name;
                    if(empty($title))
                    {
                        $title=$product_name_ar;
                    }
                    $damage_imei.="<span class='badges bg-lightgreen'>".$title."</span> ";
                    $stk_type='<input type="hidden" name="stock_type" class="undo_stock_type" value="1" >';
                }
                $qty_div.='<input type="hidden" class="product_id" name="product_id" value="'.$id.'" >
                            '.$stk_type.'
                            <div class="row">
                                <div class="col-md-1 col-6">
                                    <label class="checkboxs">
                                        <input type="checkbox" class="single_damage_qty" name="all_damge_requests[]" type="checkbox" value="'.$qty_history->id.'" id="'.$qty_history->id.'_qty">
                                        <span class="checkmarks" for="'.$qty_history->id.'_qty"></span>
                                    </label>
                                </div>
                                <div class="col-lg-5 col-sm-6 col-6">
                                    <div class="form-group">
                                        '.$damage_imei.'
                                     </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-6">
                                    <div class="form-group">
                                        <input class="form-control undo_current_qty" name="undo_current_qty" readonly value="'.$product_data->quantity.'">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-6">
                                    <div class="form-group">
                                        <input class="form-control undo_damage_qty" value="'.$qty_history->given_qty.'" readonly name="undo_damage_qty">
                                    </div>
                                </div>
                            </div>';
            }
            $qty_div.='<div class="row">
                            <div class="col-lg-6 col-sm-12 col-12">
                                <div class="form-group">
                                    <label>'.trans('messages.reason_lang', [], session('locale')).'</label>
                                    <textarea  class="form-control undo_reason" rows="3" name="reason"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-submit me-2 submit_form">'.trans('messages.submit_lang', [], session('locale')).'</button>
                            <a class="btn btn-cancel" data-bs-dismiss="modal">'.trans('messages.cancel_lang', [], session('locale')).'</a>
                        </div>';

            return response()->json(['qty_status' => 1, 'qty_div' => $qty_div]);
        }
    }
    //

    // add undo damage qty
    public function add_undo_damage_qty (Request $request)
    {

        $user_id = Auth::id();
        $data= User::where('id', $user_id)->first();
        $user= $data->username;
        $reason = $request['reason'];
        $all_damge_requests = $request['all_damge_requests'];

        // undo damage items
        for ($i=0; $i < count($all_damge_requests) ; $i++) {
            // get product qty data
            $product_qty_history = Product_qty_history::where('id', $all_damge_requests[$i])->first();
            // get product data
            $product_data = Product::where ('id', $product_qty_history->product_id)->first();

            if(empty($product_qty_history->imei))
            {
                $current_qty = $product_data->quantity;
                $damage_qty = $product_qty_history->given_qty;
                $new_qty = $current_qty + $damage_qty;
                // product qty history
                $product_qty_history_save = new Product_qty_history();

                $product_qty_history_save->order_no ="";
                $product_qty_history_save->product_id =$product_qty_history->product_id;
                $product_qty_history_save->barcode=$product_data->barcode;
                $product_qty_history_save->source='undo_damage';
                $product_qty_history_save->type=1;
                $product_qty_history_save->previous_qty=$current_qty;
                $product_qty_history_save->given_qty=$damage_qty;
                $product_qty_history_save->new_qty=$new_qty;
                $product_qty_history_save->notes=$reason;
                $product_qty_history_save->added_by = $user;
                $product_qty_history_save->user_id = $user_id;
                $product_qty_history_save->save();

                // update qty
                $product_data->quantity=$new_qty;
                $product_data->save();
            }
            else
            {
                $current_qty = $product_data->quantity;
                $damage_qty = $product_qty_history->given_qty;
                $new_qty = $current_qty + $damage_qty;
                // product qty history
                $product_qty_history_save = new Product_qty_history();

                $product_qty_history_save->order_no ="";
                $product_qty_history_save->product_id = $product_qty_history->product_id;
                $product_qty_history_save->barcode= $product_data->barcode;
                $product_qty_history_save->imei= $product_qty_history->imei;
                $product_qty_history_save->source= 'undo_damage';
                $product_qty_history_save->type= 1;
                $product_qty_history_save->previous_qty= $current_qty;
                $product_qty_history_save->given_qty= $damage_qty;
                $product_qty_history_save->new_qty= $new_qty;
                $product_qty_history_save->notes=$reason;
                $product_qty_history_save->added_by = $user;
                $product_qty_history_save->user_id = $user_id;
                $product_qty_history_save->save();

                // update qty
                $product_data->quantity=$new_qty;
                $product_data->save();

                // add imei
                $all_imeis = $product_qty_history->imei;
                $undo_imeis = explode(',' , $all_imeis);
                for ($i=0; $i < count($undo_imeis) ; $i++) {

                    // add imei
                    $product_imei = new Product_imei();

                    $product_imei->product_id=$product_qty_history->product_id;
                    $product_imei->barcode=$product_data->barcode;
                    $product_imei->imei=$undo_imeis[$i];
                    $product_imei->added_by = $user;
                    $product_imei->user_id = $user_id;
                    $product_imei->save();
                }

            }
            // update histoy table
            $product_qty_history->status=2;
            $product_qty_history->save();
        }
    }
    //

    // replace  imei pro

    public function replace_pro_imei(Request $request){
        $id = $request->input('id');
        // product data
        $product_data = Product::where('id', $id)->first();


        $purchase_invoice = Purchase_Detail::where('barcode', $product_data['barcode'])
                                        ->groupBy('invoice_no')
                                        ->pluck('invoice_no');

        $invoice_nos = $purchase_invoice->toArray();

        if (count($invoice_nos) > 1) {
            $invoice_nos_string = implode(', ', $invoice_nos);
        } else {
            $invoice_nos_string = $invoice_nos[0];
        }
        return response()->json(['order_no' => $invoice_nos_string]);
    }
    //

    // add_replace_product
    public function add_replace_product (Request $request)
    {

        $user_id = Auth::id();
        $data= User::where('id', $user_id)->first();
        $user= $data->username;
        $notes = $request['notes'];
        $order_no = $request['order_no'];
        $current_imei = $request['current_imei'];
        $new_imei = $request['new_imei'];
        $product_id = $request['product_id'];

        $product_current_imei = Product_Imei::where ('imei', $current_imei)
                                            ->where ('product_id', $product_id)
                                            ->first();

        if(empty($product_current_imei))
        {
            $status = 2;
            return response()->json(['status' => $status]);
            exit;
        }

        // check duplication
        $product_new_imei = Product_Imei::where ('imei', $new_imei)
                                            ->first();
        if(!empty($product_new_imei))
        {
            $status = 3;
            return response()->json(['status' => $status]);
            exit;
        }

        //
         // get product data
        $product_data = Product::where ('id', $product_id)->first();

        $current_qty = $product_data->quantity;
        $damage_qty = 1;
        $new_qty = $current_qty - $damage_qty;
        // product qty history
        $product_qty_history_save = new Product_qty_history();

        $product_qty_history_save->order_no =$order_no;
        $product_qty_history_save->product_id =$product_id;
        $product_qty_history_save->barcode=$product_data->barcode;
        $product_qty_history_save->imei=$current_imei;
        $product_qty_history_save->source='replace product';
        $product_qty_history_save->type=2;
        $product_qty_history_save->previous_qty=$current_qty;
        $product_qty_history_save->given_qty=$damage_qty;
        $product_qty_history_save->new_qty=$new_qty;
        $product_qty_history_save->notes=$notes;
        $product_qty_history_save->added_by = $user;
        $product_qty_history_save->user_id = $user_id;
        $product_qty_history_save->save();

        // adding histoy
        $current_qty = $product_data->quantity-1;
        $damage_qty = 1;
        $new_qty = $current_qty + $damage_qty;
        // product qty history
        $product_qty_history_save = new Product_qty_history();

        $product_qty_history_save->order_no =$order_no;
        $product_qty_history_save->product_id =$product_id;
        $product_qty_history_save->barcode=$product_data->barcode;
        $product_qty_history_save->imei=$new_imei;
        $product_qty_history_save->source='replace product';
        $product_qty_history_save->type=1;
        $product_qty_history_save->previous_qty=$current_qty;
        $product_qty_history_save->given_qty=$damage_qty;
        $product_qty_history_save->new_qty=$new_qty;
        $product_qty_history_save->notes=$notes;
        $product_qty_history_save->added_by = $user;
        $product_qty_history_save->user_id = $user_id;
        $product_qty_history_save->save();

        // update imei
        $product_current_imei->imei = $new_imei;
        $product_current_imei->save();
        return response()->json(['status' => 1]);
    }
    //


    // qty audit report
    public function qty_audit(Request $request)
    {

        $user = Auth::user();
        $permit = User::find($user->id)->permit_type;
        $permit_array = json_decode($permit, true);

        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');
        $product_id = "";
        if($request['start_date'])
        {
            $start_date = $request['start_date'];
        }
        if($request['end_date'])
        {
            $end_date = $request['end_date'];
        }
        if($request['product_id'])
        {
            $product_id = $request['product_id'];
        }
        $product= product::all();

        if ($permit_array && in_array('4', $permit_array)) {

            return view('stock.qty_audit', compact('product', 'start_date' , 'end_date' , 'product_id', 'permit_array'));
        } else {

            return redirect()->route('home');
        }

    }


    // show qty audit
    public function show_qty_audit(Request $request)
    {
        $sno=0;

        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');
        $product_id = "";
        if($request['start_date'])
        {
            $start_date = $request['start_date'];
        }
        if($request['end_date'])
        {
            $end_date = $request['end_date'];
        }
        if($request['product_id'])
        {
            $product_id = $request['product_id'];
        }

        $query = Product_qty_history::whereDate('created_at', '>=', $start_date)
                                    ->whereDate('created_at', '<=', $end_date);
         if (!empty($product_id)) {
            $query->where('product_id', $product_id);
        }
        $product_qty_history = $query->orderBy('id')->get();


        if(count($product_qty_history)>0)
        {
            foreach($product_qty_history as $value)
            {
                // product_name
                $product_name = getColumnValue('products','id',$value->product_id,'product_name');
                $product_name_ar = getColumnValue('products','id',$value->product_id,'product_name_ar');
                $title=$product_name;
                if(empty($title))
                {
                    $title=$product_name_ar;
                }

                $title_name='<a  href="'.url('product_detail').'/'.$value->id.'">'.$title.'</a>';

                // source
                $source="";
                if ($value->source == "purchase") {
                    $source = "<span class='badges bg-lightgreen badges_table'>" . trans('messages.purchase_lang', [], session('locale')) . "</span>";
                } else if ($value->source == "damage") {
                    $source = "<span class='badges bg-lightgreen badges_table'>" . trans('messages.damage_lang', [], session('locale')) . "</span>";
                } else if ($value->source == "undo_damage") {
                    $source = "<span class='badges bg-lightgreen'>" . trans('messages.revert_damage_lang', [], session('locale')) . "</span>";
                } else if ($value->source == "sale") {
                    $source = "<span class='badges bg-lightgreen'>" . trans('messages.source_sale_lang', [], session('locale')) . "</span>";
                } else if ($value->source == "replace") {
                    $source = "<span class='badges bg-lightgreen'>" . trans('messages.source_replace_lang', [], session('locale')) . "</span>";
                } else if ($value->source == "replace_damage") {
                    $source = "<span class='badges bg-lightgreen'>" . trans('messages.source_replace_damage_lang', [], session('locale')) . "</span>";
                } else if ($value->source == "restore sale") {
                    $source = "<span class='badges bg-lightgreen'>" . trans('messages.source_restore_sale_lang', [], session('locale')) . "</span>";
                } else if ($value->source == "replace product") {
                    $source = "<span class='badges bg-lightgreen'>" . trans('messages.source_replace_product_lang', [], session('locale')) . "</span>";
                } else if ($value->source == "purchase return") {
                    $source = "<span class='badges bg-lightgreen'>" . trans('messages.source_purchase_return_lang', [], session('locale')) . "</span>";
                }

                // Qty type
                if ($value->type == 1) {
                    $stock_type = "<span class='text text-success'><b>" . trans('messages.in_lang', [], session('locale')) . "</b></span>";
                } else if ($value->type == 2) {
                    $stock_type = "<span class='text text-danger'><b>" . trans('messages.out_lang', [], session('locale')) . "</b></span>";
                }


                $user= User::where('id', $value->user_id)->first();
                $added_by= $user->username ?? '';
                // tim date
                $data_time=get_date_time($value->created_at);
                $sno++;
                $json[]= array(
                            $value->order_no,
                            $title_name,
                            $value->barcode,
                            $value->imei,
                            $value->previous_qty,
                            $value->given_qty." ".$stock_type,
                            $value->new_qty,
                            $source,
                            $value->notes,
                            $added_by,
                            $data_time,
                            $value->id,
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

    // product barcode

    public function product_barcode($id){

        $user = Auth::user();
        $permit = User::find($user->id)->permit_type;
        $permit_array = json_decode($permit, true);

        $product_view = Product::where('id', $id)->first();
        $category = getColumnValue('categories','id',$product_view->category_id,'category_name');
        $brand = getColumnValue('brands','id',$product_view->brand_id,'brand_name');
        $store = getColumnValue('stores','id',$product_view->store_id,'store_name');
        $supplier = getColumnValue('suppliers','id',$product_view->supplier_id,'supplier_name');
        $title = $product_view->product_name ?: $product_view->product_name_ar;
        $barcode = $product_view->barcode;

        if ($permit_array && in_array('2', $permit_array)) {
            return view('stock.product_barcode', compact('barcode', 'title', 'permit_array'));
        } else {
            return redirect()->route('home');
        }
    }

    // delete duplicate imei
    public function delete_imeis(){
        Product_imei::whereNotIn('id', function ($query) {
            $query->select(DB::raw('MIN(id)'))
                ->from('product_imeis')
                ->groupBy('imei');
        })
        ->delete();

        Purchase_imei::whereNotIn('id', function ($query) {
            $query->select(DB::raw('MIN(id)'))
                ->from('purchase_imeis')
                ->groupBy('imei');
        })
        ->delete();
    }

    public function delete_imei(){
        // Step 1: Get all products where check_imei is 1
        $products = Product::where('check_imei', 1)->get();

        // Step 2: Loop through each product and get the count of related ProductImei rows
        foreach ($products as $product) {
            $imeiCount = Product_imei::where('product_id', $product->id)->count();
            if($imeiCount != $product->quantity)
            {
                echo $product->barcode.'<br>';
            }
            // Step 3: Update the product quantity
            $product->quantity = $imeiCount;
            $product->save();
        }
    }

    // add_replace_product
    public function send_item_back (Request $request)
    {

        $user_id = Auth::id();
        $data= User::where('id', $user_id)->first();
        $user= $data->username;
        $id = $request['id'];
        // not sold item and multiple fatora
        $pro_sold = PosOrderDetail::where('product_id', $id)->count();
        if($pro_sold<=0)
        {
            $pro_data = Product::where('id', $id)->first();
            $purchase_invoice = Purchase_Detail::where('barcode', $pro_data->barcode)
                                                ->pluck('invoice_no')
                                                ->unique();

            $invoice_count = $purchase_invoice->count();
            if ($invoice_count == 1) {
                $single_invoice_no = $purchase_invoice->first();
                $purchase_data = Purchase::where('invoice_no', $single_invoice_no)->first();
                if($purchase_data->status == 1)
                {
                    $product_imei = Product_imei::where('barcode', $pro_data->barcode)->get();

                    if(count($product_imei)>0)
                    {

                        $all_in_one="";
                        $em=1;
                        foreach ($product_imei as $key => $imei) {
                            // take imeis in one variable
                            if($em==count($product_imei))
                            {
                                $all_in_one.=$imei->imei;
                            }
                            else
                            {
                                $all_in_one.=$imei->imei.', ';
                            }
                            // incerment in em
                            $em++;
                        }
                        Product_imei::where('barcode', $pro_data->barcode)->delete();
                        // product qty history
                        $product_qty_history = new Product_qty_history();

                        $product_qty_history->order_no =$single_invoice_no;
                        $product_qty_history->product_id =$pro_data->id;
                        $product_qty_history->barcode=$pro_data->barcode;
                        $product_qty_history->imei=$all_in_one;
                        $product_qty_history->source='purchase return';
                        $product_qty_history->type=2;
                        $product_qty_history->previous_qty=$pro_data->quantity;
                        $product_qty_history->given_qty=$pro_data->quantity;
                        $product_qty_history->new_qty=0;
                        $product_qty_history->added_by = $user;
                        $product_qty_history->user_id = $user_id;
                        $product_qty_history->save();
                    }
                    else
                    {
                        // product qty history
                        $product_qty_history = new Product_qty_history();

                        $product_qty_history->order_no =$single_invoice_no;
                        $product_qty_history->product_id =$pro_data->id;
                        $product_qty_history->barcode=$pro_data->barcode;
                        $product_qty_history->source='purchase return';
                        $product_qty_history->type=2;
                        $product_qty_history->previous_qty=$pro_data->quantity;
                        $product_qty_history->given_qty=$pro_data->quantity;
                        $product_qty_history->new_qty=0;
                        $product_qty_history->added_by = $user;
                        $product_qty_history->user_id = $user_id;
                        $product_qty_history->save();


                    }
                    // edit_status product
                    $purchase_pro = Purchase_Detail::where('barcode', $pro_data->barcode)->first();
                    $purchase_pro->status = 1;
                    $purchase_pro->save();
                    Product::where('barcode', $pro_data->barcode)->delete();

                    $status = 1;
                    return response()->json(['status' => 1]);
                    exit;
                }
                else
                {
                    $status = 2;
                    return response()->json(['status' => 1]);
                    exit;
                }
            }
            else
            {
                $status = 4;
                return response()->json(['status' => 1]);
                exit;
            }
        }
        else
        {
            $status = 3;
            return response()->json(['status' => 1]);
            exit;
        }

    }
    //


}
