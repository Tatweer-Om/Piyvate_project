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
use App\Models\SessionData;
use App\Models\SessionList;
use App\Models\Patientfiles;
use Illuminate\Http\Request;
use App\Models\ClinicalNotes;
use App\Models\SessionDetail;
use App\Models\SessionsPayment;
use App\Models\AppointmentDetail;
use App\Models\AppointmentPayment;
use App\Models\AppointmentSession;
use Illuminate\Support\Facades\DB;
use App\Models\PatientPrescription;
use App\Models\SessionsonlyPayment;
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
        $accounts = Account::where('branch_id',$branch_id)->where('account_type', 1)->get();
        return view('appointments.appointments', compact('doctors', 'branches', 'countries','setting', 'accounts'));
    }

   public function all_appointments(){

    $user_id = Auth::id();
        $data = User::where('id', $user_id)->first();
        $user = $data->user_name;
        $branch_id = $data->branch_id;
        $doctors = Doctor::all();
        $branches = Branch::all();
        $ministries = GovtDept::with('ministrycats')->get();

        $offers= Offer::all();
        $sessions= Sation::where('session_type', 'normal')->get();
        $countries = Country::all();
        $setting= Setting::first();
        $accounts = Account::where('branch_id',$branch_id)->where('account_type', 1)->get();
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
                    $statusText = 'Appointment In Process';
                    $statusIcon = '<i class="fa fa-calendar"></i> ';
                    $modal2 = '<span class="badge ' . $statusClass . ' px-2 py-1">' . $statusIcon . $statusText . '</span>';
                    // $modal = '<a href="edit_appointment/' . $appointment->id . '" class="me-3"><i class="fa fa-pencil fs-18 text-success"></i></a><a href="javascript:void(0);" onclick=cancel("' . $appointment->id . '")><i class="fa fa-ban fs-18 text-danger"></i></a>';
                }
                elseif ($appointment->session_status ==8 ) {
                    $statusClass = 'badge-danger';
                    $statusText = 'Approve Appointment';
                    $statusIcon = '<i class="fa fa-calendar"></i> ';
                    $modal = '<a href="edit_appointment/' . $appointment->id . '" class="me-3"><i class="fa fa-pencil fs-18 text-success"></i></a><a href="javascript:void(0);" onclick=cancel("' . $appointment->id . '")><i class="fa fa-ban fs-18 text-danger"></i></a>';
                    $modal2 = '<span class="badge ' . $statusClass . ' px-2 py-1" onclick="approve_appointment(' . $appointment->id . ')" style="cursor: pointer;">' . $statusIcon . $statusText . '</span>';

                }
                elseif ($appointment->session_status == 3) {
                    $statusText = 'Sessions: ';
                    $statusIcon = '<i class="fa fa-list"></i> ';
                    $modal2 = '<span class="badge badge-primary px-2 py-1">' . $statusIcon . $statusText . $total_sessions . '</span>';
                } elseif ($appointment->session_status == 4) {
                    $statusClass = 'badge-dark';
                    $statusText = 'Cancelled';
                    $statusIcon = '<i class="fa fa-times-circle"></i> ';
                    $modal2 = '<span class="badge ' . $statusClass . ' px-2 py-1">' . $statusIcon . $statusText . '</span>';
                }elseif ($appointment->session_status == 5) {
                    // Check if appointment_date is today
                    if (\Carbon\Carbon::parse($appointment->appointment_date)->isToday()) {
                        $statusClass = 'badge-success';
                        $statusText = 'Approve Appointment';
                        $statusIcon = '<i class="fa fa-check-circle"></i> ';
                        $modal2 = '<span class="badge ' . $statusClass . ' px-2 py-1" onclick="approve_appointment(' . $appointment->id . ')" style="cursor: pointer;">' . $statusIcon . $statusText . '</span>';
                    } else {
                        $statusClass = 'badge-info';
                        $statusText = 'Pre-Registered';
                        $statusIcon = '<i class="fa fa-user-plus"></i> ';
                        $modal2 = '<span class="badge ' . $statusClass . ' px-2 py-1">' . $statusIcon . $statusText . '</span>';
                    }

                    $modal = '<a href="edit_appointment/' . $appointment->id . '" class="me-3"><i class="fa fa-pencil fs-18 text-success"></i></a>
                              <a href="javascript:void(0);" onclick=cancel("' . $appointment->id . '")><i class="fa fa-ban fs-18 text-danger"></i></a>';
                }
                elseif ($appointment->session_status == 7) {
                    $statusClass = 'badge-success';
                    $statusText = 'Appointment Done';
                    $statusIcon = '<i class="fa fa-check-circle"></i> ';
                    $modal2 = '<span class="badge ' . $statusClass . ' px-2 py-1">' . $statusIcon . $statusText . '</span>';
                    $modal = '';  // Remove edit and cancel icons
                }
                else {
                    $modal = '<a href="edit_appointment/' . $appointment->id . '" class="me-3"><i class="fa fa-pencil fs-18 text-success"></i></a>';
                }




                $appointment_date_time = $appointment->appointment_date . ' <br> (' . Carbon::parse($appointment->time_from)->format('h:i A') . ' - ' . Carbon::parse($appointment->time_to)->format('h:i A') . ')';
                $added_date = Carbon::parse($appointment->created_at)->format('d-m-Y (h:i a)');
                $doctor_name = Doctor::where('id', $appointment->doctor_id)->value('doctor_name');
                $patient = Patient::where('id', $appointment->patient_id)->first();
                $patient_name= $patient->full_name;
                $country_name = Country::where('id', $appointment->country_id)->value('name');
                $session = AppointmentDetail::where('appointment_id', $appointment->id)->first();
                $session_payment = '';

                if ($session) {
                    if ($appointment->payment_status == 3) {
                        $session_payment = '<span class="badge bg-secondary bg-sm text-center">' . $session->total_price . ' OMR (Pending)</span>';
                    } elseif ($appointment->payment_status == 2) {
                        $session_payment = '<span class="badge bg-warning bg-sm text-center text-dark">' . $session->total_price . ' OMR (Offer)</span>';
                    } elseif ($appointment->payment_status == 4) {
                        $session_payment = '<span class="badge bg-warning bg-sm text-center text-dark">' . $session->total_price . ' OMR (Contract-Paid)</span>';
                    } else {
                        $session_payment = '<span class="badge bg-danger bg-sm text-center">' . $session->total_price . ' OMR (Normal)</span>';
                    }
                } else {
                    $session_payment = '<span class="badge bg-info bg-sm text-center">Appointment Only</span>';
                }


                $sno++;
                $json[] = array(
                    '<span class="patient-info ps-0"><a href="' . url('patient_appointment/' . $appointment->id) . '">' . $appointment->appointment_no . '</a></span>',

                    '<span class="text-nowrap ms-2"><a href="' . url('patient_profile/' . $patient->id) . '">' . $patient_name . '</span>',
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

        $doctor_id = $request->doctor;
        $appointment_date = $request->appointment_date;
        $time_from = $request->time_from;
        $time_to = $request->time_to;
        // 1. Check for overlapping appointment
        if ($doctor_id) {
            $existingAppointment = Appointment::where('doctor_id', $doctor_id)
                ->where('appointment_date', $appointment_date)
                ->where(function ($query) use ($time_from, $time_to) {
                    $query->where(function ($q) use ($time_from, $time_to) {
                        $q->where('time_from', '<', $time_to)
                        ->where('time_to', '>', $time_from);
                    });
                })
                ->first();

            if ($existingAppointment) {
                return response()->json([
                    'status' => 9,
                    'message' => 'Doctor already has an appointment during this time.',
                ]);
            }
        }

        // 2. Check for session with status = 4 that overlaps
        $existingSession = SessionData::where('doctor_id', $doctor_id)->where('session_date', $appointment_date)
            ->where('status', 4)
            ->whereTime('session_time', '>=', $time_from)
            ->whereTime('session_time', '<=', $time_to)
            ->first();

        if ($existingSession) {
            return response()->json([
                'status' => 10,
                'message' => 'A session with status 4 already exists during this time.',
            ]);
        }



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
        ->whereDate('appointment_date', $request->appointment_date)
        ->where('session_status', 2) // Ensure the appointment is for the specified date
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
            $appointment->appointment_type = $request->appointment_type;
            $appointment->appointment_fee = $setting->appointment_fee;
            $appointment->time_from = $request->time_from;
            $appointment->time_to = $request->time_to;
            $appointment->notes = $request->notes;
            if ($appointment->appointment_date > now()->toDateString()) {
                $appointment->session_status = 5; // Set to "Pre-Registered"
            } else {
                $appointment->session_status = 8; // Set to "Appointment"
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
                        $payment->payment_status = 1;

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
    public function approve_appointment(Request $request)
    {

        $id = $request->input('id');

        $appointment = Appointment::findOrFail($id);

        $appointment->session_status=2;
        $appointment->save();

        return response()->json([
            'success' => trans('messages.appointment_approved_success_lang'),
        ]);
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
        $appointment->appointment_type = $request->appointment_type;

        $appointment->time_from = $request->time_from;
        $appointment->time_to = $request->time_to;
        if ($appointment->appointment_date > now()->toDateString()) {
            $appointment->session_status = 5; // Set to "Pre-Registered"
        } else {
            $appointment->session_status = 8; // Set to "Appointment"
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
        $prescription = PatientPrescription::where('appointment_id', $appointment_id)->first();


        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found'], 404);
        }

        $doctor = Doctor::find($appointment->doctor_id);
        $patient= Patient::find($appointment->patient_id);

        $gap = $prescription->session_gap;
        $sessions = $prescription->sessions_reccomended;


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
        $ministries = GovtDept::with('ministrycats')->get();
        return view ('appointments.sessions', compact('offers', 'doctors','countries' , 'ministries'));

    }


    public function getSessionPrice(Request $request)
    {

        $sessionType = $request->session_type;
        $noOfSessions = $request->no_of_sessions ?? 1;
        $ministryId = (int) $request->ministry_id;
        $offerId = (int) $request->offer_id;

        if ($sessionType == 'offer' && $offerId) {
            $offer = Offer::where('id', $offerId)->first();
            if (!$offer) {
                return response()->json(['success' => false, 'message' => 'Offer not found']);
            }

            $totalPrice = $offer->offer_price;
            return response()->json(['success' => true, 'session_price' => $totalPrice, 'offer_sessions'=>$offer->sessions]);
        }

        $query = Sation::where('session_type', $sessionType);

        if ($sessionType == 'ministry' && $ministryId) {
            $query->where('government_id', $ministryId);
        }

        // Fetch session
        $session = $query->first();

        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Session type not found']);
        }

        // Calculate total price
        $totalPrice = $session->session_price;

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

            $sessions = is_string($request->sessions)
            ? json_decode($request->sessions, true)
            : $request->sessions;


            $appoint = Appointment::find($request->appointment_id);

            if ($appoint) {
                if ($request->session_type == 'ministry') {
                    $appoint->payment_status = 3;
                } else if ($request->session_type == 'offer') {

                    $appoint->payment_status = 2;
                } else if ($request->session_type == 'normal') {
                    $appoint->payment_status = 1;
                } else {
                    dd('Invalid session type');
                }

                $appoint->save();
            } else {
                dd('Appointment not found');
            }


            $appointment = new AppointmentDetail();
            $prescription= PatientPrescription::where('appointment_id', $request->appointment_id)->first();
            $appointment->appointment_id = $request->appointment_id;

            if (count($sessions) !=  $prescription->sessions_reccomended) {

            }

            $appointment->session_type = $request->session_type;
            $appointment->ministry_id = $request->ministry_id;
            $appointment->offer_id = $request->offer_id;

            if(!empty($appointment->offer_id)){
                $offer_check= Offer::where('id', $appointment->offer_id)->first();
                $offer_sessions= $offer_check->sessions;
                $prescription->sessions_reccomended=$offer_sessions;

                $prescription->save();
                if (count($sessions) !=  $prescription->sessions_reccomended) {
                    return response()->json([
                        'status' => 5,
                        'appointment_id' => $appointment->appointment_id,
                    ]);
                }
            }

            $appointment->patient_id = $request->patient_id;
            $appointment->doctor_id = $request->doctor_id;
            $appointment->total_price = $request->total_price;
            $appointment->ot_sessions = $prescription->ot_sessions;
            $appointment->pt_sessions = $prescription->pt_sessions;
            $appointment->session_gap = $prescription->session_gap;
            $appointment->session_cat = $prescription->session_cat;

            $appointment->total_sessions = $prescription->sessions_reccomended;

            $sessionCount = count($sessions);

            if ($sessionCount > $appointment->total_sessions) {
                $diff = $sessionCount - $appointment->total_sessions;
                $prescription->sessions_reccomended = $sessionCount;

                if ($prescription->ot_sessions > 0 && $prescription->pt_sessions > 0) {
                    if ($prescription->ot_sessions < $prescription->pt_sessions) {
                        $prescription->ot_sessions += $diff;
                    } elseif ($prescription->pt_sessions < $prescription->ot_sessions) {
                        $prescription->pt_sessions += $diff;
                    } else {
                        // If both are equal
                        $prescription->ot_sessions += $diff;
                    }
                } elseif ($prescription->ot_sessions > 0) {
                    $prescription->ot_sessions += $diff;
                } elseif ($prescription->pt_sessions > 0) {
                    $prescription->pt_sessions += $diff;
                }

                $prescription->save();

                return response()->json([
                    'status' => 6,
                    'appointment_id' => $appointment->appointment_id,
                ]);
            }

            if ($sessionCount < $appointment->total_sessions) {
                $diff = $appointment->total_sessions - $sessionCount;
                $prescription->sessions_reccomended = $sessionCount;

                if ($prescription->ot_sessions > 0 && $prescription->pt_sessions > 0) {
                    if ($prescription->ot_sessions > $prescription->pt_sessions) {
                        $prescription->ot_sessions -= $diff;
                    } elseif ($prescription->pt_sessions > $prescription->ot_sessions) {
                        $prescription->pt_sessions -= $diff;
                    } else {
                        // If both are equal
                        $prescription->ot_sessions -= $diff;
                    }
                } elseif ($prescription->ot_sessions > 0) {
                    $prescription->ot_sessions -= $diff;
                } elseif ($prescription->pt_sessions > 0) {
                    $prescription->pt_sessions -= $diff;
                }

                $prescription->save();

                return response()->json([
                    'status' => 7,
                    'appointment_id' => $appointment->appointment_id,
                ]);
            }


            $single_session_price = ($appointment->total_sessions > 0)
            ?$appointment->total_price / $appointment->total_sessions
            : 0;
            $appointment->single_session_price = $single_session_price;
            $appointment->session_data = json_encode($sessions);

            $appointment->user_id = $user->id;
            $appointment->contract_payment = 1;
            $appointment->added_by = $user->id;
            $appointment->branch_id = $user->branch_id;



            $appointment->save();


            $ot_sessions_left =  $appointment->ot_sessions ?? 0;
                $pt_sessions_left =  $appointment->pt_sessions ?? 0;
            if (!empty($sessions)) {
                foreach ($sessions as $session) {

                    if ($ot_sessions_left > 0) {
                        $session_cat = 'OT'; // First $ot_sessions_left sessions will be OT
                        $ot_sessions_left--; // Decrease the count of remaining OT sessions
                    } else {
                        $session_cat = 'PT'; // If no OT sessions left, assign PT
                    }

                        $sessiondetail = new AppointmentSession();

                        $sessiondetail->appointment_id = $appointment->appointment_id;
                        $sessiondetail->patient_id = $appointment->patient_id;
                        $sessiondetail->doctor_id = $appointment->doctor_id;
                        $sessiondetail->session_cat = $session_cat;
                        $sessiondetail->contract_payment = 1;
                        $sessiondetail->session_date = $session['session_date'];
                        $sessiondetail->session_time = $session['session_time'];
                        $sessiondetail->session_price = $single_session_price;
                        $sessiondetail->status = '1';
                        $sessiondetail->save();
                        $session_data = new SessionData();
                        $session_data->main_appointment_id = $appointment->appointment_id;
                        $session_data->patient_id = $appointment->patient_id;
                        $session_data->doctor_id = $appointment->doctor_id;
                        $session_data->session_cat = $session_cat;
                        $session_data->contract_payment = 1;
                        $session_data->session_date = $session['session_date'];
                        $session_data->session_time = $session['session_time'];
                        $session_data->session_price = $single_session_price;
                        $session_data->source = 2;
                        $session_data->status = 1;
                        $session_data->user_id = $user_id;


                        $session_data->save();

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


    // public function save_session_payment(Request $request)
    // {

    //     $user_id = Auth::id();
    //     $user = User::find($user_id);

    //     if (!$user) {
    //         return response()->json(['success' => false, 'message' => 'User not found'], 404);
    //     }

    //     $totalPaid = 0;
    //     $appointment = Appointment::where('id', $request->appointment_id2)->first();
    //     $appointment->session_status= 3;
    //     $appointment->save();

    //     $hasValidPayment = false;


    //     if (is_array($request->payment_methods) && !empty($request->payment_methods)) {
    //         foreach ($request->payment_methods as $paymentData) {
    //             if (!isset($paymentData['account_id'], $paymentData['amount'])) {
    //                 continue;
    //             }

    //             echo $paymentData['amount'];


    //             $paymentMethodId = $paymentData['account_id'];
    //             $paidAmount = $paymentData['amount'];
    //             $refNo = $paymentData['ref_no'] ?? null;


    //             if ($paidAmount > 0) {
    //                 $payment = new SessionsPayment();
    //                 $payment->appointment_id = $request->appointment_id2;
    //                 $payment->contract_payment = ($request->payment_status == 3) ? 1 : null;
    //                 $payment->payment_status = $request->payment_status;
    //                 $payment->account_id = $paymentMethodId;
    //                 $payment->ref_no = $refNo;
    //                 $payment->amount = $paidAmount;
    //                 $payment->user_id = $user_id;
    //                 $payment->contract_payment =1;
    //                 $payment->branch_id = $user->branch_id;
    //                 $payment->added_by = $user->id;
    //                 $payment->save();

    //                 $appointii = Appointment::find($request->appointment_id2);
    //                 $appointii->session_status = 3;
    //                 $appointii->save();

    //                 $account = Account::find($paymentMethodId);
    //                 if ($account) {
    //                     $account->opening_balance += $paidAmount;
    //                     $account->save();

    //                     if ($account->account_status != 1 && !empty($account->commission) && $account->commission > 0) {
    //                         $commissionFee = ($paidAmount / 100) * $account->commission;

    //                         $paymentExpense = new SessionPaymentExpense();
    //                         $paymentExpense->total_amount = $paidAmount;
    //                         $paymentExpense->account_tax = $account->commission;
    //                         $paymentExpense->account_tax_fee = $commissionFee;
    //                         $paymentExpense->account_id = $paymentMethodId;
    //                         $paymentExpense->appointment_id = $request->appointment_id2;
    //                         $paymentExpense->user_id = $user_id;
    //                         $paymentExpense->branch_id = $user->branch_id;
    //                         $paymentExpense->added_by = $user->id;
    //                         $paymentExpense->save();
    //                     }
    //                 }

    //                 $totalPaid += $paidAmount;
    //                 $hasValidPayment = true;
    //             }
    //         }
    //     }

    //     if (!$hasValidPayment) {
    //         $payment = new SessionsPayment();
    //         $payment->appointment_id = $request->appointment_id2;
    //         $payment->account_id = null;
    //         $payment->contract_payment = ($request->payment_status == 3) ? 1 : null;
    //         $payment->payment_status = $request->payment_status;
    //         $payment->amount = $request->input('totalAmount');
    //         $payment->user_id = $user_id;
    //         $payment->contract_payment = 1;
    //         $payment->branch_id = $user->branch_id;
    //         $payment->added_by = $user->id;
    //         $payment->save();
    //     }
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Payment saved successfully',
    //         'total_paid' => $totalPaid,
    //     ]);
    // }


    public function save_session_payment(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $appointment = Appointment::find($request->appointment_id2);
        if (!$appointment) {
            return response()->json(['success' => false, 'message' => 'Appointment not found'], 404);
        }

        $appointment->session_status = 3;
        $appointment->save();

        $totalPaid = 0;
        $hasValidPayment = false;

        // Handle multiple payments
        if (is_array($request->payment_methods) && !empty($request->payment_methods)) {
            foreach ($request->payment_methods as $paymentData) {
                $paymentMethodId = $paymentData['account_id'] ?? null;
                $paidAmount = $paymentData['amount'] ?? 0;
                $refNo = $paymentData['ref_no'] ?? null;

                if ($paymentMethodId && $paidAmount > 0) {
                    $payment = new SessionsPayment();
                    $payment->appointment_id = $appointment->id;
                    $payment->contract_payment = ($request->payment_status == 3) ? 1 : null;
                    $payment->payment_status = $request->payment_status;
                    $payment->account_id = $paymentMethodId;
                    $payment->ref_no = $refNo;
                    $payment->amount = $paidAmount;
                    $payment->user_id = $user->id;
                    $payment->contract_payment = 1;
                    $payment->branch_id = $user->branch_id;
                    $payment->added_by = $user->id;
                    $payment->save();

                    // Update account
                    $account = Account::find($paymentMethodId);
                    if ($account) {
                        $account->opening_balance += $paidAmount;
                        $account->save();

                        // Apply commission if applicable
                        if ($account->account_status != 1 && !empty($account->commission) && $account->commission > 0) {
                            $commissionFee = ($paidAmount / 100) * $account->commission;

                            $paymentExpense = new SessionPaymentExpense();
                            $paymentExpense->total_amount = $paidAmount;
                            $paymentExpense->account_tax = $account->commission;
                            $paymentExpense->account_tax_fee = $commissionFee;
                            $paymentExpense->account_id = $paymentMethodId;
                            $paymentExpense->appointment_id = $appointment->id;
                            $paymentExpense->user_id = $user->id;
                            $paymentExpense->branch_id = $user->branch_id;
                            $paymentExpense->added_by = $user->id;
                            $paymentExpense->save();
                        }
                    }

                    $totalPaid += $paidAmount;
                    $hasValidPayment = true;
                }
            }
        }

        // Only save fallback entry if no valid payments were processed
        if (!$hasValidPayment && $request->input('totalAmount') > 0) {
            $payment = new SessionsPayment();
            $payment->appointment_id = $appointment->id;
            $payment->account_id = null;
            $payment->contract_payment = ($request->payment_status == 3) ? 1 : null;
            $payment->payment_status = $request->payment_status;
            $payment->amount = $request->input('totalAmount');
            $payment->user_id = $user->id;
            $payment->contract_payment = 1;
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



public function patient_appointment($id, Request $request) {


    $user_id = Auth::id();
    $data = User::where('id', $user_id)->first();
    $user = $data->user_name;
    $branch_id = $data->branch_id;

    $appointment_id= $id;
    $appointment= Appointment::where('id', $id)->first();
    $id=$appointment->patient_id;

    $patient_total_sessions = SessionData::where('patient_id', $id)->count();
    $total_session_taken = SessionData::where('patient_id', $id)->where('status', 2)->count();
    $total_active_session = SessionData::where('patient_id', $id)->where('status', 1)->count();

    $notes = ClinicalNotes::where('patient_id', $id)
    ->whereNotIn('notes_status', [5, 6])
    ->get();

    $accounts= Account::where('branch_id',   $branch_id)->get();
        $patient = Patient::where('id', $id)->first();
        $country= $patient->country_id ?? '';
        $country_name= Country::where('id', $country)->value('name');

        $apt = Appointment::where('patient_id', $patient->id)->latest()->first();
        $total_apt= Appointment::where('patient_id', $patient->id)->count();
        $apt_id= $apt->id ?? '';
        if ($patient && $patient->dob) {
            $dob = Carbon::parse($patient->dob);
            $now = Carbon::now();

            $ageInYears = $dob->diffInYears($now);
            $ageInMonths = $dob->diffInMonths($now) % 12;
            $ageInDays = $dob->diffInDays($now) % 30;

            if ($ageInYears >= 1) {
                $age = "$ageInYears years";
            } elseif ($ageInMonths >= 1) {
                $age = "$ageInMonths months";
            } else {
                $age = "$ageInDays days";
            }
        } else {
            $age = 'N/A';
        }


        $sessions = SessionDetail::where('patient_id', $id)
        ->whereNotNull('ministry_id')
        ->where('contract_payment', 1)
        ->get(['ministry_id', 'total_sessions', 'total_fee', 'id']);

    $app_sessions = AppointmentDetail::where('patient_id', $id)
        ->whereNotNull('ministry_id')
        ->where('contract_payment', 1)
        ->get(['ministry_id', 'total_sessions', 'total_price', 'appointment_id']);

    $ministry_name = null;
    $ministry_data = null;

    // Check which one has data
    if ($sessions->isNotEmpty()) {
        $firstSession = $sessions->first();
        $payment= SessionsonlyPayment::where('session_id', $firstSession->session_id)->value('amount');
        $ministry_name = GovtDept::where('id', $firstSession->ministry_id)->value('govt_name');

        $ministry_data = [
            'type' => 'session',
            'id'=>$firstSession->id,
            'ministry_id' => $firstSession->ministry_id,
            'no_of_sessions' => $firstSession->total_sessions,
            'session_fee' => $firstSession->total_fee,
        ];
    } elseif ($app_sessions->isNotEmpty()) {
        $firstAppSession = $app_sessions->first();
        $ministry_name = GovtDept::where('id', $firstAppSession->ministry_id)->value('govt_name');

        $ministry_data = [
            'type' => 'appointment',
            'ministry_id' => $firstAppSession->ministry_id,
            'appointment_id' => $firstAppSession->appointment_id,
            'total_sessions' => $firstAppSession->total_sessions,
            'total_price' => $firstAppSession->total_price,
        ];
    }
    $doctors= Doctor::all();
    $patients = Patient::all();


    $check1 = AppointmentDetail::where('patient_id', $id)
    ->whereNotNull('ministry_id')->where('contract_payment', 1)->first();



$check2 = SessionDetail::where('patient_id', $id)
    ->whereNotNull('ministry_id')->where('contract_payment', 1)->first();


// Merge both filtered sets

$data_check = SessionData::where('main_appointment_id', $appointment_id)->exists();


return view('patients.patient_appointment', compact('patient', 'data_check', 'appointment', 'check1', 'check2', 'doctors', 'patients', 'notes', 'patient_total_sessions', 'total_session_taken', 'total_active_session', 'total_apt', 'country_name', 'accounts', 'apt', 'apt_id', 'age', 'ministry_name', 'ministry_data'));

    }


    public function patient_appointment_detail($id)
    {
        $appointment = Appointment::where('id', $id)->first();

        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }


        $clinic_notes= ClinicalNotes::where('appointment_id', $appointment->id)->get();

        // Session data and counts
        $data = SessionData::where('main_appointment_id', $appointment->id)->get();
        $appointment->total_sessions = $data->count();
        $appointment->pt_sessions = $data->where('session_cat', 'PT')->count();
        $appointment->ot_sessions = $data->where('session_cat', 'OT')->count();
        $appointment->session_taken = $data->where('status', 2)->count();
        $appointment->session_remain = $data->where('status', 1)->count();

        // File attachments
        $files = Patientfiles::where('appointment_id', $appointment->id)->get();
        $appointment->files = $files->map(function ($file) {
            return [
                'file_name' => $file->file_name,
                'file_id' => $file->id,
                'file_path' => $file->file_path,
            ];
        });

        $appointment->clinical_notes = $clinic_notes->map(function ($note) {
            switch ($note->notes_status) {
                case 1:
                    $img = asset('images/logo/6.png');
                    $view = route('neuro_pedriatic_view', $note->id);
                    $edit = route('neuro_pedriatic_view', $note->id);
                    $label = 'PT-NEURO-PED';
                    break;
                case 2:
                    $img = asset('images/logo/4.png');
                    $view = route('edit_otatp_ortho', $note->id);
                    $edit = route('edit_otatp_ortho', $note->id);
                    $label = 'PT-ORTHO';
                    break;
                case 3:
                    $img = asset('images/logo/5.png');
                    $view = route('edit_otp_pediatric', $note->id);
                    $edit = route('edit_otp_pediatric', $note->id);
                    $label = 'OTP-PEDIATRICS';
                    break;
                case 4:
                    $img = asset('images/logo/3.png');
                    $view = route('edit_physical_dysfunction', $note->id);
                    $edit = route('edit_physical_dysfunction', $note->id);
                    $label = 'OTP-PHY.DF';
                    break;
                case 5:
                    $img = asset('images/logo/2.png');
                    $view = route('edit_soap_ot', $note->id);
                    $edit = route('edit_soap_ot', $note->id);
                    $label = 'SOAP-OT';
                    break;
                case 6:
                    $img = asset('images/logo/1.png');
                    $view = route('edit_soap_pt', $note->id);
                    $edit = route('edit_soap_pt', $note->id);
                    $label = 'SOAP-PT';
                    break;
                default:
                    $img = asset('images/dummy_images/no_image.jpg');
                    $view = '#';
                    $edit = '#';
                    $label = 'Unknown';
                    break;
            }

            return [
                'form_type' => $label,
                'icon' => $img,
                'view_url' => $view,
                'edit_url' => $edit,
            ];
        });


        // Test recommendations & prescription notes
        $prescription = PatientPrescription::where('appointment_id', $appointment->id)->first();
        $appointment->prescription_notes = $prescription->notes ?? '';
        $appointment->test_recommendations = $prescription ? json_decode($prescription->test_recommendations, true) : [];

        // Notes
        $appointment->notes = $appointment->notes ?? '';

        return response()->json($appointment);
    }


    public function show_all_sessions_under_appointment(Request $request)
{
    $main_id = $request->main_id;

    $json = [];
    $sno = 0;



    $baseQuery = null;

    $baseQuery = SessionData::where('main_appointment_id', $main_id);


    $sessions = $baseQuery ? $baseQuery->get() : collect();


    foreach ($sessions as $session) {
        $sno++;

        // Optional: Get patient name if needed
        $doctor_name = DB::table('doctors')->where('id', $session->doctor_id)->value('doctor_name');

        // Determine badge color based on the source
        $badgeColor = 'bg-warning'; // Default badge color for source


        if ($session->source == 1) {
            // For direct sessions, use a different badge color and text
            $badgeColor = 'bg-success';
            $sourceText = 'Direct Session';
        }

        // Determine badge color based on status
        $statusBadgeColor = 'bg-secondary'; // Default badge color for status
        $statusText = 'Unknown'; // Default text for status

        if ($session->status == 1) {
            $statusBadgeColor = 'bg-warning'; // Pending
            $statusText = 'Pending';
        } elseif ($session->status == 2) {
            $statusBadgeColor = 'bg-success'; // Completed
            $statusText = 'Completed';
        } elseif ($session->status == 3) {
            $statusBadgeColor = 'bg-info'; // Transferred
            $statusText = 'Transferred';
        }
        elseif ($session->status == 4) {
            $statusBadgeColor = 'bg-danger'; // Transferred
            $statusText = 'On-going';
        }

        $modal = '';
        $sessionDate = Carbon::parse($session->session_date)->format('Y-m-d');
        $today = Carbon::now()->format('Y-m-d');

        // Check if status is Pending (1)
        if ($session->status == 1) {
            // Show edit + transfer icons with tooltips
            $modal .= '
                <a href="javascript:void(0);" class="me-3" data-bs-toggle="modal" data-bs-target="#transferModal" onclick="transfer(' . $session->id . ', \'' . $session->source . '\')" title="Transfer Sessions from one patient to other" data-bs-toggle="tooltip">
                    <i class="fa fa-right-left fs-18 text-info"></i>
                </a>
                <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#editSessionModal" onclick="edit(' . $session->id . ', \'' . $session->source . '\')" title="Edit Session" data-bs-toggle="tooltip">
                    <i class="fa fa-pencil fs-18 text-success"></i>
                </a>';

            // Only show image if session date IS today
            if ($sessionDate == $today) {
                if ($session->session_cat === 'OT') {
                    $url = url('soap_ot/' . $session->id);
                    $img = asset('images/logo/1.png');
                } elseif ($session->session_cat === 'PT') {
                    $url = url('soap_pt/' . $session->id);
                    $img = asset('images/logo/2.png');
                } else {
                    $url = '#';
                    $img = asset('images/logo/default.png');
                }

                $modal .= '
                    <a href="' . $url . '" class="me-3 text-decoration-none text-dark" title="Edit Session" data-bs-toggle="tooltip">
                        <img src="' . $img . '" class="rounded-circle shadow-sm mb-2" style="width: 30px; height: 30px; object-fit: cover;">
                    </a>';
            }

        } elseif ($session->status != 1) {
            // For all other statuses except On-going (4), show only the image
            if ($session->session_cat === 'OT') {
                $url = url('soap_ot/' . $session->id);
                $img = asset('images/logo/1.png');
            } elseif ($session->session_cat === 'PT') {
                $url = url('soap_pt/' . $session->id);
                $img = asset('images/logo/2.png');
            } else {
                $url = '#';
                $img = asset('images/logo/default.png');
            }

            $modal .= '
                <a href="' . $url . '" class="me-3 text-decoration-none text-dark" >
                    <img src="' . $img . '" class="rounded-circle shadow-sm mb-2" style="width: 30px; height: 30px; object-fit: cover;">
                </a>';
        }


        // Add session data to the response
        $json[] = [
            '<span class="text-muted">#' . $sno . '</span>',
            // '<span class="text-dark ">' . ($session->session_date ?? 'N/A') . '</span>',
            '<span>' . Carbon::parse($session->session_date)->format('d-m-Y') . '</span>',
            '<span>' . ($doctor_name ?? 'Unknown') . '</span>',
            '<span>' . $session->session_time . '</span>',
            '<span>' . $session->session_cat . '</span>',

            // '<span>' . $session->session_price . '</span>',
            '<span class="badge ' . $statusBadgeColor . '">' . $statusText . '</span>', // Add status badge
           $modal,
        ];
    }

    return response()->json(['success' => true, 'aaData' => $json]);
}

public function getNextAvailableTime(Request $request)
{
    $doctorId = $request->doctor_id;
    $date = $request->appointment_date;
    $inputTimeFrom = $request->time_from;
    $inputTimeTo = $request->time_to;

    // Get the doctor's session time (status = 4)
    $session = SessionData::where('doctor_id', $doctorId)
        ->where('status', 4)
        ->whereDate('session_date', $date)
        ->first();

    if (!$session) {
        return response()->json(['error' => 'Doctor has no available session on this date.'], 404);
    }

    $sessionStart = \Carbon\Carbon::parse($session->session_time);
    $sessionEnd = (clone $sessionStart)->addHour(); // Assuming session = 1 hour

    // Case 1: Validate manually entered times
    if ($inputTimeFrom && $inputTimeTo) {
        try {
            $inputStart = \Carbon\Carbon::parse($inputTimeFrom);
            $inputEnd = \Carbon\Carbon::parse($inputTimeTo);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid time format.'], 422);
        }

        // Check if time falls within session
        if ($inputStart->lt($sessionStart) || $inputEnd->gt($sessionEnd)) {
            return response()->json(['error' => 'Time is outside of session bounds.'], 409);
        }

        // Check appointment conflict
        $conflict = Appointment::where('doctor_id', $doctorId)
            ->whereIn('session_status', [2, 5, 8])
            ->whereDate('appointment_date', $date)
            ->where(function ($q) use ($inputTimeFrom, $inputTimeTo) {
                $q->where('time_from', '<', $inputTimeTo)
                  ->where('time_to', '>', $inputTimeFrom);
            })->exists();

        if ($conflict) {
            return response()->json(['error' => 'Doctor has a conflict at this time.'], 409);
        }

        return response()->json([
            'message' => 'Selected time is available.',
            'time_from' => $inputStart->format('H:i'),
            'time_to' => $inputEnd->format('H:i')
        ]);
    }

    // Case 2: Auto-suggest next available time
    $lastAppointment = Appointment::where('doctor_id', $doctorId)
        ->whereIn('session_status', [2, 5, 8])
        ->whereDate('appointment_date', $date)
        ->orderBy('time_to', 'desc')
        ->first();

    $startTime = $lastAppointment
        ? \Carbon\Carbon::parse($lastAppointment->time_to)
        : clone $sessionStart;

    $endTime = (clone $startTime)->addHour();

    if ($endTime->gt($sessionEnd)) {
        return response()->json(['error' => 'No available time slots left in session.'], 409);
    }

    return response()->json([
        'time_from' => $startTime->format('H:i'),
        'time_to' => $endTime->format('H:i')
    ]);
}






}
