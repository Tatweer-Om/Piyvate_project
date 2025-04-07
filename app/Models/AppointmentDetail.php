<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentDetail extends Model
{
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id'); // Assuming doctor_id is the foreign key in AppointmentDetail table
    }
}
