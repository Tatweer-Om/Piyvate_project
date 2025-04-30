<?php

namespace App\Http\Controllers;

use Log;
use App\Models\User;
use App\Models\Branch;
use App\Models\Account;
use App\Models\Country;
use App\Models\History;
use App\Models\Patient;
use App\Models\Setting;
use App\Models\GovtDept;
use App\Models\Appointment;
use App\Models\SessionList;
use App\Models\Patientfiles;
use Illuminate\Http\Request;
use App\Models\SessionDetail;
use Illuminate\Support\Carbon;
use App\Models\AllSessioDetail;
use App\Models\SessionsPayment;
use App\Models\AppointmentDetail;
use App\Models\AppointmentSession;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\PatientPrescription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\AppointPaymentExpense;
use App\Models\ClinicalNotes;
use App\Models\Doctor;
use App\Models\SessionData;
use App\Models\SessionPaymentExpense;
use App\Models\SessionsonlyPayment;
use App\Models\SessionsonlyPaymentExp;
use Illuminate\Contracts\Session\Session;

use function PHPUnit\Framework\returnValueMap;

class PatientController extends Controller
{
    public function patient_list(){

        $countries= Country::all();
        return view ('patients.patients_list', compact( 'countries'));
    }



    public function patient_profile($id, Request $request) {


    $user_id = Auth::id();
    $data = User::where('id', $user_id)->first();
    $user = $data->user_name;
    $branch_id = $data->branch_id;

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


        $sessions = SessionList::where('patient_id', $id)
        ->whereNotNull('ministry_id')
        ->get(['ministry_id', 'no_of_sessions', 'session_fee', 'id']);

    $app_sessions = AppointmentDetail::where('patient_id', $id)
        ->whereNotNull('ministry_id')
        ->get(['ministry_id', 'total_sessions', 'total_price', 'appointment_id']);

    $ministry_name = null;
    $ministry_data = null;

    // Check which one has data
    if ($sessions->isNotEmpty()) {
        $firstSession = $sessions->first();
        $ministry_name = GovtDept::where('id', $firstSession->ministry_id)->value('govt_name');

        $ministry_data = [
            'type' => 'session',
            'id'=>$firstSession->id,
            'ministry_id' => $firstSession->ministry_id,
            'no_of_sessions' => $firstSession->no_of_sessions,
            'session_fee' => $firstSession->session_fee,
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


    $detail = AppointmentDetail::where('appointment_id', $apt_id)
    ->where('ministry_id', null)
    ->first();
return view('patients.patient_profile', compact('patient', 'doctors', 'patients', 'notes', 'patient_total_sessions', 'total_session_taken', 'total_active_session', 'total_apt', 'country_name', 'accounts', 'apt', 'apt_id', 'age', 'ministry_name', 'ministry_data'));

    }

    public function show_patient()
    {
        $sno = 0;
        $patients = Patient::all();

        if ($patients->count() > 0) {
            foreach ($patients as $patient) {
                $patient_name = '<a class="patient-info ps-0" href="javascript:void(0);">' . $patient->full_name . '</a>';
                $modal = '<a href="javascript:void(0);" class="me-3 edit-patient" data-bs-toggle="modal" data-bs-target="#add_patient" onclick=edit("' . $patient->id . '")>
                            <i class="fa fa-pencil fs-18 text-success"></i>
                         </a>
                         <a href="javascript:void(0);" onclick=del("' . $patient->id . '")>
                            <i class="fa fa-trash fs-18 text-danger"></i>
                         </a>';

                $add_data = Carbon::parse($patient->created_at)->format('d-m-Y (h:i a)');

                $branch = Branch::where('id', $patient->branch_id)->value('branch_name');
                $country = Country::where('id', $patient->country_id)->value('name');


                $sno++;
                $json[] = array(
                    '<span class="patient-info ps-0">' . $patient->HN . '</span>',
                    '<span class="text-primary">' .$patient_name. '</span>',
                    '<span class="badge bg-primary"><i class="fas fa-phone-alt"></i> ' . $patient->mobile . '</span>',
                    '<span >' . $country . '</span>',
                    '<span class="badge bg-secondary" style="font-size: 10px;"><i class="fas fa-birthday-cake"></i> ' . $patient->age . '</span>',

                    '<span>' . $branch . '</span>',
                    '<span>' . $add_data . '</span>',
                    '<span>' . $patient->added_by . '</span>',

                    $modal
                );
            }

            return response()->json(['success' => true, 'aaData' => $json]);
        }

        return response()->json(['sEcho' => 0, 'iTotalRecords' => 0, 'iTotalDisplayRecords' => 0, 'aaData' => []]);
    }

    public function add_patient(Request $request)
{
    $user_id = Auth::id();
    $data = User::where('id', $user_id)->first();
    $user = $data->user_name;
    $branch_id = $data->branch_id;

    // Determine title
    $title = '';
    if ($request->title == 1) {
        $title = 'Miss';
    } elseif ($request->title == 2) {
        $title = 'Mr.';
    } elseif ($request->title == 3) {
        $title = 'Mrs.';
    }

    // Generate full name
    $full_name = trim($title . ' ' . $request->first_name . ' ' . $request->second_name);

    try {
        // Check if patient exists based on mobile
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




            // Create new patient
            $patient = new Patient();
            $patient->title = $request->title;
            $patient->first_name = $request->first_name;
            $patient->second_name = $request->second_name;
            $patient->full_name = $full_name;
            $patient->mobile = $request->mobile;
            $patient->country_id = $request->country;
            $patient->id_passport = $request->id_passport;
            $patient->dob = $request->dob;
            $patient->gender = $request->gender;
            $patient->age = $request->age;
            $patient->branch_id = $branch_id;
            $patient->added_by = $user;
            $patient->user_id = $user_id;
            $patient->HN = $clinicNumber;
            $patient->save();

            return response()->json(['success' => 'Patient added successfully', 'patient_id' => $patient->id]);
        } else {
            return response()->json(['error' => 'Patient with this mobile number already exists'], 400);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to add patient', 'message' => $e->getMessage()], 500);
    }
}


    public function edit_patient(Request $request)
    {
        $patient = Patient::find($request->input('id'));
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        return response()->json([
            'patient_id' => $patient->id,
            'title' => $patient->title,
            'first_name' => $patient->first_name,
            'second_name' => $patient->second_name,
            'mobile' => $patient->mobile,
            'id_passport' => $patient->id_passport,
            'dob' => $patient->dob,
            'age' => $patient->age,
            'gender' => $patient->gender,
            'country' => $patient->country_id,
            'details' => $patient->details,
        ]);
    }

    public function update_patient(Request $request)
    {
        $user_id = Auth::id();
        $data = User::where('id', $user_id)->first();
        $user = $data->user_name;
        $branch_id = $data->branch_id;


        $patient = Patient::find($request->input('patient_id'));
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        // Store previous data before updating
        $previousData = $patient->only([
            'title', 'first_name', 'second_name', 'mobile', 'id_passport', 'dob', 'country', 'details', 'added_by', 'created_at'
        ]);
        $title = '';
        if ($request->title == 1) {
            $title = 'Miss';
        } elseif ($request->title == 2) {
            $title = 'Mr.';
        } elseif ($request->title == 3) {
            $title = 'Mrs.';
        }

        // Generate full name
        $full_name = trim($title . ' ' . $request->first_name . ' ' . $request->second_name);



        // Create new patient
        $patient->title = $request->title;
        $patient->first_name = $request->first_name;
        $patient->second_name = $request->second_name;
        $patient->full_name = $full_name;
        $patient->mobile = $request->mobile;
        $patient->country_id = $request->country;
        $patient->id_passport = $request->id_passport;
        $patient->dob = $request->dob;
        $patient->gender = $request->gender;
        $patient->age = $request->age;

        $patient->branch_id = $branch_id;
        $patient->added_by = $user;
        $patient->user_id = $user_id;
        $patient->HN = $patient->HN;
        $patient->save();

        // Save update history
        $updatedData = $patient->only([
            'title', 'first_name', 'second_name', 'mobile', 'gender', 'age', 'HN', 'id_passport', 'dob', 'country', 'details', 'added_by'
        ]);

        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'patients';
        $history->function = 'update';
        $history->function_status = 1;
        $history->record_id = $patient->id;
        $history->branch_id = $branch_id;

        $history->previous_data = json_encode($previousData);
        $history->updated_data = json_encode($updatedData);
        $history->added_by = $user;
        $history->save();

        return response()->json(['success' => 'Patient updated successfully']);
    }

    public function delete_patient(Request $request)
    {

        $id= $request->input('id');
        $patient = Patient::where('id', $id)->first();
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        // Store previous data before deletion
        $previousData = $patient->only([
            'title', 'first_name', 'second_name', 'gender', 'age', 'HN', 'mobile', 'id_passport', 'dob', 'country', 'details', 'added_by', 'created_at'
        ]);

        // Get current user info
        $currentUser = Auth::user();
        $username = $currentUser->user_name;
        $user_id = $currentUser->id;
        $branch_id= $currentUser->branch_id;


        // Save delete history
        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'patients';
        $history->function = 'delete';
        $history->function_status = 2; // Status for delete
        $history->record_id = $patient->id;
        $history->branch_id = $branch_id;

        $history->previous_data = json_encode($previousData);
        $history->added_by = $username;
        $history->save();

        // Delete patient record
        $patient->delete();

        return response()->json(['success' => 'Patient deleted successfully']);
    }



    public function getAppointments($id)
    {
        $appointments = Appointment::where('patient_id', $id)
            ->with('doctor:id,doctor_name')
            ->orderBy('appointment_date', 'desc')
            ->get();

        foreach ($appointments as $appointment) {


            if ($appointment->session_status == 1) {
                $statusClass = 'badge-danger';
                $statusText = 'Sessions Recommended';
                $statusIcon = '<i class="fa fa-exclamation-circle"></i> ';
                $badge = '<span class="badge ' . $statusClass . ' px-2 py-1"  style="cursor: pointer;">' . $statusIcon . $statusText . '</span>';
            } elseif ($appointment->session_status == 2) {
                $statusClass = 'badge-warning';
                $statusText = 'Appointment';
                $statusIcon = '<i class="fa fa-calendar"></i> ';
                $badge = '<span class="badge ' . $statusClass . ' px-2 py-1">' . $statusIcon . $statusText . '</span>';
            } elseif ($appointment->session_status == 3) {
                $total_sessions = (int) AppointmentDetail::where('appointment_id', $appointment->id)->value('total_sessions');

                $statusText = 'Sessions: ';
                $statusIcon = '<i class="fa fa-list"></i> ';
                $badge = '<span class="badge badge-primary px-2 py-1">' . $statusIcon . $statusText . $total_sessions . '</span>';
            }

             elseif ($appointment->session_status == 4) {
                $statusClass = 'badge-dark';
                $statusText = 'Cancelled';
                $statusIcon = '<i class="fa fa-times-circle"></i> ';
                $badge = '<span class="badge ' . $statusClass . ' px-2 py-1">' . $statusIcon . $statusText . '</span>';
            } elseif ($appointment->session_status == 5) {
                $statusClass = 'badge-info';
                $statusText = 'Pre-Registered';
                $statusIcon = '<i class="fa fa-user-plus"></i> ';
                $badge = '<span class="badge ' . $statusClass . ' px-2 py-1">' . $statusIcon . $statusText . '</span>';
            }
            elseif ($appointment->session_status == 7) {
                $statusClass = 'badge-info';
                $statusText = 'Appointment Done';
                $statusIcon = '<i class="fa fa-check-circle"></i> ';
                $badge = '<span class="badge ' . $statusClass . ' px-2 py-1">' . $statusIcon . $statusText . '</span>';
            }
             else {
                $badge = '<span class="badge bg-secondary px-2 py-1">Unknown</span>';
            }

            $appointment->status_badge = $badge;
        }

        return response()->json($appointments);
    }

    public function appointmentsdetail($id)
    {
        $appointments = Appointment::where('patient_id', $id)
            ->with('doctor:id,doctor_name')
            ->orderBy('appointment_date', 'desc')
            ->get();

        foreach ($appointments as $appointment) {
            // Session data and counts
            $data = SessionData::where('main_appointment_id', $appointment->id)->get();
            $appointment->total_sessions = $data->count();
            $appointment->pt_sessions = $data->where('session_cat', 'PT')->count();
            $appointment->ot_sessions = $data->where('session_cat', 'OT')->count();
            $appointment->session_taken = $data->where('status', 2)->count();
            $appointment->session_remain = $data->where('status', 1)->count();

            // File attachments (get all files for this appointment)
            $files = Patientfiles::where('appointment_id', $appointment->id)->get();

            // Build file data (simple view: icon + download URL)
            $appointment->files = $files->map(function ($file) {
                return [
                    'file_name' => $file->file_name,
                    'file_id' => $file->id,
                    'file_path' => $file->file_path,
                    // Adjust this path if needed
                ];
            });

            // Test recommendations
            $prescription = PatientPrescription::where('appointment_id', $appointment->id)->first();
            $appointment->prescription_notes = $prescription->notes ?? '';
            $appointment->notes = $appointment->notes ?? '';

            $appointment->test_recommendations = $prescription ? json_decode($prescription->test_recommendations, true) : [];
        }

        return response()->json($appointments);
    }



public function show_all_sessions_by_patient(Request $request)
{
    $patient_id = $request->input('patient_id');
    $json = [];
    $sno = 0;

    $sessions = SessionData::where('patient_id', $patient_id)->get();

    // Loop through the sessions to create the table data
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

        $modal = '
        <a href="javascript:void(0);" class="me-3" data-bs-toggle="modal" data-bs-target="#transferModal" onclick="transfer(' . $session->id . ', \'' . $session->source . '\')">
            <i class="fa fa-right-left fs-18 text-info"></i>
        </a>
        <a href="javascript:void(0);" class="me-3 edit-staff" data-bs-toggle="modal" data-bs-target="#editSessionModal" onclick="edit(' . $session->id . ', \'' . $session->source . '\')">
            <i class="fa fa-pencil fs-18 text-success"></i>
        </a>';

    if ($session->session_cat === 'OT') {
        $url = url('soap_ot/' . $session->id);
        $img = asset('images/logo/1.png');
    } elseif ($session->session_cat === 'PT') {
        $url = url('soap_pt/' . $session->id);
        $img = asset('images/logo/2.png');
    } else {
        $url = '#';
        $img = asset('images/logo/default.png'); // Optional default image
    }

    $modal .= '
        <a href="' . $url . '" class="me-3 text-decoration-none text-dark">
            <img src="' . $img . '" class="rounded-circle shadow-sm mb-2" style="width: 30px; height: 30px; object-fit: cover;">
        </a>';




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


public function getAppointmentsAndSessions($id)
{
    // Get appointments for the given patient_id
    $appointments = AppointmentDetail::where('patient_id', $id)
        ->with('doctor:id,doctor_name')
        ->orderBy('created_at', 'desc')
        ->get();

    // Get sessions for the same patient_id
    $sessions = SessionDetail::where('patient_id', $id)
        ->with('doctor:id,doctor_name') // You can add more relationships if necessary
        ->orderBy('created_at', 'desc')
        ->get();

    // Merge both appointments and sessions into one array
    $appointmentsAndSessions = [];

    foreach ($appointments as $appointment) {
        $apt_no= Appointment::where('id', $appointment->appointment_id)->value('appointment_no');

        $appointmentsAndSessions[] = [
            'type' => 'appointment', // Mark as appointment
            'appointment_no' => $apt_no,
            'fee' => $appointment->total_price,
            'session_count' => $appointment->total_sessions, // Assuming a relationship exists
            'single_session_fee' => ($appointment->total_sessions > 0)
                ? $appointment->total_price / $appointment->total_sessions
                : 0,
        ];

    }

    foreach ($sessions as $session) {
        // Get the session number for the current session
        $session_no = SessionList::where('id', $session->session_id)->value('session_no');

        $appointmentsAndSessions[] = [
            'type' => 'session', // Mark as session
            'appointment_no' => $session_no,
            'fee' => $session->total_fee,
            'session_count' => $session->total_sessions, // One session at a time
            'single_session_fee' => ($session->total_sessions > 0)
                ? $session->total_fee / $session->total_sessions
                : 0,
        ];
    }

    // Return as JSON
    return response()->json($appointmentsAndSessions);
}







public function submit_contract_payment(Request $request)
{
    $user_id = Auth::id();
    $user = User::find($user_id);

    $patient_id     = $request->patient_id;
    $ministry_id    = $request->ministry_id;
    $type           = $request->type;
    $total_sessions = $request->total_sessions;
    $total_price    = $request->total_price;
    $apt_id         = $request->appointment_id;
    $session_id     = $request->session_id;

    $hasValidPayment = false;

    try {
        // 1. Update status
        if ($type === 'session') {
            $session_list = SessionList::find($session_id);
            if ($session_list) {
                $session_list->payment_status = 4;
                $session_list->save();
            }

            AllSessioDetail::where('session_id', $session_id)->update(['contract_payment' => 2]);

            $session_detail = SessionDetail::where('session_id', $session_id)->first();
            if ($session_detail) {
                $session_detail->contract_payment = 2;
                $session_detail->save();
            }
        } elseif ($type === 'appointment') {
            $appointment = Appointment::find($apt_id);
            $session_payment = SessionsPayment::where('appointment_id', $apt_id)->first();

            if ($appointment) {
                $appointment->payment_status = 4;
                $appointment->save();
            }

            if ($session_payment) {
                $session_payment->contract_payment = 2;
                $session_payment->save();
            }

            AppointmentSession::where('appointment_id', $apt_id)->update(['contract_payment' => 2]);

            $detail = AppointmentDetail::where('appointment_id', $apt_id)->first();
            if ($detail) {
                $detail->contract_payment = 2;
                $detail->save();
            }
        }

        // 2. Handle payment recording
        if (is_array($request->payment_methods) && !empty($request->payment_methods)) {
            foreach ($request->payment_methods as $index => $paymentMethodId) {
                $paidAmount = $request->payment_amounts[$paymentMethodId] ?? 0;
                $refNo = $request->ref_nos[$paymentMethodId] ?? null;

                if ($paidAmount > 0) {
                    if ($type === 'appointment') {
                        // Save to SessionsPayment
                        $payment = new SessionsPayment();
                        $payment->appointment_id = $apt_id;
                    } else {
                        // Save to Sessiononlypayment
                        $payment = new SessionsonlyPayment();
                        $payment->session_id = $session_id;
                    }

                    $payment->contract_payment = 2;
                    $payment->payment_status = 4;
                    $payment->account_id = $paymentMethodId;
                    $payment->ref_no = $refNo;
                    $payment->amount = $paidAmount;
                    $payment->user_id = $user_id;
                    $payment->branch_id = $user->branch_id;
                    $payment->added_by = $user->id;
                    $payment->save();

                    // Update account balance
                    $account = Account::find($paymentMethodId);
                    if ($account) {
                        $account->opening_balance += $paidAmount;
                        $account->save();

                        // Save commission if applicable
                        if ($account->account_status != 1 && !empty($account->commission) && $account->commission > 0) {
                            $commissionFee = ($paidAmount / 100) * $account->commission;

                            if ($type === 'appointment') {
                                $paymentExpense = new SessionPaymentExpense();
                                $paymentExpense->appointment_id = $apt_id;
                            } else {
                                $paymentExpense = new SessionsonlyPaymentExp();
                                $paymentExpense->session_id = $session_id;
                            }

                            $paymentExpense->total_amount = $paidAmount;
                            $paymentExpense->account_tax = $account->commission;
                            $paymentExpense->account_tax_fee = $commissionFee;
                            $paymentExpense->account_id = $paymentMethodId;
                            $paymentExpense->user_id = $user_id;
                            $paymentExpense->branch_id = $user->branch_id;
                            $paymentExpense->added_by = $user->id;
                            $paymentExpense->save();
                        }
                    }

                    $hasValidPayment = true;
                }
            }
        }

        return response()->json(['message' => 'Payment recorded successfully']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Something went wrong. Please try again later.']);
    }
}







public function save_prescription(Request $request)
{
    $user_id = Auth::id();
    $data = User::where('id', $user_id)->first();
    $user_name = $data->user_name;
    $branch_id = $data->branch_id;

    try {
        $appointment = Appointment::find($request->appointment_id);

        if (!$appointment) {
            return response()->json(['success' => false, 'message' => 'Appointment not found.']);
        }

        // Update appointment session status
        if (!empty($request->session_types)) {
            $appointment->session_status = 1; // Sessions Recommended
        } else {
            $appointment->session_status = 7; // Appointment Only
        }
        $appointment->save();

        $prescription_type = $request->prescription_type ?? 'appointment';

        // Check if a prescription already exists for this appointment
        $prescription = PatientPrescription::where('appointment_id', $request->appointment_id)->first();

        if (!$prescription) {
            $prescription = new PatientPrescription();
            $prescription->appointment_id = $request->appointment_id;
            $prescription->patient_id = $request->patient_id;
            $prescription->user_id = $user_id;
            $prescription->added_by = $user_name;
            $prescription->branch_id = $branch_id;
        }

        $prescription->prescription_type = $prescription_type;
        $prescription->notes = $request->notes;

        if ($prescription_type === 'session') {
            if (!empty($request->session_types)) {
                $prescription->session_cat = implode(',', $request->session_types); // OT, PT
            }

            $prescription->ot_sessions = $request->ot_sessions ? (int)$request->ot_sessions : 0;
            $prescription->pt_sessions = $request->pt_sessions ? (int)$request->pt_sessions : 0;
            $prescription->sessions_reccomended = $prescription->ot_sessions + $prescription->pt_sessions;

            $prescription->session_gap = $request->session_gap ?? null;

            if ($prescription->session_gap == null) {
                return response()->json([
                    'status' => 2,
                ]);
            }
        }

        if ($prescription_type === 'test') {
            if ($request->has('test_recommendation') && is_array($request->test_recommendation)) {
                $existingTests = [];

                // Decode existing recommendations if they exist
                if (!empty($prescription->test_recommendations)) {
                    $existingTests = json_decode($prescription->test_recommendations, true);
                    if (!is_array($existingTests)) {
                        $existingTests = [];
                    }
                }

                // Merge and remove duplicates
                $mergedTests = array_unique(array_merge($existingTests, $request->test_recommendation));

                // Save the merged array
                $prescription->test_recommendations = json_encode($mergedTests);
            }
        }

        $prescription->save();

        return response()->json([
            'success' => true,
            'message' => 'Prescription saved successfully!',
            'updated' => isset($prescription->id) ? true : false
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error saving prescription: ' . $e->getMessage()
        ]);
    }
}



public function lab_reports_upload(Request $request)
{


    // Validate the incoming files
    $user_id = Auth::id();
    $data = User::where('id', $user_id)->first();
    $user_name = $data->user_name;
    $branch_id= $data->branch_id;

    try {
        $folderPath = public_path('images/lab_reports');

        if (!File::isDirectory($folderPath)) {
            File::makeDirectory($folderPath, 0777, true, true);
        }

        $savedFiles = [];
        foreach ($request->file('lab_reports') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($folderPath, $fileName);
            $patientFile = new PatientFiles();
            $patientFile->patient_id = $request->patient_id;
            $patientFile->appointment_id = $request->appoint_id;
            $patientFile->file_name = $fileName;
            $patientFile->file_path = 'images/lab_reports/' . $fileName;
            $patientFile->file_type = $file->getClientOriginalExtension();
            $patientFile->user_id =  $user_id;
            $patientFile->added_by =  $user_name;
            $patientFile->branch_id =  $branch_id;

            $patientFile->save();

            $savedFiles[] = $fileName;
        }

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Files uploaded successfully!',
            'files' => $savedFiles,
        ]);
    } catch (\Exception $e) {
        // Return error response if any exception occurs
        return response()->json([
            'success' => false,
            'message' => 'Error uploading files: ' . $e->getMessage(),
        ]);
    }
}


public function edit_ind_session(Request $request)
{
    $id = $request->input('id');


$session= SessionData::where('id', $id)->first();


    if ($session) {

        $doctor= Doctor::where('id', $session->doctor_id)->value('id');
        $patient= Patient::where('id', $session->patient_id)->first();

        // Return the session data as JSON
        return response()->json([
            'patient' => $patient->full_name,
            'patient_id' => $patient->id,
            'date' => $session->session_date,
            'time' => $session->session_time,
            'doctor' => $doctor,
            'session_primary_id' => $session->id,

        ]);
    } else {

        return response()->json(null);
    }
}


public function update_ind_session(Request $request)
{
    $id = $request->input('id');


    $user = Auth::user();
        $username = $user->user_name;
        $branch = $user->branch_id;
        $user_id = $user->id;



    $session =SessionData::where('id', $id)->first();

    if (!$session) {
        return response()->json(['message' => 'Session not found!'], 404);
    }

    $fieldsToUpdate = [
        'patient_id' => $request->input('patient_id'),
        'session_date' => $request->input('session_date'),
        'session_time' => $request->input('session_time'),
        'doctor_id' => $request->input('doctor')
    ];

    $changes = [];

    foreach ($fieldsToUpdate as $field => $newValue) {
        $oldValue = $session->$field;
        if ($oldValue != $newValue) {
            $changes[] = [
                'session_id' => $id,
                'field_name' => $field,
                'old_value' => $oldValue,
                'new_value' => $newValue,
                'updated_by' => $user_id, // Or use session()->get('user_id') if not using Auth
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    }

    if (!empty($changes)) {
        DB::table('sessionupdatelogs')->insert($changes);
    }

    DB::table('session_data')->where('id', $id)->update($fieldsToUpdate);

    return response()->json(['message' => 'Session updated successfully!']);
}


public function transfer_ind_session(Request $request)
{
    $id = $request->input('id');
 $session= SessionData::where('id', $id)->first();

    if ($session) {

        $doctor= Doctor::where('id', $session->doctor_id)->value('id');
        $patient= Patient::where('id', $session->patient_id)->first();

        // Return the session data as JSON
        return response()->json([
            'patient' => $patient->full_name,
            'patient_id' => $patient->id,
            'date' => $session->session_date,
            'time' => $session->session_time,
            'session_primary_id' => $session->id,

        ]);
    } else {

        return response()->json(null);
    }
}


public function update_transfer_ind_session(Request $request)
{
    $id = $request->input('id');
    $old_patient_id = $request->input('old_patient_id');
    $new_patient_id = $request->input('target_patient');
    $notes = $request->input('notes');

    $user = Auth::user();
    $username = $user->user_name;
    $branch = $user->branch_id;
    $user_id = $user->id;

$session= SessionData::where('id', $id)->first();
    if ($session) {
        // Update patient_id
        DB::table('session_data')
        ->where('id', $id)
        ->update([
            'patient_id' => $new_patient_id,

        ]);

        DB::table('session_transfer_logs')->insert([
            'session_id' => $id,

            'old_patient_id' => $old_patient_id,
            'new_patient_id' => $new_patient_id,
            'transferred_by' => $username, // assuming user is authenticated
            'user_id' =>   $user_id,
        ]);

        return response()->json(['message' => 'Session transferred and logged successfully!']);
    } else {
        return response()->json(['message' => 'Session not found!'], 404);
    }
}



public function patient_session($id, Request $request) {



    $id = $request->id;

    $user_id = Auth::id();
    $data = User::where('id', $user_id)->first();
    $user = $data->user_name;
    $branch_id = $data->branch_id;


    $session= SessionData::where('id', $id)->first();
    $patient_id=$session->patient_id;


        $patient = Patient::where('id', $patient_id)->first();
        $country= $patient->country_id ?? '';
        $country_name= Country::where('id', $country)->value('name');

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



$patient_total_sessions= SessionData::where('patient_id', $patient_id)->count();
$total_session_taken= SessionData::where('patient_id', $patient_id)->where('status', 2)->count();

$total_active_session=SessionData::where('patient_id', $patient_id)->where('status', 1)->count();
$ot_sessions = SessionData::where('session_cat', 'OT')
    ->where('patient_id', $patient_id)
    ->count();

$pt_sessions = SessionData::where('session_cat', 'PT')
    ->where('patient_id', $patient_id)
    ->count();

$ot_sessions_taken = SessionData::where('session_cat', 'OT')
    ->where('status', 2)
    ->where('patient_id', $patient_id)
    ->count();

$pt_sessions_taken = SessionData::where('session_cat', 'PT')
    ->where('status', 2)
    ->where('patient_id', $patient_id)
    ->count();

// Calculate pending
$ot_sessions_pending = $ot_sessions - $ot_sessions_taken;
$pt_sessions_pending = $pt_sessions - $pt_sessions_taken;
$doctors= Doctor::all();
$patients= Patient::all();
    return view('patients.patient_session', compact('patient', 'session', 'patients', 'doctors', 'ot_sessions_pending', 'pt_sessions_pending', 'ot_sessions_taken', 'pt_sessions_taken',  'ot_sessions', 'pt_sessions',  'patient_total_sessions', 'total_session_taken', 'total_active_session',  'country_name',  'age', ));

    }



    public function download($fileId)
    {
        // Retrieve the file record from the database
        $file = Patientfiles::find($fileId);

        if ($file) {
            // Construct the full path to the file
            $filePath = public_path('images/lab_reports/' . $file->file_path);

            // Check if the file exists before attempting to download
            if (file_exists($filePath)) {
                return response()->download($filePath, $file->file_name);
            } else {
                return abort(404, 'File not found');
            }
        }

        return abort(404, 'File not found');
    }


}
