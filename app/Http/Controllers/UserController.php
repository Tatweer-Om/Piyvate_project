<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Branch;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){

        // if (!Auth::check()) {

        //     return redirect()->route('login_page')->with('error', trans('messages.please_log_in', [], session('locale')));
        // }

        // $user = Auth::user();

        // if (in_array(6, explode(',', $user->permit_type))) {

        $branches= Branch::all();

        return view('users.add_user', compact('branches'));
        // } else {


//  return redirect()->route('/')->with('error', trans('messages.you_dont_have_permissions', [], session('locale')));
//         }

    }

    public function show_user()
    {

        $sno=0;

        $view_authuser= User::all();
        if(count($view_authuser)>0)
        {
            foreach($view_authuser as $value)
            {

                $user_name='<a class-"patient-info ps-0" href="javascript:void(0);">'.$value->user_name.'</a>';

                $modal = '
                <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_user_modal" onclick=edit("'.$value->id.'")>
                    <i class="fa fa-pencil fs-18 text-success"></i>
                </a>
                <a href="javascript:void(0);" onclick=del("'.$value->id.'")>
                    <i class="fa fa-trash fs-18 text-danger"></i>
                </a>';

                $add_data=Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');
                $user_image = asset('images/dummy_images/no_image.jpg');
                if(!empty($value->user_image))
                {
                    $user_image = asset('images/user_images')."/".$value->user_image;
                }
                $src = '<img src="'.$user_image.'" class-"patient-info ps-0" style="max-width:40px">';

                $user_type = "";
                if ($value->user_type == 1) {
                    $user_type = 'Admin';
                } else {
                    $user_type = 'User';
                }

                $branch= Branch::where('id', $value->branch_id)->value('branch_name');

                $sno++;
                $json[] = array(
                    '<span class="patient-info ps-0">'. $sno . '</span>',
                    '<span class="text-nowrap ms-2">' . $src .'  '. $user_name . '</span>',
                    '<span class="text-primary">' . $value->user_phone . '</span>',
                    '<span >' . $user_type . '</span>',

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

    public function add_user(Request $request){

        $user_id = Auth::id();
        $data= User::where('id', $user_id)->first();
        $username= $data->user_name;


        $user_image = "";

        if ($request->hasFile('user_image')) {
            $folderPath = public_path('images/user_images');

            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }

            $user_image = time() . '.' . $request->file('user_image')->extension();
            $request->file('user_image')->move($folderPath, $user_image);
        }

        $user = new User();

        $user->user_name = $request['user_name'];
        $user->user_email = $request['email'];
        $user->user_phone = $request['phone'];
        $user->permissions = implode(',',$request['permissions']);
        $user->password = Hash::make($request['password']);
        $user->user_image = $user_image;
        $user->branch_id = $request['branch_id'];
        $user->user_type = $request['user_type'];
        $user->notes = $request['notes'];
        $user->added_by = $username;
        $user->user_id = $user_id;
        $user->save();
        return response()->json(['user_id' => $user->id]);

    }

    public function edit_user(Request $request){
        // $user = new User();
        $user_id = $request->input('id');
        $user_data = User::where('id', $user_id)->first();



        if (!$user_data) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.user_not_found', [], session('locale'))], 404);
        }
        $permit = explode(',',$user_data->permissions);
        $checkboxValues = [
            ['id' => 'user', 'value' => 1, 'name' => 'messages.checkbox_user'],
            ['id' => 'expense', 'value' => 2, 'name' => 'messages.checkbox_expense'],
            ['id' => 'reports', 'value' => 3, 'name' => 'messages.checkbox_reports'],
            ['id' => 'doctors', 'value' => 4, 'name' => 'messages.checkbox_doctors'],
            ['id' => 'staff', 'value' => 5, 'name' => 'messages.checkbox_staff'],
            ['id' => 'register', 'value' => 6, 'name' => 'messages.checkbox_register'],
            ['id' => 'patients', 'value' => 7, 'name' => 'messages.checkbox_patients'],
            ['id' => 'stock', 'value' => 8, 'name' => 'messages.checkbox_stock'],
            ['id' => 'billing', 'value' => 9, 'name' => 'messages.checkbox_billing'],
            ['id' => 'hr', 'value' => 10, 'name' => 'messages.checkbox_hr'],
            ['id' => 'settings', 'value' => 11, 'name' => 'messages.checkbox_settings'],
            ['id' => 'accounts', 'value' => 12, 'name' => 'messages.checkbox_accounts'],
        ];


                $checked_html   =  '<div class="container mt-3">
                <div class="form-check mb-2">
                    <input type="checkbox" class="form-check-input" id="selectAll">
                    <label class="form-check-label fw-bold" for="selectAll">All Permissions</label>
                </div>
                <hr>
                <div class="row d-flex flex-wrap">
            ';

            foreach ($checkboxValues as $key => $value) {
            $checked = in_array($value['value'], $permit) ? "checked='true'" : "";

            $checked_html .= '<div class="col-md-1 col-3">
                    <label class="d-block small" for="'.$value['name'].'">'.trans($value['name'], [], session('locale')).'</label>
                    <input type="checkbox" id="'.$value['name'].'" class="form-check-input permission-checkbox" name="permissions[]" value="'.$value['value'].'" '.$checked.'>
                </div>';
            }

            $checked_html .= '</div></div>'; // Closing row and container divs


        $user_image=$user_data->user_image ? asset('images/user_images/' . $user_data->user_image) : asset('images/dummy_images/cover-image-icon.png');

        // Add more attributes as needed
        $data = [
            'user_id' => $user_data->id,
            'user_name' => $user_data->user_name,
            'user_email' => $user_data->user_email,
            'user_phone' => $user_data->user_phone,
            'permissions' => $user_data->permissions,
            'password' => $user_data->password,
            'branch_id' => $user_data->branch_id,
            'user_type' => $user_data->user_type,
            'user_image' => $user_image,
            'notes' => $user_data->notes,
            'checked_html' => $checked_html,
            // Add more attributes as needed
        ];

        return response()->json($data);
    }

    public function update_user(Request $request){


        $user_id = $request->input('user_id');

        $user = User::where('id', $user_id)->first();
        if (!$user) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.authuser_not_found', [], session('locale'))], 404);
        }

        $previousData = $user->only([
            'user_name', 'user_email', 'user_phone', 'permissions', 'user_image', 'branch_id', 'user_type', 'notes', 'user_id', 'added_by', 'created_at'
        ]);

        $user_id = Auth::id();
        $data= User::where('id', $user_id)->first();
        $username= $data->user_name;
        $branch= $data->branch_id;

        $user_image = $user->user_image;

        if ($request->hasFile('user_image')) {
            $oldImagePath = public_path('images/user_images/' . $user->user_image);
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }

            $folderPath = public_path('images/user_images');
            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }

            $user_image = time() . '.' . $request->file('user_image')->extension();
            $request->file('user_image')->move($folderPath, $user_image);
        }

        $user->user_name = $request['user_name'];
        $user->user_email = $request['email'];
        $user->user_phone = $request['phone'];
        $user->permissions = implode(',',$request['permissions']);
        $user->password = Hash::make($request['password']);
        $user->user_image = $user_image;
        $user->branch_id = $request['branch_id'];
        $user->user_type = $request['user_type'];
        $user->notes = $request['notes'];
        $user->added_by = $username;
        $user->user_id = $user_id;
        $user->save();

        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'users';
        $history->function = 'update';
        $history->function_status = 1;


        $history->branch_id = $branch;
        $history->record_id = $user->id;
        $history->previous_data = json_encode($previousData);
        $history->updated_data = json_encode($user->only([
            'user_name', 'user_email', 'user_phone', 'permissions', 'user_image', 'branch_id', 'user_type', 'notes', 'user_id', 'added_by'
        ]));
        $history->added_by = $username;

        $history->save();
        return response()->json([trans('messages.success_lang', [], session('locale')) => trans('messages.user_update_lang', [], session('locale'))]);
    }

    public function delete_user(Request $request) {
        $user_id = $request->input('id');
        $user = User::where('id', $user_id)->first();

        if (!$user) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.user_not_found', [], session('locale'))], 404);
        }

        // Store previous data before deletion
        $previousData = $user->only([
            'user_name', 'user_email', 'user_phone', 'permissions', 'user_image', 'branch_id', 'user_type', 'notes', 'user_id', 'added_by', 'created_at'
        ]);

        // Get current user info
        $currentUser = Auth::user();
        $username = $currentUser->user_name;
        $branch = $currentUser->branch_id;

        // Save history before deletion
        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'users';
        $history->branch_id = $branch;
        $history->function = 'delete';
        $history->function_status = 2;
        $history->record_id = $user->id;
        $history->previous_data = json_encode($previousData);
        $history->added_by = $username;
        $history->save();

        $user->delete();

        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.user_deleted_lang', [], session('locale'))
        ]);
    }




    //login logout

    public function login_page(){
        return view ('pages.login');
    }


    public function login(Request $request)
    {
        $baseInput = $request->input('user_name'); // Or user_email (the value passed could be either email or username)
        $password = $request->input('password');

        // Attempt to find the user by email or username
        $user = User::where('user_name', $baseInput)
                    ->orWhere('user_email', $baseInput)
                    ->first();

        // If the user exists
        if ($user) {
            // Check if the password matches the stored password hash
            if (Hash::check($password, $user->password)) {
                // Log the user in
                Auth::login($user);

                return response()->json([
                    'status' => 1,
                    'message' => 'Login successful',
                    'redirect' => route('home') // or any other route
                ]);
            } else {
                // Password is incorrect
                return response()->json([
                    'status' => 2,
                    'message' => 'Password is incorrect'
                ]);
            }
        } else {
            // User is not found, need to check which field is incorrect
            $userByEmail = User::where('user_email', $baseInput)->first();
            $userByName = User::where('user_name', $baseInput)->first();

            if ($userByEmail) {
                // If the email exists but username does not
                return response()->json([
                    'status' => 2,
                    'message' => 'Username is incorrect'
                ]);
            } elseif ($userByName) {
                // If the username exists but email does not
                return response()->json([
                    'status' => 2,
                    'message' => 'Email is incorrect'
                ]);
            } else {
                // Neither email nor username exists
                return response()->json([
                    'status' => 2,
                    'message' => 'User not found'
                ]);
            }
        }
    }



    public function logout(Request $request)
{
    if (Auth::check()) {
        Auth::logout();

        return response()->json(['status' => 1]);
    }
    return response()->json(['status' => 2]);
}

}
