<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GovtDept extends Model
{
    public function ministrycats()
    {
        return $this->hasManyThrough(
            Ministrycat::class,
            Sation::class,
            'government_id',      // Foreign key on Sation table (refers to GovtDept)
            'id',                 // Local key on Ministrycat (we are matching on Sation's ministry_cat_id)
            'id',                 // Local key on GovtDept
            'ministry_cat_id'     // Foreign key on Sation table (refers to Ministrycat)
        );
    }

}
