<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\History;
use App\Models\Category;
use App\Models\GovtDept;
use Illuminate\Support\Facades\Auth;

class GovtController extends Controller
{
    public function index(){

        return view ('appointments.govt_agency');

        }

        public function show_govt()
{
    $sno = 0;
    $view_govt = GovtDept::all(); // Fetch all records

    if (count($view_govt) > 0) {
        foreach ($view_govt as $value) {

            $govt_name = '<a class="patient-info ps-0" href="javascript:void(0);">' . $value->govt_name . '</a>';

            $modal = '
            <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_govt_modal" onclick=edit("' . $value->id . '")>
                <i class="fa fa-pencil fs-18 text-success"></i>
            </a>
            <a href="javascript:void(0);" onclick=del("' . $value->id . '")>
                <i class="fa fa-trash fs-18 text-danger"></i>
            </a>';

            $added_date = Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');

            $sno++;
            $json[] = array(
                '<span class="patient-info ps-0">' . $sno . '</span>',
                '<span class="text-nowrap ms-2">' . $govt_name . '</span>',
                '<span class="text-primary">' . $value->govt_phone . '</span>',
                '<span class="text-primary">' . $value->govt_email . '</span>',
                '<span>' . $value->added_by . '</span>',
                '<span>' . $added_date . '</span>',
                $modal
            );
        }

        return response()->json([
            'success' => true,
            'aaData' => $json
        ]);
    } else {
        return response()->json([
            'sEcho' => 0,
            'iTotalRecords' => 0,
            'iTotalDisplayRecords' => 0,
            'aaData' => []
        ]);
    }
}

public function add_govt(Request $request)
{
    $user_id = Auth::id();
    $user = User::find($user_id);
    $user_name = $user ? $user->user_name : 'Unknown';

    $govt = new GovtDept();
    $govt->govt_name = $request->govt_name;
    $govt->govt_phone = $request->govt_phone;
    $govt->govt_email = $request->govt_email;
    $govt->notes = $request->notes;
    $govt->added_by = $user_name;
    $govt->user_id = $user_id;
    $govt->save();

    return response()->json(['govt_id' => $govt->id]);
}



public function edit_govt(Request $request)
{
    $govt_id = $request->input('id');
    $govt_data = GovtDept::where('id', $govt_id)->first();

    if (!$govt_data) {
        return response()->json(['error' => trans('messages.govt_not_found', [], session('locale'))], 404);
    }

    $data = [
        'govt_id' => $govt_data->id,
        'govt_name' => $govt_data->govt_name,
        'govt_email' => $govt_data->govt_email,
        'govt_phone' => $govt_data->govt_phone,
        'notes' => $govt_data->notes,
    ];

    return response()->json($data);
}

public function update_govt(Request $request)
{
    $govt_id = $request->input('govt_id');
    $user_id = Auth::id();
    $user = User::where('id', $user_id)->first();

    if (!$user) {
        return response()->json(['error' => trans('messages.user_not_found', [], session('locale'))], 404);
    }

    $govt = GovtDept::where('id', $govt_id)->first();

    if (!$govt) {
        return response()->json(['error' => trans('messages.govt_not_found', [], session('locale'))], 404);
    }

    $previousData = $govt->only(['govt_name', 'govt_email', 'govt_phone', 'notes', 'added_by', 'user_id', 'created_at']);

    $govt->govt_name = $request->input('govt_name');
    $govt->govt_email = $request->input('govt_email');
    $govt->govt_phone = $request->input('govt_phone');
    $govt->notes = $request->input('notes');
    $govt->added_by = $user->user_name;
    $govt->user_id = $user_id;
    $govt->save();

    // Save change history
    $history = new History();
    $history->user_id = $user_id;
    $history->table_name = 'govt_depts';
    $history->function = 'update';
    $history->function_status = 1;
    $history->branch_id = $user->branch_id;
    $history->record_id = $govt->id;
    $history->previous_data = json_encode($previousData);
    $history->updated_data = json_encode($govt->only(['govt_name', 'govt_email', 'govt_phone', 'notes', 'added_by', 'user_id']));
    $history->added_by = $user->user_name;
    $history->save();

    return response()->json([trans('messages.success_lang', [], session('locale')) => trans('messages.govt_update_success', [], session('locale'))]);
}

public function delete_govt(Request $request)
{
    $user_id = Auth::id();
    $user = User::where('id', $user_id)->first();

    if (!$user) {
        return response()->json(['error' => trans('messages.user_not_found', [], session('locale'))], 404);
    }

    $govt_id = $request->input('id');
    $govt = GovtDept::where('id', $govt_id)->first();

    if (!$govt) {
        return response()->json(['error' => trans('messages.govt_not_found', [], session('locale'))], 404);
    }

    $previousData = $govt->only(['govt_name', 'govt_email', 'govt_phone', 'notes', 'added_by', 'user_id', 'created_at']);

    // Save delete history
    $history = new History();
    $history->user_id = $user_id;
    $history->table_name = 'govt_depts';
    $history->function = 'delete';
    $history->function_status = 2;
    $history->branch_id = $user->branch_id;
    $history->record_id = $govt->id;
    $history->previous_data = json_encode($previousData);
    $history->added_by = $user->user_name;
    $history->save();

    $govt->delete();

    return response()->json([
        trans('messages.success_lang', [], session('locale')) => trans('messages.govt_deleted_success', [], session('locale'))
    ]);
}

}
