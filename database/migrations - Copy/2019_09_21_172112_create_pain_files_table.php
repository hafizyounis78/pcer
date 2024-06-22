<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePainFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pain_files', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('patient_id')->comment('Patient Table Id');
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->date('start_date')->comment('Open File Date');
            $table->date('close_date')->nullable()->comment('Close File Date');
            $table->integer('status')->default(1)->comment('file Status lookups code 16');
            $table->string('note')->nullable()->comment('Close Note');
            $table->unsignedInteger('created_by')->comment('User Id');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('org_id')->comment('Organizations');
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
        Schema::dropIfExists('pain_files');
    }
}
