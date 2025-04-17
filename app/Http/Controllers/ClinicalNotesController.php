<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\ClinicalNotes;
use App\Models\Doctor;
use App\Models\Otppediatric;
use Illuminate\Http\Request;

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

    public function physical_dysfunction($id)
    {
        $patient = Patient::where('id', $id)->first();
        $apt = Appointment::where('patient_id', $id)->first();
        $doctor = Doctor::where('id', $apt->doctor_id)->value('doctor_name');

        return view('clinical_notes.otatp_physical_dysfunction', compact('patient', 'apt', 'doctor'));
    }

    public function otatp_ortho($id)
    {
        $patient = Patient::where('id', $id)->first();

        $apt = Appointment::where('patient_id', $id)->first();
        $doctor = Doctor::where('id', $apt->doctor_id)->value('doctor_name');

        return view('clinical_notes.otatp_orthopedic', compact('patient', 'apt', 'doctor'));
    }




    //neuro_add

    public function add_neuro_pedriatic(Request $request)
{

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
    $neuroPediatric->patient_id =  $request->patient_id;

    $neuroPediatric->save();

    return redirect()->back()->with('success', 'Data saved successfully.');
}

public function update_neuro_pediatric(Request $request, $id)
{
    $neuroPediatric = ClinicalNotes::findOrFail($id);

    // Handle base64 canvas image update
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
        // If no new image, keep the existing one
        $existingData = json_decode($neuroPediatric->form_data, true);
        $imagePath = $existingData['canvas_image'] ?? null;
    }

    $data = [
        'hn' => $request->hn,
        'pt' => $request->pt,
        'checkbox_tl' => $request->has('checkbox_tl') ? 1 : 0,
        'checkbox_tr' => $request->has('checkbox_tr') ? 1 : 0,
        'checkbox_bl' => $request->has('checkbox_bl') ? 1 : 0,
        'checkbox_br' => $request->has('checkbox_br') ? 1 : 0,
        'canvas_image' => $imagePath,
        'full_name' => $request->full_name,
        'age' => $request->age,
        'gender' => $request->gender_m ? 'Male' : ($request->gender_f ? 'Female' : null),
        'dob' => $request->dob,
        'therapist' => $request->therapist,
        'appointment_id' => $request->appointment_id,
        'patient_id' => $request->patient_id,
        'doctor_id' => $request->doctor_id,
        'bp' => $request->bp,
        'pr' => $request->pr,
        'rr' => $request->rr,
        't' => $request->t,
        'o2sat' => $request->o2sat,
        'bw' => $request->bw,
        'chief_complaint' => $request->chief_complaint,
        'history_of_illness' => $request->history_of_illness,
        'underlying' => $request->underlying,
        'precaution' => $request->precaution,
        'operation' => $request->operation,
        'lab_result' => $request->lab_result,
        'level_of_consciousness' => $request->level_of_consciousness,
        'interpreter' => $request->interpreter,
        'observation' => $request->observation,
        'muscle_tone' => $request->muscle_tone,
        'muscle_strength' => $request->muscle_strength,
        'sensation' => $request->sensation,
        'asia' => $request->asia,
        'bed_mobility' => $request->bed_mobility,
        'transfer' => $request->transfer,
        'stream_score' => [
            'upper_extremity' => $request->score_upper,
            'lower_extremity' => $request->score_lower,
            'basic_mobility' => $request->score_mobility,
            'total' => $request->score_total,
        ],
        'stream_percent' => [
            'upper_extremity' => $request->percent_upper,
            'lower_extremity' => $request->percent_lower,
            'basic_mobility' => $request->percent_mobility,
            'total' => $request->percent_total,
        ],
        'berg_balance_scale' => [
            'sit_to_standing' => $request->sit_to_standing,
            'eyes_closed' => $request->eyes_closed,
            'turning_360' => $request->turning_360,
            'standing_unsupported' => $request->standing_unsupported,
            'feet_together' => $request->feet_together,
            'foot_on_stool' => $request->foot_on_stool,
            'sitting_unsupported' => $request->sitting_unsupported,
            'reaching_forward' => $request->reaching_forward,
            'foot_in_front' => $request->foot_in_front,
            'standing_to_sitting' => $request->standing_to_sitting,
            'retrieve_object' => $request->retrieve_object,
            'one_foot' => $request->one_foot,
            'transfer' => $request->transfer,
            'look_behind' => $request->look_behind,
            'total_score' => $request->total_score,
        ],
        'gmfm_scores' => [
            'lying_rolling' => $request->lying_rolling,
            'sitting' => $request->sitting,
            'crawling_kneeling' => $request->crawling_kneeling,
            'standing' => $request->standing,
            'walking_running' => $request->walking_running,
            'total_score' => $request->total_score_gmfm,
        ],
        'gait_analysis' => $request->gait_analysis,
        'others' => $request->others,
        'adl_feeding' => [
            'dependent' => $request->feeding_dependent,
            'independent' => $request->feeding_independent,
        ],
        'adl_bathing' => [
            'dependent' => $request->bathing_dependent,
            'independent' => $request->bathing_independent,
        ],
        'adl_dressing' => [
            'dependent' => $request->dressing_dependent,
            'independent' => $request->dressing_independent,
        ],
        'adl_sleeping' => [
            'dependent' => $request->sleeping_dependent,
            'independent' => $request->sleeping_independent,
        ],
        'adl_carrying' => [
            'dependent' => $request->carrying_dependent,
            'independent' => $request->carrying_independent,
        ],
        'fall_risk_assessment' => $request->fall_risk_assessment,
        'pain_assessment' => [
            'pain' => $request->pain,
            'location' => $request->pain_location,
            'duration' => $request->pain_duration,
            'frequency' => $request->pain_frequency,
            'reassessment_score' => $request->pain_reassessment_score,
        ],
        'assessment_tool' => $request->assessment_tool,
        'pt_diagnosis' => $request->pt_diagnosis,
        'treatment_goals' => [
            'long_term' => $request->long_term_goal,
            'short_term' => $request->short_term_goal,
        ]
    ];

    $neuroPediatric->form_data = json_encode($data);
    $neuroPediatric->form_type = 'PT Neuro and Pediatric';
    $neuroPediatric->notes_status = 1;
    $neuroPediatric->doctor_id = $request->doctor_id;
    $neuroPediatric->appointment_id = $request->appointment_id;
    $neuroPediatric->patient_id =  $request->patient_id;

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

}
