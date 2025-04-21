<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\History;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function index(){

        return view ('vouchers.voucher');

        }

        public function show_voucher()
        {

            $sno=0;

            $view_authvoucher= voucher::all();
            if(count($view_authvoucher)>0)
            {
                foreach($view_authvoucher as $value)
                {

                    $voucher_name='<a class-"patient-info ps-0" href="javascript:void(0);">'.$value->code.'</a>';
                    $modal="";
                    if($value->status==1)
                    { 
                        $modal = '
                        <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_voucher_modal" onclick=edit("'.$value->id.'")>
                            <i class="fa fa-pencil fs-18 text-success"></i>
                        </a>
                        <a href="javascript:void(0);" onclick=del("'.$value->id.'")>
                            <i class="fa fa-trash fs-18 text-danger"></i>
                        </a>';
                    }

                    $add_data=Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');




                    $sno++;
                    $json[] = array(
                        '<span class="patient-info ps-0">'. $sno . '</span>',
                        '<span class="text-nowrap ms-2">' . $voucher_name . '</span>',
                        '<span class="text-nowrap ms-2">' . $value->amount . '</span>',
                        '<span class="text-primary">' . $value->notes . '</span>',
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

        public function add_voucher(Request $request){

            $user_id = Auth::id();
            $data= User::where('id', $user_id )->first();
            $user_name= $data->user_name;
            $branch_id= $data->branch_id;



            $voucher = new voucher();

            $voucher->code = $request['code'];
            $voucher->amount = $request['amount'];
            $voucher->notes = $request['notes'];
            $voucher->added_by = $user_name;
            $voucher->status = 1;
            $voucher->user_id = $user_id;
            $voucher->branch_id = $branch_id;
            $voucher->save();
            return response()->json(['voucher_id' => $voucher->id]);

        }


        public function edit_voucher(Request $request){

            $voucher_id = $request->input('id');

            $voucher_data = voucher::where('id', $voucher_id)->first();
            $data = [
                'voucher_id' => $voucher_data->id,
                'code' => $voucher_data->code,
                'amount' => $voucher_data->amount,
                'notes' => $voucher_data->notes,
                // Add more attributes as needed
            ];

            return response()->json($data);
        }

        public function update_voucher(Request $request)
    {
        $voucher_id = $request->input('voucher_id');
        $user_id = Auth::id();

        $user = User::where('id', $user_id)->first();
        $user_name = $user->user_name;
        $branch_id = $user->branch_id;


        $voucher = voucher::where('id', $voucher_id)->first();

        if (!$voucher) {
            return response()->json(['error' => trans('messages.voucher_not_found', [], session('locale'))], 404);
        }

        $previousData = $voucher->only(['code','amount','status',  'notes', 'added_by', 'user_id', 'created_at']);

        $voucher->code = $request->input('code');
        $voucher->amount = $request->input('amount');
        $voucher->notes = $request->input('notes');
        $voucher->updated_by = $user_name; 
        $voucher->save();

        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'vouchers';
        $history->function = 'update';
        $history->function_status = 1;
        $history->branch_id = $branch_id;
        $history->record_id = $voucher->id;
        $history->previous_data = json_encode($previousData);
        $history->updated_data = json_encode($voucher->only([
            'code', 'amount', 'notes', 'updated_by', 'user_id','status'
        ]));
        $history->added_by = $user_name;
        $history->save();

        return response()->json([trans('messages.success_lang', [], session('locale')) => trans('messages.user_update_lang', [], session('locale'))]);
    }


    public function delete_voucher(Request $request) {


        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        $user_name = $user->user_name;
        $branch_id = $user->branch_id;
        $voucher_id = $request->input('id');
        $voucher = voucher::where('id', $voucher_id)->first();

        if (!$voucher) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.voucher_not_found', [], session('locale'))], 404);
        }

        $previousData = $voucher->only([
            'code','amount', 'status','notes', 'added_by', 'user_id', 'created_at'
        ]);

        

        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'vouchers';
        $history->function = 'delete';
        $history->function_status = 2;
        $history->branch_id = $branch_id;
        $history->record_id = $voucher->id;
        $history->previous_data = json_encode($previousData);

        $history->added_by = $user_name;
        $history->save();
        $voucher->delete();

        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.user_deleted_lang', [], session('locale'))
        ]);
    }
}
