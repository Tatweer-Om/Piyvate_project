<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Doctor;
use App\Models\SoapOT;
use App\Models\SoapPT;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\SessionData;
use App\Models\SessionList;
use App\Models\Otppediatric;
use Illuminate\Http\Request;
use App\Models\ClinicalNotes;
use App\Models\AllSessioDetail;
use App\Models\AppointmentDetail;
use App\Models\AppointmentSession;
use App\Models\PatientPrescription;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class ClinicalNotesController extends Controller
{
    public function soap_ot($id)

    {

        $session_data = SessionData::where('id', $id)->where('session_cat', 'OT')->first();

        $soap= SoapOT::where('session_id', $id)->first();

        if (!$session_data) {
            return redirect()->back()->with('error', 'Session not found.');
        }
        $patient_id = $session_data->patient_id;
        $patient = Patient::find($patient_id);
        $session = SessionList::find($session_data->main_session_id);
        $apt = Appointment::where('patient_id', $patient_id)
            ->where('id', $session_data->main_appointment_id)
            ->whereIn('session_status', [0, 2, 5])
            ->first();

            $doctor = Doctor::where('id', $session_data->doctor_id)->value('doctor_name');


        return view('clinical_notes.soap_ot', compact('patient', 'soap', 'session_data', 'session', 'apt', 'doctor',));
    }



    public function soap_pt($id)
    {

        $session_data = SessionData::where('id', $id)->where('session_cat', 'PT')->first();

        if (!$session_data) {
            return redirect()->back()->with('error', 'Session not found.');
        }

        $soap= SoapPT::where('session_id', $id)->first();


        $patient_id = $session_data->patient_id;
        $patient = Patient::find($patient_id);
        $session = SessionList::find($session_data->main_session_id);


        $apt = Appointment::where('patient_id', $patient_id)
            ->where('id', $session_data->main_appointment_id)
            ->whereIn('session_status', [0, 2, 5])
            ->first();


        $doctor = null;
        if ($apt && $session_data->doctor_id) {
            $doctor = Doctor::where('id', $session_data->doctor_id)->value('doctor_name');
        } elseif ($session && $session_data->doctor_id) {
            $doctor = Doctor::where('id', $session_data->doctor_id)->value('doctor_name');
        }

        return view('clinical_notes.soap_pt', compact('patient', 'session', 'soap',  'session_data',  'apt', 'doctor'));
    }

    public function soap_pt_all($id)
    {

        $session_data = SoapPT::where('patient_id', $id)->get();

        if ($session_data->isEmpty()) {
            return redirect()->back()->with('error', 'Session not found.');
        }

        $patient = Patient::find($id);
        $doctor = null;
        $pt = null;

        if ($session_data->first()->doctor_id) {
            $doctor = Doctor::where('id', $session_data->first()->doctor_id)->value('doctor_name');
        }

        $pt = $session_data->first()->pt ?? null;

        return view('clinical_notes.soap_pt_all', compact('patient', 'pt', 'session_data', 'doctor'));
    }

    public function soap_ot_all($id)
    {
        $session_data = SoapOT::where('patient_id', $id)->get();

        if ($session_data->isEmpty()) {
            return redirect()->back()->with('error', 'Session not found.');
        }

        $patient = Patient::find($id);
        $doctor = null;
        $pt = null;

        if ($session_data->first()->doctor_id) {
            $doctor = Doctor::where('id', $session_data->first()->doctor_id)->value('doctor_name');
        }

        $pt = $session_data->first()->pt ?? null;

        return view('clinical_notes.soap_ot_all', compact('patient', 'pt', 'session_data', 'doctor'));
    }


    //opt_pediatrics

    public function otatp_pedriatic($id)
    {
        $patient = Patient::where('id', $id)->first();
        $apt = Appointment::where('patient_id', $id)->where('session_status', 2)->first();


        if ($apt) {
            $doctor = $apt ? Doctor::where('id', $apt->doctor_id)->value('doctor_name') : null;
        }

        return view('clinical_notes.otatp_pediatric', compact('patient',  'apt', 'doctor'));
    }



    public function neuro_pedriatic($id)
    {
        $patient = Patient::find($id);
        $apt = Appointment::where('patient_id', $id)->where('session_status', 2)->first();
                $note = ClinicalNotes::where('patient_id', $id)->first();


        if ($apt) {
            $doctor = $apt ? Doctor::where('id', $apt->doctor_id)->value('doctor_name') : null;
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

        $apt = Appointment::where('patient_id', $id)->where('session_status', 2)->first();

        if ($apt) {
            $doctor = $apt ? Doctor::where('id', $apt->doctor_id)->value('doctor_name') : null;
        }

        return view('clinical_notes.otatp_orthopedic', compact('patient', 'apt', 'doctor'));
    }

    public function physical_dysfunction($id)
    {
        $patient = Patient::where('id', $id)->first();

        $apt = Appointment::where('patient_id', $id)->where('session_status', 2)->first();

        if ($apt) {
            $doctor = $apt ? Doctor::where('id', $apt->doctor_id)->value('doctor_name') : null;
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


        return redirect()->to('patient_appointment/' . $neuroPediatric->appointment_id)->with('success', 'Data saved successfully.');
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

        return redirect('patient_appointment/' . $neuroPediatric->appointment_id)->with('success', 'Data saved successfully.');
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


        return redirect()->to('patient_appointment/' . $neuroPediatric->appointment_id)->with('success', 'Data saved successfully.');
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

        return redirect('patient_appointment/' . $neuroPediatric->appointment_id)->with('success', 'Data saved successfully.');
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



        return redirect()->to('patient_appointment/' . $neuroPediatric->appointment_id)->with('success', 'Data saved successfully.');
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

        return redirect('patient_appointment/' . $neuroPediatric->appointment_id)->with('success', 'Data saved successfully.');
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


        return redirect()->to('patient_appointment/' . $neuroPediatric->appointment_id)->with('success', 'Data saved successfully.');
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

        return redirect('patient_appointment/' . $neuroPediatric->appointment_id)->with('success', 'Data saved successfully.');
    }


    public function add_soap_ot(Request $request)
    {
        if (empty($request->date) || empty($request->time)) {
            return redirect()->back()->with('error', 'Date and Time are required.');
        }


        $user = Auth::user();
        $user_id = $user->id;
        $branch_id = $user->branch_id;
        $id = $request->session_id;


        $soap = SoapOT::where('session_id', $id)->first();

        if ($soap) {
            $soap->main_session_id = $request->main_session_id;
            $soap->main_appointment_id = $request->main_appointment_id;
            $soap->patient_id = $request->patient_id;
            $soap->doctor_id = $request->doctor_id;
            $soap->pt = $request->pt;
            $soap->date = $request->date;
            $soap->time = $request->time;
            $soap->bp = $request->bp;
            $soap->pulse = $request->pulse;
            $soap->o2sat = $request->o2sat;
            $soap->temp = $request->temp;
            $soap->ps = $request->ps;
            $soap->s = $request->s;
            $soap->o = $request->o;
            $soap->a = $request->a;
            $soap->p = $request->p;
            $soap->branch_id = $branch_id;
            $soap->user_id = $user_id;
            $soap->number = $request->number;
            $soap->signature = $request->signature;
            $soap->save();

            $message = 'Data updated successfully.';
        } else {
            $soap = new SoapOT;
            $soap->main_session_id = $request->main_session_id;
            $soap->main_appointment_id = $request->main_appointment_id;
            $soap->session_id = $id;
            $soap->patient_id = $request->patient_id;
            $soap->doctor_id = $request->doctor_id;
            $soap->pt = $request->pt;
            $soap->date = $request->date;
            $soap->time = $request->time;
            $soap->bp = $request->bp;
            $soap->pulse = $request->pulse;
            $soap->o2sat = $request->o2sat;
            $soap->temp = $request->temp;
            $soap->ps = $request->ps;
            $soap->s = $request->s;
            $soap->o = $request->o;
            $soap->a = $request->a;
            $soap->p = $request->p;
            $soap->branch_id = $branch_id;
            $soap->user_id = $user_id;
            $soap->number = $request->number;
            $soap->signature = $request->signature;
            $soap->save();

            $message = 'Data saved successfully.';
        }

        if (!is_null($soap->main_session_id)) {
            // $nextSession = AllSessioDetail::where('session_id', $soap->main_session_id)->where('id', $id)->first();
            $session_data = SessionData::where('source', 1)
                ->where('main_session_id', $soap->main_session_id)->where('id', $id)
                ->first();

            if ( !$session_data) {
                return redirect()->back()->with('error', 'You do not have a session on the selected date.');
            }

            // $nextSession->status = 2;
            $session_data->status = 2;
            // $nextSession->save();
            $session_data->save();
        }

        if (!is_null($soap->main_appointment_id)) {
            // $nextAppSession = AppointmentSession::where('appointment_id', $soap->main_appointment_id)->where('id', $id)->first();
            $sessiondata = SessionData::where('main_appointment_id', $soap->main_appointment_id)->where('id', $id)
                ->where('session_cat', 'OT')
                ->where('source', 2)
                ->first();

            if ( !$sessiondata) {
                return redirect()->back()->with('error', 'You do not have session on the selected date.');
            }

            $sessiondata->status = 2;
            $sessiondata->save();
        }

        if (is_null($soap->main_appointment_id)) {
            $source = 11;
            $main_id = $soap->main_session_id;
        } elseif (is_null($soap->main_session_id)) {
            $source = 10;
            $main_id = $soap->main_appointment_id;
        } else {
            // Fallback: if both are not null, use session-based redirect
            return redirect('patient_profile/' . $soap->patient_id)->with('success', $message);
        }

        // Build and redirect to: patient_session/{main_id}?source={source}
        $redirectUrl = 'patient_session/' . $main_id . '?source=' . $source;
        return redirect($redirectUrl)->with('success', $message);    }


    public function add_soap_pt(Request $request)
    {

        if (empty($request->date) || empty($request->time)) {
            return redirect()->back()->with('error', 'Date and Time are required.');
        }

        $user = Auth::user();
        $user_id = $user->id;
        $branch_id = $user->branch_id;
        $id = $request->session_id;


        $imagePath = null;
        $base64Image = $request->input('canvas_image');

        // Handle base64 image if it exists
        if ($base64Image) {
            $folderPath = public_path('images/notes_images');

            // Create directory if it doesn't exist
            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }

            // Generate unique image name
            $imageName = 'marked_image_' . time() . '.png';
            $imagePath = 'images/notes_images/' . $imageName;

            // If the base64 string contains a data URL prefix, remove it
            if (strpos($base64Image, ',') !== false) {
                $base64Image = explode(',', $base64Image)[1];
            }

            // Decode the base64 string and save the image
            file_put_contents($folderPath . '/' . $imageName, base64_decode($base64Image));
            Log::info('Canvas image saved at: ' . $imagePath);
        }



        $soap = SoapPT::where('session_id', $id)->first();

        if ($soap) {
            $soap->main_session_id = $request->main_session_id;
            $soap->main_appointment_id = $request->main_appointment_id;
            $soap->patient_id = $request->patient_id;
            $soap->doctor_id = $request->doctor_id;
            $soap->pt = $request->pt;
            $soap->date = $request->date;
            $soap->time = $request->time;
            $soap->bp = $request->bp;
            $soap->pulse = $request->pulse;
            $soap->o2sat = $request->o2sat;
            $soap->temp = $request->temp;
            $soap->ps = $request->ps;
            $soap->s = $request->s;
            $soap->o = $request->o;
            $soap->a = $request->a;
            $soap->p = $request->p;
            $soap->soap_image = $imagePath;
            $soap->ticked_points = $request->input('ticked_points');
            $soap->branch_id = $branch_id;
            $soap->user_id = $user_id;
            $soap->number = $request->number;
            $soap->signature = $request->signature;
            $soap->save();

            $message = 'Data updated successfully.';
        } else {
            $soap = new SoapPT;
            $soap->main_session_id = $request->main_session_id;
            $soap->main_appointment_id = $request->main_appointment_id;
            $soap->session_id = $id;
            $soap->patient_id = $request->patient_id;
            $soap->doctor_id = $request->doctor_id;
            $soap->pt = $request->pt;
            $soap->date = $request->date;
            $soap->soap_image = $imagePath;
            $soap->ticked_points = $request->input('ticked_points');
            $soap->time = $request->time;
            $soap->bp = $request->bp;
            $soap->pulse = $request->pulse;
            $soap->o2sat = $request->o2sat;
            $soap->temp = $request->temp;
            $soap->ps = $request->ps;
            $soap->s = $request->s;
            $soap->o = $request->o;
            $soap->a = $request->a;
            $soap->p = $request->p;
            $soap->branch_id = $branch_id;
            $soap->user_id = $user_id;
            $soap->number = $request->number;
            $soap->signature = $request->signature;
            $soap->save();

            $message = 'Data saved successfully.';
        }

        if (!is_null($soap->main_session_id)) {


            $session_data = SessionData::where('source', 1)
                ->where('main_session_id', $soap->main_session_id)->where('id', $id)
                ->first();

            if (!$session_data) {
                return redirect()->back()->with('error', 'You do not have a session on the selected date.');
            }

            $session_data->status = 2;
            $session_data->save();
        }

        if (!is_null($soap->main_appointment_id)) {

            $nextAppSession = AppointmentSession::where('appointment_id', $soap->main_appointment_id)->where('id', $id)->first();
            $sessiondata = SessionData::where('main_appointment_id', $soap->main_appointment_id)->where('id', $id)
                ->where('session_cat', 'PT')
                ->where('source', 2)
                ->first();

            if (!$nextAppSession || !$sessiondata) {
                return redirect()->back()->with('error', 'You do not have session on the selected date.');
            }

            $nextAppSession->status = 2;
            $sessiondata->status = 2;
            $nextAppSession->save();
            $sessiondata->save();
        }



        if (is_null($soap->main_appointment_id)) {
            $source = 11;
            $main_id = $soap->main_session_id;
        } elseif (is_null($soap->main_session_id)) {
            $source = 10;
            $main_id = $soap->main_appointment_id;
        } else {
            // Fallback: if both are not null, use session-based redirect
            return redirect('patient_profile/' . $soap->patient_id)->with('success', $message);
        }

        // Build and redirect to: patient_session/{main_id}?source={source}
        $redirectUrl = 'patient_session/' . $main_id . '?source=' . $source;
        return redirect($redirectUrl)->with('success', $message);    }

}
