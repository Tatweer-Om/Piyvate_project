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
use Illuminate\Support\Facades\Auth;

class SessionCONTROLLER extends Controller
{
    public function index(){

        $branches= Branch::all();
        $govts= GovtDept::all();
        return view ('sessions.session', compact('branches', 'govts'));

        }

        public function show_session()
        {

            $sno=0;

            $view_authsession= Sation::all();
            if(count($view_authsession)>0)
            {
                foreach($view_authsession as $value)
                {

                    $session_name='<a class-"patient-info ps-0" href="javascript:void(0);">'.$value->session_name.'</a>';

                    $modal = '
                    <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_session_modal" onclick=edit("'.$value->id.'")>
                        <i class="fa fa-pencil fs-18 text-success"></i>
                    </a>
                    <a href="javascript:void(0);" onclick=del("'.$value->id.'")>
                        <i class="fa fa-trash fs-18 text-danger"></i>
                    </a>';

                    $add_data=Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');

                    $branch= Branch::where('id', $value->branch_id)->value('branch_name');
                    $govt= GovtDept::where('id', $value->government_id)->value('govt_name');


                    $sno++;
                    $json[] = array(
                        '<span class="patient-info ps-0">'. $sno . '</span>',
                        '<span class="text-nowrap ms-2">' . $session_name . '</span>',
                        '<span >' . $value->session_price . '</span>',
                        '<span >' . $govt . '</span>',

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


        public function edit_session(Request $request)
        {
            $session_id = $request->input('id');
            $session = Sation::where('id', $session_id)->first();

            if (!$session) {
                return response()->json(['error' => 'Session not found'], 404);
            }

            $data = [
                'session_id' => $session->id,
                'govt_id' => $session->government_id,
                'session_name' => $session->session_name,
                'session_type' => $session->session_type,
                'session_price' => $session->session_price,
                'notes' => $session->notes,
            ];

            if ($session->session_type === 'ministry') {
                $data['government'] = $session->government_id; // Provide government ID if ministry
                $data['session_name'] = null;
            } else {
                $data['session_name'] = $session->session_name;
                $data['government'] = null;
            }

            return response()->json($data);
        }


        public function update_session(Request $request)
        {
            $session_id = $request->input('session_id');
            $user_id = Auth::id();
            $user = User::where('id', $user_id)->first();

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $session = Sation::where('id', $session_id)->first();
            if (!$session) {
                return response()->json(['error' => trans('messages.session_not_found', [], session('locale'))], 404);
            }

            $previousData = $session->only([
                'session_type', 'session_name', 'government_id', 'session_price', 'branch_id', 'notes', 'added_by', 'user_id', 'created_at'
            ]);

            $session->session_type = $request->input('session_type');
            $session->session_price = $request->input('session_price');
            $session->branch_id = $request->input('branch_id');
            $session->notes = $request->input('notes');
            $session->added_by = $user->user_name;
            $session->user_id = $user_id;

            if ($request->input('session_type') === 'ministry') {
                $session->government_id = $request->input('government');
                $session->session_name = null;
            } else {
                $session->session_name = $request->input('session_name');
                $session->government_id = null;
            }

            $session->save();

            $history = new History();
            $history->user_id = $user_id;
            $history->table_name = 'sessions';
            $history->function = 'update';
            $history->function_status = 1;
            $history->branch_id = $session->branch_id;
            $history->record_id = $session->id;
            $history->previous_data = json_encode($previousData);
            $history->updated_data = json_encode($session->only([
                'session_type', 'session_name', 'government_id', 'session_price', 'branch_id', 'notes', 'added_by', 'user_id'
            ]));
            $history->added_by = $user->user_name;
            $history->save();

            return response()->json([trans('messages.success_lang', [], session('locale')) => trans('messages.user_update_lang', [], session('locale'))]);
        }



        public function delete_session(Request $request) {
            $user_id = Auth::id();
            $user = User::where('id', $user_id)->first();

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $session_id = $request->input('id');
            $session = Sation::where('id', $session_id)->first();

            if (!$session) {
                return response()->json([
                    trans('messages.error_lang', [], session('locale')) => trans('messages.session_not_found', [], session('locale'))
                ], 404);
            }

            // Capture previous data before deletion
            $previousData = $session->only([
                'session_type', 'session_name', 'government_id', 'session_price', 'branch_id', 'notes', 'added_by', 'user_id', 'created_at'
            ]);

            // Create a history record for the deletion
            $history = new History();
            $history->user_id = $user_id;
            $history->table_name = 'sessions';
            $history->function = 'delete';
            $history->function_status = 2; // 2 indicates a deletion
            $history->branch_id = $session->branch_id;
            $history->record_id = $session->id;
            $history->previous_data = json_encode($previousData);
            $history->added_by = $user->user_name;
            $history->save();

            // Delete the session
            $session->delete();

            return response()->json([
                trans('messages.success_lang', [], session('locale')) => trans('messages.user_deleted_lang', [], session('locale'))
            ]);
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

}
