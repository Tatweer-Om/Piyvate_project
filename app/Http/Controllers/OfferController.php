<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Offer;
use App\Models\Branch;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    public function index(){

        $branches= Branch::all();
        return view ('offers.offer', compact('branches'));

        }

        public function show_offer()
        {

            $sno=0;

            $view_authoffer= Offer::all();
            if(count($view_authoffer)>0)
            {
                foreach($view_authoffer as $value)
                {

                    $offer_name='<a class-"patient-info ps-0" href="javascript:void(0);">'.$value->offer_name.'</a>';

                    $modal = '
                    <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_offer_modal" onclick=edit("'.$value->id.'")>
                        <i class="fa fa-pencil fs-18 text-success"></i>
                    </a>
                    <a href="javascript:void(0);" onclick=del("'.$value->id.'")>
                        <i class="fa fa-trash fs-18 text-danger"></i>
                    </a>';

                    $add_data=Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');

                    $branch= Branch::where('id', $value->branch_id)->value('branch_name');

                    $sno++;
                    $json[] = array(
                        '<span class="patient-info ps-0">'. $sno . '</span>',
                        '<span class="text-nowrap ms-2">' . $offer_name . '</span>',
                        '<span class="text-primary">' . $value->sessions . '</span>',
                        '<span >' . $value->offer_price . '</span>',
                        '<span >' . $branch . '</span>',

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

        public function add_offer(Request $request){

            $user_id = Auth::id();
            $data= User::where('id', $user_id)->first();
            $user_name= $data->user_name;



            $offer = new Offer();

            $offer->offer_name = $request['offer_name'];
            $offer->sessions = $request['sessions'];
            $offer->offer_price = $request['offer_price'];
            $offer->branch_id = $request['branch_id'];
            $offer->notes = $request['notes'];
            $offer->added_by =  $user_name;
            $offer->user_id = $user_id;
            $offer->save();
            return response()->json(['offer_id' => $offer->id]);

        }


        public function edit_offer(Request $request){

            $offer_id = $request->input('id');

            $offer_data = Offer::where('id', $offer_id)->first();
            $data = [
                'offer_id' => $offer_data->id,
                'branch_id' => $offer_data->branch_id,
                'offer_name' => $offer_data->offer_name,
                'sessions' => $offer_data->sessions,
                'offer_price' => $offer_data->offer_price,
                'notes' => $offer_data->notes,
            ];

            return response()->json($data);
        }

        public function update_offer(Request $request)
        {
            $offer_id = $request->input('offer_id');
            $user_id = Auth::id();
            $user = User::where('id', $user_id)->first();
            $user_name = $user->user_name;

            $offer = Offer::where('id', $offer_id)->first();

            if (!$offer) {
                return response()->json(['error' => trans('messages.offer_not_found', [], session('locale'))], 404);
            }

            $previousData = $offer->only(['offer_name', 'offer_email', 'offer_phone', 'branch_id', 'notes', 'added_by', 'user_id', 'created_at']);

            $offer->offer_name = $request['offer_name'];
            $offer->sessions = $request['sessions'];
            $offer->offer_price = $request['offer_price'];
            $offer->branch_id = $request['branch_id'];
            $offer->notes = $request['notes'];
            $offer->added_by = $user_name;
            $offer->user_id = $user_id;
            $offer->save();

            $history = new History();
            $history->user_id = $user_id;
            $history->table_name = 'offers';
            $history->function = 'update';
            $history->function_status = 1;
            $history->branch_id = $offer->branch_id;
            $history->record_id = $offer->id;
            $history->previous_data = json_encode($previousData);
            $history->updated_data = json_encode($offer->only([
                'offer_name', 'sessions', 'offer_price', 'branch_id', 'notes', 'added_by', 'user_id' // Correct fields
            ]));
            $history->added_by = $user_name;
            $history->save();

            return response()->json([trans('messages.success_lang', [], session('locale')) => trans('messages.user_update_lang', [], session('locale'))]);
        }


        public function delete_offer(Request $request) {
            $user_id = Auth::id();
            $user = User::where('id', $user_id)->first();
            $user_name = $user->user_name;

            $offer_id = $request->input('id');
            $offer = Offer::where('id', $offer_id)->first();

            if (!$offer) {
                return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.offer_not_found', [], session('locale'))], 404);
            }

            // Capture the previous data before deletion
            $previousData = $offer->only([
                'offer_name', 'sessions', 'offer_price', 'branch_id', 'notes', 'added_by', 'user_id', 'created_at'
            ]);

            // Create the history record for the deletion
            $history = new History();
            $history->user_id = $user_id;
            $history->table_name = 'offers';  // Corrected table name
            $history->function = 'delete';
            $history->function_status = 2; // 2 for deletion
            $history->branch_id = $offer->branch_id;
            $history->record_id = $offer->id;
            $history->previous_data = json_encode($previousData); // Store previous data before deletion
            $history->added_by = $user_name;
            $history->save();

            // Delete the offer
            $offer->delete();

            return response()->json([
                trans('messages.success_lang', [], session('locale')) => trans('messages.user_deleted_lang', [], session('locale'))
            ]);
        }

}
