<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;

    public function Users()
    {
        return $this->hasMany(User::class,'org_id','id');
    }
}
