<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaselineTreatmentGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baseline_treatment_goals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pain_file_id');
            $table->foreign('pain_file_id')->references('id')->on('pain_files');
            $table->string('baseline_goal')->nullable();
            $table->integer('baseline_score')->nullable()->comment('list 0-10');
            $table->integer('baseline_goal_score')->nullable()->comment('list 0-10');
            $table->unsignedInteger('org_id');
            $table->foreign('org_id')->references('id')->on('organizations');
            $table->unsignedInteger('created_by')->comment('User Id');
            $table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('baseline_treatment_goals');
    }
}
