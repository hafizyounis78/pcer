<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQutenzaTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qutenza_treatments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pain_file_id');
            $table->foreign('pain_file_id')->references('id')->on('pain_files');
            $table->date('visit_date')->comment('visit_date');
            $table->integer('visit_type')->comment('1=baseline,2=followup');
            $table->unsignedInteger('followup_id');
            // $table->foreign('followup_id')->references('id')->on('followup_patients');
            $table->boolean('allodynia')->default('0')->comment('1=Yes/0=No');
            $table->decimal('application_time', 3, 2)->comment('Application time'); // instead of float
            $table->boolean('erythema')->default('0')->comment('1=Yes/0=No');
            $table->boolean('pain')->default('0')->comment('1=Yes/0=No');
            $table->boolean('pruritus')->default('0')->comment('1=Yes/0=No');
            $table->boolean('papules')->default('0')->comment('1=Yes/0=No');
            $table->boolean('edema')->default('0')->comment('1=Yes/0=No');
            $table->boolean('swelling')->default('0')->comment('1=Yes/0=No');
            $table->boolean('dryness')->default('0')->comment('1=Yes/0=No');
            $table->boolean('nasopharyngitis')->default('0')->comment('1=Yes/0=No');
            $table->boolean('bronchitis')->default('0')->comment('1=Yes/0=No');
            $table->boolean('sinusitis')->default('0')->comment('1=Yes/0=No');
            $table->boolean('nausea')->default('0')->comment('1=Yes/0=No');
            $table->boolean('vomiting')->default('0')->comment('1=Yes/0=No');
            $table->boolean('skin_pruritus')->default('0')->comment('1=Yes/0=No');
            $table->boolean('hypertension')->comment('1=Yes/0=No');
            $table->integer('hypertension_systolic')->comment('B.P systolic');
            $table->integer('hypertension_diastolic')->comment('B.P diastolic');
           // $table->integer('after_ttt_systolic')->comment('After ttt B.P systolic');
           // $table->integer('after_ttt_diastolic')->comment('After ttt B.P diastolic');
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
        Schema::dropIfExists('qutenza_treatments');
    }
}
