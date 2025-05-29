<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Offer;
use App\Models\Account;
use App\Models\History;
use App\Models\Patient;
use App\Models\Category;
use App\Models\GovtDept;
use App\Models\Appointment;
use App\Models\SessionData;
use App\Models\SessionList;
use Illuminate\Http\Request;
use App\Models\SessionDetail;
use App\Models\SessionsPayment;
use App\Models\AppointmentDetail;
use App\Models\SessionsonlyPayment;
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

            $govt_name = '<a class="patient-info ps-0" href="govt_detail/' . $value->id . '">' . $value->govt_name . '</a>';

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
                 $govt_name,
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

public function govt_detail($id) {

    $mini = GovtDept::where('id', $id)->first();

    $appointments = AppointmentDetail::where('ministry_id', $id)
        ->whereNotNull('ministry_id')
        ->where('contract_payment', 1)
        ->get();
        $appointments2 = AppointmentDetail::where('ministry_id', $id)
        ->whereNotNull('ministry_id')
        ->get();

    $totalAppointmentPrice = $appointments->sum('total_price');
    $totalUniquePatientsAppointments = $appointments2->pluck('patient_id')->unique()->count();

    $sessionDetails = SessionDetail::where('ministry_id', $id)
        ->whereNotNull('ministry_id')
        ->where('contract_payment', 1)
        ->get();
        $sessionDetails2 = SessionDetail::where('ministry_id', $id)
        ->whereNotNull('ministry_id')
        ->get();

    $totalSessionFee = $sessionDetails->sum('total_fee');
    $totalUniquePatientsSessions = $sessionDetails2->pluck('patient_id')->unique()->count();

    $total_pending = $totalAppointmentPrice + $totalSessionFee;

    // Removed the condition of contract_payment == 2 for counting patients
    $totalUniquePatients = $appointments2->pluck('patient_id')->merge($sessionDetails2->pluck('patient_id'))->unique()->count();



    $totalPaidAppointments = SessionsPayment::
        where('contract_payment', 2)
        ->sum('amount');

    $totalPaidSessions = SessionsonlyPayment::
        where('contract_payment', 2)
        ->sum('amount');

    $total_paid_combined = $totalPaidAppointments + $totalPaidSessions;

    return view('sessions.ministry_detail', compact('mini', 'total_pending', 'total_paid_combined', 'totalUniquePatients'));
}



public function show_all_contract(Request $request) {
    $miniId = $request->input('mini_id');
    $appointments = AppointmentDetail::whereNotNull('ministry_id')->where('ministry_id', $miniId)->get();
    $sessions = SessionDetail::whereNotNull('ministry_id')->where('ministry_id', $miniId)->get();

    $data = [];


    foreach ($appointments as $appointment) {
        $payments = SessionsPayment::where('appointment_id', $appointment->appointment_id)->where('contract_payment', 2)->get();
        $detail = AppointmentDetail::where('appointment_id', $appointment->appointment_id)->first();


        $isContractPayment = $detail->ministry_id ? true : false;
        $contractPaymentStatus = $detail->contract_payment;

        $total_paid_amount = 0;
        $account_amounts = [];
        $voucher_codes = [];
        $voucher_amounts = [];

        foreach ($payments as $payment) {
            $total_paid_amount += $payment->amount ?? 0;

            if ($payment->account_id) {
                $account_name = Account::where('id', $payment->account_id)->value('account_name');
                if ($account_name) {
                    if (!isset($account_amounts[$account_name])) {
                        $account_amounts[$account_name] = 0;
                    }
                    $account_amounts[$account_name] += $payment->amount ?? 0;
                }
            }

            if ($payment->voucher_code) {
                $voucher_codes[] = $payment->voucher_code;
            }

            if ($payment->voucher_amount) {
                $voucher_amounts[] = $payment->voucher_amount;
            }
        }

        // === APPLY CONTRACT PAYMENT RULES ===
        if ($isContractPayment && $contractPaymentStatus == 1) {
            $total_paid_amount = 'Pending';
            $account_amounts = [];
        }

        $apt_no = Appointment::where('id', $appointment->appointment_id)->value('appointment_no') ?? '';
        $total_sessions = SessionData::where('main_appointment_id', $appointment->appointment_id)->count();
        $taken_session= SessionData::where('main_appointment_id', $appointment->appointment_id)->where('status', 2)->count();
        $pending= SessionData::where('main_appointment_id', $appointment->appointment_id)->where('status', 1)->count();
        $ot= SessionData::where('main_appointment_id', $appointment->appointment_id)->where('session_cat', 'OT')->count();
        $pt= SessionData::where('main_appointment_id', $appointment->appointment_id)->where('session_cat', 'PT')->count();

        $data[] = [
            'type' => 'appointment',
            'appointment_no' => $apt_no,
            'taken_session'=>$taken_session,
            'pending'=>$pending,
            'ot'=>$ot,
            'pt'=>$pt,
            'fee' => $appointment->total_price ?? 0,
            'paid_amount' => $total_paid_amount,
            'account_amounts' => $account_amounts,
            'payment_type' => $detail->session_type,
            'voucher_codes' => array_unique($voucher_codes),
            'voucher_amounts' => $voucher_amounts,
            'session_count' => $total_sessions,
            'single_session_fee' => ($total_sessions > 0)
                ? $appointment->total_price / $total_sessions
                : 0,
            'contract_payment_check' => [
                'is_contract' => $isContractPayment,
                'status' => $contractPaymentStatus,
            ],
        ];
    }

    foreach ($sessions as $session) {
        $payments = SessionsonlyPayment::where('session_id', $session->session_id)->where('contract_payment', 2)->get();
        $detail = SessionDetail::where('session_id', $session->session_id)->first();



        $isContractPayment = $detail->ministry_id ? true : false;
        $contractPaymentStatus = $detail->contract_payment;

        $total_paid_amount = 0;
        $account_amounts = [];
        $voucher_codes = [];
        $voucher_amounts_sum = 0;

        foreach ($payments as $payment) {
            $total_paid_amount += $payment->amount ?? 0;

            if ($payment->account_id) {
                $account_name = Account::where('id', $payment->account_id)->value('account_name');
                if ($account_name) {
                    if (!isset($account_amounts[$account_name])) {
                        $account_amounts[$account_name] = 0;
                    }
                    $account_amounts[$account_name] += $payment->amount ?? 0;
                }
            }

            if ($payment->voucher_code) {
                $voucher_codes[] = $payment->voucher_code;
            }

            if ($payment->voucher_amount) {
                $voucher_amounts_sum += $payment->voucher_amount;
            }
        }

        // === APPLY CONTRACT PAYMENT RULES ===
        if ($isContractPayment && $contractPaymentStatus == 1) {
            $total_paid_amount = 'Pending';
            $account_amounts = [];
        }

        $session_no = SessionList::where('id', $session->session_id)->value('session_no') ?? '';
        $total_sessions = SessionData::where('main_session_id', $session->session_id)->count();
        $taken_session= SessionData::where('main_session_id', $session->session_id)->where('status', 2)->count();
        $pending= SessionData::where('main_session_id', $session->session_id)->where('status', 1)->count();
        $ot= SessionData::where('main_session_id', $session->session_id)->where('session_cat', 'OT')->count();
        $pt= SessionData::where('main_session_id', $session->session_id)->where('session_cat', 'PT')->count();


        $data[] = [
            'type' => 'session',
            'appointment_no' => $session_no,
            'fee' => $session->total_fee ?? 0,
            'paid_amount' => $total_paid_amount,
            'taken_session'=>$taken_session,
            'pending'=>$pending,
            'ot'=>$ot,
            'pt'=>$pt,
            'account_amounts' => $account_amounts,
            'payment_type' => $detail->session_type,
            'voucher_codes' => array_unique($voucher_codes),
            'total_voucher_amount' => $voucher_amounts_sum,
            'session_count' => $total_sessions,
            'single_session_fee' => ($total_sessions > 0)
                ? $session->total_fee / $total_sessions
                : 0,
            'contract_payment_check' => [
                'is_contract' => $isContractPayment,
                'status' => $contractPaymentStatus,
            ],
        ];
    }


    return response()->json([
        'data' => $data,
    ]);
}

}
