<?php

namespace App\Models;

use App\Models\SessionsonlyPayment;
use Illuminate\Database\Eloquent\Model;

class SessionList extends Model
{
    public function payment()
    {
        return $this->hasOne(SessionsonlyPayment::class, 'session_id', 'id');
    }
}
