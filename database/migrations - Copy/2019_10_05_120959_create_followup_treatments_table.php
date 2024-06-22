<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowupTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followup_treatments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pain_file_id');
            $table->foreign('pain_file_id')->references('id')->on('pain_files');
            $table->unsignedInteger('followup_id');
            $table->foreign('followup_id')->references('id')->on('followup_patients');
            $table->integer('followup_score')->nullable();
            $table->unsignedInteger('drug_id');
            $table->foreign('drug_id')->references('id')->on('drug_lists');
            $table->string('drug_specify')->nullable()->comment('specify');
            $table->string('concentration')->nullable()->comment('concentration');
            $table->string('dosage')->nullable()->comment('pharmacist dosage');
            $table->integer('frequency')->nullable()->comment('frequency');
            $table->integer('duration')->nullable()->comment('duration');
            $table->integer('quantity')->nullable()->comment('Quantity=dosage*frequency*duration');
            $table->integer('compliance')->nullable()->comment('pharmacist Compliance lookups 88');
            $table->integer('adverse_effects')->nullable()->comment('pharmacist Adverse effects lookups 92');
            $table->integer('decision')->nullable()->comment('pharmacist Decision lookups 115');
            $table->string('specify_other_adverse_effects')->nullable()->comment('pharmcist other adverse effects specify');
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
        Schema::dropIfExists('followup_treatments');
    }
}
