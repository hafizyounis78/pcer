<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use  SoftDeletes;
   /* protected $appends = ['pain_file_id'];
    function PainFile()
    {
        return $this->hasMany(PainFile::class, 'patient_id', 'id');
    }

    public function getPainFileIdAttribute()
    {
        return $this->PainFile()->select('id')->where('isActive',17);
        
    }*/
}
