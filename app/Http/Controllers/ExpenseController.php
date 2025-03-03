<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Account;
use App\Models\Expense;
use App\Models\History;
use App\Models\Expensecat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Validator;

class ExpenseController extends Controller
{
    public function index(){


        $view_account= Account::all();
        $expense_cats= Expensecat::all();


        // if (!Auth::check()) {

        //     return redirect()->route('login_page')->with('error', trans('messages.please_log_in', [], session('locale')));
        // }

        // $user = Auth::user();

        // if (in_array(5, explode(',', $user->permit_type))) {

            return view ('expense.expense', compact('view_account', 'expense_cats'));
        // } else {


//  return redirect()->route('/')->with('error', trans('messages.you_dont_have_permissions', [], session('locale')));
//         }

    }

    public function show_expense()
    {
        $sno=0;

        $view_expense= Expense::all();
        if(count($view_expense)>0)
        {
            foreach($view_expense as $value)
            {

                $expense_name='<a href="javascript:void(0);">'.$value->expense_name.'</a>';

                $cat_name = Expensecat::where('id', $value->category_id)->value('expense_category_name');
                $payment_method = Account::where('id', $value->payment_method)->value('account_name');
                $modal = '
                <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#add_expense_modal" onclick=edit("'.$value->id.'")>
                    <i class="fa fa-pencil fs-18 text-success"></i>
                </a>
                <a href="javascript:void(0);" onclick=del("'.$value->id.'")>
                    <i class="fa fa-trash fs-18 text-danger"></i>
                </a>';

                $add_data=Carbon::parse($value->created_at)->format('d-m-Y (h:i a)');

                $sno++;
                $json[]= array(
                            $sno,
                            $cat_name,
                            $expense_name,
                            $value->amount,
                            $value->expense_date,
                            $payment_method,
                            $value->added_by,
                            $add_data,
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

    public function add_expense(Request $request)
    {
        $user_id = Auth::id();
        $data = User::where('id', $user_id)->first();
        $user = $data->user_name;

        $expense = new Expense();
        $expense_file = "";

        // Handle the file upload
        if ($request->hasFile('expense_file')) {
            $folderPath = public_path('uploads/expense_files');

            // Check if the folder exists, if not create it
            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }

            // Create a unique filename
            $expense_file = time() . '.' . $request->file('expense_file')->extension();
            $request->file('expense_file')->move($folderPath, $expense_file);
        }

        // Save expense details
        $expense->category_id = $request['category_id'];
        $expense->expense_name = $request['expense_name'];
        $expense->payment_method = $request['account_id'];
        $expense->amount = $request['amount'];
        $expense->expense_date = $request['expense_date'];
        $expense->notes = $request['notes'];
        $expense->expense_image = $expense_file; // Save the file name in the database
        $expense->added_by = $user;
        $expense->user_id = $user_id;
        $expense->save();

        $account_data = Account::where('id', $request['account_id'])->first();
        if ($account_data) {
            $opening_balance = $account_data->opening_balance ?? 0;
            $new_amount = $opening_balance - $request['amount'];

            $account_data->opening_balance = $new_amount;
            $account_data->updated_by = $user;
            $account_data->save();
        }
        $account_data->save();

        return response()->json(['expense_id' => $expense->id]);
    }



    public function edit_expense(Request $request){
        $expense_id = $request->input('id');
        // Use the Eloquent where method to retrieve the expense by column name
        $expense_data = Expense::where('id', $expense_id)->first();

        if (!$expense_data) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.expense_not_found', [], session('locale'))], 404);
        }

        // Check the file extension and prepare appropriate icon or image preview
        $expense_image = $expense_data->expense_image;
        $file_name = basename($expense_image); // Get the original file name without the path

        $file_url = null;
        $file_type = null;

        if ($expense_image) {
            // Get the file extension
            $extension = pathinfo($expense_image, PATHINFO_EXTENSION);

            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                // If it's an image, show image preview
                $file_url = asset('images/expense_images/' . $expense_image);
                $file_type = 'image';
            } elseif ($extension == 'pdf') {
                // If it's a PDF, show PDF icon
                $file_url = asset('images/dummy_images/pdf.png');
                $file_type = 'pdf';
            } elseif (in_array($extension, ['doc', 'docx'])) {
                // If it's a Word document, show Word icon
                $file_url = asset('images/dummy_images/word.jpeg');
                $file_type = 'word';
            } elseif (in_array($extension, ['xls', 'xlsx'])) {
                // If it's an Excel file, show Excel icon
                $file_url = asset('images/dummy_images/excel.jpeg');
                $file_type = 'excel';
            } else {
                // Default file icon
                $file_url = asset('images/dummy_images/file.png');
                $file_type = 'other';
            }
        }

        $data = [
            'expense_id' => $expense_data->id,
            'expense_name' => $expense_data->expense_name,
            'category_id' => $expense_data->category_id,
            'amount' => $expense_data->amount,
            'payment_method' => $expense_data->payment_method,
            'expense_date' => $expense_data->expense_date,
            'category_image' => $expense_data->category_image,
            'notes' => $expense_data->notes,
            'expense_image' => $file_url,
            'file_type' => $file_type,
        ];

        return response()->json($data);
    }



    public function update_expense(Request $request)
    {
        $user_id = Auth::id();
        $data = User::where('id', $user_id)->first(); // No need to call `first()` after `find()`
        $user_name = $data->user_name; // Corrected variable name
        $branch_id= $data->branch_id;

        $expense_id = $request->input('expense_id');
        $expense = Expense::where('id', $expense_id)->first();

        if (!$expense) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.expense_not_found', [], session('locale'))], 404);
        }

        // Capture the previous data before the update
        $previousData = $expense->toArray();

        // Store file if provided
        $expense_file = $expense->expense_image; // Keep the old file if no new file is uploaded.

        if ($request->hasFile('expense_file')) {
            $folderPath = public_path('uploads/expense_files');

            // Check if the folder exists, if not create it
            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }

            // Create a unique filename
            $expense_file = time() . '.' . $request->file('expense_file')->extension();

            // Move the file
            $request->file('expense_file')->move($folderPath, $expense_file);

            // Optionally delete the old file if a new one is uploaded
            if ($expense->expense_image && file_exists(public_path('uploads/expense_files/' . $expense->expense_image))) {
                unlink(public_path('uploads/expense_files/' . $expense->expense_image));
            }
        }

        // Save updated expense details
        $expense->category_id = $request['category_id'];
        $expense->expense_name = $request['expense_name'];
        $expense->payment_method = $request['account_id'];
        $expense->amount = $request['amount'];
        $expense->expense_date = $request['expense_date'];
        $expense->notes = $request['notes'];
        $expense->expense_image = $expense_file; // Save the file name in the database
        $expense->added_by = $user_name;
        $expense->user_id = $user_id;
        $expense->save();

        // Handle account data
        $account_data = Account::where('id', $request['account_id'])->first();

        if ($account_data) {
            $opening_balance = $account_data->opening_balance ?? 0;
            $new_amount = $opening_balance - $request['amount'];

            $account_data->opening_balance = $new_amount;
            $account_data->updated_by = $user_name;
            $account_data->save(); // Save account data once
        }

        // Log the update in the history table
        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'expenses'; // Corrected table name to 'expenses'
        $history->function = 'update';
        $history->function_status = 1;
        $history->branch_id = $branch_id;

        $history->record_id = $expense->id; // Use expense id as the record_id
        $history->previous_data = json_encode($previousData); // Store the previous data
        $history->updated_data = json_encode($expense->only([
            'category_id', 'expense_name', 'payment_method', 'amount', 'expense_date', 'notes', 'expense_image', 'added_by', 'user_id'
        ])); // Store the updated data
        $history->added_by = $user_name;
        $history->save();

        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.expense_update_lang', [], session('locale'))
        ]);
    }



    public function delete_expense(Request $request)
    {
        $expense_id = $request->input('id');
        $expense = Expense::where('id', $expense_id)->first();

        if (!$expense) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.expense_not_found', [], session('locale'))], 404);
        }

        // Capture the previous data before the delete
        $previousData = $expense->only([
            'category_id', 'expense_name', 'payment_method', 'amount', 'expense_date', 'notes', 'expense_image', 'added_by', 'user_id', 'created_at'
        ]);

        // Get the current user
        $user_id = Auth::id();
        $data = User::where('id', $user_id)->first();
        $user_name = $data->user_name;
        $branch_id= $data->branch_id;


        // Log the deletion in the history table
        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'expenses'; // Table name is 'expenses'
        $history->function = 'delete';
        $history->function_status = 1;
        $history->branch_id = $branch_id;
        $history->record_id = $expense->id; // Use expense id as the record_id
        $history->previous_data = json_encode($previousData); // Store the previous data (before deletion)
        $history->updated_data = null; // No updated data since it's a delete operation
        $history->added_by = $user_name;
        $history->save();

        // Delete the expense
        $expense->delete();

        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.expense_deleted_lang', [], session('locale'))
        ]);
    }


    // download
    public function download_expense_image($filename)
    {
        $filePath = public_path('customer_images/expense_images/' . $filename);

        // Check if file exists
        if (file_exists($filePath)) {
            return response()->download($filePath, $filename);
        }

        // File not found
        abort(404);
    }
}
