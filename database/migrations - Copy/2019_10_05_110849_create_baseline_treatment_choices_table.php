<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaselineTreatmentChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baseline_treatment_choices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pain_file_id');
            $table->foreign('pain_file_id')->references('id')->on('pain_files');
            $table->unsignedInteger('drug_id');
            $table->foreign('drug_id')->references('id')->on('drug_lists');
            $table->string('drug_specify')->nullable()->comment('specify');
            $table->string('concentration')->nullable()->comment('concentration');
            $table->string('dosage')->nullable()->comment('dosage');
            $table->integer('frequency')->nullable()->comment('frequency');
            $table->integer('duration')->nullable()->comment('duration');
            $table->integer('quantity')->nullable()->comment('Quantity=dosage*frequency*duration');
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
        Schema::dropIfExists('baseline_treatment_choices');
    }
}
