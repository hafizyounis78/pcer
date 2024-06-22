<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FollowupLastTreatmentGoal extends Model
{
    protected $appends=['goal_text'];
    use  SoftDeletes;

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
