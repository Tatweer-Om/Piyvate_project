<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\History;
use App\Models\Speciality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpecialityController extends Controller
{
    public function index(){

        return view ('doctors.speciality');

        }

        public function show_speciality()
        {

            $sno=0;

            $view_authspeciality= Speciality::all();
            if(count($view_authspeciality)>0)
            {
                foreach($view_authspeciality as $value)
                {

                    $speciality_name='<a class-"patient-info ps-0" href="javascript:void(0);">'.$value->speciality_name.'</a>';

                    $modal = '
                    <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_speciality_modal" onclick=edit("'.$value->id.'")>
                        <i class="fa fa-pencil fs-18 text-success"></i>
                    </a>
                    <a href="javascript:void(0);" onclick=del("'.$value->id.'")>
                        <i class="fa fa-trash fs-18 text-danger"></i>
                    </a>';

                    $add_data=Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');




                    $sno++;
                    $json[] = array(
                        '<span class="patient-info ps-0">'. $sno . '</span>',
                        '<span class="text-nowrap ms-2">' . $speciality_name . '</span>',
                        '<span class="text-primary">' . $value->notes . '</span>',
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

        public function add_speciality(Request $request){

            $user_id = Auth::id();
            $data= User::where('id', $user_id )->first();
            $user_name= $data->user_name;



            $speciality = new Speciality();

            $speciality->speciality_name = $request['speciality_name'];

            $speciality->notes = $request['notes'];
            $speciality->added_by = $user_name;
            $speciality->user_id = $user_id;
            $speciality->save();
            return response()->json(['speciality_id' => $speciality->id]);

        }


        public function edit_speciality(Request $request){

            $speciality_id = $request->input('id');

            $speciality_data = Speciality::where('id', $speciality_id)->first();
            $data = [
                'speciality_id' => $speciality_data->id,
                'speciality_name' => $speciality_data->speciality_name,

                'notes' => $speciality_data->notes,
                // Add more attributes as needed
            ];

            return response()->json($data);
        }

        public function update_speciality(Request $request)
    {
        $speciality_id = $request->input('speciality_id');
        $user_id = Auth::id();

        $user = User::where('id', $user_id)->first();
        $user_name = $user->user_name;
        $branch_id = $user->branch_id;


        $speciality = Speciality::where('id', $speciality_id)->first();

        if (!$speciality) {
            return response()->json(['error' => trans('messages.speciality_not_found', [], session('locale'))], 404);
        }

        $previousData = $speciality->only(['speciality_name',  'notes', 'added_by', 'user_id', 'created_at']);

        $speciality->speciality_name = $request->input('speciality_name');

        $speciality->notes = $request->input('notes');
        $speciality->added_by = $user_name;
        $speciality->user_id = $user_id;
        $speciality->save();

        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'specialityes';
        $history->function = 'update';
        $history->function_status = 1;
        $history->branch_id = $branch_id;
        $history->record_id = $speciality->id;
        $history->previous_data = json_encode($previousData);
        $history->updated_data = json_encode($speciality->only([
            'speciality_name', 'speciality_email', 'speciality_phone', 'notes', 'added_by', 'user_id'
        ]));
        $history->added_by = $user_name;
        $history->save();

        return response()->json([trans('messages.success_lang', [], session('locale')) => trans('messages.user_update_lang', [], session('locale'))]);
    }


    public function delete_speciality(Request $request) {


        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        $user_name = $user->user_name;
        $speciality_id = $request->input('id');
        $speciality = Speciality::where('id', $speciality_id)->first();

        if (!$speciality) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.speciality_not_found', [], session('locale'))], 404);
        }

        $previousData = $speciality->only([
            'speciality_name',  'notes', 'added_by', 'user_id', 'created_at'
        ]);

        $currentUser = Auth::user();
        $username = $currentUser->user_name;
        $branch_id = $currentUser->branch_id;

        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'specialityes';
        $history->function = 'delete';
        $history->function_status = 2;
        $history->branch_id = $branch_id;
        $history->record_id = $speciality->id;
        $history->previous_data = json_encode($previousData);

        $history->added_by = $user_name;
        $history->save();
        $speciality->delete();

        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.user_deleted_lang', [], session('locale'))
        ]);
    }

}
