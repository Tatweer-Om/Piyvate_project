<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }
}
