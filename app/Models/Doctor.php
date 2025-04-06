<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    public function appointmentSessions()
    {
        return $this->hasMany(AppointmentSession::class, 'doctor_id');
    }

    public function allSessionDetails()
    {
        return $this->hasMany(AllSessioDetail::class, 'doctor_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }
}


