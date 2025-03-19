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
use App\Models\Patient;
use App\Models\Setting;
use App\Models\GovtDept;
use App\Models\Appointment;
use App\Models\SessionList;
use Illuminate\Http\Request;
use App\Models\AppointmentPayment;
use Illuminate\Support\Facades\Auth;
use App\Models\AppointPaymentExpense;

class AppointmentController extends Controller
{
    public function appointments(){

        $user_id = Auth::id();
        $data = User::where('id', $user_id)->first();
        $user = $data->user_name;
        $branch_id = $data->branch_id;
        $doctors = Doctor::all();
        $branches = Branch::all();

        $countries = Country::all();
        $setting= Setting::first();
        $accounts = Account::where('branch_id',$branch_id)->get();
        return view('appointments.appointments', compact('doctors', 'branches', 'countries','setting', 'accounts'));
    }

   public function all_appointments(){

    $user_id = Auth::id();
        $data = User::where('id', $user_id)->first();
        $user = $data->user_name;
        $branch_id = $data->branch_id;
        $doctors = Doctor::all();
        $branches = Branch::all();

        $countries = Country::all();
        $setting= Setting::first();
        $accounts = Account::where('branch_id',$branch_id)->get();
    return view ('appointments.all_appointments', compact('doctors', 'branches', 'countries','setting', 'accounts'));
   }

   public function show_appointment()
{
    $sno = 0;
    $appointments = Appointment::all(); // Fetch all appointments

    if ($appointments->count() > 0) {
        foreach ($appointments as $appointment) {



        $statusClass = 'badge-info'; // Always green
        $statusText = 'Sessions Recomended'; // Dummy status text

        $modal2 = '
        <span class="badge ' . $statusClass . ' px-2 py-1" onclick="session(' . $appointment->id . ')" style="cursor: pointer;">
            ' . $statusText . '
        </span>';




            $modal = '
            <a href="javascript:void(0);" class="me-3 edit-appointment" onclick=session("' . $appointment->id . '")>
                <i class="fa fa-pencil fs-18 text-success"></i>
            </a>
            <a href="javascript:void(0);" onclick=del("' . $appointment->id . '")>
                <i class="fa fa-trash fs-18 text-danger"></i>
            </a>';

            $appointment_date_time = $appointment->appointment_date .
            ' <br> (' .
            Carbon::parse($appointment->time_from)->format('h:i A') .
            ' - ' .
            Carbon::parse($appointment->time_to)->format('h:i A') .
            ')';
            // Formatting created_at date
            $added_date = Carbon::parse($appointment->created_at)->format('d-m-Y (h:i a)');

            // Fetch doctor name
            $doctor_name = Doctor::where('id', $appointment->doctor_id)->value('doctor_name');
            $patient_name = Patient::where('id', $appointment->patient_id)->value('full_name');


            // Fetch country name
            $country_name = Country::where('id', $appointment->country_id)->value('name');

            $sno++;
            $json[] = array(
                '<span class="patient-info ps-0">' . $sno . '</span>',
                '<span class="text-nowrap ms-2">' . $patient_name . '</span>',
                '<span class="text-primary">' . $doctor_name . '</span>',
                 $modal2,
                '<span class="badge bg-success bg-sm text-center">' . $appointment->appointment_fee . ' OMR</span>',
                '<span>' . $appointment_date_time . '</span>', // Shows date & time in one field
                '<span >' . $appointment->added_by . '</span>',
                '<span >' . $added_date . '</span>',
                $modal
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



public function add_appointment(Request $request)
{

    // dd($request->all());
    $user_id = Auth::id();
    $data = User::where('id', $user_id)->first();
    $user = $data->user_name;
    $branch_id = $data->branch_id;
    $setting = Setting::first();

    // Determine title
    $title = '';
    if ($request->title == 1) {
        $title = 'Miss';
    } elseif ($request->title == 2) {
        $title = 'Mr.';
    } elseif ($request->title == 3) {
        $title = 'Mrs.';
    }

    $full_name = trim($title . ' ' . $request->first_name . ' ' . $request->second_name);

    try {
        // Check if patient exists
        $patient = Patient::where('mobile', $request->mobile)->first();

        if (!$patient) {
            // Create New Patient
            $lastClinicNumber = Patient::max('clinic_no'); // Get last saved number
            $nextNumber = $lastClinicNumber ? intval(explode('-', $lastClinicNumber)[1]) + 1 : 1;
            $clinicNumber = sprintf('00-%d', $nextNumber);

            // Save Patient
            $patient = new Patient();
            $patient->title = $request->title;
            $patient->first_name = $request->first_name;
            $patient->second_name = $request->second_name;
            $patient->full_name = $full_name;
            $patient->mobile = $request->mobile;
            $patient->id_passport = $request->id_passport;
            $patient->dob = $request->dob;
            $patient->branch_id = $branch_id;
            $patient->added_by = $user;
            $patient->user_id = $user_id;
            $patient->clinic_no = $clinicNumber;
            $patient->save();

        }

        // Create Appointment & Link to Patient
        $appointment = new Appointment();
        $appointment->patient_id = $patient->id; // Link patient
        $appointment->clinic_no = $patient->clinic_no; // Link patient
        $appointment->doctor_id = $request->doctor;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->appointment_fee = $setting->appointment_fee;
        $appointment->time_from = $request->time_from;
        $appointment->time_to = $request->time_to;
        $appointment->notes = $request->notes;
        $appointment->added_by = $user;
        $appointment->user_id = $user_id;
        $appointment->branch_id = $branch_id;
        $appointment->save();

        $totalPaid = 0;
        $remainingBalance = $appointment->appointment_fee;

        if ($request->has('payment_methods') && $request->has('payment_amounts')) {
            foreach ($request->payment_methods as $paymentMethodId) {
                $paidAmount = $request->payment_amounts[$paymentMethodId] ?? 0;

                if ($paidAmount > 0) {
                    // Save payment in `appointment_payment`
                    $payment = new AppointmentPayment();
                    $payment->appointment_id = $appointment->id;
                    $payment->account_id = $paymentMethodId;
                    $payment->amount = $paidAmount;
                    $payment->user_id = $user_id;
                    $payment->branch_id = $branch_id;
                    $payment->added_by = $user;
                    $payment->save();

                    // Update account balance
                    $account = Account::find($paymentMethodId);
                    if ($account) {
                        $account->opening_balance += $paidAmount;
                        $account->save();

                        // Handle Commission if Account has a fee
                        if ($account->account_status != 1 && !empty($account->commission) && $account->commission > 0) {
                            $commissionFee = ($paidAmount / 100) * $account->commission;

                            $paymentExpense = new AppointPaymentExpense();
                            $paymentExpense->total_amount = $paidAmount;
                            $paymentExpense->account_tax = $account->commission;
                            $paymentExpense->account_tax_fee = $commissionFee;
                            $paymentExpense->account_id = $paymentMethodId;
                            $paymentExpense->appointment_id = $appointment->id;
                            $paymentExpense->user_id = $user_id;
                            $paymentExpense->branch_id = $branch_id;
                            $paymentExpense->added_by = $user;
                            $paymentExpense->save();
                        }
                    }

                    // Update total paid & remaining balance
                    $totalPaid += $paidAmount;
                    $remainingBalance -= $paidAmount;
                }
            }
        }

        return response()->json([
            'success' => trans('messages.appointment_add_success_lang'),
            'total_paid' => $totalPaid,
            'remaining_balance' => max($remainingBalance, 0),
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => trans('messages.appointment_add_failed_lang'),
            'message' => $e->getMessage(),
        ], 500);
    }
}




   public function getSessionData($appointment_id)
{
    $appointment = Appointment::find($appointment_id);

    if (!$appointment) {
        return response()->json(['error' => 'Appointment not found'], 404);
    }

    $doctor = Doctor::find($appointment->doctor_id);

    $gap = 2; // Default gap of 2 days (can be modified as per requirement)
    $sessions = 10; // Default session count (can be changed dynamically)

    return response()->json([
        'patient_name' => $appointment->full_name,
        'doctor_name' => $doctor ? $doctor->doctor_name : 'Unknown',
        'appointment_date' => $appointment->appointment_date,
        'sessions' => $sessions,
        'gap' => $gap
    ]);
}


public function sessions_list(){

    $doctors= Doctor::all();
    $offers= Offer::all();
    $countries= Country::all();
    $ministries= GovtDept::all();
    return view ('appointments.sessions', compact('offers', 'doctors','countries' , 'ministries'));

}


public function getSessionPrice(Request $request)
{
    $sessionType = $request->session_type;
    $noOfSessions = $request->no_of_sessions; // Default to 1
    $ministryId = (int) $request->ministry_id; // Convert to integer
    $offerId = (int) $request->offer_id; // Convert to integer

    // If session type is "offer", get price from the "offers" table
    if ($sessionType == 'offer' && $offerId) {
        $offer = Offer::where('id', $offerId)->first();


        if (!$offer) {
            return response()->json(['success' => false, 'message' => 'Offer not found']);
        }

        $totalPrice = $offer->offer_price * $offer->sessions;
        return response()->json(['success' => true, 'session_price' => $totalPrice, 'offer_sessions'=>$offer->sessions]);
    }

    // Base query for normal & pact sessions
    $query = Sation::where('session_type', $sessionType);

    // If session type is "pact", filter by ministry
    if ($sessionType == 'pact' && $ministryId) {
        $query->where('government_id', $ministryId);
    }

    // Fetch session
    $session = $query->first();

    if (!$session) {
        return response()->json(['success' => false, 'message' => 'Session type not found']);
    }

    // Calculate total price
    $totalPrice = $session->session_price * $noOfSessions;

    return response()->json(['success' => true, 'session_price' => $totalPrice]);
}




}
