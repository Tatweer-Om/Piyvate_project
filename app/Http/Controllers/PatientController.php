<?php

namespace App\Http\Controllers;

use Log;
use App\Models\User;
use App\Models\Offer;
use App\Models\Branch;
use App\Models\Doctor;
use App\Models\Account;
use App\Models\Country;
use App\Models\History;
use App\Models\Patient;
use App\Models\Setting;
use App\Models\Voucher;
use App\Models\GovtDept;
use App\Models\Appointment;
use App\Models\SessionData;
use App\Models\SessionList;
use Illuminate\Support\Str;
use App\Models\Patientfiles;
use Illuminate\Http\Request;
use App\Models\ClinicalNotes;
use App\Models\SessionDetail;
use Illuminate\Support\Carbon;
use App\Models\AllSessioDetail;
use App\Models\SessionsPayment;
use App\Models\AppointmentDetail;
use App\Models\AppointmentPayment;
use App\Models\AppointmentSession;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\PatientPrescription;
use App\Models\SessionsonlyPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\AppointPaymentExpense;
use App\Models\SessionPaymentExpense;
use PHPUnit\Framework\returnValueMap;

use App\Models\SessionsonlyPaymentExp;
use Illuminate\Contracts\Session\SessionnValueMap;
use Symfony\Component\HttpFoundation\Session\Session;

class PatientController extends Controller
{
    public function patient_list()
    {

        $countries = Country::all();
        return view('patients.patients_list', compact('countries'));
    }


    public function patient_profile($id, Request $request)
    {


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

        $accounts = Account::where('branch_id',   $branch_id)->where('account_type', 1)->get();
        $patient = Patient::where('id', $id)->first();
        $country = $patient->country_id ?? '';
        $country_name = Country::where('id', $country)->value('name');

        $apt = Appointment::where('patient_id', $patient->id)->latest()->first();
        $total_apt = Appointment::where('patient_id', $patient->id)->count();
        $apt_id = $apt->id ?? '';
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
            $payment = SessionsonlyPayment::where('session_id', $firstSession->session_id)->value('amount');
            $ministry_name = GovtDept::where('id', $firstSession->ministry_id)->value('govt_name');

            $ministry_data = [
                'type' => 'session',
                'id' => $firstSession->id,
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
        $doctors = Doctor::all();
        $patients = Patient::all();


        $check1 = AppointmentDetail::where('patient_id', $id)
            ->whereNotNull('ministry_id')->where('contract_payment', 1)->first();



        $check2 = SessionDetail::where('patient_id', $id)
            ->whereNotNull('ministry_id')->where('contract_payment', 1)->first();


        // Merge both filtered sets


        return view('patients.patient_profile', compact('patient', 'check1', 'check2', 'doctors', 'patients', 'notes', 'patient_total_sessions', 'total_session_taken', 'total_active_session', 'total_apt', 'country_name', 'accounts', 'apt', 'apt_id', 'age', 'ministry_name', 'ministry_data'));
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
                    '<span class="text-primary">' . $patient_name . '</span>',
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
            'title',
            'first_name',
            'second_name',
            'mobile',
            'id_passport',
            'dob',
            'country',
            'details',
            'added_by',
            'created_at'
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
            'title',
            'first_name',
            'second_name',
            'mobile',
            'gender',
            'age',
            'HN',
            'id_passport',
            'dob',
            'country',
            'details',
            'added_by'
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

        $id = $request->input('id');
        $patient = Patient::where('id', $id)->first();
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        // Store previous data before deletion
        $previousData = $patient->only([
            'title',
            'first_name',
            'second_name',
            'gender',
            'age',
            'HN',
            'mobile',
            'id_passport',
            'dob',
            'country',
            'details',
            'added_by',
            'created_at'
        ]);

        // Get current user info
        $currentUser = Auth::user();
        $username = $currentUser->user_name;
        $user_id = $currentUser->id;
        $branch_id = $currentUser->branch_id;


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




    public function appointmentsdetail($id)
    {


        $appointments = Appointment::where('patient_id', $id)
            ->with('doctor:id,doctor_name')
            ->orderBy('appointment_date', 'desc')
            ->get();



        foreach ($appointments as $appointment) {


            $clinic_notes = ClinicalNotes::where('appointment_id', $appointment->id)->get();
            $data = SessionData::where('main_appointment_id', $appointment->id)->get();
            $appointment->total_sessions = $data->count();
            $appointment->pt_sessions = $data->where('session_cat', 'PT')->count();
            $appointment->ot_sessions = $data->where('session_cat', 'OT')->count();
            $appointment->session_taken = $data->where('status', 2)->count();
            $appointment->session_remain = $data->where('status', 1)->count();
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

            // Test recommendations
            $prescription = PatientPrescription::where('appointment_id', $appointment->id)->first();
            $appointment->prescription_notes = $prescription->notes ?? '';
            $appointment->notes = $appointment->notes ?? '';

            $appointment->test_recommendations = $prescription ? json_decode($prescription->test_recommendations, true) : [];
        }

        return response()->json($appointments);
    }



    public function patient_session($id, Request $request)
    {

        $main_id = $id;
        $source = $request->query('source');

        $user = Auth::user();
        $branch_id = $user->branch_id;


        //11 for session and 10 for appointment
        $source = (int) $source;


        if ($source === 11) {
            $baseQuery = SessionData::where('main_session_id', $main_id);
        }
        // Check if source is 'appointment'
        elseif ($source === 10) {
            $baseQuery = SessionData::where('main_appointment_id', $main_id);
        }

        // If neither session nor appointment, you can return an error or empty data:
        else {
            return response()->json(['message' => 'Invalid source'], 400);
        }
        // Get sessions and patient ID
        $session = $baseQuery->get();
        $firstSession = $baseQuery->first();

        if (!$firstSession) {
            return response()->json(['message' => 'No session found'], 404);
        }

        $patient_id = $firstSession->patient_id;
        $patient = Patient::find($patient_id);
        $country_name = $patient && $patient->country_id
            ? Country::where('id', $patient->country_id)->value('name')
            : '';

        // Age calculation
        $age = 'N/A';

        if ($patient && $patient->dob) {
            $dob = Carbon::parse($patient->dob);
            $now = Carbon::now();

            $diff = $dob->diff($now); // Use diff() to get Y/M/D together

            $years = $diff->y;
            $months = $diff->m;
            $days = $diff->d;

            $parts = [];
            if ($years > 0) {
                $parts[] = "$years " . Str::plural('year', $years);
            }
            if ($months > 0) {
                $parts[] = "$months " . Str::plural('month', $months);
            }
            if ($days > 0) {
                $parts[] = "$days " . Str::plural('day', $days);
            }

            $age = !empty($parts) ? implode(' ', $parts) : '0 days';
        }
        // Session statistics
        $patient_total_sessions = (clone $baseQuery)->count();
        $total_session_taken = (clone $baseQuery)->where('status', 2)->count();
        $total_active_session = (clone $baseQuery)->where('status', 1)->count();

        $ot_sessions = (clone $baseQuery)->where('session_cat', 'OT')->count();
        $pt_sessions = (clone $baseQuery)->where('session_cat', 'PT')->count();

        $ot_sessions_taken = (clone $baseQuery)->where('session_cat', 'OT')->where('status', 2)->count();
        $pt_sessions_taken = (clone $baseQuery)->where('session_cat', 'PT')->where('status', 2)->count();

        $ot_sessions_pending = $ot_sessions - $ot_sessions_taken;
        $pt_sessions_pending = $pt_sessions - $pt_sessions_taken;

        $doctors = Doctor::all();
        $patients = Patient::all();

        return view('patients.patient_session', compact(
            'patient',
            'session',
            'main_id',
            'source',
            'patient_id',
            'patients',
            'doctors',
            'ot_sessions_pending',
            'pt_sessions_pending',
            'ot_sessions_taken',
            'pt_sessions_taken',
            'ot_sessions',
            'pt_sessions',
            'patient_total_sessions',
            'total_session_taken',
            'total_active_session',
            'country_name',
            'age'
        ));
    }

    public function show_all_sessions_by_patient(Request $request)
    {
        $source = $request->source;
        $main_id = $request->main_id;

        $json = [];
        $sno = 0;

        $source = (int) $source;


        $baseQuery = null;

        if ($source === 11) {
            $baseQuery = SessionData::where('main_session_id', $main_id);
        } elseif ($source === 10) {
            $baseQuery = SessionData::where('main_appointment_id', $main_id);
        }

        $sessions = $baseQuery ? $baseQuery->get() : collect();


        foreach ($sessions as $session) {
            $sno++;

            // Optional: Get patient name if needed
            $doctor_name = DB::table('doctors')->where('id', $session->doctor_id)->value('doctor_name');

            $badgeColor = 'bg-warning'; // Default badge color for source


            if ($session->source == 1) {
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
            } elseif ($session->status == 4) {
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
                // if ($sessionDate == $today) {
                //     if ($session->session_cat === 'OT') {
                //         $url = url('soap_ot/' . $session->id);
                //         $img = asset('images/logo/1.png');
                //     } elseif ($session->session_cat === 'PT') {
                //         $url = url('soap_pt/' . $session->id);
                //         $img = asset('images/logo/2.png');
                //     } else {
                //         $url = '#';
                //         $img = asset('images/logo/default.png');
                //     }

                //     $modal .= '
                //     <a href="' . $url . '" class="me-3 text-decoration-none text-dark" title="Edit Session" data-bs-toggle="tooltip">
                //         <img src="' . $img . '" class="rounded-circle shadow-sm mb-2" style="width: 30px; height: 30px; object-fit: cover;">
                //     </a>';
                // }
            } elseif (!in_array($session->status, [1, 3])) {
                // For all other statuses except On-going (4), show only the image
                if ($session->session_cat === 'OT') {
                    $url = url('soap_ot/' . $session->id);
                    $img = asset('images/logo/2.png');
                } elseif ($session->session_cat === 'PT') {
                    $url = url('soap_pt/' . $session->id);
                    $img = asset('images/logo/1.png');
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


    // PatientController.php

    public function session_transfer($id)
    {


        $transfer_logs = DB::table('session_transfer_logs')
            ->where(function ($query) use ($id) {
                $query->where('old_patient_id', $id)
                    ->orWhere('new_patient_id', $id);
            })
            ->get();

        $patientIds = $transfer_logs->pluck('old_patient_id')
            ->merge($transfer_logs->pluck('new_patient_id'))
            ->unique()
            ->toArray();

        $sessionIds = $transfer_logs->pluck('session_id')->unique()->toArray();

        // Get patient names
        $patients = DB::table('patients')
            ->whereIn('id', $patientIds)
            ->pluck('full_name', 'id'); // returns [id => name]

        $sessions = DB::table('session_data')
            ->whereIn('id', $sessionIds)
            ->get(['id', 'session_date', 'session_time']) // get all needed columns
            ->keyBy('id'); // returns [id => session_data]

        // Attach names and session data to each log
        $transfer_logs = $transfer_logs->map(function ($log) use ($patients, $sessions) {
            $log->old_patient_name = $patients[$log->old_patient_id] ?? 'Unknown';
            $log->new_patient_name = $patients[$log->new_patient_id] ?? 'Unknown';
            $log->session_date = $sessions[$log->session_id]->session_date ?? 'No date';
            $log->session_time = $sessions[$log->session_id]->session_time ?? 'No time';
            $log->created_at_date = \Carbon\Carbon::parse($log->created_at)->format('Y-m-d');
            $log->added_by_name = $log->transferred_by ?? 'Unknown';  // directly from table
            return $log;
        });

        return response()->json($transfer_logs);
    }





    public function getAppointmentsAndSessions($id)
    {
        $appointments = AppointmentDetail::where('patient_id', $id)
            ->with('doctor:id,doctor_name')
            ->orderBy('created_at', 'desc')
            ->get();

        $sessions = SessionDetail::where('patient_id', $id)
            ->with('doctor:id,doctor_name')
            ->orderBy('created_at', 'desc')
            ->get();

        $appointmentsAndSessions = [];

        foreach ($appointments as $appointment) {
            $payments = SessionsPayment::where('appointment_id', $appointment->appointment_id)->where('contract_payment', 2)->get();
            $detail = AppointmentDetail::where('appointment_id', $appointment->appointment_id)->first();

            $name = '';
            if ($detail->offer_id) {
                $name = Offer::where('id', $detail->offer_id)->value('offer_name');
            }
            if ($detail->ministry_id) {
                $name = GovtDept::where('id', $detail->ministry_id)->value('govt_name');
            }

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
            $taken_session = SessionData::where('main_appointment_id', $appointment->appointment_id)->where('status', 2)->count();
            $pending = SessionData::where('main_appointment_id', $appointment->appointment_id)->where('status', 1)->count();
            $ot = SessionData::where('main_appointment_id', $appointment->appointment_id)->where('session_cat', 'OT')->count();
            $pt = SessionData::where('main_appointment_id', $appointment->appointment_id)->where('session_cat', 'PT')->count();

            $appointmentsAndSessions[] = [
                'type' => 'appointment',
                'id' => $appointment->appointment_id,
                'appointment_no' => $apt_no,
                'taken_session' => $taken_session,
                'pending' => $pending,
                'ot' => $ot,
                'pt' => $pt,
                'fee' => $appointment->total_price ?? 0,
                'paid_amount' => $total_paid_amount,
                'account_amounts' => $account_amounts,
                'payment_type' => $detail->session_type,
                'name' => $name,
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

            $name = '';
            if ($detail->offer_id) {
                $name = Offer::where('id', $detail->offer_id)->value('offer_name');
            }
            if ($detail->ministry_id) {
                $name = GovtDept::where('id', $detail->ministry_id)->value('govt_name');
            }

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
            $taken_session = SessionData::where('main_session_id', $session->session_id)->where('status', 2)->count();
            $pending = SessionData::where('main_session_id', $session->session_id)->where('status', 1)->count();
            $ot = SessionData::where('main_session_id', $session->session_id)->where('session_cat', 'OT')->count();
            $pt = SessionData::where('main_session_id', $session->session_id)->where('session_cat', 'PT')->count();


            $appointmentsAndSessions[] = [
                'type' => 'session',
                'id' => $session->session_id,
                'appointment_no' => $session_no,
                'fee' => $session->total_fee ?? 0,
                'paid_amount' => $total_paid_amount,
                'taken_session' => $taken_session,
                'pending' => $pending,
                'ot' => $ot,
                'pt' => $pt,
                'account_amounts' => $account_amounts,
                'payment_type' => $detail->session_type,
                'name' => $name,
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

                            $payment = new SessionsPayment();
                            $payment->appointment_id = $apt_id;
                        } else {


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
            $appointment = Appointment::where('id', $request->appointment_id)
                ->first();

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
        $branch_id = $data->branch_id;

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


        $session = SessionData::where('id', $id)->first();


        if ($session) {

            $doctor = Doctor::where('id', $session->doctor_id)->value('id');
            $patient = Patient::where('id', $session->patient_id)->first();

            // Return the session data as JSON
            return response()->json([
                'patient' => $patient->full_name,
                'patient_id' => $patient->id,
                'session_cat' => $session->session_cat,
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



        $session = SessionData::where('id', $id)->first();


        if (!$session) {
            return response()->json(['message' => 'Session not found!'], 404);
        }


        $fieldsToUpdate = [
            'patient_id' => $request->input('patient_id'),
            'session_date' => $request->input('session_date'),
            'session_time' => $request->input('session_time'),
            'doctor_id' => $request->input('doctor'),
            'session_cat' => $request->input('session_cat')

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
        $session = SessionData::where('id', $id)->first();

        if ($session) {

            $doctor = Doctor::where('id', $session->doctor_id)->value('id');
            $patient = Patient::where('id', $session->patient_id)->first();

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

        if ($old_patient_id == $new_patient_id) {

            return response()->json(['status' => 9]);
        }


        $user = Auth::user();
        $username = $user->user_name;
        $branch = $user->branch_id;
        $user_id = $user->id;

        $session = SessionData::where('id', $id)->first();
        if ($session) {
            // Update patient_id
            DB::table('session_data')
                ->where('id', $id)
                ->update([
                    'patient_id' => $new_patient_id,
                    'status' => 3,

                ]);

            DB::table('session_transfer_logs')->insert([
                'session_id' => $id,

                'old_patient_id' => $old_patient_id,
                'new_patient_id' => $new_patient_id,
                'transferred_by' => $username, // assuming user is authenticated
                'user_id' =>   $user_id,
            ]);


            $lastSession = SessionList::latest()->first();
            $lastSessionNo = $lastSession ? $lastSession->session_no : null;

            // Get current month and year
            $month = Carbon::now()->format('n'); // No leading zero
            $year = Carbon::now()->format('y');  // Last two digits of year

            if ($lastSessionNo) {
                // Extract parts
                preg_match('/(\d+)(\d{2})S-(\d+)/', $lastSessionNo, $matches);
                $lastMonth = $matches[1] ?? null;
                $lastYear = $matches[2] ?? null;
                $lastCount = $matches[3] ?? 0;

                // If same month and year, increment the sequence, else start from 1
                $newCount = ($lastMonth == $month && $lastYear == $year) ? ((int)$lastCount + 1) : 1;
            } else {
                $newCount = 1;
            }

            // Format to 3 digits with leading zeros
            $newSessionNo = $month . $year . 'S-' . str_pad($newCount, 3, '0', STR_PAD_LEFT);
            $patient_check = Patient::where('id', $new_patient_id)->first();
            $session1 = new SessionList();
            $session1->doctor_id = $session->doctor_id;
            $session1->patient_id = $patient_check->id;
            $session1->session_no = $newSessionNo;
            $session1->HN = $patient_check->HN;
            $session1->session_type = 'normal';
            $session1->session_fee = $session->session_price;
            $session1->no_of_sessions = 1;
            $session1->session_cat = $session->session_cat;
            if ($session1->session_cat == 'OT') {
                $session1->ot_sessions = 1;
            } else {
                $session1->pt_sessions = 1;
            }
            $session1->payment_status = 1;
            $session1->session_date = $session->session_date;
            $session1->user_id = $user_id;
            $session1->added_by = $username;
            $session1->branch_id = $branch;

            $session1->save();
            $appointment = new SessionDetail();

            $session_data = SessionList::find($request->session_id);
            $appointment->session_id = $session1->id;
            $appointment->patient_id = $session1->patient_id;
            $appointment->doctor_id = $session1->doctor_id;
            $appointment->total_fee = $session1->session_fee;
            $appointment->session_data = 1;

            $appointment->session_type = $session1->session_type;
            $appointment->total_sessions =  $session1->no_of_sessions;

            $appointment->session_cat = $session1->session_cat;
            $appointment->ot_sessions = $session1->ot_sessions ?? 0;
            $appointment->pt_sessions = $session1->pt_sessions ?? 0;
            $appointment->contract_payment = 1;
            $appointment->single_session_price = $session1->session_fee;
            $appointment->user_id = $user->id;
            $appointment->added_by = $user->id;
            $appointment->branch_id = $user->branch_id;
            $appointment->save();

            return response()->json(['message' => 'Session transferred and logged successfully!']);
        } else {
            return response()->json(['message' => 'Session not found!'], 404);
        }
    }




    public function download($file_id)

    {

        $file = Patientfiles::where('id', $file_id)->first();  // adjust to your actual model

        $filePath = public_path('images/lab_reports/' . $file->file_name);

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            abort(404, 'File not found');
        }
    }





    public function payment_history($id)
    {
        $appointments = AppointmentDetail::where('patient_id', $id)
            ->with('doctor:id,doctor_name')
            ->orderBy('created_at', 'desc')
            ->get();
        $sessions = SessionDetail::where('patient_id', $id)
            ->with('doctor:id,doctor_name')
            ->orderBy('created_at', 'desc')
            ->get();



        $appointmentsAndSessions = [];

        foreach ($appointments as $appointment) {
            $payments = SessionsPayment::where('appointment_id', $appointment->appointment_id)->whereNotNull('account_id')->where('contract_payment', 1)->get();
            $detail = AppointmentDetail::where('appointment_id', $appointment->appointment_id)->first();

            $name = '';
            if ($detail->offer_id) {
                $name = Offer::where('id', $detail->offer_id)->value('offer_name');
            }
            if ($detail->ministry_id) {
                $name = GovtDept::where('id', $detail->ministry_id)->value('govt_name');
            }

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


            $appointment_fee = Appointment::where('id', $appointment->appointment_id)->value('appointment_fee') ?? '';
            $appointmentPayments = AppointmentPayment::where('appointment_id', $appointment->appointment_id)->get();

            $appointment_paid_amount = 0;
            $appointment_account_amounts = [];

            foreach ($appointmentPayments as $payment) {
                $appointment_paid_amount += $payment->amount ?? 0;

                if ($payment->account_id) {
                    $account_name = Account::where('id', $payment->account_id)->value('account_name');
                    if ($account_name) {
                        if (!isset($appointment_account_amounts[$account_name])) {
                            $appointment_account_amounts[$account_name] = 0;
                        }
                        $appointment_account_amounts[$account_name] += $payment->amount ?? 0;
                    }
                }
            }
            $apt_no = Appointment::where('id', $appointment->appointment_id)->value('appointment_no') ?? '';
            $total_sessions = SessionData::where('main_appointment_id', $appointment->appointment_id)->count();
            $taken_session = SessionData::where('main_appointment_id', $appointment->appointment_id)->where('status', 2)->count();
            $pending = SessionData::where('main_appointment_id', $appointment->appointment_id)->where('status', 1)->count();
            $ot = SessionData::where('main_appointment_id', $appointment->appointment_id)->where('session_cat', 'OT')->count();
            $pt = SessionData::where('main_appointment_id', $appointment->appointment_id)->where('session_cat', 'PT')->count();



            $appointmentsAndSessions[] = [
                'type' => 'appointment',
                'id' => $appointment->id,
                'appointment_no' => $apt_no,
                'taken_session' => $taken_session,
                'pending' => $pending,
                'ot' => $ot,
                'pt' => $pt,
                'fee' => $appointment->total_price ?? 0,
                'paid_amount' => $total_paid_amount,
                'account_amounts' => $account_amounts,
                'payment_type' => $detail->session_type,
                'name' => $name,
                'voucher_codes' => array_unique($voucher_codes),
                'voucher_amounts' => $voucher_amounts,
                'session_count' => $total_sessions,
                'single_session_fee' => ($total_sessions > 0)
                    ? number_format($appointment->total_price / $total_sessions, 3, '.', '')
                    : number_format(0, 3, '.', ''),

                'contract_payment_check' => [
                    'is_contract' => $isContractPayment,
                    'status' => $contractPaymentStatus,
                ],
                'appointment_fee_paid' => $appointment_paid_amount,
                'appointment_account_amounts' => $appointment_account_amounts,
            ];
        }

        foreach ($sessions as $session) {
            $payments = SessionsonlyPayment::where('session_id', $session->session_id)->whereNotNull('account_id')->where('contract_payment', 1)->get();
            $detail = SessionDetail::where('session_id', $session->session_id)->first();

            $name = '';
            if ($detail->offer_id) {
                $name = Offer::where('id', $detail->offer_id)->value('offer_name');
            }
            if ($detail->ministry_id) {
                $name = GovtDept::where('id', $detail->ministry_id)->value('govt_name');
            }

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
            $taken_session = SessionData::where('main_session_id', $session->session_id)->where('status', 2)->count();
            $pending = SessionData::where('main_session_id', $session->session_id)->where('status', 1)->count();
            $ot = SessionData::where('main_session_id', $session->session_id)->where('session_cat', 'OT')->count();
            $pt = SessionData::where('main_session_id', $session->session_id)->where('session_cat', 'PT')->count();


            $appointmentsAndSessions[] = [
                'type' => 'session',
                'id' => $session->id,
                'appointment_no' => $session_no,
                'fee' => $session->total_fee ?? 0,
                'paid_amount' => $total_paid_amount,
                'taken_session' => $taken_session,
                'pending' => $pending,
                'ot' => $ot,
                'pt' => $pt,
                'account_amounts' => $account_amounts,
                'payment_type' => $detail->session_type,
                'name' => $name,
                'voucher_codes' => array_unique($voucher_codes),
                'total_voucher_amount' => $voucher_amounts_sum,
                'session_count' => $total_sessions,
                'single_session_fee' => ($total_sessions > 0)
                    ? number_format($session->total_fee / $total_sessions, 3, '.', '')
                    : number_format(0, 3, '.', ''),
                'contract_payment_check' => [
                    'is_contract' => $isContractPayment,
                    'status' => $contractPaymentStatus,
                ],
                'appointment_fee_paid' => 0,
                'appointment_account_amounts' => null,
            ];
        }

        return response()->json($appointmentsAndSessions);
    }
}
