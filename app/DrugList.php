<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DrugList extends Model
{
    use  SoftDeletes;
    function followTreatment()
    {
        return $this->belongsTo(FollowupTreatment::class, 'pain_file_id', 'id');
    }
}
