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
use App\Models\Ministrycat;
use App\Models\SessionList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SationController extends Controller
{
    public function index() {
        $branches = Branch::all();

        $usedCategoryIds = Sation::pluck('ministry_cat_id')->filter()->all();
        $categories = Ministrycat::when(!empty($usedCategoryIds), function ($query) use ($usedCategoryIds) {
            return $query->whereNotIn('id', $usedCategoryIds);
        })->get();

        $usedGovtIds = Sation::pluck('government_id')->filter()->all();
        $govts = GovtDept::when(!empty($usedGovtIds), function ($query) use ($usedGovtIds) {
            return $query->whereNotIn('id', $usedGovtIds);
        })->get();

        $data = Sation::where('session_type', 'normal')->first();

        return view('sessions.sation', compact('branches', 'data', 'govts', 'categories'));
    }



        public function show_sation()
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
                    $category= Ministrycat::where('id', $value->ministry_cat_id)->value('ministry_category_name');



                    $sno++;
                    $json[] = array(
                        '<span class="patient-info ps-0">'. $sno . '</span>',
                        '<span class="text-nowrap ms-2">' . $session_name . '</span>',
                        '<span >' . $value->session_price . '</span>',
                        '<span >' . $govt . '</span>',
                        '<span >' . $category . '</span>',

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



        public function add_sation(Request $request)
        {
            $user_id = Auth::id();
            $user = User::find($user_id);

            // Check for duplicates (only for ministry session)
            if ($request->input('session_type') === 'ministry') {
                $existing = Sation::where('government_id', $request->input('government'))
                                  ->where('ministry_cat_id', $request->input('ministry_cat_id'))
                                  ->first();

                if ($existing) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This ministry category is already assigned to the selected government department.'
                    ]);
                }
            }

            $session = new Sation();
            $session->session_type = $request->input('session_type');
            $session->session_price = $request->input('session_price');
            $session->branch_id = $user->branch_id;
            $session->notes = $request->input('notes');
            $session->added_by = $user->user_name;
            $session->user_id = $user_id;

            if ($request->input('session_type') === 'ministry') {
                $session->government_id = $request->input('government');
                $session->ministry_cat_id = $request->input('ministry_cat_id');

                // Optional logic to assign category to the government (if needed)
                $govt = GovtDept::find($session->government_id);
                $govt->ministry_category_id = $session->ministry_cat_id;

                $mincat = Ministrycat::find($govt->ministry_category_id);
                $govt->ministry_category_color = $mincat->ministry_category_color;
                $govt->save();

                $session->session_name = null;
            } else {
                $session->session_name = $request->input('session_name');
                $session->government_id = null;
                $session->ministry_cat_id = null;
            }

            $session->save();

            return response()->json(['success' => true, 'message' => 'Session added successfully!']);
        }



        public function edit_sation(Request $request)
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
                $data['government'] = $session->government_id;
                $data['ministry_cat'] = $session->ministry_cat_id;

                $data['session_name'] = null;
            } else {
                $data['session_name'] = $session->session_name;
                $data['government'] = null;
            }

            return response()->json($data);
        }


        public function update_sation(Request $request)
        {
            $session_id = $request->input('session_id');
            $user_id = Auth::id();
            $user = User::find($user_id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $session = Sation::find($session_id);
            if (!$session) {
                return response()->json(['error' => trans('messages.session_not_found', [], session('locale'))], 404);
            }

            // 🛑 Check for duplicates only if session_type is ministry
            if ($request->input('session_type') === 'ministry') {
                $exists = Sation::where('government_id', $request->input('government'))
                                ->where('ministry_cat_id', $request->input('ministry_cat_id'))
                                ->where('id', '!=', $session_id) // Exclude current session from check
                                ->exists();

                if ($exists) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This ministry category is already assigned to the selected government department.'
                    ]);
                }
            }

            $previousData = $session->only([
                'session_type', 'session_name', 'government_id', 'session_price', 'branch_id', 'notes', 'added_by', 'user_id', 'created_at'
            ]);

            // ✏️ Update fields
            $session->session_type = $request->input('session_type');
            $session->session_price = $request->input('session_price');
            $session->branch_id = $user->branch_id;
            $session->notes = $request->input('notes');
            $session->added_by = $user->user_name;
            $session->user_id = $user_id;

            if ($request->input('session_type') === 'ministry') {
                $session->government_id = $request->input('government');
                $session->ministry_cat_id = $request->input('ministry_cat_id');
                $session->session_name = null;
            } else {
                $session->session_name = $request->input('session_name');
                $session->government_id = null;
                $session->ministry_cat_id = null;
            }

            $session->save();

            // 📚 History logging
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

            return response()->json([
                trans('messages.success_lang', [], session('locale')) =>
                trans('messages.user_update_lang', [], session('locale'))
            ]);
        }



        public function delete_sation(Request $request) {
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
}
