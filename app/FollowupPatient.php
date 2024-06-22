<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FollowupPatient extends Model
{
    use  SoftDeletes;
    function painFile()
    {
        return $this->belongsTo(PainFile::class, 'pain_file_id', 'id');
    }
    function followTreatments()
    {
        return $this->hasMany(FollowupTreatment::class, 'followup_id', 'id');
    }

}
