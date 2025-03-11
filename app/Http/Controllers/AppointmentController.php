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

}
