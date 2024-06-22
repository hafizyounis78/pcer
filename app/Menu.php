<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'menu_id', 'id');
    }
}
