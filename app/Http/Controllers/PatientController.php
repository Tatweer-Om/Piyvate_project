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
use App\Models\SessionPaymentExpense;
use App\Models\SessionsonlyPayment;
use App\Models\SessionsonlyPaymentExp;

class PatientController extends Controller
{
    public function patient_list(){

        $countries= Country::all();
        return view ('patients.patients_list', compact( 'countries'));
    }



    public function patient_profile($id) {


    $user_id = Auth::id();
    $data = User::where('id', $user_id)->first();
    $user = $data->user_name;
    $branch_id = $data->branch_id;


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

    return view('patients.patient_profile', compact('patient', 'total_apt', 'country_name', 'accounts', 'apt', 'apt_id', 'age', 'ministry_name', 'ministry_data'));

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
                $statusText = 'Appointent Done';
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


public function show_all_sessions_by_patient(Request $request)
{
    $patient_id = $request->input('patient_id');
    $json = [];
    $sno = 0;

    // Get appointment sessions
    $appointmentSessions = DB::table('appointment_sessions')
        ->where('patient_id', $patient_id)
        ->select(
            'id',
            'session_time',
            'doctor_id',
            'session_date',
            'session_time',
            'session_price',
            'status',
            DB::raw("'appointment_sessions' as source")
        )
        ->get();

    // Get all session details
    $allSessions = DB::table('all_sessio_details')
        ->where('patient_id', $patient_id)
        ->select(
            'id',
            DB::raw("'' as session_time"),
            'doctor_id',
            'session_date',
            'session_time',
            'session_price',
            'status',
            DB::raw("'all_sessio_details' as source")
        )
        ->get();

    // Merge both appointment and all sessions
    $sessions = $appointmentSessions->merge($allSessions);

    // Loop through the sessions to create the table data
    foreach ($sessions as $session) {
        $sno++;

        // Optional: Get patient name if needed
        $doctor_name = DB::table('doctors')->where('id', $session->doctor_id)->value('doctor_name');

        // Determine badge color based on the source
        $badgeColor = 'bg-warning'; // Default badge color for source
        $sourceText = ucfirst(str_replace('_', ' ', $session->source)); // Default text for source

        if ($session->source == 'all_sessio_details') {
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

        // Add session data to the response
        $json[] = [
            '<span class="text-muted">#' . $sno . '</span>',
            // '<span class="text-dark ">' . ($session->session_date ?? 'N/A') . '</span>',
            '<span>' . Carbon::parse($session->session_date)->format('d-m-Y') . '</span>',
            '<span>' . ($doctor_name ?? 'Unknown') . '</span>',
            '<span>' . $session->session_time . '</span>',
            '<span>' . $session->session_price . '</span>',
            '<span class="badge ' . $statusBadgeColor . '">' . $statusText . '</span>', // Add status badge
            '<span class="badge ' . $badgeColor . '">' . $sourceText . '</span>',

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


// public function show_all_payment_by_patient(Request $request)
// {
//     $patient_id = $request->input('patient_id');
//     $json = [];
//     $sno = 0;

//     $appointmentSessions = AppointmentSession::where('patient_id', $patient_id)
//         ->with('doctor:id,doctor_name')
//         ->orderBy('session_date', 'desc')
//         ->get();

//     $allSessions = AllSessioDetail::where('patient_id', $patient_id)
//         ->with('doctor:id,doctor_name')
//         ->orderBy('session_date', 'desc')
//         ->get();

//         foreach ($appointmentSessions as $appointment) {
//             $sno++;
//             $total_sessions = null;
//             $apt_no = null;
//             $paymen_status = null;
//             $apt_detail = null; // ✅ initialize to avoid undefined variable

//             if (!empty($appointment->appointment_id)) {
//                 $apt = Appointment::find($appointment->appointment_id);

//                 if ($apt) {
//                     $apt_detail = AppointmentDetail::where('appointment_id', $apt->id)->first();

//                     $total_sessions = $apt_detail->total_sessions ?? null;
//                     $apt_no = $apt->appointment_no ?? null;
//                     $paymen_status = $apt->payment_status ?? null;
//                 }
//             }

//             if ($paymen_status == 0 || $paymen_status == 1) {
//                 $paymen_status = 'Normal Session';
//                 $paymentBadge = '<span class="badge bg-primary">Normal - Completed</span>';
//             } elseif ($paymen_status == 2) {
//                 $paymen_status = 'Offer';
//                 $paymentBadge = $appointment->status == 2
//                     ? '<span class="badge bg-success">Offer - Completed</span>'
//                     : '<span class="badge bg-warning">Offer - Balance</span>';
//             } elseif ($paymen_status == 3) {
//                 $paymen_status = 'Contract';
//                 $paymentBadge = $appointment->status == 4
//                     ? '<span class="badge bg-success">Contract - Completed</span>'
//                     : '<span class="badge bg-danger">Contract - Pending</span>';
//             } else {
//                 $paymen_status = 'Unknown';
//                 $paymentBadge = '<span class="badge bg-secondary">Unknown Status</span>';
//             }

//             $dedtail_data= AppointmentDetail::where('appointment_id', $appointment->appointment_id)->first();
//             $fee= $detail_data->single_session_price ?? '';

//             $json[] = [
//                 '<span class="text-muted">#' . $sno . '</span>',
//                 '<span class="text-muted">#' . $apt_no . '</span>' . '<br>' .
//                 '<span class="text-muted">#' . $total_sessions . '</span>' . '<br>' .
//                 '<span class="text-muted">#' . ($apt_detail->total_price ?? '') . '</span>',

//                 '<span class="badge bg-primary">Appointment</span>',
//                 $appointment->doctor->doctor_name ?? 'Unknown',
//                 Carbon::parse($appointment->session_date)->format('d-m-Y'),
//                 $paymentBadge,
//                 $fee, // No fee in appointment session
//             ];
//         }


//         foreach ($allSessions as $session) {
//             $sno++;

//             // Initialize all needed variables
//             $session_check = null;
//             $session_no = null;
//             $paymen_status = null;
//             $paymentBadge = '<span class="badge bg-secondary">Unknown</span>'; // default badge
//             $no_of_sessions = null;
//             $session_fee = null;

//             // Fetch session details safely
//             $session_check = SessionList::where('id', $session->session_id)->first();

//             if ($session_check) {
//                 $session_no = $session_check->session_no ?? null;
//                 $paymen_status = $session_check->payment_status ?? null;
//                 $no_of_sessions = $session_check->no_of_sessions ?? null;
//                 $session_fee = $session_check->session_fee ?? 0;

//                 if ($paymen_status == 0 || $paymen_status == 1) {
//                     $paymen_status = 'Normal Session';
//                     $paymentBadge = '<span class="badge bg-success">Normal-Completed</span>';
//                 } elseif ($paymen_status == 2) {
//                     $paymen_status = 'Offer';
//                     $paymentBadge = $session->status == 2
//                         ? '<span class="badge bg-success">Offer-Completed</span>'
//                         : '<span class="badge bg-warning">Offer-Balance</span>';
//                 } elseif ($paymen_status == 3) {
//                     $paymen_status = 'Contract';
//                     $paymentBadge = $session->status == 4
//                         ? '<span class="badge bg-success">Contract-Completed</span>'
//                         : '<span class="badge bg-danger">Contract-Pending</span>';
//                 } else {
//                     $paymen_status = 'Unknown';
//                     $paymentBadge = '<span class="badge bg-secondary">Unknown</span>';
//                 }
//             }

//             $json[] = [
//                 '<span class="text-muted">#' . $sno . '</span>',
//                 '<span>Session No: ' . ($session_no ?? '-') . '</span><br>' .
//                 '<span>Total Sessions: ' . ($no_of_sessions ?? '-') . '</span><br>' .
//                 '<span>Total Fee: Rs. ' . number_format($session_fee ?? 0, 2) . '</span>',

//                 '<span class="badge bg-success">Session</span>',
//                 $session->doctor->doctor_name ?? 'Unknown',
//                 Carbon::parse($session->session_date)->format('d-m-Y'),
//                 $paymentBadge,
//                 '<span class="text-dark">OMR. ' . number_format($session->total_fee ?? 0, 2) . '</span>',
//             ];
//         }


//     return response()->json(['success' => true, 'aaData' => $json]);
// }




// public function submit_contract_payment(Request $request)
// {

//     $user_id = Auth::id();
//     $user = User::find($user_id);

//     $patient_id     = $request->patient_id;
//     $ministry_id    = $request->ministry_id;
//     $type           = $request->type;
//     $total_sessions = $request->total_sessions;
//     $total_price    = $request->total_price;
//     $apt_id         = $request->appointment_id;
//     $session_id     = $request->session_id;

//     $hasValidPayment = false;

//     try {
//         // 1. Update status (same as before)
//         if ($type === 'session') {
//             $session_list = SessionList::find($session_id);
//             if ($session_list) {
//                 $session_list->payment_status = 4;
//                 $session_list->save();
//             }

//             AllSessioDetail::where('session_id', $session_id)->update(['contract_payment' => 2]);

//             $session_detail = SessionDetail::where('session_id', $session_id)->first();
//             if ($session_detail) {
//                 $session_detail->contract_payment = 2;
//                 $session_detail->save();
//             }
//         } elseif ($type === 'appointment') {
//             $appointment = Appointment::find($apt_id);
//             $session_payment = SessionsPayment::where('appointment_id', $apt_id)->first();

//             if ($appointment) {
//                 $appointment->payment_status = 4;
//                 $appointment->save();
//             }

//             if ($session_payment) {
//                 $session_payment->contract_payment = 2;
//                 $session_payment->save();
//             }

//             AppointmentSession::where('appointment_id', $apt_id)->update(['contract_payment' => 2]);

//             $detail = AppointmentDetail::where('appointment_id', $apt_id)->first();
//             if ($detail) {
//                 $detail->contract_payment = 2;
//                 $detail->save();
//             }
//         }

//         // 2. Handle multiple payment methods (with expense logic)
//         if (is_array($request->payment_methods) && !empty($request->payment_methods)) {
//             foreach ($request->payment_methods as $index => $paymentMethodId) {
//                 // Use the $index to fetch amounts and ref_nos correctly
//                 $paidAmount = $request->payment_amounts[$paymentMethodId] ?? 0;
//                 $refNo = $request->ref_nos[$paymentMethodId] ?? null; // Get ref_no if it exists, or null if not

//                 if ($paidAmount > 0) {
//                     // Create a new payment record
//                     $payment = new SessionsPayment();
//                     $payment->appointment_id = $apt_id;
//                     $payment->contract_payment = 2;
//                     $payment->payment_status = 4;
//                     $payment->account_id = $paymentMethodId;
//                     $payment->ref_no = $refNo;
//                     $payment->amount = $paidAmount;
//                     $payment->user_id = $user_id;
//                     $payment->branch_id = $user->branch_id;
//                     $payment->added_by = $user->id;
//                     $payment->save();

//                     // Update the account balance
//                     $account = Account::find($paymentMethodId);
//                     if ($account) {
//                         $account->opening_balance += $paidAmount;
//                         $account->save();

//                         // Handle commission if applicable
//                         if ($account->account_status != 1 && !empty($account->commission) && $account->commission > 0) {
//                             $commissionFee = ($paidAmount / 100) * $account->commission;

//                             // Save payment expense
//                             $paymentExpense = new SessionPaymentExpense();
//                             $paymentExpense->total_amount = $paidAmount;
//                             $paymentExpense->account_tax = $account->commission;
//                             $paymentExpense->account_tax_fee = $commissionFee;
//                             $paymentExpense->account_id = $paymentMethodId;
//                             $paymentExpense->appointment_id = $apt_id;
//                             $paymentExpense->user_id = $user_id;
//                             $paymentExpense->branch_id = $user->branch_id;
//                             $paymentExpense->added_by = $user->id;
//                             $paymentExpense->save();
//                         }
//                     }

//                     $hasValidPayment = true;
//                 }
//             }
//         }

//         return response()->json(['message' => 'Payment recorded successfully']);
//     } catch (\Exception $e) {
//         return response()->json(['error' => 'Something went wrong. Please try again later.']);
//     }
// }

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
        // Update session_status in Appointment model based on sessions_recommended
        $appointment = Appointment::find($request->appointment_id);

        if (!$appointment) {
            return response()->json(['success' => false, 'message' => 'Appointment not found.']);
        }

        $appointment->session_status = $request->sessions_recommended == null ?  7: 1;
        $appointment->save();

        // Determine prescription_type based on session_cat and sessions_recommended
        $prescription_type = 'appointment'; // Default is 'appointment'

        // Check if session_cat and sessions_recommended are not null
        if (!is_null($request->session_cat) && !is_null($request->sessions_recommended)) {
            $prescription_type = 'session'; // Set to 'session' if both are not null
        }

        // Save new prescription
        $prescription = new PatientPrescription();
        $prescription->appointment_id = $request->appointment_id;
        $prescription->patient_id = $request->patient_id;
        $prescription->prescription_type = $prescription_type; // Set the correct prescription type
        $prescription->session_cat = $request->session_cat;
        $prescription->sessions_reccomended = $request->sessions_recommended;
        $prescription->session_gap = $request->session_gap;
        $prescription->notes = $request->notes;
        $prescription->user_id = $user_id;
        $prescription->added_by = $user_name;
        $prescription->branch_id = $branch_id;

        // Save test recommendations as a JSON array if provided
        if ($request->has('test_recommendations') && is_array($request->test_recommendations)) {
            $prescription->test_recommendations = json_encode($request->test_recommendations);  // Store as JSON
        }

        $prescription->save();

        return response()->json([
            'success' => true,
            'message' => 'Prescription saved successfully!'
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



}
