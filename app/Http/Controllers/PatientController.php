<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Country;
use App\Models\History;
use App\Models\Patient;
use App\Models\Setting;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\AllSessioDetail;
use App\Models\AppointmentDetail;
use App\Models\AppointmentSession;
use App\Models\SessionDetail;
use App\Models\SessionList;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class PatientController extends Controller
{
    public function patient_list(){

        $countries= Country::all();
        return view ('patients.patients_list', compact( 'countries'));
    }



    public function patient_profile($id) {
        $patient = Patient::where('id', $id)->first();

        // Check if dob exists and calculate the age in years, months, and days
        if ($patient && $patient->dob) {
            $dob = Carbon::parse($patient->dob);
            $now = Carbon::now();

            // Calculate the age in years, months, and days
            $ageInYears = $dob->diffInYears($now);
            $ageInMonths = $dob->diffInMonths($now) % 12;
            $ageInDays = $dob->diffInDays($now) % 30;

            // Logic to return appropriate age format
            if ($ageInYears >= 1) {
                $age = "$ageInYears years";
            } elseif ($ageInMonths >= 1) {
                $age = "$ageInMonths months";
            } else {
                $age = "$ageInDays days";
            }
        } else {
            $age = 'N/A'; // If no DOB, set age to N/A
        }

        return view('patients.patient_profile', compact('patient', 'age'));
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
                $statusText = 'Sessions: ';
                $statusIcon = '<i class="fa fa-list"></i> ';
                $badge = '<span class="badge badge-primary px-2 py-1">' . $statusIcon . $statusText. '</span>';
            } elseif ($appointment->session_status == 4) {
                $statusClass = 'badge-dark';
                $statusText = 'Cancelled';
                $statusIcon = '<i class="fa fa-times-circle"></i> ';
                $badge = '<span class="badge ' . $statusClass . ' px-2 py-1">' . $statusIcon . $statusText . '</span>';
            } elseif ($appointment->session_status == 5) {
                $statusClass = 'badge-info';
                $statusText = 'Pre-Registered';
                $statusIcon = '<i class="fa fa-user-plus"></i> ';
                $badge = '<span class="badge ' . $statusClass . ' px-2 py-1">' . $statusIcon . $statusText . '</span>';
            } else {
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
            'fee' => $appointment->total_fee,
            'session_count' => $appointment->total_sessions, // Assuming a relationship exists
            'single_session_fee' => ($appointment->total_sessions > 0)
                ? $appointment->total_fee / $appointment->total_sessions
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


public function show_all_payment_by_patient(Request $request)
{
    $patient_id = $request->input('patient_id');
    $appointmentsAndSessions = [];

    $appointmentSessions = AppointmentSession::where('patient_id', $patient_id)
        ->with('doctor:id,doctor_name')
        ->orderBy('session_date', 'desc')
        ->get();


    $allSessions = AllSessioDetail::where('patient_id', $patient_id)
        ->with('doctor:id,doctor_name')
        ->orderBy('session_date', 'desc')
        ->get();


        foreach ($appointmentSessions as $appointment) {
            $apt = Appointment::where('id', $appointment->appointment_id)->first();
            $apt_no = $apt->appointment_no;
            $paymen_status = $apt->payment_status;

            if ($paymen_status == 0 || $paymen_status == 1) {
                $paymen_status = 'Normal Session';
                $paymentBadge = '<span class="badge bg-success">Completed</span>';
            } elseif ($paymen_status == 2) {
                $paymen_status = 'Offer';
                if ($appointment->status == 2) {
                    $paymentBadge = '<span class="badge bg-success">Completed</span>';
                } else {
                    $paymentBadge = '<span class="badge bg-warning">Balance</span>';
                }
            } elseif ($paymen_status == 3) {
                $paymen_status = 'Contract';
                if ($appointment->status == 4) {
                    $paymentBadge = '<span class="badge bg-success">Completed</span>';
                } else {
                    $paymentBadge = '<span class="badge bg-danger">Pending</span>';
                }
            } else {
                $paymen_status = 'Unknown';
                $paymentBadge = '<span class="badge bg-secondary">Unknown</span>';
            }

            $appointmentsAndSessions[] = [
                'type' => 'appointment',
                'doctor_name' => $appointment->doctor->doctor_name ?? 'Unknown',
                'session_date' => Carbon::parse($appointment->session_date)->format('d-m-Y'),
                'status' => $appointment->status,
                'source' => 'appointment_sessions',
                'payment_status_badge' => $paymentBadge,
            ];
        }


        foreach ($allSessions as $session) {
            $session_check = SessionList::where('id', $session->session_id)->first();
            $session_no = $session_check->session_no;
            $paymen_status = $session_check->payment_status;

            if ($paymen_status == 0 || $paymen_status == 1) {
                $paymen_status = 'Normal Session';
                $paymentBadge = '<span class="badge bg-success">Completed</span>';
            } elseif ($paymen_status == 2) {
                $paymen_status = 'Offer';
                if ($session->status == 2) {
                    $paymentBadge = '<span class="badge bg-success">Completed</span>';
                } else {
                    $paymentBadge = '<span class="badge bg-warning">Balance</span>';
                }
            } elseif ($paymen_status == 3) {
                $paymen_status = 'Contract';
                if ($session->status == 4) {
                    $paymentBadge = '<span class="badge bg-success">Completed</span>';
                } else {
                    $paymentBadge = '<span class="badge bg-danger">Pending</span>';
                }
            } else {
                $paymen_status = 'Unknown';
                $paymentBadge = '<span class="badge bg-secondary">Unknown</span>';
            }

            $appointmentsAndSessions[] = [
                'type' => 'session',
                'appointment_no' => $session_no,
                'doctor_name' => $session->doctor->doctor_name ?? 'Unknown',
                'fee' => $session->total_fee,
                'session_count' => $session->total_sessions,
                'single_session_fee' => ($session->total_sessions > 0)
                    ? $session->total_fee / $session->total_sessions
                    : 0,
                'session_date' => Carbon::parse($session->session_date)->format('d-m-Y'),
                'status' => $session->status,
                'source' => 'all_sessio_details',
                'payment_status_badge' => $paymentBadge,
            ];
        }



    return response()->json($appointmentsAndSessions);
}


}
