<?php

namespace App\Models;

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
}
