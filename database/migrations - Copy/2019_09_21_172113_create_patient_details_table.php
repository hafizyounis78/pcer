<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pain_file_id')->comment('PainFile Table Id');
            $table->foreign('pain_file_id')->references('id')->on('pain_files');
            $table->integer('no_of_child')->nullable()->comment('No Of Child');
            $table->integer('education')->nullable()->comment('Education lookups 19');
            $table->integer('current_work')->nullable()->comment('Current work lookups 24');
            $table->integer('weekly_hours')->nullable()->comment('Weekly Hours');
            $table->float('monthly_income')->nullable()->comment('Monthly Income');
            $table->boolean('isProvider')->nullable()->comment('Family provider Yes/No');
            $table->boolean('isOnlyProvider')->nullable()->comment('only family provider Yes/No');
            $table->integer('num_of_family')->nullable()->comment('Number of Family');
            $table->integer('isSmoke')->nullable()->comment('is smoke');
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
        Schema::dropIfExists('patient_details');
    }
}
