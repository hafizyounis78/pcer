<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaselineMentalHealthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baseline_mental_healths', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pain_file_id');
            $table->foreign('pain_file_id')->references('id')->on('pain_files');
            $table->integer('eating_disorder')->comment('Do you have any eating disorder?Yes/No');
            $table->integer('sleep_disturbances')->comment('Do you suffer from sleep disturbances?Yes/No');
            $table->integer('psychopath_in_family')->comment('Is there a psychopath in the family?Yes/No');
            $table->string('family_relationship')->comment('If yes (relationship)?');
            $table->integer('psychological_problems')->comment('Have you ever had psychological problems?Yes/No');
            $table->integer('social_problem')->comment('Do you have any social problem?Yes/No');
            $table->integer('ability_control_actions')->comment('What is the patients ability to control his actions?Lookup 505');
            $table->integer('ability_control_words')->comment('What is the patients ability to control his words?Lookup 505');
            $table->integer('suicidal_thoughts')->comment('Do you have suicidal thoughts?Yes/No');
            $table->integer('attacked_or_bullied')->comment('Have you been attacked or bullied?Yes/No');
            $table->integer('bad_dreams')->comment('Are you experiencing bad dreams?Yes/No');
            $table->integer('opioids')->comment('Do you take opioids?Yes/No');
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
        Schema::dropIfExists('baseline_mental_healths');
    }
}
