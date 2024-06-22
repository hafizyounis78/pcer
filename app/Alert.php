<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alert extends Model
{
    protected $appends=['user_name'];
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    function getUserNameAttribute($key)
    {
        $emp = $this->user()->first();
        return $emp->name;
    }

}
