<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemsBatchMovementsTb extends Model
{
    protected $table='items_batch_movements_tb';
    use SoftDeletes;
}
