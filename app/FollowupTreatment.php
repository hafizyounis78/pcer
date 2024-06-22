<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FollowupTreatment extends Model
{
    protected $appends = ['Drug_name'];


    use  SoftDeletes;
    function followDrugs()
    {
        return $this->hasMany(DrugList::class, 'drug_id', 'id');
    }
    function followup()
    {
        return $this->belongsTo(FollowupPatient::class, 'followup_id', 'id');
    }
    public function getDrugNameAttribute()
    {
        $model = $this->followDrugs()->first();


        if (isset($model)) {

            return $model->name;

        }

        return '';
    }

}
