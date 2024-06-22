<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowupLastTreatmentGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followup_last_treatment_goals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pain_file_id');
            $table->foreign('pain_file_id')->references('id')->on('pain_files');
            $table->unsignedInteger('followup_id');
            $table->foreign('followup_id')->references('id')->on('followup_last');
            $table->unsignedInteger('baseline_goal_id');
            $table->foreign('baseline_goal_id')->references('id')->on('baseline_treatment_goals');
            $table->integer('followup_score')->nullable()->comment('list 0-10');
            $table->unsignedInteger('created_by')->comment('User Id');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('org_id');
            $table->foreign('org_id')->references('id')->on('organizations');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('followup_last_treatment_goals');
    }
}
