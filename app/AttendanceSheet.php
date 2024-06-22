<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceSheet extends Model
{ 
    protected $appends=['user_name','title_id'];
    use SoftDeletes;

       public function user()
    {
        return $this->belongsTo(User::class,'user_id','national_id');
    }
    function getUserNameAttribute($key)
    {
        $emp = $this->user()->first();
        return $emp->name;
    }
    function getTitleIdAttribute($key)
    {
        $emp = $this->user()->first();
        return $emp->title_id;
    }
}
