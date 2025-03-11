<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Brand;
use App\Models\Branch;
use App\Models\History;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product_imei;
use Illuminate\Http\Request;
use App\Models\Purchase_imei;
use App\Models\PosOrderDetail;
use App\Models\Purchase_detail;
use App\Models\ProductQtyHistory;
use Illuminate\Support\Facades\DB;
use App\Models\Product_qty_history;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        $categories= Category::all();
        $stores= Branch::all();

        $user = Auth::user();
        $permit = User::find($user->id)->permit_type;
        $permit_array = json_decode($permit, true);

        // if ($permit_array && in_array('2', $permit_array)) {

            return view('purchase.products', compact('categories', 'stores'));
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



                // check remaining
                $category = Category::where('id', $value->category_id)->value('category_name');
                $branch = Branch::where('id', $value->store_id)->value('branch_name');
                $supplier = Supplier::where('id', $value->supplier_id)->value('supplier_name');



                $add_data=Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');
                $product_type = "";
                if ($value->product_type == 1) { // Use == for comparison
                    $product_type = 'Clinic Use';
                } else {
                    $product_type = 'For Sale';
                }

                $total_purchase = $value->purchase_price * $value->quantity;

                $stock_image = asset('images/dummy_images/no_image.jpg');
                if(!empty($value->stock_image))
                {
                    $stock_image = asset('images/product_images')."/".$value->stock_image;
                }
                $src = '<img src="'.$stock_image.'" class-"patient-info ps-0" style="max-width:40px">';


                $sno++;
                $json[]= array(
                            $sno,
                            '<span class="text-nowrap ms-2">' . $src .'  '. $title . '</span>',

                            $category,
                            $value->barcode,
                            $value->purchase_price,
                            $value->quantity,
                            $total_purchase,
                            $value->sale_price,
                            $product_type,
                            $branch,
                            // $value->quantity,
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

        $stock_image = $product_data->stock_image
        ? asset('images/product_images/' . $product_data->stock_image)
        : asset('images/dummy_images/cover-image-icon.png');


        $data = [
            'product_id'=>$product_data->id,
            'category_id' => $product_data->category_id,
            'branch_id' => $product_data->store_id,
            'product_name' => $product_data->product_name,
            'sale_price' => $product_data->sale_price,
            'purchase_price' => $product_data->purchase_price,
            'quantity' => $product_data->quantity,
            'barcode' => $product_data->barcode,
            'tax' => $product_data->tax,
            'stock_image' => $stock_image,
            'notes' => $product_data->notes,
            'product_type' => $product_data->product_type,

        ];

        return response()->json($data);
    }

    public function update_product(Request $request)
{
    $user_id = Auth::id();
    $data= User::where('id', $user_id)->first();
    $user= $data->user_name;
    $branch= $data->branch_id;


    // Find the product
    $product = Product::where('id', $request->product_id)->first();


    $previous_data = json_encode($product->only([
        'store_id', 'category_id', 'product_name', 'barcode', 'purchase_price',
        'sale_price', 'quantity', 'tax', 'product_type', 'description', 'stock_image'
    ]));
    // Update product details
    $product->store_id      = $request->store_id_stk;
    $product->category_id   = $request->category_id_stk;
    $product->product_name  = $request->product_name;
    $product->barcode       = $request->barcode;
    $product->purchase_price= $request->purchase_price;
    $product->sale_price    = $request->sale_price;
    $product->quantity      = $request->quantity;
    $product->tax           = $request->tax ?? 0;
    $product->product_type  = $request->product_type;
    $product->description   = $request->description;
    $product->updated_by    = $user;

    // Handle image upload
    if (!empty($product->stock_image)) {
        $oldImagePath = public_path('images/product_images/' . $product->stock_image);
        if (File::exists($oldImagePath)) {
            File::delete($oldImagePath);
        }
    }

    if ($request->hasFile('stock_image')) {
        $stock_image = time() . '.' . $request->file('stock_image')->extension();
        $request->file('stock_image')->move(public_path('images/product_images'), $stock_image);
        $product->stock_image = $stock_image; // Assign new image only if uploaded
    }
    $new_data = json_encode($product->only([
        'store_id', 'category_id', 'product_name', 'barcode', 'purchase_price',
        'sale_price', 'quantity', 'tax', 'product_type', 'description', 'stock_image'
    ]));

    $history = new History();
    $history->user_id = $user_id;
    $history->table_name = 'products';
    $history->function = 'update data';
    $history->function_status = 1;
    $history->branch_id = $branch;
    $history->record_id = $product->id;
    $history->previous_data = $previous_data;
    $history->updated_data = $new_data;
    $history->added_by = $user;
    $history->save();

    $product->save();

    return response()->json([
        'success' => trans('messages.product_update_lang', [], session('locale'))
    ], 200);
}


    public function delete_product(Request $request){

        $user_id = Auth::id();
        $data= User::where('id', $user_id)->first();
        $user= $data->user_name;
        $branch= $data->branch_id;

        $product_id = $request->input('id');
        $product = Product::where('id', $product_id)->first();
        if (!$product) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.product_not_found', [], session('locale'))], 404);
        }
        $product_data = json_encode($product->only([
            'store_id', 'category_id', 'product_name', 'barcode', 'purchase_price',
            'sale_price', 'quantity', 'tax', 'product_type', 'description', 'stock_image'
        ]));

        // Delete the product
        $product->delete();

        // Save history log for the deleted product
        $history = new History();
        $history->user_id = $user_id; // Add the user who performed the delete action
        $history->table_name = 'products';
        $history->function = 'delete data';
        $history->function_status = 2; // 4 for delete action
        $history->branch_id = $branch;
        $history->record_id = $product_id;
        $history->previous_data = $product_data; // Store the deleted product data as previous data
        $history->added_by = $user; // User who performed the delete
        $history->save();

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
        $product= Product::all();

        // if ($permit_array && in_array('4', $permit_array)) {

            return view('purchase.qty_audit', compact('product', 'start_date' , 'end_date' , 'product_id'));
        // } else {

        //     return redirect()->route('home');
        // }

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

        $query = ProductQtyHistory::whereDate('created_at', '>=', $start_date)
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
                $product_name= Product::where('id', $value->product_id)->value('product_name');
                $title=$product_name;


                $title_name='<a  href="'.url('product_detail').'/'.$value->id.'">'.$title.'</a>';

                // source
                $source="";
                if ($value->source == "Purchase") {
                    $source = "<span class='badges bg-lightgreen badges_table'>" . trans('messages.purchase_lang', [], session('locale')) . "</span>";
                } else if ($value->source == "sale") {
                    $source = "<span class='badges bg-lightgreen'>" . trans('messages.source_sale_lang', [], session('locale')) . "</span>";
                } else if ($value->source == "replace") {
                    $source = "<span class='badges bg-lightgreen'>" . trans('messages.source_replace_lang', [], session('locale')) . "</span>";
                }  else if ($value->source == "restore sale") {
                    $source = "<span class='badges bg-lightgreen'>" . trans('messages.source_restore_sale_lang', [], session('locale')) . "</span>";
                } else if ($value->source == "replace product") {
                    $source = "<span class='badges bg-lightgreen'>" . trans('messages.source_replace_product_lang', [], session('locale')) . "</span>";
                } else if ($value->source == "purchase return") {
                    $source = "<span class='badges bg-lightgreen'>" . trans('messages.source_purchase_return_lang', [], session('locale')) . "</span>";
                }

                // Qty tYpe
                if ($value->change_type == 1) {
                    $stock_type = "<span class='text text-success'><b>" . trans('messages.in_lang', [], session('locale')) . "</b></span>";
                } else if ($value->change_type == 2) {
                    $stock_type = "<span class='text text-danger'><b>" . trans('messages.out_lang', [], session('locale')) . "</b></span>";
                }


                $user= User::where('id', $value->user_id)->first();
                $added_by= $user->user_name ?? '';
                // tim date
                $data_time=Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');
                $sno++;
                $json[] = array(
                    // Check if order_no is null
                    $value->order_no ?
                        '<span class="badge badge-info">Order</span> ' . $value->order_no :
                        '<span class="badge badge-primary">Purchase</span> ' . $value->purchase_id, // Fallback to purchase_id
                    $title_name,
                    $value->barcode,
                    $value->previous_qty,
                    $value->new_qty . " " . $stock_type,
                    $value->current_qty,
                    $source,
                    $value->notes,
                    $added_by,
                    $data_time,
                    // $value->id,
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




}
