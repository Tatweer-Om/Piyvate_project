<?php

namespace App\Models;

use App\Models\Appointment;
use App\Models\SessionDetail;
use App\Models\AllSessioDetail;
use App\Models\AppointmentDetail;
use App\Models\AppointmentSession;
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
    public function appointmentDetails()
    {
        return $this->hasMany(AppointmentDetail::class, 'doctor_id'); // Assuming doctor_id is the foreign key in AppointmentDetail table
    }

    // Define the relationship with SessionDetail
    public function sessionDetails()
    {
        return $this->hasMany(SessionDetail::class, 'doctor_id'); // Assuming doctor_id is the foreign key in SessionDetail table
    }
}


