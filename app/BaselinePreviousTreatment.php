<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaselinePreviousTreatment extends Model
{
    use  SoftDeletes;
    protected $table ='baseline_previous_treatment';
}
