<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointments extends Model
{
    use SoftDeletes;
    function painFile()
    {
        return $this->belongsTo(PainFile::class, 'pain_file_id', 'id');
    }
}
