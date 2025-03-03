<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Branch;
use App\Models\Account;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index(){

        // $user_id = Auth::id();
        // $data= User::where('id', $user_id)->first();
        // $user= $data->user_name;
        // $branch_id= $data->user_branch;
        // $branches="";
        // if($data->user_type==1){
        // $branches= Branch::all();

        // }
        // else{
            $branches = Branch::all();
        //   }

        // if (!Auth::check()) {

            // return redirect()->route('login_page')->with('error', 'Please LogIn first()');
        // }

        // $user = Auth::user();


        // if (in_array(5, explode(',', $user->permit_type))) {

            return view('expense.account', compact('branches'));
        // } else {

        //     return redirect()->route('home')->with( 'error', 'You dont have Permission');
        // }

    }


    public function show_account()
    {
        $sno=0;

        $view_account= Account::all();
        if(count($view_account)>0)
        {
            foreach($view_account as $value)
            {

                $account_name='<a href="javascript:void(0);">'.$value->account_name.'</a>';
                $modal = '
                <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_account_modal" onclick=edit("'.$value->id.'")>
                    <i class="fa fa-pencil fs-18 text-success"></i>
                </a>
                <a href="javascript:void(0);" onclick=del("'.$value->id.'")>
                    <i class="fa fa-trash fs-18 text-danger"></i>
                </a>';
                $add_data=Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');
                if($value->account_type) {
                    $account_type = trans('accounts.normal_account', [], session('locale'));
                } else {
                    $account_type = trans('accounts.saving_account', [], session('locale'));
                }

                $sno++;
                $json[] = array(
                    $sno,
                    $account_name .' '.(  $value->account_branch),
                    $value->account_no .' '. $value->account_type,
                    $value->opening_balance,
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

    public function add_account(Request $request){

        $user_id = Auth::id();
        $data= User::where('id', $user_id)->first();
        $user= $data->user_name;
        $branch_id=  $request['branch_id'];

        $account = new Account();
        $account->account_name = $request['account_name'];
        $account->account_branch = $request['account_branch'];
        $account->account_no = $request['account_no'];
        $account->opening_balance = $request['opening_balance'];
        $account->commission = $request['commission'];
        $account->account_type = $request['account_type'];
        $account->account_status = $request['account_status'];
        $account->branch_id = $branch_id;
        $account->notes = $request['notes'];
        $account->added_by = $user;
        $account->user_id =  $user_id;
        $account->save();
        return response()->json(['account_id' => $account->id]);

    }

    public function edit_account(Request $request){
        $account_id = $request->input('id');
        $account_data = Account::where('id', $account_id)->first();

        if (!$account_data) {
            return response()->json(['error' => trans('messages.account_not_found_lang', [], session('locale'))], 404);
        }
        $data = [
            'account_id' => $account_data->id,
            'account_name' => $account_data->account_name,
            'account_branch' => $account_data->account_branch,
            'account_no' => $account_data->account_no,
            'opening_balance' => $account_data->opening_balance,
            'commission' => $account_data->commission,
            'account_type' => $account_data->account_type,
            'branch_id' => $account_data->branch_id,
            'account_status' => $account_data->account_status,
            'notes' => $account_data->notes,
        ];

        return response()->json($data);
    }

    public function update_account(Request $request) {
        $account_id = $request->input('account_id');
        $account = Account::where('id', $account_id)->first();

        if (!$account) {
            return response()->json(['error' => trans('messages.account_not_found_lang', [], session('locale'))], 404);
        }

        // Store previous data for history
        $previousData = $account->only([
            'account_name', 'account_branch', 'account_no', 'opening_balance',
            'commission', 'account_type', 'account_status', 'branch_id', 'notes', 'added_by', 'user_id'
        ]);

        // Fetch logged-in user details
        $user_id = Auth::id();
        $user_name = Auth::user()->user_name;
        $branch_id = $request->input('branch_id');

        // Update account details
        $account->account_name = $request->input('account_name');
        $account->account_branch = $request->input('account_branch');
        $account->account_no = $request->input('account_no');
        $account->opening_balance = $request->input('opening_balance');
        $account->commission = $request->input('commission');
        $account->account_type = $request->input('account_type');
        $account->account_status = $request->input('account_status');
        $account->branch_id = $branch_id;
        $account->notes = $request->input('notes');
        $account->added_by = $user_name;
        $account->user_id = $user_id;
        $account->save();

        // Store update history
        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'accounts'; // Corrected table name
        $history->function = 'update';
        $history->function_status = 1;
        $history->branch_id = $branch_id;
        $history->record_id = $account->id; // Corrected to account ID
        $history->previous_data = json_encode($previousData);
        $history->updated_data = json_encode($account->only([
            'account_name', 'account_branch', 'account_no', 'opening_balance',
            'commission', 'account_type', 'account_status', 'branch_id', 'notes', 'added_by', 'user_id'
        ]));
        $history->added_by = $user_name;
        $history->save();

        return response()->json(['success' => trans('messages.data_update_success_lang', [], session('locale'))]);
    }


    public function delete_account(Request $request){

        $user_id = Auth::id();
        $user= User::where('id', $user_id)->first();
        $branch_id= $user->branch_id;

        $account_id = $request->input('id');
        $account = Account::where('id', $account_id)->first();
        if (!$account) {
            return response()->json(['error' => trans('messages.account_not_found_lang', [], session('locale'))], 404);
        }

        $previousData = $account->only([
            'account_name', 'account_branch', 'account_no', 'opening_balance',
            'commission', 'account_type', 'account_status', 'branch_id', 'notes', 'added_by', 'user_id'
        ]);

        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'accounts'; // Corrected table name
        $history->function = 'delete';
        $history->function_status = 2;
        $history->branch_id = $branch_id;
        $history->record_id = $account->id; // Corrected to account ID
        $history->previous_data = json_encode($previousData);
        $history->added_by = $user->user_name;
        $history->save();
        $account->delete();
        return response()->json(['success' => trans('messages.delete_success_lang', [], session('locale'))]);
    }
}
