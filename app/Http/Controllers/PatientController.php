<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Country;
use App\Models\History;
use App\Models\Patient;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function patient_list(){

        $countries= Country::all();
        return view ('patients.patients_list', compact( 'countries'));
    }

    public function patient_profile(){
        return view ('patients.patient_profile');
    }

    public function show_patient()
    {
        $sno = 0;
        $patients = Patient::all();

        if ($patients->count() > 0) {
            foreach ($patients as $patient) {
                $patient_name = '<a class="patient-info ps-0" href="javascript:void(0);">' . $patient->full_name . '</a>';
                $modal = '<a href="javascript:void(0);" class="me-3 edit-patient" data-bs-toggle="modal" data-bs-target="#add_patient" onclick=edit("' . $patient->id . '")>
                            <i class="fa fa-pencil fs-18 text-success"></i>
                         </a>
                         <a href="javascript:void(0);" onclick=del("' . $patient->id . '")>
                            <i class="fa fa-trash fs-18 text-danger"></i>
                         </a>';

                $add_data = Carbon::parse($patient->created_at)->format('d-m-Y (h:i a)');

                $branch = Branch::where('id', $patient->branch_id)->value('branch_name');
                $country = Country::where('id', $patient->country_id)->value('name');


                $sno++;
                $json[] = array(
                    '<span class="patient-info ps-0">' . $sno . '</span>',
                    '<span class="patient-info ps-0">' . $patient->clinic_no . '</span>',
                    '<span class="text-primary">' .$patient_name. '</span>',
                    '<span >' . $patient->mobile . '</span>',
                    '<span >' . $country . '</span>',

                    '<span>' . $branch . '</span>',
                    '<span>' . $add_data . '</span>',
                    '<span>' . $patient->added_by . '</span>',

                    $modal
                );
            }

            return response()->json(['success' => true, 'aaData' => $json]);
        }

        return response()->json(['sEcho' => 0, 'iTotalRecords' => 0, 'iTotalDisplayRecords' => 0, 'aaData' => []]);
    }

    public function add_patient(Request $request)
{
    $user_id = Auth::id();
    $data = User::where('id', $user_id)->first();
    $user = $data->user_name;
    $branch_id = $data->branch_id;

    // Determine title
    $title = '';
    if ($request->title == 1) {
        $title = 'Miss';
    } elseif ($request->title == 2) {
        $title = 'Mr.';
    } elseif ($request->title == 3) {
        $title = 'Mrs.';
    }

    // Generate full name
    $full_name = trim($title . ' ' . $request->first_name . ' ' . $request->second_name);

    try {
        // Check if patient exists based on mobile
        $patient = Patient::where('mobile', $request->mobile)->first();

        if (!$patient) {
            // Generate clinic number
            $lastClinicNumber = Patient::max('clinic_no');
            $nextNumber = $lastClinicNumber ? intval(explode('-', $lastClinicNumber)[1]) + 1 : 1;
            $clinicNumber = sprintf('00-%d', $nextNumber);

            // Create new patient
            $patient = new Patient();
            $patient->title = $request->title;
            $patient->first_name = $request->first_name;
            $patient->second_name = $request->second_name;
            $patient->full_name = $full_name;
            $patient->mobile = $request->mobile;
            $patient->country_id = $request->country;
            $patient->id_passport = $request->id_passport;
            $patient->dob = $request->dob;
            $patient->branch_id = $branch_id;
            $patient->added_by = $user;
            $patient->user_id = $user_id;
            $patient->clinic_no = $clinicNumber;
            $patient->save();

            return response()->json(['success' => 'Patient added successfully', 'patient_id' => $patient->id]);
        } else {
            return response()->json(['error' => 'Patient with this mobile number already exists'], 400);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to add patient', 'message' => $e->getMessage()], 500);
    }
}


    public function edit_patient(Request $request)
    {
        $patient = Patient::find($request->input('id'));
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        return response()->json([
            'patient_id' => $patient->id,
            'title' => $patient->title,
            'first_name' => $patient->first_name,
            'second_name' => $patient->second_name,
            'mobile' => $patient->mobile,
            'id_passport' => $patient->id_passport,
            'dob' => $patient->dob,
            'country' => $patient->country_id,
            'details' => $patient->details,
        ]);
    }

    public function update_patient(Request $request)
    {
        $user_id = Auth::id();
        $data = User::where('id', $user_id)->first();
        $user = $data->user_name;
        $branch_id = $data->branch_id;

        // Determine title


        $patient = Patient::find($request->input('patient_id'));
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        // Store previous data before updating
        $previousData = $patient->only([
            'title', 'first_name', 'second_name', 'mobile', 'id_passport', 'dob', 'country', 'details', 'added_by', 'created_at'
        ]);
        $title = '';
        if ($request->title == 1) {
            $title = 'Miss';
        } elseif ($request->title == 2) {
            $title = 'Mr.';
        } elseif ($request->title == 3) {
            $title = 'Mrs.';
        }

        // Generate full name
        $full_name = trim($title . ' ' . $request->first_name . ' ' . $request->second_name);

        $lastClinicNumber = Patient::max('clinic_no');
        $nextNumber = $lastClinicNumber ? intval(explode('-', $lastClinicNumber)[1]) + 1 : 1;
        $clinicNumber = sprintf('00-%d', $nextNumber);

        // Create new patient
        $patient->title = $request->title;
        $patient->first_name = $request->first_name;
        $patient->second_name = $request->second_name;
        $patient->full_name = $full_name;
        $patient->mobile = $request->mobile;
        $patient->country_id = $request->country;
        $patient->id_passport = $request->id_passport;
        $patient->dob = $request->dob;
        $patient->branch_id = $branch_id;
        $patient->added_by = $user;
        $patient->user_id = $user_id;
        $patient->clinic_no = $clinicNumber;
        $patient->save();

        // Save update history
        $updatedData = $patient->only([
            'title', 'first_name', 'second_name', 'mobile', 'id_passport', 'dob', 'country', 'details', 'added_by'
        ]);

        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'patients';
        $history->function = 'update';
        $history->function_status = 1;
        $history->record_id = $patient->id;
        $history->branch_id = $branch_id;

        $history->previous_data = json_encode($previousData);
        $history->updated_data = json_encode($updatedData);
        $history->added_by = $user;
        $history->save();

        return response()->json(['success' => 'Patient updated successfully']);
    }

    public function delete_patient(Request $request)
    {

        $id= $request->input('id');
        $patient = Patient::where('id', $id)->first();
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        // Store previous data before deletion
        $previousData = $patient->only([
            'title', 'first_name', 'second_name', 'mobile', 'id_passport', 'dob', 'country', 'details', 'added_by', 'created_at'
        ]);

        // Get current user info
        $currentUser = Auth::user();
        $username = $currentUser->user_name;
        $user_id = $currentUser->id;
        $branch_id= $currentUser->branch_id;


        // Save delete history
        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'patients';
        $history->function = 'delete';
        $history->function_status = 2; // Status for delete
        $history->record_id = $patient->id;
        $history->branch_id = $branch_id;

        $history->previous_data = json_encode($previousData);
        $history->added_by = $username;
        $history->save();

        // Delete patient record
        $patient->delete();

        return response()->json(['success' => 'Patient deleted successfully']);
    }
}
