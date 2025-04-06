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
use App\Models\Setting;
use App\Models\GovtDept;
use App\Models\Appointment;
use App\Models\Ministrycat;
use App\Models\SessionList;
use Illuminate\Http\Request;
use App\Models\SessionsPayment;
use App\Models\AppointmentDetail;
use App\Models\AppointmentPayment;
use App\Models\AppointmentSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\AppointPaymentExpense;
use App\Models\SessionPaymentExpense;

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
        $ministries= GovtDept::all();
        $offers= Offer::all();
        $sessions= Sation::where('session_type', 'normal')->get();
        $countries = Country::all();
        $setting= Setting::first();
        $accounts = Account::where('branch_id',$branch_id)->get();
    return view ('appointments.all_appointments', compact('doctors', 'sessions', 'branches', 'countries','setting', 'offers', 'ministries', 'accounts'));
   }




public function show_appointment()
{
    $sno = 0;
    $appointments = Appointment::all();

    if ($appointments->count() > 0) {
        foreach ($appointments as $appointment) {
            $total_sessions = (int) AppointmentDetail::where('appointment_id', $appointment->id)->value('total_sessions');
            $statusClass = '';
            $statusText = '';
            $statusIcon = '';
            $modal2 = '';
            $modal = '';

            if ($appointment->session_status == 1) {
                $statusClass = 'badge-danger';
                $statusText = 'Sessions Recommended';
                $statusIcon = '<i class="fa fa-exclamation-circle"></i> ';
                $modal2 = '<span class="badge ' . $statusClass . ' px-2 py-1" onclick="session(' . $appointment->id . ')" style="cursor: pointer;">' . $statusIcon . $statusText . '</span>';
            } elseif ($appointment->session_status == 2) {
                $statusClass = 'badge-warning';
                $statusText = 'Appointment';
                $statusIcon = '<i class="fa fa-calendar"></i> ';
                $modal2 = '<span class="badge ' . $statusClass . ' px-2 py-1">' . $statusIcon . $statusText . '</span>';
                $modal = '<a href="edit_appointment/' . $appointment->id . '" class="me-3"><i class="fa fa-pencil fs-18 text-success"></i></a><a href="javascript:void(0);" onclick=cancel("' . $appointment->id . '")><i class="fa fa-ban fs-18 text-danger"></i></a>';
            } elseif ($appointment->session_status == 3) {
                $statusText = 'Sessions: ';
                $statusIcon = '<i class="fa fa-list"></i> ';
                $modal2 = '<span class="badge badge-primary px-2 py-1">' . $statusIcon . $statusText . $total_sessions . '</span>';
            } elseif ($appointment->session_status == 4) {
                $statusClass = 'badge-dark';
                $statusText = 'Cancelled';
                $statusIcon = '<i class="fa fa-times-circle"></i> ';
                $modal2 = '<span class="badge ' . $statusClass . ' px-2 py-1">' . $statusIcon . $statusText . '</span>';
            } elseif ($appointment->session_status == 5) {
                $statusClass = 'badge-info';
                $statusText = 'Pre-Registered';
                $statusIcon = '<i class="fa fa-user-plus"></i> ';
                $modal2 = '<span class="badge ' . $statusClass . ' px-2 py-1">' . $statusIcon . $statusText . '</span>';
                $modal = '<a href="edit_appointment/' . $appointment->id . '" class="me-3"><i class="fa fa-pencil fs-18 text-success"></i></a><a href="javascript:void(0);" onclick=cancel("' . $appointment->id . '")><i class="fa fa-ban fs-18 text-danger"></i></a>';
            } else {
                $modal = '<a href="edit_appointment/' . $appointment->id . '" class="me-3"><i class="fa fa-pencil fs-18 text-success"></i></a>';
            }

            $appointment_date_time = $appointment->appointment_date . ' <br> (' . Carbon::parse($appointment->time_from)->format('h:i A') . ' - ' . Carbon::parse($appointment->time_to)->format('h:i A') . ')';
            $added_date = Carbon::parse($appointment->created_at)->format('d-m-Y (h:i a)');
            $doctor_name = Doctor::where('id', $appointment->doctor_id)->value('doctor_name');
            $patient_name = Patient::where('id', $appointment->patient_id)->value('full_name');
            $country_name = Country::where('id', $appointment->country_id)->value('name');
            $session = SessionsPayment::where('appointment_id', $appointment->id)->first();
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
                 $session_payment = '<span class="badge bg-info bg-sm text-center">Appointment Only</span>'; // Red

            }


            $sno++;
            $json[] = array(
                '<span class="patient-info ps-0">' . $appointment->appointment_no . '</span>',
                '<span class="text-nowrap ms-2">' . $patient_name . '</span>',
                '<span class="text-primary">' . $doctor_name . '</span>',
                $modal2,
                '<span class="badge bg-success bg-sm text-center">' . $appointment->appointment_fee . ' OMR</span>',

                '<span class="d-block ">' . $session_payment . '</span>',
                '<span>' . $appointment_date_time . '</span>',
                '<span>' . $appointment->added_by . '</span>',
                '<span>' . $added_date . '</span>',
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
            $patient->title = $request->title;
            $patient->first_name = $request->first_name;
            $patient->second_name = $request->second_name;
            $patient->full_name = $full_name;
            $patient->mobile = $request->mobile;
            $patient->country_id = $request->country;
            $patient->id_passport = $request->id_passport;
            $patient->dob = $request->dob;
            $patient->age = $request->age;
            $patient->gender = $request->gender;
            $patient->branch_id = $branch_id;
            $patient->added_by = $user;
            $patient->user_id = $user_id;
            $patient->HN = $clinicNumber;
            $patient->save();

        }

        $month = date('n');
        $year = date('y');
        $clinicPrefix = "{$month}{$year}A-";

        $lastAppointment = Appointment::where('appointment_no', 'like', "{$clinicPrefix}%")
            ->orderBy('appointment_no', 'desc')
            ->first();

        if ($lastAppointment) {
            $lastSequence = (int) substr($lastAppointment->appointment_no, strrpos($lastAppointment->appointment_no, '-') + 1);
            $newSequence = str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newSequence = '001';
        }

        $clinicNo = "{$clinicPrefix}{$newSequence}";


        $clinicNo = "{$clinicPrefix}{$newSequence}";

        $check = Appointment::where('clinic_no', $patient->HN)
        ->whereDate('appointment_date', $request->appointment_date) // Ensure the appointment is for the specified date
        ->exists(); // Check if any matching records exist

    if ($check) {
        return response()->json([
            'error' => trans('messages.appointment_already_booked_for_today_lang'),
            'status' => 7
        ]);
    }




        $appointment = new Appointment();
        $appointment->patient_id = $patient->id; // Link patient
        $appointment->clinic_no = $patient->HN; // Link patient
        $appointment->appointment_no =  $clinicNo;
        $appointment->doctor_id = $request->doctor;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->appointment_fee = $setting->appointment_fee;
        $appointment->time_from = $request->time_from;
        $appointment->time_to = $request->time_to;
        $appointment->notes = $request->notes;
        if ($appointment->appointment_date > now()->toDateString()) {
            $appointment->session_status = 5; // Set to "Pre-Registered"
        } else {
            $appointment->session_status = 2; // Set to "Appointment"
        }
        $appointment->payment_status = 0;
        $appointment->added_by = $user;
        $appointment->user_id = $user_id;
        $appointment->branch_id = $branch_id;
        $appointment->save();

        $totalPaid = 0;
        $remainingBalance = $appointment->appointment_fee;

        if ($request->has('payment_methods') && $request->has('payment_amounts')) {
            foreach ($request->payment_methods as $paymentMethodId) {
                $paidAmount = $request->payment_amounts[$paymentMethodId] ?? 0;
                $refNo = $request->payment_ref_nos[$paymentMethodId] ?? null; // Get ref_no if exists


                if ($paidAmount > 0) {
                    $payment = new AppointmentPayment();
                    $payment->appointment_id = $appointment->id;
                    $payment->account_id = $paymentMethodId;
                    $payment->ref_no = $refNo;

                    $payment->amount = $paidAmount;
                    $payment->user_id = $user_id;
                    $payment->branch_id = $branch_id;
                    $payment->added_by = $user;
                    $payment->save();

                    $account = Account::find($paymentMethodId);
                    if ($account) {
                        $account->opening_balance += $paidAmount;
                        $account->save();

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

public function edit_appointment($id)
{

    $appointment = Appointment::findOrFail($id);
    $patient= Patient::where('id', $appointment->patient_id)->first();
    $countries = Country::all();
    $doctors = Doctor::all();
    $setting= Setting::first();

    return view('appointments.edit_appointment', compact('appointment', 'countries', 'setting', 'doctors', 'patient'));
}

public function update_appointment(Request $request)
{
    $user_id = Auth::id();
    $user = Auth::user();
    $branch_id = $user->branch_id;
    $appointment_id = $request->input('appointment_id');

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

    $appointment = Appointment::where('id', $appointment_id)->first();
    if (!$appointment) {
        return response()->json(['error' => trans('messages.appointment_not_found')], 404);
    }

    $patient = Patient::where('id', $appointment->patient_id)->first();
    if (!$patient) {
        return response()->json(['error' => trans('messages.patient_not_found')], 404);
    }

    // Save previous data before update (for history tracking)
    $previousPatientData = $patient->only([
        'title', 'first_name', 'second_name', 'full_name', 'mobile', 'HN', 'country_id', 'id_passport', 'dob', 'branch_id', 'updated_by'
    ]);

    $previousAppointmentData = $appointment->only([
        'doctor_id', 'appointment_date', 'time_from', 'time_to', 'notes', 'updated_by'
    ]);

    // Update patient details
    $patient->title = $request->title;
    $patient->first_name = $request->first_name;
    $patient->second_name = $request->second_name;
    $patient->full_name = $full_name;
    $patient->mobile = $request->mobile;
    $patient->country_id = $request->country;
    $patient->id_passport = $request->id_passport;
    $patient->dob = $request->dob;
    $patient->branch_id = $branch_id;
    $patient->updated_by = $user->user_name;
    $patient->save();

    // Update appointment details
    $appointment->doctor_id = $request->doctor;
    $appointment->appointment_date = $request->appointment_date;
    $appointment->time_from = $request->time_from;
    $appointment->time_to = $request->time_to;
    if ($appointment->appointment_date > now()->toDateString()) {
        $appointment->session_status = 5; // Set to "Pre-Registered"
    } else {
        $appointment->session_status = 2; // Set to "Appointment"
    }
    $appointment->notes = $request->notes;
    $appointment->updated_by = $user->user_name;
    $appointment->save();

    // Save update history for Patient
    $patientHistory = new History();
    $patientHistory->user_id = $user_id;
    $patientHistory->table_name = 'patients';
    $patientHistory->function = 'update';
    $patientHistory->function_status = 1;
    $patientHistory->record_id = $patient->id;
    $patientHistory->branch_id = $branch_id;

    $patientHistory->previous_data = json_encode($previousPatientData);
    $patientHistory->updated_data = json_encode($patient->only([
        'title', 'first_name', 'second_name', 'full_name', 'mobile', 'country_id', 'id_passport', 'dob', 'branch_id', 'updated_by'
    ]));
    $patientHistory->added_by = $user->user_name;
    $patientHistory->save();

    // Save update history for Appointment
    $appointmentHistory = new History();
    $appointmentHistory->user_id = $user_id;
    $appointmentHistory->table_name = 'appointments';
    $appointmentHistory->function = 'update';
    $appointmentHistory->function_status = 1;
    $appointmentHistory->record_id = $appointment->id;
    $appointmentHistory->branch_id = $branch_id;

    $appointmentHistory->previous_data = json_encode($previousAppointmentData);
    $appointmentHistory->updated_data = json_encode($appointment->only([
        'doctor_id', 'appointment_date', 'time_from', 'time_to', 'notes', 'updated_by'
    ]));
    $appointmentHistory->added_by = $user->user_name;
    $appointmentHistory->save();

    return response()->json([
        'success' => trans('messages.appointment_update_success_lang'),
    ]);
}

public function cancel_appointment(Request $request)
{
    $user_id = Auth::id();
    $user = User::where('id', $user_id)->first();

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    $appointment = Appointment::find($request->id);

    if (!$appointment) {
        return response()->json(['error' => 'Appointment not found'], 404);
    }

    // Store previous data before update
    $previousData = $appointment->only(['session_status', 'appointment_date', 'time_from', 'time_to', 'doctor_id', 'patient_id', 'branch_id']);

    // Update appointment status to "Cancelled"
    $appointment->session_status = 4;
    $appointment->save();

    // Save cancel history
    $history = new History();
    $history->user_id = $user_id;
    $history->table_name = 'appointments';
    $history->function = 'cancel';
    $history->function_status = 2;
    $history->branch_id = $user->branch_id;
    $history->record_id = $appointment->id;
    $history->previous_data = json_encode($previousData);
    $history->added_by = $user->user_name;
    $history->save();

    return response()->json([
        'success' => true,
        'message' => 'Appointment has been cancelled successfully.'
    ]);
}

   public function getSessionData($appointment_id)
{
    $appointment = Appointment::find($appointment_id);

    if (!$appointment) {
        return response()->json(['error' => 'Appointment not found'], 404);
    }

    $doctor = Doctor::find($appointment->doctor_id);
    $patient= Patient::find($appointment->patient_id);

    $gap = 2; // Default gap of 2 days (can be modified as per requirement)
    $sessions = 10; // Default session count (can be changed dynamically)

    return response()->json([
        'patient_id' => $patient->id,
        'patient_name' => $patient->full_name,
        'payment_status' => $appointment->payment_status,
        'doctor_id' => $doctor ? $doctor->id : null,
        'doctor_name' => $doctor ? $doctor->doctor_name : 'Unknown',
        'appointment_id' => $appointment->id,
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


public function getMinistryDetails($id)
    {
        $ministry = GovtDept::find($id);
        $sation= Sation::where('government_id', $ministry->id)->first();
        $session_price= $sation->session_price;
        $ministry_cat= Ministrycat::where('id', $sation->ministry_cat_id)->value('ministry_category_name');


        if ($ministry) {
            return response()->json([
                'success' => true,
                'price' => $session_price, // Assuming 'price' column exists
                'category' => $ministry_cat // Assuming 'category' column exists
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Ministry not found!'], 404);
        }
    }

    public function getsessionDetails($id)
    {
        $sation = Sation::find($id);
        $session_price= $sation->session_price;


        if ($sation) {
            return response()->json([
                'success' => true,
                'price' => $session_price, // Assuming 'price' column exists
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Ministry not found!'], 404);
        }
    }

    // Fetch Offer Details
    public function getOfferDetails($id)
    {
        $offer = Offer::find($id);

        if ($offer) {
            return response()->json([
                'success' => true,
                'price' => $offer->offer_price, // Assuming 'price' column exists
                'sessions' => $offer->sessions // Assuming 'price' column exists

            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Offer not found!'], 404);
        }
    }


    public function save_sessions(Request $request)
    {

        try {
            $user_id = Auth::id();
            $user = User::find($user_id);


            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            if ($request->session_type == 'ministry') {
                $appoint = Appointment::where('id', $request->appointment_id)->first();

                if ($appoint) {
                    $appoint->payment_status = 3;
                    $appoint->save();
                } else {
                    dd('Appointment not found');
                }
            }


            $single_session_price = ($request->total_sessions > 0)
            ? $request->total_price / $request->total_sessions
            : 0;

            $appointment = new AppointmentDetail();
            $appointment->appointment_id = $request->appointment_id;
            $appointment->session_type = $request->session_type;
            $appointment->ministry_id = $request->ministry_id;
            $appointment->offer_id = $request->offer_id;
            $appointment->patient_id = $request->patient_id;
            $appointment->doctor_id = $request->doctor_id;
            $appointment->total_price = $request->total_price;
            $appointment->total_sessions = $request->total_sessions;
            $appointment->single_session_price = $single_session_price;
            $appointment->session_data = json_encode($request->sessions);
            $appointment->user_id = $user->id;
            $appointment->added_by = $user->id;
            $appointment->branch_id = $user->branch_id;
            $appointment->save();
            if (!empty($request->sessions)) {
                foreach ($request->sessions as $session) {
                    $sessiondetail = new AppointmentSession();
                    $sessiondetail->appointment_id = $appointment->id;
                    $sessiondetail->patient_id =   $appointment->patient_id;
                    $sessiondetail->doctor_id =   $appointment->doctor_id;
                    $sessiondetail->session_date = $session['session_date']; // Assuming `session_date` is provided
                    $sessiondetail->session_time = $session['session_time']; // Assuming `session_time` is provided
                    $sessiondetail->session_price = $single_session_price;
                    $sessiondetail->status = '1'; // Default status as per migration (1 = Pending)
                    $sessiondetail->save();
                }
            }

            return response()->json([
                'success' => trans('messages.appointment_add_success_lang'),
                'data' => $appointment,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => trans('messages.appointment_add_failed_lang'),
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function save_session_payment(Request $request)
    {
        $user_id = Auth::id();
        $user = User::find($user_id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $totalPaid = 0;

        $appointment = Appointment::where('id', $request->appointment_id2)->first();
        $appointment->session_status= 3;
        $appointment->save();


        if (is_array($request->payment_methods) && !empty($request->payment_methods)) {
            foreach ($request->payment_methods as $paymentData) {
                $paymentMethodId = $paymentData['account_id'];
                $paidAmount = $paymentData['amount'];
                $refNo = $paymentData['ref_no'];

                if ($paidAmount > 0) {
                    $payment = new SessionsPayment();
                    $payment->appointment_id = $request->appointment_id2;
                    $payment->payment_status = $request->payment_status;
                    $payment->account_id = $paymentMethodId;
                    $payment->ref_no = $refNo;
                    $payment->amount = $paidAmount;
                    $payment->user_id = $user_id;
                    $payment->branch_id = $user->branch_id;
                    $payment->added_by = $user->id;
                    $payment->save();

                    $appointii= Appointment::where('id', $request->appointment_id2)->first();
                    $appointii->session_status= 3;
                    $appointii->save();
                    $account = Account::find($paymentMethodId);
                    if ($account) {
                        $account->opening_balance += $paidAmount;
                        $account->save();

                        if ($account->account_status != 1 && !empty($account->commission) && $account->commission > 0) {
                            $commissionFee = ($paidAmount / 100) * $account->commission;

                            $paymentExpense = new SessionPaymentExpense();
                            $paymentExpense->total_amount = $paidAmount;
                            $paymentExpense->account_tax = $account->commission;
                            $paymentExpense->account_tax_fee = $commissionFee;
                            $paymentExpense->account_id = $paymentMethodId;
                            $paymentExpense->appointment_id = $request->appointment_id2;
                            $paymentExpense->user_id = $user_id;
                            $paymentExpense->branch_id = $user->branch_id;
                            $paymentExpense->added_by = $user->id;
                            $paymentExpense->save();
                        }
                    }

                    $totalPaid += $paidAmount;
                }
            }
        } else {

            $payment = new SessionsPayment();
            $payment->appointment_id = $request->appointment_id2;
            $payment->account_id = null; // No account since no payment method
            $payment->payment_status = $request->payment_status;
            $payment->amount = $request->input('totalAmount'); // No amount since no payment made
            $payment->user_id = $user_id;
            $payment->branch_id = $user->branch_id;
            $payment->added_by = $user->id;
            $payment->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment saved successfully',
            'total_paid' => $totalPaid,
        ]);
    }





public function searchPatient(Request $request)
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


}
