<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaselineDoctorConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baseline_doctor_consultations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pain_file_id');
            $table->foreign('pain_file_id')->references('id')->on('pain_files');
            $table->date('visit_date')->comment('visit_date');
            $table->integer('injury_mechanism')->nullable()->comment('lookup 29');
            $table->string('specify_injury_mechanism')->nullable()->comment('specify injury mechanism');
            $table->boolean('other_pains_before_injury')->nullable()->comment('Yes/No');
            $table->string('specify_other_pains_before_injury')->nullable();
            $table->boolean('other_nonpain_symptoms')->nullable()->comment('Yes/No');
            $table->string('specify_other_nonpain_symptoms')->nullable();
            $table->boolean('comorbidities')->nullable()->comment('Yes/No');
            $table->string('specify_comorbidities')->nullable();
            $table->boolean('allergies')->nullable()->comment('Yes/No');
            $table->string('specify_allergies')->nullable();

            $table->integer('no_treatment_offered_due_to')->nullable()->comment('lookup 128');
            $table->string('specify_no_treatment_offered_due_to')->nullable()->comment('specify no treatment_offered due to');

            $table->boolean('previous_surgery')->nullable()->comment('Yes/No');
            $table->string('specify_previous_surgery')->nullable();
            $table->boolean('active_rehabilitation')->nullable()->comment('Yes/No');
            $table->string('specify_active_rehabilitation')->nullable();

            $table->boolean('passive_rehabilitation')->nullable()->comment('Yes/No');
            $table->string('specify_passive_rehabilitation')->nullable();

            $table->boolean('nonpharmacological_treatments')->nullable()->comment('Yes/No');
            $table->string('specify_nonpharmacological_treatments')->nullable();
            $table->boolean('take_drug')->nullable()->comment('Yes/No');
            $table->string('other_drugs')->nullable()->comment('Other Drugs');
            $table->boolean('neuro_history_of_pain')->nullable()->comment('Yes/No');
            $table->integer('neuro_pain_localized')->nullable()->comment('Pain Loc. Lookups 45');
            $table->string('neuro_stump_distribution_of_pain')->nullable();
            $table->string('neuro_phantom_type_of_plp')->nullable();
            $table->string('neuro_other_finding')->nullable();
            $table->boolean('diagnostic_additional_ptsd')->nullable();

            $table->string('diagnostic_specify_combination')->nullable();
            $table->integer('physical_treatment')->nullable()->comment('Physical treatment lookups 83');
            $table->string('specify_physical_treatment')->nullable();
            $table->boolean('pharmacological_treatment')->nullable()->comment('Pharmacological treatment Yes/No');
            $table->string('other_treatments')->nullable()->comment('Other treatments');
            $table->string('doctor_message')->nullable()->comment('Doctor Message');
            $table->unsignedInteger('second_doctor_id')->nullable()->comment('User Id');
            $table->foreign('second_doctor_id')->references('id')->on('users');
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
        Schema::dropIfExists('baseline_doctor_consultations');
    }
}
