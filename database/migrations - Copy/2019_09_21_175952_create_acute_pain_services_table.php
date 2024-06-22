<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcutePainServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acute_pain_services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pain_file_id');
            $table->foreign('pain_file_id')->references('id')->on('pain_files');
            $table->date('visit_date')->comment('visit_date');
            $table->boolean('first_war_injury')->nullable()->comment('Yes/No');
            $table->integer('injury_mechanism')->nullable()->comment('lookups 29');
            $table->string('specify_injury_mechanism')->nullable();
            $table->integer('status')->nullable()->comment('Lookups 34');
            $table->string('specify_status')->nullable();
            $table->integer('timing_of_consultation')->nullable()->comment('lookups 40');
            $table->string('specify_timing_of_consultation')->nullable()->comment('Specify Tome Of Consultation');
            $table->boolean('medication_before_injury')->nullable()->comment('Yes/No');
            $table->string('other_medication_before_injury')->nullable();
            $table->boolean('medication_now')->nullable()->comment('Yes/No');
            $table->string('planned_further_treatment')->nullable();
            $table->boolean('neuro_history_of_pain')->nullable()->comment('Yes/No');
            $table->integer('neuro_pain_localized')->nullable()->comment('Pain Loc. Lookups 45');
            $table->string('neuro_stump_distribution_of_pain')->nullable();
            $table->string('neuro_phantom_type_of_plp')->nullable();
            $table->string('neuro_other_finding')->nullable();
            $table->unsignedInteger('second_doctor_id')->nullable()->comment('User Id');
            $table->foreign('second_doctor_id')->references('id')->on('users');
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
        Schema::dropIfExists('acute_pain_services');
    }
}
