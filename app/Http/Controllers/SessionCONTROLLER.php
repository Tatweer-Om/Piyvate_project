<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Offer;
use App\Models\Branch;
use App\Models\Doctor;
use App\Models\Sation;
use App\Models\Account;
use App\Models\Country;
use App\Models\History;
use App\Models\Patient;
use App\Models\Session;
use App\Models\Setting;
use App\Models\GovtDept;
use App\Models\SessionList;
use Illuminate\Http\Request;
use App\Models\SessionDetail;
use App\Models\AllSessioDetail;
use App\Models\AppointmentSession;
use App\Models\SessionsallSession;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use App\Models\SessionsonlyPayment;
use Illuminate\Support\Facades\Auth;
use App\Models\SessionsonlyPaymentExp;

class SessionCONTROLLER extends Controller
{



        public function add_session(Request $request)
        {
            $user_id = Auth::id();
            $data = User::where('id', $user_id)->first();
            $user = $data->user_name;
            $branch_id = $data->branch_id;

            // Determine title
            $titles = [1 => 'Miss', 2 => 'Mr.', 3 => 'Mrs.'];
            $title = $titles[$request->title] ?? '';

            $full_name = trim($title . ' ' . $request->first_name . ' ' . $request->second_name);

            // Check if patient exists
            $patient = Patient::where('mobile', $request->mobile)->first();

            if (!$patient) {
                $prefix = 'HN-125'; // Correct prefix format

            $lastClinicNumber = Patient::where('HN', 'like', $prefix . '%')
                ->orderBy('HN', 'desc')
                ->value('HN');

            if ($lastClinicNumber) {
                $lastNumber = intval(substr($lastClinicNumber, strlen($prefix)));
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $clinicNumber = sprintf('%s%05d', $prefix, $nextNumber);
                $patient = new Patient();
                $patient->full_name = $full_name;
                $patient->title = $request->title;
                $patient->first_name = $request->first_name;
                $patient->second_name = $request->second_name;
                $patient->mobile = $request->mobile;
                $patient->id_passport = $request->id_passport;
                $patient->dob = $request->dob;
                $patient->age = $request->age;
                $patient->gender = $request->gender;
                $patient->country_id = $request->country;
                $patient->branch_id = $branch_id;
                $patient->added_by = $user;
                $patient->user_id = $user_id;
                $patient->HN = $clinicNumber;
                $patient->save();
            }

            $month = date('n');
            $year = date('y');
            $clinicPrefix = "{$month}{$year}S-";

            $lastsession = SessionList::where('session_no', 'like', "{$clinicPrefix}%")
                ->orderBy('session_no', 'desc')
                ->first();

            $newSequence = $lastsession
                ? str_pad((int) substr($lastsession->session_no, strrpos($lastsession->session_no, '-') + 1) + 1, 3, '0', STR_PAD_LEFT)
                : '001';

            $clinicNo = "{$clinicPrefix}{$newSequence}";

            $sessions_count= $request->no_of_sessions;
            $session_fee= $request->session_fee;

            $session = new SessionList();
            $session->doctor_id = $request->doctor;
            $session->session_type = $request->session_type;
            $session->session_fee = $session_fee;
            $session->no_of_sessions = $sessions_count;
            $session->session_gap = $request->session_gap;
            $session->session_date = $request->session_date;
            $session->offer_id = $request->offer_id;
            $session->ministry_id = $request->ministry_id;
            $session->session_cat = $request->session_cat;
            $session->user_id = $user_id;
            $session->added_by = $user;
            $session->branch_id = $branch_id;
            $session->notes = $request->notes;
            $session->patient_id = $patient->id;
            $session->HN = $patient->HN;
            $session->session_no = $clinicNo;
            if ($session->session_date > now()->toDateString()) {
                $session->session_status = 5; // Set to "Pre-Registered"
            } else {
                $session->session_status = 2; // Set to "Appointment"
            }
            if ($session->session_type == 'normal') {
                $session->payment_status = 1;
            } elseif ($session->session_type == 'ministry') {
                $session->payment_status = 3;
            } elseif ($session->session_type == 'offer') {
                $session->payment_status = 2;
            } else {
                $session->payment_status = 0;
            }

            $session->save();


            if ($session->save()) {
                return response()->json(['success' => true, 'session_id' => $session->id, 'message' => 'Session added successfully!']);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to add session!']);
            }
        }


        public function session_detail($id)
        {
            $user_id = Auth::id();
            $user = User::find($user_id);
            $branch_id= $user->branch_id;
            $session = SessionList::findOrFail($id);
            $accounts = Account::where('branch_id',$branch_id)->get();

            $offer_name= "";
            $mini_name = "";
            if (!empty($session->offer_id)) {
                $offer_name = Offer::where('id', $session->offer_id)->value('offer_name');
            }

            if (!empty($session->ministry_id)) {
                $mini_name = GovtDept::where('id', $session->ministry_id)->value('govt_name');
            }

            return view('appointments.session_detail', [
                'patient_name' => Patient::find($session->patient_id)->full_name ?? 'Unknown',
                'doctor_name'  => Doctor::find($session->doctor_id)->doctor_name ?? 'Unknown',
                'sessions'     => $session->no_of_sessions,
                'gap'          => $session->session_gap,
                'session'=>$session,
                'offer_name'   => $offer_name,
                'mini_name'    => $mini_name,
                'accounts'=>$accounts
            ]);
        }



        public function add_session_detail(Request $request)
        {

            try {
                $user_id = Auth::id();
                $user = User::find($user_id);

                if (!$user) {
                    return response()->json(['message' => 'User not found'], 404);
                }

                // Check if the session is under "ministry" and update payment status
                if ($request->session_type == 'ministry') {
                    $appoint = SessionList::find($request->session_id);
                    if ($appoint) {
                        $appoint->payment_status = 3;
                        $appoint->save();
                    } else {
                        return response()->json(['message' => 'Appointment not found'], 404);
                    }
                }

                // Calculate price per session
                $no_of_sessions = $request->no_of_sessions ?? 0;
                $session_fee = $request->session_fee ?? 0;

                $single_session_price = ($no_of_sessions > 0)
                    ? $session_fee / $no_of_sessions
                    : 0;

                $appointment = new SessionDetail();
                $appointment->session_id = $request->session_id;
                $appointment->session_type = $request->session_type;
                $appointment->ministry_id = $request->mini_id;
                $appointment->offer_id = $request->offer_id;
                $appointment->patient_id = $request->patient_id;
                $appointment->doctor_id = $request->doctor_id;
                $appointment->total_fee = $request->session_fee;
                $appointment->total_sessions = $request->no_of_sessions;
                $appointment->single_session_price = $single_session_price;
                $appointment->session_data = $request->sessions; // Save as JSON
                $appointment->user_id = $user->id;
                $appointment->added_by = $user->id;
                $appointment->branch_id = $user->branch_id;
                $appointment->save();


                $sessions = json_decode($request->sessions, true); // Convert JSON string to array

                if (!empty($sessions)) {
                    foreach ($sessions as $session) {
                        $sessiondetail = new AllSessioDetail();
                        $sessiondetail->session_id =  $appointment->session_id;
                        $sessiondetail->patient_id =   $appointment->patient_id;
                        $sessiondetail->doctor_id =   $appointment->doctor_id;
                        $sessiondetail->session_date = $session['date'];
                        $sessiondetail->session_time = $session['time'];
                        $sessiondetail->session_price = $single_session_price;
                        $sessiondetail->status = '1';
                        $sessiondetail->save();
                    }
                }


                return response()->json([
                    'success' => true,
                    'message' => trans('messages.appointment_add_success_lang'),
                    'session_id' => $appointment->id,
                    'session_type' => $request->session_type
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => trans('messages.appointment_add_failed_lang'),
                    'error' => $e->getMessage(),
                ], 500);
            }
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
        ->orWhere('HN', 'LIKE', "%{$query}%")
        ->orWhere('mobile', 'LIKE', "%{$query}%")
        ->limit(10)
        ->get();

    return response()->json($patients);
}


public function save_session_payment2(Request $request)
{
    $user_id = Auth::id();
    $user = User::find($user_id);
    $user_name = $user->user_name;

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }

    $totalPaid = 0;
    // dd($request->session_id);

    $appointment = SessionList::find($request->session_id);

    if ($appointment) {
        $appointment->session_status = 3;
        $appointment->save();
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Session not found'
        ], 404);
    }
    $voucher_id= "";
    $voucher_code= "";
    $voucher_discount= 0;
    $voucher_user_id= "";
    $voucher_added= "";
    if(!empty($request->voucher_code))
    {
        $voucher_data = Voucher::where('code',$request->voucher_code)->first();
        $voucher_id= $voucher_data->id;
        $voucher_code= $request->voucher_code;
        $voucher_discount= $request->voucher_amount;
        $voucher_user_id= $user_id;
        $voucher_added= $user_name;
    }


    if (is_array($request->payment_methods) && !empty($request->payment_methods)) {
        foreach ($request->payment_methods as $paymentData) {
            $paymentMethodId = $paymentData['account_id'];
            $paidAmount = $paymentData['amount'];
            $refNo = $paymentData['ref_no'];

            if ($paidAmount > 0) {
                $payment = new SessionsonlyPayment();
                $payment->session_id = $request->session_id;
                $payment->payment_status = $request->payment_status;
                $payment->account_id = $paymentMethodId;
                $payment->ref_no = $refNo;
                $payment->amount = $paidAmount;
                $payment->voucher_code = $voucher_code;
                $payment->voucher_amount = $voucher_discount;
                $payment->voucher_added = $voucher_added;
                $payment->voucher_user_id = $voucher_user_id;
                $payment->voucher_id = $voucher_id;
                $payment->user_id = $user_id;
                $payment->branch_id = $user->branch_id;
                $payment->added_by = $user_name;
                $payment->save();

                $appointii= SessionList::where('id', $request->session_id)->first();
                $appointii->session_status= 3;
                $appointii->save();
                $account = Account::find($paymentMethodId);
                if ($account) {
                    $account->opening_balance += $paidAmount;
                    $account->save();

                    if ($account->account_status != 1 && !empty($account->commission) && $account->commission > 0) {
                        $commissionFee = ($paidAmount / 100) * $account->commission;

                        $paymentExpense = new SessionsonlyPaymentExp();
                        $paymentExpense->total_amount = $paidAmount;
                        $paymentExpense->account_tax = $account->commission;
                        $paymentExpense->account_tax_fee = $commissionFee;
                        $paymentExpense->account_id = $paymentMethodId;
                        $paymentExpense->session_id = $request->session_id;
                        $paymentExpense->user_id = $user_id;
                        $paymentExpense->branch_id = $user->branch_id;
                        $paymentExpense->added_by = $user->id;
                        $paymentExpense->save();
                    }
                }

                $totalPaid += $paidAmount;
            }
        }
    }
    $voucher_data->status = 2;
    $voucher_data->save();
    // else {

    //     $payment = new SessionsonlyPayment();
    //     $payment->session_id = $request->session_id;
    //     $payment->account_id = null; // No account since no payment method
    //     $payment->payment_status = $request->payment_status;
    //     $payment->amount = $request->input('totalAmount'); // No amount since no payment made
    //     $payment->user_id = $user_id;
    //     $payment->branch_id = $user->branch_id;
    //     $payment->added_by = $user->id;
    //     $payment->save();
    // }

    return response()->json([
        'success' => true,
        'message' => 'Payment saved successfully',
        'total_paid' => $totalPaid,
    ]);
}

public function all_sessions(){
return view('appointments.all_sessions');
}


public function show_sessions()
{
    $sno = 0;
    $appointments = SessionList::all();

    if ($appointments->count() > 0) {
        foreach ($appointments as $appointment) {
            $total_sessions = (int) SessionDetail::where('session_id', $appointment->id)->value('total_sessions');

            $modal = '
            <a href="javascript:void(0);" class="me-3" >
                <i class="fa fa-pencil fs-18 text-success"></i>
            </a>
            <a href="javascript:void(0);" onclick=del("'.$appointment->id.'")>
                <i class="fa fa-trash fs-18 text-danger"></i>
            </a>';



            $appointment_date_time = $appointment->appointment_date . ' <br> (' . Carbon::parse($appointment->time_from)->format('h:i A') . ' - ' . Carbon::parse($appointment->time_to)->format('h:i A') . ')';
            $added_date = Carbon::parse($appointment->created_at)->format('d-m-Y (h:i a)');
            $doctor_name = Doctor::where('id', $appointment->doctor_id)->value('doctor_name');
            $patient_name = Patient::where('id', $appointment->patient_id)->value('full_name');
            $country_name = Country::where('id', $appointment->country_id)->value('name');
            $session = SessionsonlyPayment::where('session_id', $appointment->id)->first();
            $session_payment = '';

            if ($session) {
                if ($session->payment_status == 3) {
                    $session_payment = '<span class="badge bg-secondary bg-sm text-center">' . $session->amount . ' OMR (Pending)</span>'; // Green
                } elseif ($session->payment_status == 2) {
                    $session_payment = '<span class="badge bg-warning bg-sm text-center text-dark">' . $session->amount . ' OMR (Offer)</span>'; // Yellow
                } else {
                    $session_payment = '<span class="badge bg-danger bg-sm text-center">' . $session->amount . ' OMR (Normal)</span>'; // Red
                }
            } else {
                 $session_payment = '<span class="badge bg-info bg-sm text-center">Referenerce</span>'; // Red

            }

            $remaining_sessions= AppointmentSession::where('status', 1)->where('appointment_id', $appointment->id)->count();


            $sno++;
            $json[] = array(
                '<span class="patient-info ps-0">' . $appointment->session_no . '</span>',
                '<span class="text-nowrap ms-2">' . $patient_name . '</span>',
                // '<span class="text-primary">' . $doctor_name . '</span>',
                '<span class="badge bg-success bg-sm text-center">
                    <i class="fas fa-list-alt me-1"></i>' . $appointment->no_of_sessions . '
                </span>',
                '<span class="badge bg-success bg-sm text-center"> <i class="fas fa-clock me-1"></i>' . $remaining_sessions . '</span>',

                '<span class="d-block ">' . $session_payment . '</span>',
                '<span>' . $appointment->added_by . '</span>',
                '<span>' . $added_date . '</span>',


            );
        }

        $response = array();
        $response['success'] = true;
        $response['aaData'] = $json;
        echo json_encode($response);
    } else {
        $response = array();
        $response['sEcho'] = 0;
        $response['iTotalRecords'] = 0;
        $response['iTotalDisplayRecords'] = 0;
        $response['aaData'] = [];
        echo json_encode($response);
    }
}

    public function check_voucher(Request $request)
    {
        $voucher_data = Voucher::where('code',$request->code)->first();
        $discount_price =0;
        if (!$voucher_data) {
            $msg = 3;
        }
        $session_payment = SessionsonlyPayment::where('voucher_id',$voucher_data->id)->first();
        if ($voucher_data->status == 2) {
            $msg = 2;
        }
        if ($voucher_data) {
            $discount_price = $voucher_data->amount;
            $msg=1;
        }
         return response()->json([
            'success' => $msg,
            'discount_price' => $discount_price, 
        ]);
    }

}
