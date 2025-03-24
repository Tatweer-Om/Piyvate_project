<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\History;
use App\Models\Ministrycat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MinistryController extends Controller
{
    public function index(){

        return view ('sessions.ministrycat');


    }

public function show_ministry_category()
{
$sno=0;

$view_ministry_category= Ministrycat::all();
if(count($view_ministry_category)>0)
{
    foreach($view_ministry_category as $value)
    {

        $ministry_category_name='<a href="javascript:void(0);">'.$value->ministry_category_name.'</a>';

        $modal = '
        <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_ministry_category_modal" onclick=edit("'.$value->id.'")>
            <i class="fa fa-pencil fs-18 text-success"></i>
        </a>
        <a href="javascript:void(0);" onclick=del("'.$value->id.'")>
            <i class="fa fa-trash fs-18 text-danger"></i>
        </a>';

    $add_data=Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');

        $sno++;
        $json[]= array(
                    $sno,
                    $ministry_category_name,
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

public function add_ministry_category(Request $request){


$user_id = Auth::id();
$data= User::where('id', $user_id)->first();
$user= $data->user_name;

$ministry_category = new ministrycat();
$ministry_category->ministry_category_name = $request['ministry_category_name'];
$ministry_category->added_by = $user;
$ministry_category->user_id = $user_id;
$ministry_category->save();
return response()->json(['ministry_category_id' => $ministry_category->id]);

}

public function edit_ministry_category(Request $request){


$ministry_category_id = $request->input('id');
$ministry_category_data = Ministrycat::where('id', $ministry_category_id)->first();

if (!$ministry_category_data) {
    return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.ministry_category_not_found', [], session('locale'))], 404);
}
// Add more attributes as needed
$data = [
    'ministry_category_id' => $ministry_category_data->id,
    'ministry_category_name' => $ministry_category_data->ministry_category_name,
];

return response()->json($data);
}

public function update_ministry_category(Request $request){

$user_id = Auth::id();
$data= User::find( $user_id)->first();
$user= $data->user_name;
$branch_id= $data->branch_id;

$ministry_category_id = $request->input('ministry_category_id');
$ministry_category = Ministrycat::where('id', $ministry_category_id)->first();
if (!$ministry_category) {
    return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.ministry_category_not_found', [], session('locale'))], 404);
}

$previousData = $ministry_category->only(['ministry_category_name', 'branch_id', 'added_by', 'user_id', 'created_at']);

$ministry_category->ministry_category_name = $request->input('ministry_category_name');
 $ministry_category->updated_by = $user;
$ministry_category->save();

$history = new History();
$history->user_id = $user_id;
$history->table_name = 'ministry_category';
$history->function = 'update';
$history->function_status = 1;
$history->branch_id = $branch_id;
$history->record_id = $ministry_category->id;
$history->previous_data = json_encode($previousData);
$history->updated_data = json_encode($ministry_category->only([
    'ministry_category_name', 'branch_id', 'added_by', 'user_id',
]));
$history->added_by = $user;
$history->save();
return response()->json([
    trans('messages.success_lang', [], session('locale')) => trans('messages.ministry_category_update_lang', [], session('locale'))
]);
}

public function delete_ministry_category(Request $request){

$user_id = Auth::id();
$data= User::find( $user_id)->first();
$user= $data->user_name;
$branch_id= $data->branch_id;

$ministry_category_id = $request->input('id');
$ministry_category = Ministrycat::where('id', $ministry_category_id)->first();
$previousData = $ministry_category->only(['ministry_category_name', 'branch_id', 'added_by', 'user_id', 'created_at']);

if (!$ministry_category) {
    return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.ministry_category_not_found', [], session('locale'))], 404);
}


$history = new History();
$history->user_id = $user_id;
$history->table_name = 'ministry_category';
$history->function = 'delete';
$history->function_status = 2;
$history->branch_id = $branch_id;
$history->record_id = $ministry_category->id;
$history->previous_data = json_encode($previousData);
$history->added_by = $user;
$history->save();
$ministry_category->delete();
return response()->json([
    trans('messages.success_lang', [], session('locale')) => trans('messages.ministry_category_deleted_lang', [], session('locale'))
]);
}
}
