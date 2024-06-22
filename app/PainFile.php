<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PainFile extends Model
{
    use  SoftDeletes;

    protected $appends = ['patient_name', 'patient_name_a', 'mobile_no', 'national_id', 'baseline_doctor', 'baseline_doctor_id', 'baseline_date', 'last_followup_date', 'followup_date'];


    function baseline()
    {
        return $this->hasOne(BaselineDoctorConsultation::class, 'pain_file_id', 'id');
    }

    public function getBaselineDoctorAttribute()
    {
        $model = $this->baseline()->first();


        if (isset($model)) {

            $doctor = User::find($model->created_by);
            if (isset($doctor))
                return $doctor->name;
        }

        return '';
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

    public function getBaselineDateAttribute()
    {
        $model = $this->baseline()->first();


        if (isset($model))
            return $model->visit_date;
        return '';
    }

    public function getLastFollowupDateAttribute()
    {
        $model = $this->last_followup()->orderBy('follow_up_date', 'desc')->first();
//dd($model);

        if (isset($model))
            return $model->follow_up_date;
        return '';
    }

    function followup()
    {
        return $this->hasMany(FollowupPatient::class, 'pain_file_id', 'id');
    }

    function last_followup()
    {
        return $this->hasOne(FollowupLast::class, 'pain_file_id', 'id');
    }

    function appointment()
    {
        return $this->hasMany(Appointments::class, 'pain_file_id', 'id');
    }

    public function getFollowupDateAttribute()
    {
        $model = $this->followup()->first();
//dd($model);

        if (isset($model))
            return $model->follow_up_date;
        return '';
    }

    public function getPatientNameAttribute()
    {
        $model = $this->patient()->first();


        if (isset($model))
            return $model->name;
        return '';
    }

    public function getPatientNameAAttribute()
    {
        $model = $this->patient()->first();


        if (isset($model))
            return $model->name_a;
        return '';
    }

    function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    public function getNationalIdAttribute()
    {
        $model = $this->patient()->first();


        if (isset($model))
            return $model->national_id;
        return '';
    }

    public function getMobileNoAttribute()
    {
        $model = $this->patient()->first();


        if (isset($model))
            return $model->mobile_no;
        return '';
    }

}
