<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaselineDiagnosticsAlgrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baseline_diagnostics_algrs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pain_file_id');
            $table->foreign('pain_file_id')->references('id')->on('pain_files');

            $table->boolean('pain_dueto_neural_tissue')->nullable()->comment('Yes/No');
            $table->boolean('history_of_relevant_neurological_lesion')->nullable()->comment('Yes/No');
            $table->boolean('history_of_pain_distribution_is_neur_plausible')->nullable()->comment('Yes/No');
            $table->boolean('pain_associated_with_sensory_signs')->nullable()->comment('Yes/No');

            $table->boolean('neuropathic_pain')->nullable()->comment('Lookups 406');

            $table->boolean('pain_dueto_nonneural_tissue')->nullable()->comment('Yes/No');
            $table->boolean('history_of_relevant_nonneurological_lesion')->nullable()->comment('Yes/No');
            $table->boolean('history_of_pain_distribution_is_plausible')->nullable()->comment('Yes/No');
            $table->boolean('pain_consistently_correlated_with_nonneurological_lesion')->nullable()->comment('Yes/No');

            $table->boolean('nociceptive_pain')->nullable()->comment('Lookups 410');

            $table->boolean('Pain_independent_of_injuries_and_diseases')->nullable()->comment('Yes/No');
            $table->boolean('history_accordance_diagnostic_budapest_criteria')->nullable()->comment('Yes/No');

            $table->boolean('crps_pain')->nullable()->comment('Lookups 414');

            $table->boolean('pain_distribution_regional')->nullable()->comment('Yes/No');
            $table->boolean('pain_hypersensitivity_in_region_pain_during_assessment')->nullable()->comment('Yes/No');
            $table->boolean('pain_hypersensitivity_in_region_pain_dueto_touch')->nullable()->comment('Yes/No');

            $table->boolean('nociplastic_pain')->nullable()->comment('Lookups 418');

            $table->boolean('idiopathic_pain')->nullable()->comment('Lookups 423');

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
        Schema::dropIfExists('baseline_diagnostics_algrs');
    }
}
