<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaselineMedicationBeforeInjuriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baseline_medication_before_injuries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('acute_pain_id');
            $table->foreign('acute_pain_id')->references('id')->on('acute_pain_services');
            $table->unsignedInteger('drug_id')->comment('medication_id');
            $table->foreign('drug_id')->references('id')->on('drug_lists');
            $table->unsignedInteger('created_by')->comment('User Id');
            $table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('baseline_medication_before_injuries');
    }
}
