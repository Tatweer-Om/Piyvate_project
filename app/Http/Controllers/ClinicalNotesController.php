<?php

namespace App\Http\Controllers;

use App\Models\AllSessioDetail;
use Carbon\Carbon;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Otppediatric;
use Illuminate\Http\Request;
use App\Models\ClinicalNotes;
use App\Models\AppointmentDetail;
use App\Models\AppointmentSession;
use App\Models\SessionList;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class ClinicalNotesController extends Controller
{
    public function soap_ot($id)
    {
        $patient = Patient::find($id);
        $session = SessionList::where('patient_id', $id)->first();
        $apt = Appointment::where('patient_id', $id)->first();

        $notes = null;

        if ($apt) {
            $notes = ClinicalNotes::where('appointment_id', $apt->id)->first();
        } elseif ($session) {
            $notes = ClinicalNotes::where('session_id', $session->id)->first();
        }

        $clinicalNotes = $notes ? json_decode($notes->form_data) : null;



        if($apt){
            $doctor = $apt ? Doctor::where('id', $apt->doctor_id)->value('doctor_name') : null;

        }
        if ($session) {
            $doctor = Doctor::where('id', $session->doctor_id)->value('doctor_name');
        }


        return view('clinical_notes.soap_ot', compact('patient', 'session', 'apt', 'doctor', 'clinicalNotes'));
    }


    public function soap_pt($id)
    {
        $patient = Patient::where('id', $id)->first();
        $session = SessionList::where('patient_id', $id)->first();
        $apt = Appointment::where('patient_id', $id)->first();

        $notes = null;

        if ($apt) {
            $notes = ClinicalNotes::where('appointment_id', $apt->id)->first();
            $doctor = Doctor::where('id', $apt->doctor_id)->value('doctor_name');
        } elseif ($session) {
            $notes = ClinicalNotes::where('session_id', $session->id)->first();
            $doctor = Doctor::where('id', $session->doctor_id)->value('doctor_name');
        }

        $clinicalNotes = $notes ? json_decode($notes->form_data) : null;


        return view('clinical_notes.soap_pt', compact('patient', 'session', 'clinicalNotes', 'apt', 'doctor'));
    }

    //opt_pediatrics

    public function otatp_pedriatic($id)
    {
        $patient = Patient::where('id', $id)->first();
        $apt = Appointment::where('patient_id', $id)->first();
        $session = SessionList::where('patient_id', $id)->first();

        if($apt){
            $doctor = $apt ? Doctor::where('id', $apt->doctor_id)->value('doctor_name') : null;

        }
        if ($session) {
            $doctor = Doctor::where('id', $session->doctor_id)->value('doctor_name');
        }
    return view('clinical_notes.otatp_pediatric', compact('patient', 'apt', 'doctor'));
    }






    public function neuro_pedriatic($id)
    {
        $patient = Patient::find($id);
        $apt = Appointment::where('patient_id', $id)->first();
        $note = ClinicalNotes::where('patient_id', $id)->first();

        $session = SessionList::where('patient_id', $id)->first();

        if($apt){
            $doctor = $apt ? Doctor::where('id', $apt->doctor_id)->value('doctor_name') : null;

        }
        if ($session) {
            $doctor = Doctor::where('id', $session->doctor_id)->value('doctor_name');
        }

        return view('clinical_notes.otatp_neuro_pediatric', [
            'patient' => $patient ?? null,
            'apt' => $apt ?? null,
            'doctor' => $doctor ?? null,
            'note' => $note ?? null,
        ]);
    }



    public function otatp_ortho($id)
    {
        $patient = Patient::where('id', $id)->first();

        $apt = Appointment::where('patient_id', $id)->first();
        $session = SessionList::where('patient_id', $id)->first();

        if($apt){
            $doctor = $apt ? Doctor::where('id', $apt->doctor_id)->value('doctor_name') : null;

        }
        if ($session) {
            $doctor = Doctor::where('id', $session->doctor_id)->value('doctor_name');
        }
        return view('clinical_notes.otatp_orthopedic', compact('patient', 'apt', 'doctor'));
    }

    public function physical_dysfunction($id)
    {
        $patient = Patient::where('id', $id)->first();

        $apt = Appointment::where('patient_id', $id)->first();
        $session = SessionList::where('patient_id', $id)->first();

        if($apt){
            $doctor = $apt ? Doctor::where('id', $apt->doctor_id)->value('doctor_name') : null;

        }
        if ($session) {
            $doctor = Doctor::where('id', $session->doctor_id)->value('doctor_name');
        }
        return view('clinical_notes.otatp_physical_dysfunction', compact('patient', 'apt', 'doctor'));
    }

    //neuro_add

    public function add_neuro_pedriatic(Request $request)
    {

        $user_id = Auth::id();
        $user = Auth::user();
        $branch_id = $user->branch_id;

        $base64Image = $request->input('canvas_image');
        if ($base64Image) {
            [$type, $data] = explode(';', $base64Image);
            [, $data] = explode(',', $data);
            $decodedImage = base64_decode($data);

            $imageDirectory = public_path('images/notes_images');

            if (!file_exists($imageDirectory)) {
                mkdir($imageDirectory, 0777, true);
            }

            $imageName = 'marked_image_' . time() . '.png';

            file_put_contents($imageDirectory . '/' . $imageName, $decodedImage);

            $imagePath = 'images/notes_images/' . $imageName;
        } else {

            $imagePath = null;
        }
        $data = $request->all();
        $data['image_path'] = $imagePath;
        unset($data['canvas_image']);


        $neuroPediatric = new ClinicalNotes();

        $neuroPediatric->form_data = json_encode($data);
        $neuroPediatric->form_type = 'PT Neuro and Pediatric';
        $neuroPediatric->notes_status = 1;
        $neuroPediatric->doctor_id = $request->doctor_id;
        $neuroPediatric->appointment_id = $request->appointment_id;
        $neuroPediatric->added_by = $user_id;
        $neuroPediatric->user_id = $user_id;
        $neuroPediatric->branch_id = $branch_id;


        $neuroPediatric->patient_id =  $request->patient_id;

        $neuroPediatric->save();

        $appointment = Appointment::findOrFail($neuroPediatric->appointment_id);
        $appointment->session_status = 7;
        $appointment->save();

        return redirect()->back()->with('success', 'Data saved successfully.');
    }


    public function update_neuro_pedriatic(Request $request, $id)
    {
        $user_id = Auth::id();
        $user = Auth::user();
        $branch_id = $user->branch_id;

        $neuroPediatric = ClinicalNotes::where('id', $id)->first();

        $base64Image = $request->input('canvas_image');
        if ($base64Image) {
            [$type, $data] = explode(';', $base64Image);
            [, $data] = explode(',', $data);
            $decodedImage = base64_decode($data);

            $imageDirectory = public_path('images/notes_images');
            if (!file_exists($imageDirectory)) {
                mkdir($imageDirectory, 0777, true);
            }

            $imageName = 'marked_image_' . time() . '.png';
            file_put_contents($imageDirectory . '/' . $imageName, $decodedImage);

            $imagePath = 'images/notes_images/' . $imageName;
        } else {
            // Keep existing image if not updated
            $existingData = json_decode($neuroPediatric->form_data, true);
            $imagePath = $existingData['image_path'] ?? null;
        }

        $data = $request->all();
        $data['image_path'] = $imagePath;
        unset($data['canvas_image']);

        // Update the existing record
        $neuroPediatric->form_data = json_encode($data);
        $neuroPediatric->form_type = 'PT Neuro and Pediatric';
        $neuroPediatric->notes_status = 1;
        $neuroPediatric->doctor_id = $request->doctor_id;
        $neuroPediatric->appointment_id = $request->appointment_id;
        $neuroPediatric->patient_id = $request->patient_id;
        $neuroPediatric->updated_by = $user_id;
        $neuroPediatric->user_id = $user_id;
        $neuroPediatric->branch_id = $branch_id;

        $neuroPediatric->save();

        return redirect('patient_profile/' . $neuroPediatric->patient_id)->with('success', 'Data saved successfully.');
    }


    public function neuro_pedriatic_view($id)
    {

        $note = ClinicalNotes::findOrFail($id);
        $patient = Patient::find($note->patient_id);
        $apt = Appointment::where('patient_id', $note->patient_id)->first();

        $form_data = json_decode($note->form_data, true);

        return view('clinical_notes.otatp_neuro_pediatric_print', [
            'note' => $note,
            'patient' => $patient,
            'apt' => $apt,
            'data' => $form_data,
        ]);
    }

    //ortho_Add

    public function add_otatp_ortho(Request $request)
    {

        $user_id = Auth::id();
        $user = Auth::user();
        $branch_id = $user->branch_id;


        $base64Image = $request->input('canvas_image');
        if ($base64Image) {
            [$type, $data] = explode(';', $base64Image);
            [, $data] = explode(',', $data);
            $decodedImage = base64_decode($data);

            $imageDirectory = public_path('images/notes_images');

            if (!file_exists($imageDirectory)) {
                mkdir($imageDirectory, 0777, true);
            }

            $imageName = 'marked_image_' . time() . '.png';

            file_put_contents($imageDirectory . '/' . $imageName, $decodedImage);

            $imagePath = 'images/notes_images/' . $imageName;
        } else {

            $imagePath = null;
        }
        $data = $request->all();
        $data['image_path'] = $imagePath;
        unset($data['canvas_image']);


        $neuroPediatric = new ClinicalNotes();

        $neuroPediatric->form_data = json_encode($data);
        $neuroPediatric->form_type = 'PT Ortho-Pedic';
        $neuroPediatric->notes_status = 2;
        $neuroPediatric->doctor_id = $request->doctor_id;
        $neuroPediatric->appointment_id = $request->appointment_id;
        $neuroPediatric->added_by = $user_id;
        $neuroPediatric->user_id = $user_id;
        $neuroPediatric->branch_id = $branch_id;


        $neuroPediatric->patient_id =  $request->patient_id;

        $neuroPediatric->save();

        $appointment = Appointment::findOrFail($neuroPediatric->appointment_id);
        $appointment->session_status = 7;
        $appointment->save();

        return redirect()->back()->with('success', 'Data saved successfully.');
    }


    public function edit_otatp_ortho($id)
    {

        $note = ClinicalNotes::findOrFail($id);
        $patient = Patient::find($note->patient_id);
        $apt = Appointment::where('patient_id', $note->patient_id)->first();

        $form_data = json_decode($note->form_data, true);

        return view('clinical_notes.edit_orthopedic', [
            'note' => $note,
            'patient' => $patient,
            'apt' => $apt,
            'data' => $form_data,
        ]);
    }


    public function update_otatp_ortho(Request $request, $id)
    {
        $user_id = Auth::id();
        $user = Auth::user();
        $branch_id = $user->branch_id;

        $neuroPediatric = ClinicalNotes::where('id', $id)->first();

        $base64Image = $request->input('canvas_image');
        if ($base64Image) {
            [$type, $data] = explode(';', $base64Image);
            [, $data] = explode(',', $data);
            $decodedImage = base64_decode($data);

            $imageDirectory = public_path('images/notes_images');
            if (!file_exists($imageDirectory)) {
                mkdir($imageDirectory, 0777, true);
            }

            $imageName = 'marked_image_' . time() . '.png';
            file_put_contents($imageDirectory . '/' . $imageName, $decodedImage);

            $imagePath = 'images/notes_images/' . $imageName;
        } else {
            // Keep existing image if not updated
            $existingData = json_decode($neuroPediatric->form_data, true);
            $imagePath = $existingData['image_path'] ?? null;
        }

        $data = $request->all();
        $data['image_path'] = $imagePath;
        unset($data['canvas_image']);

        // Update the existing record
        $neuroPediatric->form_data = json_encode($data);
        $neuroPediatric->form_type = 'PT Ortho-Pedic';
        $neuroPediatric->notes_status = 2;
        $neuroPediatric->doctor_id = $request->doctor_id;
        $neuroPediatric->appointment_id = $request->appointment_id;
        $neuroPediatric->patient_id = $request->patient_id;
        $neuroPediatric->updated_by = $user_id;
        $neuroPediatric->user_id = $user_id;
        $neuroPediatric->branch_id = $branch_id;

        $neuroPediatric->save();

        return redirect('patient_profile/' . $neuroPediatric->patient_id)->with('success', 'Data saved successfully.');
    }


    public function add_otp_pediatric(Request $request)
    {

        $user_id = Auth::id();
        $user = Auth::user();
        $branch_id = $user->branch_id;
        $data = $request->all();

        $neuroPediatric = new ClinicalNotes();
        $neuroPediatric->form_data = json_encode($data);
        $neuroPediatric->form_type = 'PT Pediatric';
        $neuroPediatric->notes_status = 3;
        $neuroPediatric->doctor_id = $request->doctor_id;
        $neuroPediatric->appointment_id = $request->appointment_id;
        $neuroPediatric->added_by = $user_id;
        $neuroPediatric->user_id = $user_id;
        $neuroPediatric->branch_id = $branch_id;


        $neuroPediatric->patient_id =  $request->patient_id;

        $neuroPediatric->save();


        $appointment = Appointment::findOrFail($neuroPediatric->appointment_id);
        $appointment->session_status = 7;
        $appointment->save();

        return redirect()->back()->with('success', 'Data saved successfully.');
    }

    public function edit_otp_pediatric($id)
    {

        $note = ClinicalNotes::findOrFail($id);
        $patient = Patient::find($note->patient_id);
        $apt = Appointment::where('patient_id', $note->patient_id)->first();

        $form_data = json_decode($note->form_data, true);

        return view('clinical_notes.edit_otatp_pediatric', [
            'note' => $note,
            'patient' => $patient,
            'apt' => $apt,
            'data' => $form_data,
        ]);
    }

    public function update_otp_pediatric(Request $request, $id)
    {
        $user_id = Auth::id();
        $user = Auth::user();
        $branch_id = $user->branch_id;

        $neuroPediatric = ClinicalNotes::where('id', $id)->first();



        $data = $request->all();



        $neuroPediatric->form_data = json_encode($data);
        $neuroPediatric->form_type = 'PT Pediatric';
        $neuroPediatric->notes_status = 3;
        $neuroPediatric->doctor_id = $request->doctor_id;
        $neuroPediatric->appointment_id = $request->appointment_id;

        $neuroPediatric->patient_id = $request->patient_id;
        $neuroPediatric->updated_by = $user_id;
        $neuroPediatric->user_id = $user_id;
        $neuroPediatric->branch_id = $branch_id;

        $neuroPediatric->save();

        return redirect('patient_profile/' . $neuroPediatric->patient_id)->with('success', 'Data saved successfully.');
    }

    //physical_dysfucntion

    public function add_physical_dysfunction(Request $request)
    {
        $user_id = Auth::id();
        $user = Auth::user();
        $branch_id = $user->branch_id;
        $data = $request->all();


        $neuroPediatric = new ClinicalNotes();
        $neuroPediatric->form_data = json_encode($data);
        $neuroPediatric->form_type = 'Physical Dysfunction';
        $neuroPediatric->notes_status = 4;
        $neuroPediatric->doctor_id = $request->doctor_id;
        $neuroPediatric->appointment_id = $request->appointment_id;
        $neuroPediatric->added_by = $user_id;
        $neuroPediatric->user_id = $user_id;
        $neuroPediatric->branch_id = $branch_id;


        $neuroPediatric->patient_id =  $request->patient_id;

        $neuroPediatric->save();

        $appointment = Appointment::findOrFail($neuroPediatric->appointment_id);
        $appointment->session_status = 7;
        $appointment->save();

        return redirect()->back()->with('success', 'Data saved successfully.');
    }


    public function edit_physical_dysfunction($id)
    {

        $note = ClinicalNotes::findOrFail($id);
        $patient = Patient::find($note->patient_id);

        $apt = Appointment::where('patient_id', $note->patient_id)->first();

        $form_data = json_decode($note->form_data, true);



        return view('clinical_notes.edit_physical_dysfunction', [
            'note' => $note,
            'patient' => $patient,
            'apt' => $apt,
            'data' => $form_data,
        ]);
    }

    public function update_physical_dysfunction(Request $request, $id)
    {
        $user_id = Auth::id();
        $user = Auth::user();
        $branch_id = $user->branch_id;

        $neuroPediatric = ClinicalNotes::where('id', $id)->first();

        $data = $request->all();

        $neuroPediatric->form_data = json_encode($data);
        $neuroPediatric->form_type = 'Physical Dysfunction';
        $neuroPediatric->notes_status = 4;
        $neuroPediatric->doctor_id = $request->doctor_id;
        $neuroPediatric->appointment_id = $request->appointment_id;
        $neuroPediatric->patient_id = $request->patient_id;
        $neuroPediatric->updated_by = $user_id;
        $neuroPediatric->user_id = $user_id;
        $neuroPediatric->branch_id = $branch_id;

        $neuroPediatric->save();

        return redirect('patient_profile/' . $neuroPediatric->patient_id)->with('success', 'Data saved successfully.');
    }


    public function add_soap_ot(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $branch_id = $user->branch_id;

        // Prepare SOAP data
        $soapData = [
            'pt' => $request->pt,
            'soap_sections' => [],
        ];

        if (is_array($request->date)) {
            $count = count($request->date);
            for ($i = 0; $i < $count; $i++) {
                $soapData['soap_sections'][] = [
                    'date' => $request->date[$i],
                    'time' => $request->time[$i],
                    'bp' => $request->bp[$i],
                    'pulse' => $request->pulse[$i],
                    'o2sat' => $request->o2sat[$i],
                    'temp' => $request->temp[$i],
                    'ps' => $request->ps[$i],
                    's' => $request->s[$i],
                    'o' => $request->o[$i],
                    'a' => $request->a[$i],
                    'p' => $request->p[$i],
                    'number' => $request->number[$i],
                    'signature' => $request->signature[$i],
                ];
            }
        }

        $neuroPediatric = null;

        // Update if appointment_id is provided
        if ($request->appointment_id) {
            $neuroPediatric = ClinicalNotes::where('appointment_id', $request->appointment_id)->first();
        }

        // Or update if session_id is provided
        if (!$neuroPediatric && $request->session_id) {
            $neuroPediatric = ClinicalNotes::where('session_id', $request->session_id)->first();
        }

        // If existing record found, update it
        if ($neuroPediatric) {
            $neuroPediatric->form_data = json_encode($soapData);
            $neuroPediatric->notes_status = 5;
            $neuroPediatric->doctor_id = $request->doctor_id;
            $neuroPediatric->added_by = $user_id;
            $neuroPediatric->user_id = $user_id;
            $neuroPediatric->branch_id = $branch_id;
            $neuroPediatric->patient_id = $request->patient_id;
            $neuroPediatric->save();
        } else {
            // Create a new ClinicalNotes record
            $neuroPediatric = new ClinicalNotes();
            $neuroPediatric->form_data = json_encode($soapData);
            $neuroPediatric->form_type = 'SOAP_OT';
            $neuroPediatric->notes_status = 5;
            $neuroPediatric->doctor_id = $request->doctor_id;
            $neuroPediatric->appointment_id = $request->appointment_id;
            $neuroPediatric->session_id = $request->session_id;
            $neuroPediatric->added_by = $user_id;
            $neuroPediatric->user_id = $user_id;
            $neuroPediatric->branch_id = $branch_id;
            $neuroPediatric->patient_id = $request->patient_id;
            $neuroPediatric->save();
        }

        // Decode saved data to count sessions
        $formArray = json_decode($neuroPediatric->form_data, true);
        $sessionCount = count($formArray['soap_sections'] ?? []);

        // Update sessions_taken
        if (!is_null($neuroPediatric->appointment_id)) {
            $session_apt = AppointmentDetail::where('appointment_id', $neuroPediatric->appointment_id)->first();
            if ($session_apt) {
                $session_apt->sessions_taken += $sessionCount;
                $session_apt->save();
            }
        }

        // Update AllSessioDetail and AppointmentSession statuses
        foreach ($formArray['soap_sections'] ?? [] as $entry) {
            $date = $entry['date'] ?? null;

            if ($date) {
                if (!is_null($neuroPediatric->session_id)) {
                    $nearestSession = AllSessioDetail::where('session_id', $neuroPediatric->session_id)
                        ->whereDate('session_date', $date)
                        ->orderBy('id', 'asc')
                        ->first();

                    if ($nearestSession) {
                        $nearestSession->status = 2;
                        $nearestSession->save();
                    }
                }

                if (!is_null($neuroPediatric->appointment_id)) {
                    $nearestAppSession = AppointmentSession::where('appointment_id', $neuroPediatric->appointment_id)
                        ->whereDate('session_date', $date)
                        ->orderBy('id', 'asc')
                        ->first();

                    if ($nearestAppSession) {
                        $nearestAppSession->status = 2;
                        $nearestAppSession->save();
                    }
                }
            }
        }

        // Update today's session if not already done
        $today = Carbon::today()->toDateString();

        if (!is_null($neuroPediatric->session_id)) {
            $apt_session = AllSessioDetail::where('session_id', $neuroPediatric->session_id)
                ->whereDate('session_date', $today)
                ->orderBy('id', 'asc')
                ->first();

            if ($apt_session) {
                $apt_session->status = 2;
                $apt_session->save();
            }
        }

        return redirect('patient_profile/' . $neuroPediatric->patient_id)->with('success', 'Data saved successfully.');
    }


    public function add_soap_pt(Request $request)
    {
        // Logging any issues if there's an error in the code execution
        Log::info('Add SOAP PT Function Started.');

        $user_id = Auth::id();
        $user = Auth::user();
        $branch_id = $user->branch_id;

        if ($request->appointment_id) {
            $note = ClinicalNotes::where('form_type', 'SOAP_PT')
                ->where('appointment_id', $request->appointment_id)
                ->where('patient_id', $request->patient_id)
                ->first();
        } elseif ($request->session_id) {
            $note = ClinicalNotes::where('form_type', 'SOAP_PT')
                ->where('session_id', $request->session_id)
                ->where('patient_id', $request->patient_id)
                ->first();
        } else {
            // Handle case when neither appointment_id nor session_id is provided
            $note = null;
        }

        $imagePath = null;


        $base64Image = $request->input('canvas_image');

        // If it's an array (e.g., multiple canvas images), get the first one
        if (is_array($base64Image)) {
            $base64Image = $base64Image[0];
        }

        if ($base64Image) {
            $folderPath = public_path('images/notes_images');

            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }

            $imageName = 'marked_image_' . time() . '.png';
            $imagePath = 'images/notes_images/' . $imageName;

            if (strpos($base64Image, ',') !== false) {
                $base64Image = explode(',', $base64Image)[1];
            }

            file_put_contents($folderPath . '/' . $imageName, base64_decode($base64Image));

            Log::info('Canvas image saved at: ' . $imagePath);
        }


        $soapData = [
            'pt' => $request->pt,
            'soap_sections' => [],
        ];

        // If existing note, decode old data
        if ($note) {
            $existingData = json_decode($note->form_data, true);
            $soapData['soap_sections'] = $existingData['soap_sections'] ?? [];
        } else {
            $note = new ClinicalNotes();
        }

        // Append new entries from the form
        if (is_array($request->date)) {
            $count = count($request->date);
            for ($i = 0; $i < $count; $i++) {
                $soapData['soap_sections'][] = [
                    'date' => $request->date[$i] ?? '',
                    'time' => $request->time[$i] ?? '',
                    'bp' => $request->bp[$i] ?? '',
                    'pulse' => $request->pulse[$i] ?? '',
                    'o2sat' => $request->o2sat[$i] ?? '',
                    'temp' => $request->temp[$i] ?? '',
                    'ps' => $request->ps[$i] ?? '',
                    's' => $request->s[$i] ?? '',
                    'o' => $request->o[$i] ?? '',
                    'a' => $request->a[$i] ?? '',
                    'p' => $request->p[$i] ?? '',
                    'number' => $request->number[$i] ?? '',
                    'signature' => $request->signature[$i] ?? '',
                    'image_path' => $imagePath ?? null,
                ];
            }
        }

        $note->form_data = json_encode($soapData);
        $note->form_type = 'SOAP_PT';
        $note->notes_status = 6;
        $note->doctor_id = $request->doctor_id;
        $note->appointment_id = $request->appointment_id;
        $note->session_id = $request->session_id;

        $note->added_by = $user_id;
        $note->user_id = $user_id;
        $note->branch_id = $branch_id;
        $note->patient_id = $request->patient_id;

        $note->save();

        // Update session status (unchanged logic)
        $formArray = json_decode($note->form_data, true);
        $sessionCount = count($formArray['soap_sections'] ?? []);

        // Update sessions_taken
        if (!is_null($note->appointment_id)) {
            $session_apt = AppointmentDetail::where('appointment_id', $note->appointment_id)->first();
            if ($session_apt) {
                $session_apt->sessions_taken += $sessionCount;
                $session_apt->save();
            }
        }

        // Update AllSessioDetail and AppointmentSession statuses
        foreach ($formArray['soap_sections'] ?? [] as $entry) {
            $date = $entry['date'] ?? null;

            if ($date) {
                if (!is_null($note->session_id)) {
                    $nearestSession = AllSessioDetail::where('session_id', $note->session_id)
                        ->whereDate('session_date', $date)
                        ->orderBy('id', 'asc')
                        ->first();

                    if ($nearestSession) {
                        $nearestSession->status = 2;
                        $nearestSession->save();
                    }
                }

                if (!is_null($note->appointment_id)) {
                    $nearestAppSession = AppointmentSession::where('appointment_id', $note->appointment_id)
                        ->whereDate('session_date', $date)
                        ->orderBy('id', 'asc')
                        ->first();

                    if ($nearestAppSession) {
                        $nearestAppSession->status = 2;
                        $nearestAppSession->save();
                    }
                }
            }
        }

        // Update today's session if not already done
        $today = Carbon::today()->toDateString();

        if (!is_null($note->session_id)) {
            $apt_session = AllSessioDetail::where('session_id', $note->session_id)
                ->whereDate('session_date', $today)
                ->orderBy('id', 'asc')
                ->first();

            if ($apt_session) {
                $apt_session->status = 2;
                $apt_session->save();
            }
        }

        // Log success
        Log::info('SOAP PT note saved successfully.');

        return redirect('patient_profile/' . $note->patient_id)->with('success', 'Data saved successfully.');
    }
}
