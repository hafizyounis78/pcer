<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectFollowupPharm extends Model
{
    use SoftDeletes;
    protected $table='project_followup_pharm';
}
