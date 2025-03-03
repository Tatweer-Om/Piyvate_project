<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index(){

        return view ('staff.role');


    }

public function show_role()
{
$sno=0;

$view_role= Role::all();
if(count($view_role)>0)
{
    foreach($view_role as $value)
    {

        $role_name='<a href="javascript:void(0);">'.$value->role_name.'</a>';

        $modal = '
        <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_role_modal" onclick=edit("'.$value->id.'")>
            <i class="fa fa-pencil fs-18 text-success"></i>
        </a>
        <a href="javascript:void(0);" onclick=del("'.$value->id.'")>
            <i class="fa fa-trash fs-18 text-danger"></i>
        </a>';

    $add_data=Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');

        $sno++;
        $json[]= array(
                    $sno,
                    $role_name,
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

public function add_role(Request $request){


$user_id = Auth::id();
$data= User::where('id', $user_id)->first();
$user= $data->user_name;

$role = new Role();
$role->role_name = $request['role_name'];
$role->added_by = $user;
$role->user_id = $user_id;
$role->save();
return response()->json(['role_id' => $role->id]);

}

public function edit_role(Request $request){


$role_id = $request->input('id');
$role_data = Role::where('id', $role_id)->first();

if (!$role_data) {
    return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.role_not_found', [], session('locale'))], 404);
}
// Add more attributes as needed
$data = [
    'role_id' => $role_data->id,
    'role_name' => $role_data->role_name,
];

return response()->json($data);
}

public function update_role(Request $request){

$user_id = Auth::id();
$data= User::find( $user_id)->first();
$user= $data->user_name;
$branch_id= $data->branch_id;

$role_id = $request->input('role_id');
$role = Role::where('id', $role_id)->first();
if (!$role) {
    return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.role_not_found', [], session('locale'))], 404);
}

$previousData = $role->only(['role_name', 'branch_id', 'added_by', 'user_id', 'created_at']);

$role->role_name = $request->input('role_name');
 $role->updated_by = $user;
$role->save();

$history = new History();
$history->user_id = $user_id;
$history->table_name = 'role';
$history->function = 'update';
$history->function_status = 1;
$history->branch_id = $branch_id;
$history->record_id = $role->id;
$history->previous_data = json_encode($previousData);
$history->updated_data = json_encode($role->only([
    'role_name', 'branch_id', 'added_by', 'user_id',
]));
$history->added_by = $user;
$history->save();
return response()->json([
    trans('messages.success_lang', [], session('locale')) => trans('messages.role_update_lang', [], session('locale'))
]);
}

public function delete_role(Request $request){

$user_id = Auth::id();
$data= User::find( $user_id)->first();
$user= $data->user_name;
$branch_id= $data->branch_id;

$role_id = $request->input('id');
$role = Role::where('id', $role_id)->first();
$previousData = $role->only(['role_name', 'branch_id', 'added_by', 'user_id', 'created_at']);

if (!$role) {
    return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.role_not_found', [], session('locale'))], 404);
}


$history = new History();
$history->user_id = $user_id;
$history->table_name = 'role';
$history->function = 'delete';
$history->function_status = 2;
$history->branch_id = $branch_id;
$history->record_id = $role->id;
$history->previous_data = json_encode($previousData);
$history->added_by = $user;
$history->save();
$role->delete();
return response()->json([
    trans('messages.success_lang', [], session('locale')) => trans('messages.role_deleted_lang', [], session('locale'))
]);
}
}
