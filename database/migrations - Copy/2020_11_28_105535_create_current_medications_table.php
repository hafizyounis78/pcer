<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrentMedicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('current_medications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('patient_id')->comment('Patient Table Id');
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->string('drug_desc');
            $table->string('drug_comments')->nullable();
            $table->date('stop_date')->nullable();
            $table->boolean('isActive')->default(1);//1=active,0=not active
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
        Schema::dropIfExists('current_medications');
    }
}
