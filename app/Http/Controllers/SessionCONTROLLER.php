<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Offer;
use App\Models\Branch;
use App\Models\Doctor;
use App\Models\Sation;
use App\Models\History;
use App\Models\Session;
use App\Models\GovtDept;
use App\Models\SessionList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SessionCONTROLLER extends Controller
{


        public function add_session(Request $request)
        {

            $user_id = Auth::id();
            $data= User::where('id', $user_id)->first();
            $user= $data->user_name;
            $branch_id=   $data->branch_id;

            $title = '';
            if ($request->title == 1) {
                $title = 'Miss';
            } elseif ($request->title == 2) {
                $title = 'Mr.';
            } elseif ($request->title == 3) {
                $title = 'Mrs.';
            }

            $full_name = trim($title . ' ' . $request->first_name . ' ' . $request->second_name);
            // Save session
            $session = new SessionList();
            $session->title = $request->title;
            $session->first_name = $request->first_name;
            $session->second_name = $request->second_name;
            $session->mobile = $request->mobile;
            $session->id_passport = $request->id_passport;
            $session->dob = $request->dob;
            $session->country = $request->country;
            $session->doctor = $request->doctor;
            $session->session_type = $request->session_type;
            $session->session_fee = $request->session_fee;
            $session->no_of_sessions = $request->no_of_sessions;
            $session->session_gap = $request->session_gap;
            $session->session_date = $request->session_date;
            $session->offer_id = $request->offer_id;
            $session->ministry_id = $request->ministry_id;
            $session->session_cat = $request->session_cat;
            $session->user_id = $user_id ;
            $session->added_by = $user;
            $session->branch_id = $branch_id;
            $session->full_name = $full_name;
            $session->notes = $request->notes;

            if ($session->save()) {
                return response()->json(['success' => true, 'session_id' => $session->id, 'message' => 'Session added successfully!']);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to add session!']);
            }


        }




        public function session_detail($id)
        {
            $session = SessionList::findOrFail($id);


            $offer_name= "";
            $mini_name = "";
            if (!empty($session->offer_id)) {
                $offer_name = Offer::where('id', $session->offer_id)->value('offer_name');
            }

            if (!empty($session->ministry_id)) {
                $mini_name = GovtDept::where('id', $session->ministry_id)->value('offer_name');
            }

            return view('appointments.session_detail', [
                'patient_name' => $session->full_name,
                'doctor_name'  => Doctor::find($session->doctor)->doctor_name ?? 'Unknown',
                'sessions'     => $session->no_of_sessions,
                'gap'          => $session->session_gap,
                'session'=>$session,
                'offer_name'   => $offer_name,
                'mini_name'    => $mini_name,
            ]);
        }


        public function session_detail2($id)
        {
            $session = SessionList::find($id);

            if (!$session) {
                return response()->json(['error' => 'Session not found'], 404);
            }

            $doctor = Doctor::find($session->doctor_id);




            return response()->json([
                'patient_name' => $session->full_name,
                'doctor_name' => $doctor ? $doctor->doctor_name : 'Unknown',
                'appointment_date' => $session->session_date,  // ✅ Added this
                'sessions' => $session->no_of_sessions,
                'gap' => $session->session_gap,

            ]);
        }


 public function search_patient(Request $request)
{
    $query = $request->input('query');

    $patients = DB::table('patients')
        ->where('first_name', 'LIKE', "%{$query}%")  // Use correct column name
        ->orWhere('second_name', 'LIKE', "%{$query}%")  // If you have a second name
        ->orWhere('clinic_no', 'LIKE', "%{$query}%")
        ->orWhere('mobile', 'LIKE', "%{$query}%")
        ->limit(10)
        ->get();

    return response()->json($patients);
}

}
