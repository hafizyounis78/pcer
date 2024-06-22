<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FollowupTreatmentGoal extends Model
{
    use  SoftDeletes;
    protected $appends = ['goal_text'];

    function BaseLineFollowup()
    {
        return $this->hasOne(BaselineTreatmentGoal::class, 'id', 'baseline_goal_id');
    }

    public function getGoalTextAttribute()
    {
        $model = $this->BaseLineFollowup()->first();
       // dd($model);

        if (isset($model))
            return $model->baseline_goal;
        return '';
    }
}
