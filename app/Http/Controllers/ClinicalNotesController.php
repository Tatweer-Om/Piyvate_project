<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
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



public function add_otp_pediatric(Request $request)
{

    $otp = new Otppediatric();
    $otp->patient_id = $data['patient_id'] ?? null;
    $otp->appointment_id = $data['appointment_id'] ?? null;
    $otp->hn = $data['hn'] ?? null;
    $otp->pt_no = $data['pt_no'] ?? null;
    $otp->therapist = $data['doctor_id'] ?? null;
    $otp->chief_complaint = $data['chief_complaint'] ?? null;
    $otp->general_appearance = $data['general_appearance'] ?? null;
    $otp->birth_history = $data['birth_history'] ?? null;
    $otp->behavioural_issues = $data['behavioural_issues'] ?? null;

    $otp->gross_motor = $data['gross_motor'] ?? null;
    $otp->fine_motor = $data['fine_motor'] ?? null;
    $otp->language = $data['language'] ?? null;
    $otp->personal_social = $data['personal_social'] ?? null;
    $otp->cognitive_function = $data['cognitive_function'] ?? null;

    $otp->vestibular = $data['vestibular'] ?? null;
    $otp->proprioceptive = $data['proprioceptive'] ?? null;
    $otp->tactile = $data['tactile'] ?? null;
    $otp->muscle_tone_upper = $data['muscle_tone_upper'] ?? null;
    $otp->muscle_tone_lower = $data['muscle_tone_lower'] ?? null;
    $otp->sensation = $data['sensation'] ?? null;
    $otp->rom = $data['rom'] ?? null;
    $otp->hand_use = $data['hand_use'] ?? null;
    $otp->oro_motor = $data['oro_motor'] ?? null;
    $otp->oral_reflexes = $data['oral_reflexes'] ?? null;
    $otp->adl = $data['adl'] ?? null;
    $otp->visual_perception = $data['visual_perception'] ?? null;
    $otp->reflexes = $data['reflexes'] ?? null;

    $otp->fall_risk = $data['fall_risk'] ?? null;
    $otp->pain_assessment = $data['pain_assessment'] ?? null;
    $otp->pain_score = $data['pain_score'] ?? null;
    $otp->pain_location = $data['pain_location'] ?? null;
    $otp->pain_duration = $data['pain_duration'] ?? null;
    $otp->pain_characteristic = $data['pain_characteristic'] ?? null;
    $otp->pain_frequency = $data['pain_frequency'] ?? null;

    $otp->ot_diagnosis = $data['ot_diagnosis'] ?? null;
    $otp->ot_program = $data['ot_program'] ?? null;
    $otp->short_term_goal = $data['short_term_goal'] ?? null;
    $otp->long_term_goal = $data['long_term_goal'] ?? null;
    $otp->education = $data['education'] ?? null;
    $otp->therapist_name = $data['therapist_name'] ?? null;
    $otp->date = $data['date'] ?? null;
    $otp->time = $data['time'] ?? null;

    $otp->pain_tool = isset($data['pain_tool']) ? implode(',', $data['pain_tool']) : null;

    $otp->save();

    return redirect()->back()->with('success', 'Form submitted successfully.');
}


    public function neuro_pedriatic($id)
    {
        $patient = Patient::where('id', $id)->first();
        $apt = Appointment::where('patient_id', $id)->first();
        $doctor = Doctor::where('id', $apt->doctor_id)->value('doctor_name');

        return view('clinical_notes.otatp_neuro_pediatric', compact('patient', 'apt', 'doctor'));
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
}
