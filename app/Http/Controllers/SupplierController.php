<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Branch;
use App\Models\History;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class SupplierController extends Controller
{
    public function index(){

        $branches= Branch::all();
        $roles= Role::all();


        return view ('suppliers.supplier');
    }

    public function show_supplier()
    {

        $sno=0;

        $view_authsupplier= Supplier::all();
        if(count($view_authsupplier)>0)
        {
            foreach($view_authsupplier as $value)
            {

                $supplier_name='<a class-"patient-info ps-0" href="javascript:void(0);">'.$value->supplier_name.'</a>';

                $modal = '
                <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_supplier_modal" onclick=edit("'.$value->id.'")>
                    <i class="fa fa-pencil fs-18 text-success"></i>
                </a>
                <a href="javascript:void(0);" onclick=del("'.$value->id.'")>
                    <i class="fa fa-trash fs-18 text-danger"></i>
                </a>';

                $add_data=Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');


                $branch= Branch::where('id', $value->branch_id)->value('branch_name');


                $sno++;
                $json[] = array(
                    '<span class="patient-info ps-0">'. $sno . '</span>',
                    '<span class="text-nowrap ms-2">'.$supplier_name.'</span>',
                    '<span class="text-primary">' . $value->supplier_phone . '</span>',
                    '<span >' .  $branch . '</span>',
                    '<span >' . $value->added_by . '</span>',
                    '<span >' . $add_data . '</span>',
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

    public function add_supplier(Request $request){

        $user_id = Auth::id();
        $data= User::where('id', $user_id)->first();
        $user_name= $data->user_name;
        $branch= $data->branch_id;


        $supplier = new Supplier();

        $supplier->supplier_name = $request['supplier_name'];
        $supplier->supplier_email = $request['email'];
        $supplier->supplier_phone = $request['phone'];
        $supplier->branch_id = $branch;

        $supplier->notes = $request['notes'];
        $supplier->added_by = $user_name;
        $supplier->user_id = $user_id;
        $supplier->branch_id = $branch;

        $supplier->save();
        return response()->json(['supplier_id' => $supplier->id]);

    }

    public function edit_supplier(Request $request){
        // $supplier = new supplier();
        $supplier_id = $request->input('id');
        $supplier_data = Supplier::where('id', $supplier_id)->first();

        if (!$supplier_data) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.supplier_not_found', [], session('locale'))], 404);
        }

        // Add more attributes as needed
        $data = [
            'supplier_id' => $supplier_data->id,
            'supplier_name' => $supplier_data->supplier_name,
            'supplier_email' => $supplier_data->supplier_email,
            'supplier_phone' => $supplier_data->supplier_phone,
            'notes' => $supplier_data->notes,
        ];

        return response()->json($data);
    }

    public function update_supplier(Request $request){


        $supplier_id = $request->input('supplier_id');

        $supplier = Supplier::where('id', $supplier_id)->first();
        if (!$supplier) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.authsupplier_not_found', [], session('locale'))], 404);
        }

        $previousData = $supplier->only([
            'supplier_name', 'supplier_email', 'supplier_phone',  'branch_id',  'notes', 'supplier_id', 'added_by', 'created_at'
        ]);

        $user_id = Auth::id();
        $data= User::where('id', $user_id)->first();
        $user_name= $data->user_name;
        $branch= $data->branch_id;




        $supplier->supplier_name = $request['supplier_name'];
        $supplier->supplier_email = $request['email'];
        $supplier->supplier_phone = $request['phone'];
        $supplier->notes = $request['notes'];
        $supplier->added_by = $user_name;
        $supplier->user_id = $user_id;
        $supplier->save();

        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'suppliers';
        $history->function = 'update';
        $history->function_status = 1;
        $history->branch_id = $branch;
        $history->record_id = $supplier->id;
        $history->previous_data = json_encode($previousData);
        $history->updated_data = json_encode($supplier->only([
            'supplier_name', 'supplier_email', 'supplier_phone', 'branch_id',  'notes', 'supplier_id', 'added_by'
        ]));
        $history->added_by = $user_name;

        $history->save();
        return response()->json([trans('messages.success_lang', [], session('locale')) => trans('messages.supplier_update_lang', [], session('locale'))]);
    }

    public function delete_supplier(Request $request) {
        $supplier_id = $request->input('id');
        $supplier = Supplier::where('id', $supplier_id)->first();

        if (!$supplier) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.supplier_not_found', [], session('locale'))], 404);
        }

        // Store previous data before deletion
        $previousData = $supplier->only([
            'supplier_name', 'supplier_email', 'supplier_phone',  'branch_id',  'notes', 'supplier_id', 'added_by', 'created_at'
        ]);

        // Get current supplier info
        $user_id = Auth::id();
        $data= User::where('id', $user_id)->first();
        $user_name= $data->user_name;
        $branch= $data->branch_id;

        // Save history before deletion
        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'suppliers';
        $history->branch_id = $branch;
        $history->function = 'delete';
        $history->function_status = 2;
        $history->record_id = $supplier->id;
        $history->previous_data = json_encode($previousData);
        $history->added_by = $user_name;
        $history->save();

        $supplier->delete();

        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.supplier_deleted_lang', [], session('locale'))
        ]);
    }

}
