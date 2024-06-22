<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pain_file_id');
            $table->foreign('pain_file_id')->references('id')->on('pain_files');
            $table->dateTime('appointment_date')->nullable()->comment('Appointment Date');
            $table->integer('appointment_type')->nullable()->comment('1=New,2=Follow up');
            $table->integer('appointment_dept')->nullable()->comment('1=doctor,2=nurse,3=pharm');
            $table->integer('appointment_loc')->nullable()->comment('To doctor Ahmend or Ehab,.. ,or nurse,..');
            $table->dateTime('attend_date')->nullable()->comment(' Attendance Date');
            $table->string('comments')->nullable()->comment('Notes and Comments');
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
        Schema::dropIfExists('appointments');
    }
}
