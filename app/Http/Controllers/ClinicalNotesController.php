<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\AppointmentDetail;
use App\Models\Otppediatric;
use Illuminate\Http\Request;
use App\Models\ClinicalNotes;
use App\Models\AppointmentSession;
use Illuminate\Support\Facades\Auth;

class ClinicalNotesController extends Controller
{
    public function soap_ot($id)
    {
        $patient = Patient::where('id', $id)->first();
        $apt = Appointment::where('patient_id', $id)->first();
        $doctor = Doctor::where('id', $apt->doctor_id)->value('doctor_name');

        $doctor = Doctor::where('id', $apt->doctor_id)->value('doctor_name');
        return view('clinical_notes.soap_ot', compact('patient', 'apt', 'doctor'));
    }

    public function soap_pt($id)
    {
        $patient = Patient::where('id', $id)->first();
        $apt = Appointment::where('patient_id', $id)->first();
        $doctor = Doctor::where('id', $apt->doctor_id)->value('doctor_name');

        return view('clinical_notes.soap_pt', compact('patient', 'apt', 'doctor'));
    }

    //opt_pediatrics

    public function otatp_pedriatic($id)
    {
        $patient = Patient::where('id', $id)->first();
        $apt = Appointment::where('patient_id', $id)->first();
        $doctor = Doctor::where('id', $apt->doctor_id)->value('doctor_name');
        return view('clinical_notes.otatp_pediatric', compact('patient', 'apt', 'doctor'));
    }






    public function neuro_pedriatic($id)
    {
        $patient = Patient::find($id);
        $apt = Appointment::where('patient_id', $id)->first();
        $doctor = $apt ? Doctor::where('id', $apt->doctor_id)->value('doctor_name') : null;
        $note = ClinicalNotes::where('patient_id', $id)->first();

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
        $doctor = Doctor::where('id', $apt->doctor_id)->value('doctor_name');

        return view('clinical_notes.otatp_orthopedic', compact('patient', 'apt', 'doctor'));
    }

    public function physical_dysfunction($id)
    {
        $patient = Patient::where('id', $id)->first();

        $apt = Appointment::where('patient_id', $id)->first();
        $doctor = Doctor::where('id', $apt->doctor_id)->value('doctor_name');

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
    $appointment->session_status=7;
    $appointment->save();

    return redirect()->back()->with('success', 'Data saved successfully.');
}


public function update_neuro_pedriatic(Request $request, $id)
{
    $user_id = Auth::id();
    $user = Auth::user();
    $branch_id = $user->branch_id;

    $neuroPediatric = ClinicalNotes::where('id',$id)->first();

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

    return redirect()->back()->with('success', 'Data updated successfully.');
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
    $appointment->session_status=7;
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

    $neuroPediatric = ClinicalNotes::where('id',$id)->first();

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

    return redirect()->back()->with('success', 'Data updated successfully.');
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
    $appointment->session_status=7;
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

    $neuroPediatric = ClinicalNotes::where('id',$id)->first();



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

    return redirect()->back()->with('success', 'Data updated successfully.');
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
    $appointment->session_status=7;
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

    $neuroPediatric = ClinicalNotes::where('id',$id)->first();

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

    return redirect()->back()->with('success', 'Data updated successfully.');
}

public function add_soap_ot(Request $request)
{
    $user_id = Auth::id();
    $user = Auth::user();
    $branch_id = $user->branch_id;

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

    $neuroPediatric = new ClinicalNotes();
    $neuroPediatric->form_data = json_encode($soapData);
    $neuroPediatric->form_type = 'SOAP_OT';
    $neuroPediatric->notes_status = 5;
    $neuroPediatric->doctor_id = $request->doctor_id;
    $neuroPediatric->appointment_id = $request->appointment_id;
    $neuroPediatric->added_by = $user_id;
    $neuroPediatric->user_id = $user_id;
    $neuroPediatric->branch_id = $branch_id;

    $neuroPediatric->patient_id =  $request->patient_id;



    if (!is_null($neuroPediatric->appointment_id)) {

        $session_apt = AppointmentDetail::where('appointment_id', $neuroPediatric->appointment_id)->first();

        if ($session_apt) {
            $session_apt->sessions_taken += 1;
            $session_apt->save();
        }

        $today = Carbon::today()->toDateString();

        $apt_session = AppointmentSession::where('appointment_id', $neuroPediatric->appointment_id)
            ->whereDate('session_date', $today) // make sure 'session_date' is the correct column
            ->orderBy('id', 'asc')
            ->first();

        if ($apt_session) {
            $apt_session->status = 2;
            $apt_session->save();
        }
    }

    $neuroPediatric->save();





    return redirect()->back()->with('success', 'Data saved successfully.');
}

public function edit_soap_ot($id)
{

    $note = ClinicalNotes::findOrFail($id);
    $patient = Patient::find($note->patient_id);

    $apt = Appointment::where('patient_id', $note->patient_id)->first();

    $form_data = json_decode($note->form_data, true);



    return view('clinical_notes.edit_soap_ot', [
        'note' => $note,
        'patient' => $patient,
        'apt' => $apt,
        'data' => $form_data,
    ]);
}

public function update_soap_ot(Request $request, $id)
{
    $user_id = Auth::id();
    $user = Auth::user();
    $branch_id = $user->branch_id;

    $soapData = [
        'pt' => $request->pt,
        'soap_sections' => [],
    ];

    if (is_array($request->date)) {
        for ($i = 0; $i < count($request->date); $i++) {
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

    $note = ClinicalNotes::findOrFail($id);
    $note->form_data = json_encode($soapData);
    $note->doctor_id = $request->doctor_id;
    $note->appointment_id = $request->appointment_id;
    $note->form_type = 'SOAP_OT';
    $note->notes_status = 5;
    $note->updated_by = $user_id;
    $note->user_id = $user_id;
    $note->branch_id = $branch_id;
    $note->patient_id = $request->patient_id;

    $note->save();

    return redirect()->back()->with('success', 'Data updated successfully.');
}

public function add_soap_pt(Request $request)
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

    $soapData = [
        'pt' => $request->pt,
        'soap_sections' => [],
    ];

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
                'image_path' => $imagePath,
            ];
        }
    }

    $note = new ClinicalNotes();
    $note->form_data = json_encode($soapData);
    $note->form_type = 'SOAP_PT';
    $note->notes_status = 6;
    $note->doctor_id = $request->doctor_id;
    $note->appointment_id = $request->appointment_id;
    $note->added_by = $user_id;
    $note->user_id = $user_id;
    $note->branch_id = $branch_id;
    $note->patient_id = $request->patient_id;

    $note->save();

    if (!is_null($note->appointment_id)) {
        $session_apt = AppointmentSession::where('appointment_id', $note->appointment_id)->first();

        if ($session_apt) {
            $session_apt->sessions_taken += 1;
            $session_apt->save();
        }

        $today = Carbon::today()->toDateString();

        $apt_session = AppointmentSession::where('appointment_id', $note->appointment_id)
            ->whereDate('session_date', $today)
            ->orderBy('id', 'asc')
            ->first();

        if ($apt_session) {
            $apt_session->status = 2;
            $apt_session->save();
        }
    }

    return redirect()->back()->with('success', 'Data saved successfully.');
}


public function edit_soap_pt($id)
{

    $note = ClinicalNotes::findOrFail($id);
    $patient = Patient::find($note->patient_id);

    $apt = Appointment::where('patient_id', $note->patient_id)->first();

    $form_data = json_decode($note->form_data, true);



    return view('clinical_notes.edit_soap_pt', [
        'note' => $note,
        'patient' => $patient,
        'apt' => $apt,
        'data' => $form_data,
    ]);
}

public function update_soap_pt(Request $request, $id)
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
                'image_path' => $imagePath, // 🟡 use single imagePath here, per note

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

    $note = ClinicalNotes::findOrFail($id);
    $note->form_data = json_encode($soapData);
    $note->form_type = 'SOAP_PT';
    $note->notes_status = 5;
    $note->doctor_id = $request->doctor_id;
    $note->appointment_id = $request->appointment_id;
    $note->added_by = $user_id;
    $note->user_id = $user_id;
    $note->branch_id = $branch_id;
    $note->patient_id = $request->patient_id;

    $note->save();

    return redirect()->back()->with('success', 'SOAP PT updated successfully.');
}


}
