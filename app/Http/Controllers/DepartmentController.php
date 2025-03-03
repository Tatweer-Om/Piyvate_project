<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\History;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    public function index(){

        return view ('staff.department');


    }

public function show_department()
{
$sno=0;

$view_department= Department::all();
if(count($view_department)>0)
{
    foreach($view_department as $value)
    {

        $department_name='<a href="javascript:void(0);">'.$value->department_name.'</a>';

        $modal = '
        <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_department_modal" onclick=edit("'.$value->id.'")>
            <i class="fa fa-pencil fs-18 text-success"></i>
        </a>
        <a href="javascript:void(0);" onclick=del("'.$value->id.'")>
            <i class="fa fa-trash fs-18 text-danger"></i>
        </a>';

    $add_data=Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');

        $sno++;
        $json[]= array(
                    $sno,
                    $department_name,
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

public function add_department(Request $request){


$user_id = Auth::id();
$data= User::where('id', $user_id)->first();
$user= $data->user_name;

$department = new Department();
$department->department_name = $request['department_name'];
$department->added_by = $user;
$department->user_id = $user_id;
$department->save();
return response()->json(['department_id' => $department->id]);

}

public function edit_department(Request $request){


$department_id = $request->input('id');
$department_data = Department::where('id', $department_id)->first();

if (!$department_data) {
    return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.department_not_found', [], session('locale'))], 404);
}
// Add more attributes as needed
$data = [
    'department_id' => $department_data->id,
    'department_name' => $department_data->department_name,
];

return response()->json($data);
}

public function update_department(Request $request){

$user_id = Auth::id();
$data= User::find( $user_id)->first();
$user= $data->user_name;
$branch_id= $data->branch_id;

$department_id = $request->input('department_id');
$department = Department::where('id', $department_id)->first();
if (!$department) {
    return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.department_not_found', [], session('locale'))], 404);
}

$previousData = $department->only(['department_name', 'branch_id', 'added_by', 'user_id', 'created_at']);

$department->department_name = $request->input('department_name');
 $department->updated_by = $user;
$department->save();

$history = new History();
$history->user_id = $user_id;
$history->table_name = 'department';
$history->function = 'update';
$history->function_status = 1;
$history->branch_id = $branch_id;
$history->record_id = $department->id;
$history->previous_data = json_encode($previousData);
$history->updated_data = json_encode($department->only([
    'department_name', 'branch_id', 'added_by', 'user_id',
]));
$history->added_by = $user;
$history->save();
return response()->json([
    trans('messages.success_lang', [], session('locale')) => trans('messages.department_update_lang', [], session('locale'))
]);
}

public function delete_department(Request $request){

$user_id = Auth::id();
$data= User::find( $user_id)->first();
$user= $data->user_name;
$branch_id= $data->branch_id;

$department_id = $request->input('id');
$department = Department::where('id', $department_id)->first();
$previousData = $department->only(['department_name', 'branch_id', 'added_by', 'user_id', 'created_at']);

if (!$department) {
    return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.department_not_found', [], session('locale'))], 404);
}


$history = new History();
$history->user_id = $user_id;
$history->table_name = 'department';
$history->function = 'delete';
$history->function_status = 2;
$history->branch_id = $branch_id;
$history->record_id = $department->id;
$history->previous_data = json_encode($previousData);
$history->added_by = $user;
$history->save();
$department->delete();
return response()->json([
    trans('messages.success_lang', [], session('locale')) => trans('messages.department_deleted_lang', [], session('locale'))
]);
}
}
