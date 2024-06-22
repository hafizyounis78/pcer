<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemsTb extends Model
{
    protected $table='items_tb';
    use  SoftDeletes;
}
