<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemsBatchTb extends Model
{
    protected $table='items_batch_tb';
    use SoftDeletes;
}
