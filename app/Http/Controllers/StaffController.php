<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Branch;
use App\Models\User;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function staff_list(){

        $branches= Branch::all();
        $roles= Role::all();


        return view ('staff.staf_list', compact('branches', 'roles'));
    }

    public function staff_profile(){
        return view ('staff.staf_profile');
    }


    public function show_employee()
    {

        $sno=0;

        $view_authemployee= Staff::all();
        if(count($view_authemployee)>0)
        {
            foreach($view_authemployee as $value)
            {

                $employee_name='<a class-"patient-info ps-0" href="javascript:void(0);">'.$value->employee_name.'</a>';

                $modal = '
                <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_employee_modal" onclick=edit("'.$value->id.'")>
                    <i class="fa fa-pencil fs-18 text-success"></i>
                </a>
                <a href="javascript:void(0);" onclick=del("'.$value->id.'")>
                    <i class="fa fa-trash fs-18 text-danger"></i>
                </a>';

                $add_data=Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');
                $employee_image = asset('images/dummy_images/no_image.jpg');
                if(!empty($value->employee_image))
                {
                    $employee_image = asset('images/employee_images')."/".$value->employee_image;
                }
                $src = '<img src="'.$employee_image.'" class-"patient-info ps-0" style="max-width:40px">';


                $branch= Branch::where('id', $value->branch_id)->value('branch_name');
                $role= Role::where('id', $value->role)->value('role_name');


                $sno++;
                $json[] = array(
                    '<span class="patient-info ps-0">'. $sno . '</span>',
                    '<span class="text-nowrap ms-2">' . $src .'  '. $employee_name . '</span>',
                    '<span class="text-primary">' . $value->employee_phone . '</span>',
                    '<span >' . $role . '</span>',
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

    public function add_employee(Request $request){

        $user_id = Auth::id(); 
        $data= User::where('id', $user_id)->first();
        $user= $data->user_name; 

        $employee_image = "";

        if ($request->hasFile('employee_image')) {
            $folderPath = public_path('images/employee_images');

            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }

            $employee_image = time() . '.' . $request->file('employee_image')->extension();
            $request->file('employee_image')->move($folderPath, $employee_image);
        }

        $employee = new Staff();

        $employee->employee_name = $request['employee_name'];
        $employee->employee_email = $request['email'];
        $employee->employee_phone = $request['phone'];
        // $employee->permissions = implode(',',$request['permissions']);
        $employee->password = Hash::make($request['password']);
        $employee->employee_image = $employee_image;
        $employee->branch_id = $request['branch_id'];
        $employee->role = $request['role_id'];
        $employee->notes = $request['notes'];
        $employee->added_by = $user;
        $employee->user_id = $user_id;
        $employee->save();
        return response()->json(['employee_id' => $employee->id]);

    }

    public function edit_employee(Request $request){
        // $employee = new employee();
        $employee_id = $request->input('id');
        $employee_data = Staff::where('id', $employee_id)->first();



        if (!$employee_data) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.employee_not_found', [], session('locale'))], 404);
        }




        $employee_image=$employee_data->employee_image ? asset('images/employee_images/' . $employee_data->employee_image) : asset('images/dummy_images/cover-image-icon.png');

        // Add more attributes as needed
        $data = [
            'employee_id' => $employee_data->id,
            'employee_name' => $employee_data->employee_name,
            'employee_email' => $employee_data->employee_email,
            'employee_phone' => $employee_data->employee_phone,
            'permissions' => $employee_data->permissions,
            'password' => $employee_data->password,
            'branch_id' => $employee_data->branch_id,
            'role_id' => $employee_data->role_id,
            'employee_image' => $employee_image,
            'notes' => $employee_data->notes,
        ];

        return response()->json($data);
    }

    public function update_employee(Request $request){


        $employee_id = $request->input('employee_id');

        $employee = Staff::where('id', $employee_id)->first();
        if (!$employee) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.authemployee_not_found', [], session('locale'))], 404);
        }

        $previousData = $employee->only([
            'employee_name', 'employee_email', 'employee_phone', 'permissions', 'employee_image', 'branch_id', 'role_id', 'notes', 'employee_id', 'added_by', 'created_at'
        ]);

        $employee_id = Auth::id();
        $data= Staff::where('id', $employee_id)->first();
        $employeename= $data->employee_name;
        $branch= $data->branch_id;

        $employee_image = $employee->employee_image;

        if ($request->hasFile('employee_image')) {
            $oldImagePath = public_path('images/employee_images/' . $employee->employee_image);
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }

            $folderPath = public_path('images/employee_images');
            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }

            $employee_image = time() . '.' . $request->file('employee_image')->extension();
            $request->file('employee_image')->move($folderPath, $employee_image);
        }

        $employee->employee_name = $request['employee_name'];
        $employee->employee_email = $request['email'];
        $employee->employee_phone = $request['phone'];
        $employee->permissions = implode(',',$request['permissions']);
        $employee->password = Hash::make($request['password']);
        $employee->employee_image = $employee_image;
        $employee->branch_id = $request['branch_id'];
        $employee->role_id = $request['role_id'];
        $employee->notes = $request['notes'];
        $employee->added_by = $employeename;
        $employee->employee_id = $employee_id;
        $employee->save();

        $history = new History();
        $history->employee_id = $employee_id;
        $history->table_name = 'staffs';
        $history->function = 'update';
        $history->function_status = 1;


        $history->branch_id = $branch;
        $history->record_id = $employee->id;
        $history->previous_data = json_encode($previousData);
        $history->updated_data = json_encode($employee->only([
            'employee_name', 'employee_email', 'employee_phone', 'permissions', 'employee_image', 'branch_id', 'role_id', 'notes', 'employee_id', 'added_by'
        ]));
        $history->added_by = $employeename;

        $history->save();
        return response()->json([trans('messages.success_lang', [], session('locale')) => trans('messages.employee_update_lang', [], session('locale'))]);
    }

    public function delete_employee(Request $request) {
        $employee_id = $request->input('id');
        $employee = Staff::where('id', $employee_id)->first();

        if (!$employee) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.employee_not_found', [], session('locale'))], 404);
        }

        // Store previous data before deletion
        $previousData = $employee->only([
            'employee_name', 'employee_email', 'employee_phone', 'permissions', 'employee_image', 'branch_id', 'role_id', 'notes', 'employee_id', 'added_by', 'created_at'
        ]);

        // Get current employee info
        $currentemployee = Auth::employee();
        $employeename = $currentemployee->employee_name;
        $branch = $currentemployee->branch_id;

        // Save history before deletion
        $history = new History();
        $history->employee_id = $employee_id;
        $history->table_name = 'staffs';
        $history->branch_id = $branch;
        $history->function = 'delete';
        $history->function_status = 2;
        $history->record_id = $employee->id;
        $history->previous_data = json_encode($previousData);
        $history->added_by = $employeename;
        $history->save();

        $employee->delete();

        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.employee_deleted_lang', [], session('locale'))
        ]);
    }

}
