<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Doctor;
use App\Models\History;
use App\Models\Patient;
use App\Models\Speciality;
use App\Models\Appointment;
use App\Models\SessionList;
use Illuminate\Http\Request;
use App\Models\AllSessioDetail;
use App\Models\AppointmentDetail;
use App\Models\AppointmentSession;
use App\Models\SessionData;
use Illuminate\Support\Facades\DB;
use App\Models\SessionsonlyPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function doctor_list(){

        $branches = Branch::all();
        $specials= Speciality::all();
        return view ('doctors.doctors_list', compact('specials', 'branches'));
    }

    public function show_doctors()
    {
        $sno = 0;
        $doctors = Doctor::all();

        if ($doctors->count() > 0) {
            foreach ($doctors as $doctor) {
                $doctor_name = '<a class="doctor-info ps-0" href="doctor_profile/' . $doctor->id . '">' . $doctor->doctor_name . '</a>';
                $modal = '<a href="javascript:void(0);" class="me-3 edit-doctor" data-bs-toggle="modal" data-bs-target="#add_doctor_modal" onclick="edit(' . $doctor->id . ')">
                <i class="fa fa-pencil fs-18 text-success"></i>
              </a>
              <a href="javascript:void(0);" onclick="del(' . $doctor->id . ')">
                <i class="fa fa-trash fs-18 text-danger"></i>
              </a>
              <a href="' . url('calender/' . $doctor->id) . '">
                <i class="fa fa-calendar fs-18 text-danger"></i>
              </a>';


                $add_data = Carbon::parse($doctor->created_at)->format('d-m-Y (h:i a)');
                $doctor_image = $doctor->doctor_image ? asset('images/doctor_images/' . $doctor->doctor_image) : asset('images/dummy_images/no_image.jpg');
                $src = '<img src="' . $doctor_image . '" class="doctor-info ps-0" style="max-width:40px">';

                $branch = Branch::where('id', $doctor->branch_id)->value('branch_name');
                $speciality = Speciality::where('id', $doctor->specialization)->value('speciality_name');


                $sno++;
                $json[] = array(
                    '<span class="doctor-info ps-0">' . $sno . '</span>',
                    '<span class="text-nowrap ms-2">' . $src . ' ' . $doctor_name . '</span>',
                    '<span class="text-nowrap ms-2"> ' . $doctor->user_name . '</span>',
                    '<span class="text-nowrap ms-2"> ' . $speciality . '</span>',
                    '<span class="text-primary">' . $doctor->phone . '</span>',
                    '<span>' . $branch . '</span>',
                    '<span>' . $doctor->added_by . '</span>',
                    '<span>' . $add_data . '</span>',
                    $modal
                );
            }

            return response()->json(['success' => true, 'aaData' => $json]);
        }

        return response()->json(['sEcho' => 0, 'iTotalRecords' => 0, 'iTotalDisplayRecords' => 0, 'aaData' => []]);
    }

    public function add_doctor(Request $request)
    {
        $user_id = Auth::id();
        $user = Auth::user();
        $username = $user->user_name;

        $doctor_image = "";
        if ($request->hasFile('doctor_image')) {
            $folderPath = public_path('images/doctor_images');
            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }
            $doctor_image = time() . '.' . $request->file('doctor_image')->extension();
            $request->file('doctor_image')->move($folderPath, $doctor_image);
        }

        $doctor = new Doctor();
        $doctor->doctor_name = $request->input('doctor_name');
        $doctor->user_name = $request->input('user_name');
        $doctor->email = $request->input('email');
        $doctor->annual_leaves = $request->input('annual_leaves');
        $doctor->joining_date = $request->input('joining_date');
        $doctor->emergency_leaves = $request->input('emergency_leaves');
        if ($request->filled('password')) {
            $doctor->password = Hash::make($request->input('password'));
        }
        $doctor->phone = $request->input('phone');
        $doctor->specialization = $request->input('speciality');
        $doctor->doctor_image = $doctor_image;
        $doctor->branch_id = $request->input('branch_id');
        $doctor->notes = $request->input('notes');
        $doctor->added_by = $username;
        $doctor->save();

        return response()->json(['doctor_id' => $doctor->id]);
    }

    public function edit_doctor(Request $request)
    {
        $doctor = Doctor::find($request->input('id'));
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }


        $doctor_image = $doctor->doctor_image ? asset('images/doctor_images/' . $doctor->doctor_image) : asset('images/dummy_images/no_image.jpg');

        return response()->json([
            'doctor_id' => $doctor->id,
            'doctor_name' => $doctor->doctor_name,
            'user_name' => $doctor->user_name,
            'password' => $doctor->password,
            'email' => $doctor->email,
            'phone' => $doctor->phone,
            'annual_leaves' => $doctor->annual_leaves,
            'emergency_leaves' => $doctor->emergency_leaves,
            'specialization' => $doctor->specialization,
            'joining_date' => $doctor->joining_date,
            'doctor_image' => $doctor_image,
            'branch_id' => $doctor->branch_id,
            'notes' => $doctor->notes,
        ]);
    }

    public function update_doctor(Request $request)
    {
        $user = Auth::user();
        $username = $user->user_name;
        $branch = $user->branch_id;
        $user_id = $user->id;

        $doctor = Doctor::find($request->input('doctor_id'));
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }

        // Store previous data before updating
        $previousData = $doctor->only([
            'doctor_name', 'user_name', 'phone', 'email', 'password', 'doctor_image',
            'branch_id', 'specialization','annual_leaves','emergency_leaves', 'notes', 'user_id', 'added_by', 'created_at'
        ]);

        // Handle doctor image update
        if ($request->hasFile('doctor_image')) {
            $oldImagePath = public_path('images/doctor_images/' . $doctor->doctor_image);
            if (File::exists($oldImagePath) && !empty($doctor->doctor_image)) {
                File::delete($oldImagePath);
            }

            $folderPath = public_path('images/doctor_images');
            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }

            $doctor_image = time() . '.' . $request->file('doctor_image')->extension();
            $request->file('doctor_image')->move($folderPath, $doctor_image);
        } else {
            $doctor_image = $doctor->doctor_image; // Keep existing image
        }

        // Update doctor details
        $doctor->doctor_name = $request->input('doctor_name');
        $doctor->user_name = $request->input('user_name');
        $doctor->email = $request->input('email');

        // Only update password if a new one is provided
        if ($request->filled('password')) {
            $doctor->password = Hash::make($request->input('password'));
        }
        $doctor->annual_leaves = $request->input('annual_leaves');
        $doctor->emergency_leaves = $request->input('emergency_leaves');
        $doctor->joining_date = $request->input('joining_date');

        $doctor->phone = $request->input('phone');
        $doctor->specialization = $request->input('speciality');
        $doctor->doctor_image = $doctor_image;
        $doctor->branch_id = $request->input('branch_id');
        $doctor->notes = $request->input('notes');
        $doctor->added_by = $username;
        $doctor->save();

        // Store updated data for history
        $updatedData = $doctor->only([
            'doctor_name', 'user_name', 'phone', 'email', 'doctor_image',
            'branch_id', 'specialization', 'notes', 'annual_leaves', 'emergency_leaves', 'user_id', 'added_by'
        ]);

        // Save update history
        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'doctors'; // Corrected table name
        $history->function = 'update';
        $history->function_status = 1;
        $history->branch_id = $branch;
        $history->record_id = $doctor->id;
        $history->previous_data = json_encode($previousData);
        $history->updated_data = json_encode($updatedData);
        $history->added_by = $username;
        $history->save();

        return response()->json(['success' => 'Doctor updated successfully']);
    }

    public function delete_doctor(Request $request)
    {
        $doctor = Doctor::find($request->input('id'));
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }

        // Store previous data before deletion
        $previousData = $doctor->only([
            'doctor_name', 'user_name', 'phone', 'email', 'doctor_image',
            'branch_id', 'specialization', 'notes', 'annual_leaves', 'emergency_leaves', 'user_id', 'added_by', 'created_at'
        ]);

        // Get current user info
        $currentUser = Auth::user();
        $username = $currentUser->user_name;
        $branch = $currentUser->branch_id;
        $user_id = $currentUser->id;

        // Delete doctor image if it exists
        if (!empty($doctor->doctor_image)) {
            $imagePath = public_path('images/doctor_images/' . $doctor->doctor_image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        // Save delete history
        $history = new History();
        $history->user_id = $user_id;
        $history->table_name = 'doctors'; // Corrected table reference
        $history->branch_id = $branch;
        $history->function = 'delete';
        $history->function_status = 2; // Status for delete
        $history->record_id = $doctor->id;
        $history->previous_data = json_encode($previousData);
        $history->added_by = $username;
        $history->save();

        // Delete doctor record
        $doctor->delete();

        return response()->json(['success' => 'Doctor deleted successfully']);
    }

    public function doctor_profile($id){
        $doctor= Doctor::where('id', $id)->first();
        $branch= Branch::where('id', $doctor->branch_id)->value('branch_name');
        $special= Speciality::where('id', $doctor->specialization)->value('speciality_name');
        $total_sessions= SessionData::where('doctor_id', $id)->where('status', 2)->count();
        $all_appointment_patients = Appointment::where('doctor_id', $id)
        ->select('patient_id')
        ->distinct()
        ->count();
        $all_d_session_patients = SessionList::where('doctor_id', $id)
        ->select('patient_id')
        ->distinct()
        ->count();
        $total_patient=  $all_d_session_patients + $all_appointment_patients;

        $appointments= Appointment::where('doctor_id', $id)->get();
        $total_apt= $appointments->count();

        return view ('doctors.doctor_profile', compact('doctor', 'special', 'total_patient', 'total_apt', 'total_sessions', 'branch'));
    }



    public function getDoctorAppointments($doctorId)
    {
        $today = now()->startOfDay();
        $thirtyDaysLater = now()->addDays(30)->endOfDay();

        // Appointments within the next 30 days
        $appointments = Appointment::with('patient:id,full_name')
            ->where('doctor_id', $doctorId)
            ->whereIn('session_status', [2, 5])
            ->whereBetween('appointment_date', [$today, $thirtyDaysLater])
            ->orderByDesc('updated_at')
            ->orderByDesc('created_at')
            ->take(3)
            ->get()
            ->map(function ($session) {
                return [
                    'id' => $session->id,
                    'patient_id' => $session->patient->id ?? null,
                    'patient_name' => $session->patient->full_name ?? 'Unknown',
                    'date' => $session->appointment_date,
                    'appointment_no' => '<span class="badge text-white px-2 py-1 text-sm" style="background-color: #081339;">'
                                        . $session->appointment_no
                                        . '</span>',
                    'time' => '<span class="badge light badge-secondary text-dark px-2 py-1 text-sm">'
                              . date('h:ia', strtotime($session->time_from))
                              . ' to '
                              . date('h:ia', strtotime($session->time_to))
                              . '</span>',
                    'created_at' => $session->created_at,
                    'updated_at' => $session->updated_at
                ];
            });

        // Sessions within the next 30 days
        $sessions = SessionData::select(
                'session_data.*',
                'patients.full_name as patient_name',
                DB::raw('CASE
                            WHEN session_data.main_session_id IS NOT NULL THEN session_data.main_session_id
                            ELSE session_data.main_appointment_id
                        END as main_id'),
                DB::raw('CASE
                            WHEN session_data.main_session_id IS NOT NULL THEN 11
                            ELSE 10
                        END as source_flag')
            )
            ->join('patients', 'session_data.patient_id', '=', 'patients.id')
            ->where('session_data.doctor_id', $doctorId)
            ->where('session_data.status', 4)
            ->whereBetween('session_data.session_date', [$today, $thirtyDaysLater])
            ->get();

        return response()->json([
            'appointments' => $appointments,
            'sessions' => $sessions
        ]);
    }






public function show_doctor_patients(Request $request)
{

    $doctorId = $request->input('doctor_id');


    $appointments= Appointment::where('doctor_id', $doctorId)->get();
    $total_apt= $appointments->count();


    $sno = 0;


    if ($appointments->count() > 0) {
        foreach ($appointments as $patient) {

            $total_sessions = (int) AppointmentDetail::where('appointment_id', $patient->id)->value('total_sessions');
            $statusClass = '';
            $statusText = '';
            $statusIcon = '';
            $modal2 = '';

            if ($patient->session_status == 1) {
                $statusClass = 'badge-danger';
                $statusText = 'Sessions Recommended';
                $statusIcon = '<i class="fa fa-exclamation-circle"></i> ';
                $modal2 = '<span class="badge ' . $statusClass . ' px-2 py-1" style="cursor: pointer;">' . $statusIcon . $statusText . '</span>';
            } elseif ($patient->session_status == 2) {
                $statusClass = 'badge-warning';
                $statusText = 'Appointment';
                $statusIcon = '<i class="fa fa-calendar"></i> ';
                $modal2 = '<span class="badge ' . $statusClass . ' px-2 py-1">' . $statusIcon . $statusText . '</span>';
            } elseif ($patient->session_status == 3) {
                $statusText = 'Sessions: ';
                $statusIcon = '<i class="fa fa-list"></i> ';
                $modal2 = '<span class="badge badge-primary px-2 py-1">' . $statusIcon . $statusText . $total_sessions . '</span>';
            } elseif ($patient->session_status == 4) {
                $statusClass = 'badge-dark';
                $statusText = 'Cancelled';
                $statusIcon = '<i class="fa fa-times-circle"></i> ';
                $modal2 = '<span class="badge ' . $statusClass . ' px-2 py-1">' . $statusIcon . $statusText . '</span>';
            } elseif ($patient->session_status == 5) {
                $statusClass = 'badge-info';
                $statusText = 'Pre-Registered';
                $statusIcon = '<i class="fa fa-user-plus"></i> ';
                $modal2 = '<span class="badge ' . $statusClass . ' px-2 py-1">' . $statusIcon . $statusText . '</span>';
            }
            elseif ($patient->session_status == 7) {
                $statusClass = 'badge-info';
                $statusText = 'Appointment Done';
                $statusIcon = '<i class="fa fa-check-circle"></i> ';
                $modal2 = '<span class="badge ' . $statusClass . ' px-2 py-1">' . $statusIcon . $statusText . '</span>';
            }


            $full_name= Patient::where('id', $patient->patient_id)->value('full_name');
            $patient_name = '<a class="patient-info ps-0" href="javascript:void(0);">' . $full_name . '</a>';
            $add_data = Carbon::parse($patient->created_at)->format('d-m-Y (h:i a)');

            $sno++;
            $json[] = array(
                '<span class="patient-info ps-0">' . $patient->appointment_no . '</span>',
                '<span class="text-primary">' .$patient_name. '</span>',
                '<span class="badge bg-primary"><i class="fas fa-phone-alt"></i> ' . $patient->appointment_date . '</span>',
                $modal2
            );
        }

        return response()->json(['success' => true, 'aaData' => $json]);
    }

    return response()->json(['sEcho' => 0, 'iTotalRecords' => 0, 'iTotalDisplayRecords' => 0, 'aaData' => []]);
}

public function show_all_sessions_by_doctor(Request $request)
{
    $doctorId = $request->input('doctor_id');
    $json = [];
    $sno = 0;

    // Get appointment sessions
    $appointmentSessions = DB::table('appointment_sessions')
        ->where('doctor_id', $doctorId)
        ->select(
            'id',
            'session_time',
            'patient_id',
            'session_date',
            'session_time',
            'status',
            DB::raw("'appointment_sessions' as source")
        )
        ->get();

    // Get all session details
    $allSessions = DB::table('all_sessio_details')
        ->where('doctor_id', $doctorId)
        ->select(
            'id',
            DB::raw("'' as session_time"),
            'patient_id',
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
        $patientName = DB::table('patients')->where('id', $session->patient_id)->value('full_name');

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
            '<span>' . ($patientName ?? 'Unknown') . '</span>',
            '<span>' . $session->session_time . '</span>',
            '<span class="badge ' . $badgeColor . '">' . $sourceText . '</span>',
            '<span class="badge ' . $statusBadgeColor . '">' . $statusText . '</span>', // Add status badge
        ];
    }

    return response()->json(['success' => true, 'aaData' => $json]);
}






}
