<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectFollowupDoctor extends Model
{
    use SoftDeletes;
    protected $table='project_followup_doctor';
}
