<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FollowupLast extends Model
{
    use  SoftDeletes;
    protected $table='followup_last';
    protected $appends = ['baseline_doctor_id'];

    function pain_file()
    {
        return $this->hasOne(PainFile::class, 'id', 'pain_file_id');
    }
    function baseline()
    {
        return $this->hasOne(BaselineDoctorConsultation::class, 'pain_file_id', 'pain_file_id');
    }
    public function getBaselineDoctorIdAttribute()
    {
        $model = $this->baseline()->first();


        if (isset($model)) {

            $doctor_id = $model->created_by;
            if (isset($doctor_id))
                return $doctor_id;

        }

        return null;
    }
}
