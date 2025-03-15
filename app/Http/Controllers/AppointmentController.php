<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Doctor;
use App\Models\Country;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function appointments(){
        $doctors = Doctor::all();
        $branches = Branch::all();
        $countries = Country::all();

        return view('appointments.appointments', compact('doctors', 'branches', 'countries'));
    }

   public function show_appointments(){
    return view ('appointments.all_appointments');
   }


   public function add_appointment(Request $request)
   {

       $full_name =$request->title . ' ' .  $request->first_name . ' ' . $request->second_name;
   
       try {
           $appointment = new Appointment();
           $appointment->title = $request->title;
           $appointment->first_name = $request->first_name;
           $appointment->second_name = $request->second_name;
           $appointment->full_name = $full_name;
           $appointment->mobile = $request->mobile;
           $appointment->id_passport = $request->id_passport;
           $appointment->dob = $request->dob;
           $appointment->country_id = $request->country;
           $appointment->doctor_id = $request->doctor;
           $appointment->appointment_date = $request->appointment_date;
           $appointment->time_from = $request->time_from;
           $appointment->time_to = $request->time_to;
           $appointment->notes = $request->notes;
           $appointment->save();
   
           return response()->json(['success' => trans('messages.appointment_add_success_lang')]);
   
       } catch (\Exception $e) {
           return response()->json(['error' => trans('messages.appointment_add_failed_lang'), 'message' => $e->getMessage()], 500);
       }
   }
   


}
