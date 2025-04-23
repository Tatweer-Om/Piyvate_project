<?php

namespace App\Models;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\SessionsonlyPayment;
use Illuminate\Database\Eloquent\Model;

class AllSessioDetail extends Model
{
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function payment()
{
    return $this->belongsTo(SessionsonlyPayment::class, 'session_id', 'session_id');
}
}
