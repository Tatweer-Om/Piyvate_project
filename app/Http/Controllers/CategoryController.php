<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\History;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(){

        return view ('purchase.category');

        }

        public function show_category()
        {

            $sno=0;

            $view_authcategory= Category::all();
            if(count($view_authcategory)>0)
            {
                foreach($view_authcategory as $value)
                {

                    $category_name='<a class-"patient-info ps-0" href="javascript:void(0);">'.$value->category_name.'</a>';

                    $modal = '
                    <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_category_modal" onclick=edit("'.$value->id.'")>
                        <i class="fa fa-pencil fs-18 text-success"></i>
                    </a>
                    <a href="javascript:void(0);" onclick=del("'.$value->id.'")>
                        <i class="fa fa-trash fs-18 text-danger"></i>
                    </a>';

                    $add_data=Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');




                    $sno++;
                    $json[] = array(
                        '<span class="patient-info ps-0">'. $sno . '</span>',
                        '<span class="text-nowrap ms-2">' . $category_name . '</span>',
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

        public function add_category(Request $request){

            $user_id = Auth::id();
            $data= User::where('id', $user_id )->first();
            $user_name= $data->user_name;



            $category = new Category();

            $category->category_name = $request['category_name'];

            $category->notes = $request['notes'];
            $category->added_by = $user_name;
            $category->user_id = $user_id;
            $category->save();
            return response()->json(['category_id' => $category->id]);

        }


        public function edit_category(Request $request){

            $category_id = $request->input('id');

            $category_data = Category::where('id', $category_id)->first();
            $data = [
                'category_id' => $category_data->id,
                'category_name' => $category_data->category_name,

                'notes' => $category_data->notes,
                // Add more attributes as needed
            ];

            return response()->json($data);
        }

        public function update_category(Request $request)
    {
        $category_id = $request->input('category_id');
        $user_id = Auth::id();

        $user = User::where('id', $user_id)->first();
        $user_name = $user->user_name;
        $branch_id = $user->branch_id;


        $category = Category::where('id', $category_id)->first();

        if (!$category) {
            return response()->json(['error' => trans('messages.category_not_found', [], session('locale'))], 404);
        }

        $previousData = $category->only(['category_name',  'notes', 'added_by', 'user_id', 'created_at']);

        $category->category_name = $request->input('category_name');

        $category->notes = $request->input('notes');
        $category->added_by = $user_name;
        $category->user_id = $user_id;
        $category->save();

        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'categoryes';
        $history->function = 'update';
        $history->function_status = 1;
        $history->branch_id = $branch_id;
        $history->record_id = $category->id;
        $history->previous_data = json_encode($previousData);
        $history->updated_data = json_encode($category->only([
            'category_name', 'category_email', 'category_phone', 'notes', 'added_by', 'user_id'
        ]));
        $history->added_by = $user_name;
        $history->save();

        return response()->json([trans('messages.success_lang', [], session('locale')) => trans('messages.user_update_lang', [], session('locale'))]);
    }


    public function delete_category(Request $request) {


        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        $user_name = $user->user_name;
        $category_id = $request->input('id');
        $category = Category::where('id', $category_id)->first();

        if (!$category) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.category_not_found', [], session('locale'))], 404);
        }

        $previousData = $category->only([
            'category_name',  'notes', 'added_by', 'user_id', 'created_at'
        ]);

        $currentUser = Auth::user();
        $username = $currentUser->user_name;
        $branch_id = $currentUser->branch_id;

        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'categoryes';
        $history->function = 'delete';
        $history->function_status = 2;
        $history->branch_id = $branch_id;
        $history->record_id = $category->id;
        $history->previous_data = json_encode($previousData);

        $history->added_by = $user_name;
        $history->save();
        $category->delete();

        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.user_deleted_lang', [], session('locale'))
        ]);
    }

}
