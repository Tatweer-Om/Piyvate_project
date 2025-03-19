<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function setting(){

        $setting= Setting::first();

        if (!Auth::check()) {

            return redirect()->route('login_page')->with('error', 'Please LogIn first()');
        }

        $user = Auth::user();

        // if (in_array(8, explode(',', $user->permit_type))) {

            return view ('settings.company_profile', compact('setting'));
        // } else {

        //     return redirect()->route('home')->with( 'error', 'You dont have Permission');
        // }
    }

    public function add_setting(Request $request)
    {
        $user_id = Auth::id();
        $data= User::where('id', $user_id)->first();
        $user= $data->user_name;
        $branch_id= $data->branch_id;

        // Check if an existing record is being updated
        $setting= Setting::first();
        if (empty($setting)) {
            // Create a new record
            $setting = new Setting();
        }

        // Assign the form values to the model
        $setting->company_name = $request->company_name;
        $setting->company_email = $request->company_email;
        $setting->company_phone = $request->company_phone;
        $setting->company_cr = $request->company_cr;
        $setting->company_address = $request->company_address;
        $setting->notes = $request->notes;
        $setting->added_by = $user;
        $setting->updated_by = $user;
        $setting->user_id = $user_id;
        $setting->branch_id = $branch_id;


        // Handle file upload for the logo
        if ($request->hasFile('company_logo')) {  // Update the name to match your form field
            $folderPath = public_path('images/company_logo');
            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }
            $logo = time() . '.' . $request->file('company_logo')->extension();  // Make sure the field name matches
            $request->file('company_logo')->move(public_path('images/company_logo'), $logo);
            $setting->logo = $logo;
        }

        // Save the record (create or update)
        $setting->save();

        // Return a JSON response with the saved data
        return response()->json([
            'success' => true,
            'data' => $setting
        ]);
    }



    public function view_fee_card(){
        $setting= Setting::first();


        return view ('settings.appointment_fee', compact('setting'));
    }

    public function appointment_fee(Request $request){
        $user_id = Auth::id();
        $data= User::where('id', $user_id)->first();
        $user= $data->user_name;
        $branch_id= $data->branch_id;

        $setting= Setting::first();
        if (empty($setting)) {
            // Create a new record
            $setting = new Setting();
        }

        $setting->appointment_fee = $request->appointment_fee;
        $setting->added_by = $user;
        $setting->updated_by = $user;
        $setting->user_id = $user_id;
        $setting->branch_id = $branch_id;


        $setting->save();
        return response()->json([
            'success' => true,
            'data' => $setting
        ]);
    }
}
