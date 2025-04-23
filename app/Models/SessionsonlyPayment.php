<?php

namespace App\Models;

use App\Models\SessionList;
use App\Models\AllSessioDetail;
use Illuminate\Database\Eloquent\Model;

class SessionsonlyPayment extends Model
{
    public function sessionList()
    {
        return $this->belongsTo(SessionList::class, 'session_id', 'id');
    }

public function sessionDetails()
{
    return $this->hasMany(AllSessioDetail::class, 'session_id', 'session_id');
}
}
