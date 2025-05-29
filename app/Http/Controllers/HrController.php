<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Staff;
use App\Models\Doctor;
use App\Models\Branch;
use App\Models\User;
use App\Models\History;
use App\Models\Leave;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class HrController extends Controller
{
    public function payroll(){
        $staff= Staff::all();
        $doctor= Doctor::all();
        $branch= Branch::all();

        return view ('hr.payroll', compact('staff','doctor','branch'));
    }
    public function show_employee_payroll(Request $request)
    {

        $staff= Staff::all();
        $doctor= Doctor::all();
        // Merge both collections, adding the payroll_type and other necessary fields
        $mergedData = [];

        // Add staff data to the merged array
        foreach ($staff as $staffMember) {
            $mergedData[] = (object)[
                'id' => $staffMember->id,
                'name' => $staffMember->employee_name,  // Name from the Staff model
                'employee_type' => 1 // For Staff, payroll type is 1
            ];
        }

        // Add doctor data to the merged array
        foreach ($doctor as $doctorMember) {
            $mergedData[] = (object)[
                'id' => $doctorMember->id,
                'name' => $doctorMember->doctor_name,  // Name from the Doctor model
                'employee_type' => 2 // For Doctors, payroll type is 2
            ];
        }
        $json = [];
        foreach($mergedData as $pay_staff)
        {
            // Fetch current month payroll data
            $payroll_data = Payroll::select('employee_id', 'payroll_type', DB::raw('SUM(amount) as total_amount'))
                ->where('employee_id', $pay_staff->id) // Use the object syntax here
                ->where('employee_type', $pay_staff->employee_type) // Use the object syntax here
                ->whereMonth('pay_date', Carbon::now()->month)
                ->whereYear('pay_date', Carbon::now()->year)
                ->groupBy('employee_id', 'payroll_type')
                ->get();

            // Process the payroll data further as needed



            // Get previous month's payroll amounts
            $basic_am = 0;
            $trans_am = 0;
            $utilit_am = 0;
            $col_am = 0;

            foreach (range(1, 4) as $payroll_type) {
                // Fetch the value from the previous month
                $previous_month_data = Payroll::select('amount')
                    ->where('employee_id', $pay_staff->id)
                    ->where('employee_type', $pay_staff->employee_type)
                    ->where('payroll_type', $payroll_type)
                    ->whereNotNull('amount') // Ensure we only get records with a non-null amount
                    ->orderBy('pay_date', 'desc') // Get the latest record
                    ->first();

                if ($previous_month_data) {
                    // Assign the amount based on payroll type
                    if ($payroll_type == 1) {
                        $basic_am = $previous_month_data->amount;
                    } elseif ($payroll_type == 2) {
                        $trans_am = $previous_month_data->amount;
                    } elseif ($payroll_type == 3) {
                        $utilit_am = $previous_month_data->amount;
                    } elseif ($payroll_type == 4) {
                        $col_am = $previous_month_data->amount;
                    }
                }
            }

            // If $payroll_data is empty but any of the previous month's payroll values are not zero, add them manually
            if ($payroll_data->isEmpty() && ($basic_am > 0 || $trans_am > 0 || $utilit_am > 0 || $col_am > 0)) {
                // Manually create payroll data for previous month values
                $payroll_data = collect([
                    (object)[
                        'employee_id' => $pay_staff->id,
                        'payroll_type' => 1, // Basic
                        'total_amount' => $basic_am,
                    ],
                    (object)[
                        'employee_id' => $pay_staff->id,
                        'payroll_type' => 2, // Trans
                        'total_amount' => $trans_am,
                    ],
                    (object)[
                        'employee_id' => $pay_staff->id,
                        'payroll_type' => 3, // Utility
                        'total_amount' => $utilit_am,
                    ],
                    (object)[
                        'employee_id' => $pay_staff->id,
                        'payroll_type' => 4, // Col
                        'total_amount' => $col_am,
                    ]
                ]);
            }



            // Only process payroll data if available
            if (!$payroll_data->isEmpty()) {
                $summary = [];

                foreach ($payroll_data as $row) {
                    $employee_id = $row->employee_id;
                    $payroll_type = $row->payroll_type;
                    $total_amount = $row->total_amount;

                    // If this employee_id is not already in summary, initialize it
                    if (!isset($summary[$employee_id])) {
                        $summary[$employee_id] = [
                            1 => 0, // Basic
                            2 => 0, // Trans
                            3 => 0, // Utility
                            4 => 0, // Col
                            5 => 0, // Visa
                            6 => 0, // Moh
                            7 => 0, // Medical Ins
                            8 => 0, // Medical Bills
                            9 => 0, // Extra Income
                            10 => 0, // BLS Train
                            11 => 0, // Pasi
                            12 => 0, // Air Fare
                            13 => 0, // Extra Hours
                        ];
                    }

                    // Add the amount to the respective payroll_type
                    $summary[$employee_id][$payroll_type] = $total_amount;
                }

                // Now, create rows with the payroll type data
                $gross_salary = 0;
                $net_salary = 0;
                $total_deduction = 0;

                foreach ($summary as $employee_id => $data) {
                    // If any payroll type is empty, use the previous month's value
                    if ($data[1] <= 0) {
                        $data[1] = $basic_am;
                    }
                    if ($data[2] <= 0) {
                        $data[2] = $trans_am;
                    }
                    if ($data[3] <= 0) {
                        $data[3] = $utilit_am;
                    }
                    if ($data[4] <= 0) {
                        $data[4] = $col_am;
                    }

                    $gross_salary = $data[1] + $data[2] + $data[3] + $data[4];
                    $total_deduction = $data[11] + $data[12] + $data[13];
                    $no_of_pt = 0;

                    $net_salary = $gross_salary - $total_deduction + $data[5] + $data[6] + $data[7] + $data[8] + $data[9] + $data[10];

                    // Get notes from the database (for example, from a notes or remarks table)
                    $notes = Payroll::select('notes')
                        ->where('employee_id', $pay_staff->id)
                        ->where('employee_type', $pay_staff->employee_type)
                        ->whereMonth('pay_date', Carbon::now()->month)
                        ->whereYear('pay_date', Carbon::now()->year)
                        ->pluck('notes')  // Assuming each note is a single string
                        ->toArray();  // Convert the notes to an array
                    $remarks = implode(', ', $notes);

                    $leaves_data = Leave::select(DB::raw('SUM(total_leaves) as total_leaves'))
                        ->where('employee_id', $pay_staff->id) // Use the object syntax here
                        ->where('employee_type', $pay_staff->employee_type) // Use the object syntax here
                         ->where('status', 2) // Use the object syntax here
                        ->whereYear('from_date', Carbon::now()->year)
                        ->first();
                    $leaves = 0;
                    if($leaves_data->total_leaves > 0)
                    {
                        $leaves = $leaves_data->total_leaves;
                    }


                    $json[] = [
                        $pay_staff->name,
                        number_format($data[1], 3),
                        number_format($data[2], 3),
                        number_format($data[3], 3),
                        number_format($data[4], 3),
                        number_format($gross_salary, 3),
                        number_format($no_of_pt, 3),
                        number_format($data[5], 3),
                        number_format($data[6], 3),
                        number_format($data[7], 3),
                        $leaves,
                        number_format($data[8], 3),
                        number_format($data[9], 3),
                        number_format($data[10], 3),
                        number_format($data[11], 3),
                        number_format($data[12], 3),
                        number_format($data[13], 3),
                        number_format($total_deduction, 3),
                        number_format($net_salary, 3),
                        $remarks,
                    ];
                }
            }
        }
        if(!empty($json))
        {
            echo json_encode([
                'success' => true,
                'aaData' => $json
            ]);
        } else {
            echo json_encode([
                'sEcho' => 0,
                'iTotalRecords' => 0,
                'iTotalDisplayRecords' => 0,
                'aaData' => []
            ]);
        }

    }



    public function add_payroll(Request $request){

        $user_id = Auth::id();
        $data= User::where('id', $user_id)->first();
        $staff_data= Staff::where('id', $request['employee_id'])->first();
        $user= $data->user_name;

        $payroll_image = "";

        if ($request->hasFile('payroll_image')) {
            $folderPath = public_path('images/payroll_images');

            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }

            $payroll_image = time() . '.' . $request->file('payroll_image')->extension();
            $request->file('payroll_image')->move($folderPath, $payroll_image);
        }

        $emp = explode('-',$request['employee_id']);
        $employee_id = $emp[0];
        $employee_type = $emp[1];

        $payroll = new Payroll();

        $payroll->employee_id = $employee_id;
        $payroll->payroll_type = $request['payroll_type'];
        $payroll->employee_type = $employee_type;
        $payroll->amount = $request['amount'];
        $payroll->payment_file = $payroll_image;
        $payroll->pay_date = $request['pay_date'];
        $payroll->notes = $request['notes'];
        $payroll->branch_id = $staff_data->branch_id;
        $payroll->added_by = $user;
        $payroll->user_id = $user_id;
        $payroll->save();
        return response()->json(['payroll_id' => $payroll->id]);

    }


    public function show_employee_payroll_data(Request $request)
    {

        $sno=0;




        $payroll_data = Payroll::whereMonth('pay_date', Carbon::now()->month)
        ->whereYear('pay_date', Carbon::now()->year)
        ->get();
        if(count($payroll_data)>0)
        {
            foreach($payroll_data as $value)
            {

                if($value->employee_type==1)
                {
                    $employee_name = Staff::where('id', $value->employee_id)->value('employee_name');
                }
                else if($value->employee_type==2)
                {
                    $employee_name = Doctor::where('id', $value->employee_id)->value('doctor_name');
                }


                $modal = '
                <a href="javascript:void(0);" onclick=del_payroll("'.$value->id.'")>
                    <i class="fa fa-trash fs-18 text-danger"></i>
                </a>';
                if(!empty($value->payment_file))
                {
                    $modal.='&nbsp;<a href="'.route('download_payroll', ['filename' => $value->payment_file]).'">
                            <i class="fa-solid fa-download fs-18 text-success"></i>
                        </a>';
                }

                if ($value->payroll_type == 1) {
                    $payroll_type_name = trans('messages.basic_salary_lang',[],session('locale'));
                } elseif ($value->payroll_type == 2) {
                    $payroll_type_name = trans('messages.transport_lang',[],session('locale'));
                } elseif ($value->payroll_type == 3) {
                    $payroll_type_name = trans('messages.utilities_lang',[],session('locale'));
                } elseif ($value->payroll_type == 4) {
                    $payroll_type_name = trans('messages.residence_lang',[],session('locale'));
                } elseif ($value->payroll_type == 5) {
                    $payroll_type_name = trans('messages.visa_lang',[],session('locale'));
                } elseif ($value->payroll_type == 6) {
                    $payroll_type_name = trans('messages.moh_lang',[],session('locale'));
                } elseif ($value->payroll_type == 7) {
                    $payroll_type_name = trans('messages.medical_insurance_lang',[],session('locale'));
                } elseif ($value->payroll_type == 8) {
                    $payroll_type_name = trans('messages.medical_bills_lang',[],session('locale'));
                } elseif ($value->payroll_type == 9) {
                    $payroll_type_name = trans('messages.extra_income_lang',[],session('locale'));
                } elseif ($value->payroll_type == 10) {
                    $payroll_type_name = trans('messages.bls_training_lang',[],session('locale'));
                } elseif ($value->payroll_type == 11) {
                    $payroll_type_name = trans('messages.pasi_lang',[],session('locale'));
                } elseif ($value->payroll_type == 12) {
                    $payroll_type_name = trans('messages.air_fare_lang',[],session('locale'));
                } elseif ($value->payroll_type == 13) {
                    $payroll_type_name = trans('messages.other_salary_lang',[],session('locale'));
                }



                $sno++;
                $json[] = array(
                    $employee_name,
                    $payroll_type_name,
                    $value->amount,
                    $value->pay_date,
                    $value->notes,
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

    public function downloadPayroll($filename)
    {
        // Define the path to the file in the public folder
        $filePath = public_path('images/payroll_images/' . $filename);  // Path to your file

        // Check if the file exists
        if (file_exists($filePath)) {
            // Return file for download
            return response()->download($filePath);
        } else {
            // Return an error message if the file does not exist
            return response()->json(['message' => 'File not found'], 404);
        }
    }

    public function delete_payroll(Request $request) {
        $payroll_id = $request->input('id');
        $payroll = Payroll::where('id', $payroll_id)->first();

        if (!$payroll) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.payroll_not_found', [], session('locale'))], 404);
        }

        // Store previous data before deletion
        $previousData = $payroll->only([
            'payroll_type', 'employee_id', 'amount', 'employee_type', 'pay_date', 'branch_id', 'notes', 'added_by', 'created_at'
        ]);

        // Get current payroll info
        $user_id = Auth::id();
        $data= User::where('id', $user_id)->first();
        $user= $data->user_name;
        $branch= $data->branch_id;


        // Save history before deletion
        $history = new History();
        $history->table_name = 'payrolls';
        $history->branch_id = $branch;
        $history->function = 'delete';
        $history->function_status = 2;
        $history->record_id = $payroll->id;
        $history->previous_data = json_encode($previousData);
        $history->added_by = $user;
        $history->user_id = $user_id;
        $history->save();

        $payroll->delete();

        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.payroll_deleted_lang', [], session('locale'))
        ]);
    }

    // leaves
    public function leaves(){
        $staff= Staff::all();
        $doctor= Doctor::all();
        $branch= Branch::all();
        return view ('hr.leaves', compact('staff','doctor','branch'));
    }

    public function add_leaves(Request $request){

        $user_id = Auth::id();
        $data= User::where('id', $user_id)->first();
        $staff_data= Staff::where('id', $request['employee_id'])->first();
        $user= $data->user_name;

        $leaves_image = "";

        if ($request->hasFile('leave_image')) {
            $folderPath = public_path('images/leaves_images');

            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }

            $leaves_image = time() . '.' . $request->file('leave_image')->extension();
            $request->file('leave_image')->move($folderPath, $leaves_image);
        }

        $emp = explode('-',$request['employee_id']);
        $employee_id = $emp[0];
        $employee_type = $emp[1];
        $leaves_data = Leave::select(DB::raw('SUM(total_leaves) as total_leaves'))
                ->where('employee_id', $employee_id) // Use the object syntax here
                ->where('employee_type', $employee_type) // Use the object syntax here
                ->where('leaves_type', $request['leaves_type']) // Use the object syntax here
                ->where('status', 2) // Use the object syntax here
                ->whereYear('from_date', Carbon::now()->year)
                ->first();
        $all_leaves = 0;
        if($employee_type == 1)
        {
            $staff_data= Staff::where('id', $employee_id)->first();
            if($request['leaves_type'] == 1)
            {
                $all_leaves = $staff_data->annual_leaves;
            }
            else
            {
                $all_leaves = $staff_data->emergency_leaves;
            }

        }
        else
        {
            $doctor_data= Doctor::where('id', $employee_id)->first();
            if($request['leaves_type'] == 1)
            {
                $all_leaves = $doctor_data->annual_leaves;
            }
            else
            {
                $all_leaves = $doctor_data->emergency_leaves;
            }
        }
        $remaining_leaves =0;
        if($request['leaves_type']==3)
        {
            $remaining_leaves = 0;
        }
        else
        {
            $remaining_leaves = $all_leaves - $leaves_data->total_leaves - $request['total_leaves'];
        }
        $to_date = date('Y-m-d', strtotime($request['from_date'] . ' +'.$request['total_leaves'].' days'));
        $leaves = new Leave();

        $leaves->employee_id = $employee_id;
        $leaves->leaves_type = $request['leaves_type'];
        $leaves->employee_type = $employee_type;
        $leaves->total_leaves = $request['total_leaves'];
        $leaves->remaining_leaves = $remaining_leaves;
        $leaves->leave_file = $leaves_image;
        $leaves->from_date = $request['from_date'];
        $leaves->to_date = $to_date;
        $leaves->reason = $request['reason'];
        $leaves->branch_id = $staff_data->branch_id;
        $leaves->added_by = $user;
        $leaves->user_id = $user_id;
        $leaves->save();
        return response()->json(['leaves_id' => $leaves->id]);

    }

    public function show_employee_leaves()
    {
        $sno = 0;
        $leaves = Leave::where('status',1)->get();

        if ($leaves->count() > 0) {
            foreach ($leaves as $value) {
                if($value->employee_type==1)
                {
                    $employee_name = Staff::where('id', $value->employee_id)->value('employee_name');
                }
                else if($value->employee_type==2)
                {
                    $employee_name = Doctor::where('id', $value->employee_id)->value('doctor_name');
                }


                $modal = '
                <a href="javascript:void(0);" onclick=del_leaves("'.$value->id.'")>
                    <i class="fa fa-trash fs-18 text-danger"></i>
                </a>';
                if(!empty($value->leave_file))
                {
                    $modal.='&nbsp;<a href="'.route('download_leaves', ['filename' => $value->leave_file]).'">
                            <i class="fa-solid fa-download fs-18 text-success"></i>
                        </a>';
                }

                if ($value->leaves_type == 1) {
                    $leaves_type_name = trans('messages.annual_leaves_lang',[],session('locale'));
                } elseif ($value->leaves_type == 2) {
                    $leaves_type_name = trans('messages.emergency_leaves_lang',[],session('locale'));
                } elseif ($value->leaves_type == 3) {
                    $leaves_type_name = trans('messages.sick_leaves_lang',[],session('locale'));
                }

                $sno++;
                $json[] = array(
                    $employee_name,
                    $leaves_type_name,
                    $value->total_leaves,
                    $value->from_date,
                    $value->to_date,
                    $value->reason,
                    $modal
                );
            }

            return response()->json(['success' => true, 'aaData' => $json]);
        }

        return response()->json(['sEcho' => 0, 'iTotalRecords' => 0, 'iTotalDisplayRecords' => 0, 'aaData' => []]);
    }


    public function show_employee_leaves_data(Request $request)
    {
        $sno = 0;

        $json=[];
        if(!empty($request['employee_id']))
        {
            $emp = explode('-',$request['employee_id']);
            $employee_id = $emp[0];
            $employee_type = $emp[1];
            $leaves_data = Leave::where('employee_id', $employee_id) // Use the object syntax here
                    ->where('employee_type', $employee_type) // Use the object syntax here
                    // ->where('leaves_type', $request['leaves_type']) // Use the object syntax here
                    ->whereYear('from_date', Carbon::now()->year)
                    ->get();


            foreach ($leaves_data as $value) {
                if($value->employee_type==1)
                {
                    $employee_name = Staff::where('id', $value->employee_id)->value('employee_name');
                }
                else if($value->employee_type==2)
                {
                    $employee_name = Doctor::where('id', $value->employee_id)->value('doctor_name');
                }


                $modal = '
                <a href="javascript:void(0);" onclick=del_leaves("'.$value->id.'")>
                    <i class="fa fa-trash fs-18 text-danger"></i>
                </a>';
                if(!empty($value->leave_file))
                {
                    $modal.='&nbsp;<a href="'.route('download_leaves', ['filename' => $value->leave_file]).'">
                            <i class="fa-solid fa-download fs-18 text-success"></i>
                        </a>';
                }

                if ($value->leaves_type == 1) {
                    $leaves_type_name = trans('messages.annual_leaves_lang',[],session('locale'));
                } elseif ($value->leaves_type == 2) {
                    $leaves_type_name = trans('messages.emergency_leaves_lang',[],session('locale'));
                } elseif ($value->leaves_type == 3) {
                    $leaves_type_name = trans('messages.sick_leaves_lang',[],session('locale'));
                }


                if ($value->status == 1) {
                    $status = '<span class="badge bg-primary">'.trans('messages.pending_lang',[],session('locale')).'</span>';
                } elseif ($value->status == 2) {
                    $status = '<span class="badge bg-success">'.trans('messages.accepted_lang',[],session('locale')).'</span>';
                } elseif ($value->status == 3) {
                    $status = '<span class="badge bg-danger">'.trans('messages.rejected_lang',[],session('locale')).'</span>';
                }

                $sno++;
                $json[] = array(
                    $leaves_type_name,
                    $status,
                    $value->total_leaves,
                    $value->from_date,
                    $value->to_date,
                    $value->reason,

                );
            }

            return response()->json(['success' => true, 'aaData' => $json]);


        }
        else
        {
            return response()->json(['success' => true, 'aaData' => []]);
        }
    }


    public function downloadleaves($filename)
    {
        // Define the path to the file in the public folder
        $filePath = public_path('images/leaves_images/' . $filename);  // Path to your file

        // Check if the file exists
        if (file_exists($filePath)) {
            // Return file for download
            return response()->download($filePath);
        } else {
            // Return an error message if the file does not exist
            return response()->json(['message' => 'File not found'], 404);
        }
    }

    public function delete_leaves(Request $request) {
        $leaves_id = $request->input('id');
        $leaves = Leave::where('id', $leaves_id)->first();

        if (!$leaves) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.leaves_not_found', [], session('locale'))], 404);
        }

        // Store previous data before deletion
        $previousData = $leaves->only([
            'leaves_type', 'employee_id', 'total_leaves','remaining_leaves','from_date','to_date', 'employee_type', 'branch_id', 'reason', 'added_by', 'created_at'
        ]);

        // Get current leaves info
        $user_id = Auth::id();
        $data= User::where('id', $user_id)->first();
        $user= $data->user_name;
        $branch= $data->branch_id;


        // Save history before deletion
        $history = new History();
        $history->table_name = 'leaves';
        $history->branch_id = $branch;
        $history->function = 'delete';
        $history->function_status = 2;
        $history->record_id = $leaves->id;
        $history->previous_data = json_encode($previousData);
        $history->added_by = $user;
        $history->user_id = $user_id;
        $history->save();

        $leaves->delete();

        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.leaves_deleted_lang', [], session('locale'))
        ]);
    }


    public function get_remaining_leaves(Request $request) {
        $id = $request['id'];
        $emp = explode('-',$id);
        $employee_id = $emp[0];
        $employee_type = $emp[1];
        $leaves_data = Leave::select(DB::raw('SUM(total_leaves) as total_leaves'))
                ->where('employee_id', $employee_id) // Use the object syntax here
                ->where('employee_type', $employee_type) // Use the object syntax here
                ->where('leaves_type', $request['leaves_type']) // Use the object syntax here
                // ->where('status', 2) // Use the object syntax here
                ->whereYear('from_date', Carbon::now()->year)
                ->first();

        $all_leaves = 0;
        if($employee_type == 1)
        {
            $staff_data= Staff::where('id', $employee_id)->first();
            if($request['leaves_type'] == 1)
            {
                $all_leaves = $staff_data->annual_leaves;
            }
            else
            {
                $all_leaves = $staff_data->emergency_leaves;
            }

        }
        else
        {
            $doctor_data= Doctor::where('id', $employee_id)->first();
            if($request['leaves_type'] == 1)
            {
                $all_leaves = $doctor_data->annual_leaves;
            }
            else
            {
                $all_leaves = $doctor_data->emergency_leaves;
            }
        }
        $remaining_leaves =0;
        if($request['leaves_type']==3)
        {
            $remaining_leaves = 0;
        }
        else
        {
            $remaining_leaves = $all_leaves - $leaves_data->total_leaves ;
        }
        return response()->json([ 'remaining_leaves'=> $remaining_leaves ]);
    }

    // pending leaves
    public function pending_leaves(){

        return view ('hr.pending_leaves');
    }

    public function add_leaves_reponse(Request $request){

        $user_id = Auth::id();
        $data= User::where('id', $user_id)->first();
        $user= $data->user_name;



        $leave_id = $request['leave_id'];
        $leave_data = Leave::where('id',$leave_id)->first();


        $leave_data->status = $request['response_type'];
        $leave_data->response_reason = $request['reason'];
        $leave_data->responded_by = $user_id;
        $leave_data->responded_date = date('Y-m-d H:i:s');
        $leave_data->save();

    }

    public function show_pending_leaves()
    {
        $sno = 0;
        $leaves = Leave::where('status',1)->get();

        if ($leaves->count() > 0) {
            foreach ($leaves as $value) {
                if($value->employee_type==1)
                {
                    $employee_name = Staff::where('id', $value->employee_id)->value('employee_name');
                }
                else if($value->employee_type==2)
                {
                    $employee_name = Doctor::where('id', $value->employee_id)->value('doctor_name');
                }


                $modal = '<a href="javascript:void();"   onclick=leave_response_type("3","'.$value->id.'") data-bs-toggle="modal" data-bs-target="#add_leave_response_modal"><i class="fas fa-times fs-18 text-danger"></i></a>&nbsp;
                <a href="javascript:void();"   onclick=leave_response_type("2","'.$value->id.'") data-bs-toggle="modal" data-bs-target="#add_leave_response_modal"><i class="fas fa-check fs-18 text-success"></i></a>';
                if(!empty($value->leave_file))
                {
                    $modal.='&nbsp;<a href="'.route('download_leaves', ['filename' => $value->leave_file]).'">
                            <i class="fa-solid fa-download fs-18 text-warning"></i>
                        </a>';
                }

                if ($value->leaves_type == 1) {
                    $leaves_type_name = trans('messages.annual_leaves_lang',[],session('locale'));
                } elseif ($value->leaves_type == 2) {
                    $leaves_type_name = trans('messages.emergency_leaves_lang',[],session('locale'));
                } elseif ($value->leaves_type == 3) {
                    $leaves_type_name = trans('messages.sick_leaves_lang',[],session('locale'));
                }

                $sno++;
                $json[] = array(
                    $employee_name,
                    $leaves_type_name,
                    $value->total_leaves,
                    $value->from_date,
                    $value->to_date,
                    $value->reason,
                    $modal
                );
            }

            return response()->json(['success' => true, 'aaData' => $json]);
        }

        return response()->json(['sEcho' => 0, 'iTotalRecords' => 0, 'iTotalDisplayRecords' => 0, 'aaData' => []]);
    }

    // responded leaves
    public function responded_leaves(){

        return view ('hr.responded_leaves');
    }
    public function show_responded_leaves(Request $request)
    {
        $sno = 0;
        if (!empty($request['status'])) {
            $leaves = Leave::where('status', $request['status'])->get();
        } else {
            $leaves = Leave::where('status', '!=', 1)->get();
        }
        if ($leaves->count() > 0) {
            foreach ($leaves as $value) {
                if($value->employee_type==1)
                {
                    $employee_name = Staff::where('id', $value->employee_id)->value('employee_name');
                }
                else if($value->employee_type==2)
                {
                    $employee_name = Doctor::where('id', $value->employee_id)->value('doctor_name');
                }


                $modal="";
                if(!empty($value->leave_file))
                {
                    $modal.='&nbsp;<a href="'.route('download_leaves', ['filename' => $value->leave_file]).'">
                            <i class="fa-solid fa-download fs-18 text-warning"></i>
                        </a>';
                }

                if ($value->leaves_type == 1) {
                    $leaves_type_name = trans('messages.annual_leaves_lang',[],session('locale'));
                } elseif ($value->leaves_type == 2) {
                    $leaves_type_name = trans('messages.emergency_leaves_lang',[],session('locale'));
                } elseif ($value->leaves_type == 3) {
                    $leaves_type_name = trans('messages.sick_leaves_lang',[],session('locale'));
                }

                if ($value->status == 1) {
                    $status = '<span class="badge bg-primary">'.trans('messages.pending_lang',[],session('locale')).'</span>';
                } elseif ($value->status == 2) {
                    $status = '<span class="badge bg-success">'.trans('messages.accepted_lang',[],session('locale')).'</span>';
                } elseif ($value->status == 3) {
                    $status = '<span class="badge bg-danger">'.trans('messages.rejected_lang',[],session('locale')).'</span>';
                }

                $sno++;
                $json[] = array(
                    $employee_name,
                    $leaves_type_name,
                    $status,
                    $value->total_leaves,
                    $value->from_date,
                    $value->to_date,
                    $value->reason,
                    $modal
                );
            }

            return response()->json(['success' => true, 'aaData' => $json]);
        }

        return response()->json(['sEcho' => 0, 'iTotalRecords' => 0, 'iTotalDisplayRecords' => 0, 'aaData' => []]);
    }
}
