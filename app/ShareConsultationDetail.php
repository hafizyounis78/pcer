<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShareConsultationDetail extends Model
{
    use  SoftDeletes;
    protected $appends = ['user_name'];
    function users()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function getUserNameAttribute()
    {
        $model = $this->users()->first();
        if (isset($model))
            return $model->name;
        return '';
    }
}
