<?php

namespace App\Models;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }
}
