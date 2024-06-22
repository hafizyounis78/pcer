<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowupTreatmentAdverseEffectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followup_treatment_adverse_effects', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('followup_treatment_id');
            $table->foreign('followup_treatment_id')->references('id')->on('followup_treatments');
            $table->integer('adverse_effects')->nullable()->comment('pharmacist Adverse effects lookups 92');
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
        Schema::dropIfExists('followup_treatment_adverse_effects');
    }
}
