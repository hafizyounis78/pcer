<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaselinePreviousTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baseline_previous_treatments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pain_file_id');
            $table->foreign('pain_file_id')->references('id')->on('pain_files');
            $table->boolean('previous_surgery')->comment('Previous surgery');
            $table->string('previous_surgery_details')->nullable();
            $table->boolean('prev_act_rehab')->nullable()->comment('Active rehabilitation');
            $table->string('prev_act_rehab_details')->nullable();
            $table->boolean('prev_pas_rehab')->nullable()->comment('Passive rehabilitation');
            $table->string('prev_pas_rehab_details')->nullable();
            $table->boolean('prev_other')->nullable()->comment('Other non-pharmacological treatments');
            $table->string('prev_other_details')->nullable();
            $table->integer('effect_id')->nullable()->comment('treatment_effects lookups 76');
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
        Schema::dropIfExists('baseline_previous_treatments');
    }
}
