<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowupDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followup_doctors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pain_file_id');
            $table->foreign('pain_file_id')->references('id')->on('pain_files');
            $table->unsignedInteger('followup_id');
            $table->foreign('followup_id')->references('id')->on('followup_patients');

            $table->date('follow_up_date');

            $table->unsignedInteger('second_doctor')->nullable()->comment('Doctor Id');
            $table->foreign('second_doctor')->references('id')->on('users');
            $table->string('treatment_goal_achievements')->nullable()->comment('Treatment goal achievements. Specify goal(s) if needed.');

            $table->integer('pain_scale')->nullable()->comment('Pain scale list 0-10');
            $table->integer('pain_bothersomeness')->nullable()->comment('Pain bothersomeness list 0-10');
            $table->integer('pain_intensity_during_rest')->nullable()->comment('pain intensity during rest list 0-10');
            $table->integer('pain_intensity_during_activity')->nullable()->comment('pain intensity during activity list 0-10');

            $table->boolean('change_diagnosis')->nullable()->comment('Change diagnosis? Yes/No');
            $table->boolean('additional_ptsd')->nullable()->comment('Additional PTSD? Yes/No');
            $table->boolean('change_treatment')->nullable()->comment('Change treatment? Yes/No');
            $table->integer('physical_treatment')->nullable()->comment('Physical treatment lookups 83');
            $table->string('specify_physical_treatment')->nullable();
            $table->string('last_scheduled_further_treatment')->nullable()->comment('If last scheduled consultation, specify if any further treatment plans');
            $table->string('last_scheduled_why_treatment_ended')->nullable()->comment('If last scheduled consultation, why was treatment ended?');

            $table->string('doctor_message')->nullable()->comment('Doctor Message');
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
        Schema::dropIfExists('followup_doctors');
    }
}
