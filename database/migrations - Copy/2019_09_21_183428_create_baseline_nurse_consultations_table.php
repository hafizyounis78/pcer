<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaselineNurseConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baseline_nurse_consultations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pain_file_id');
            $table->foreign('pain_file_id')->references('id')->on('pain_files');
            $table->date('visit_date')->comment('visit_date');
            $table->integer('pain_duration')->nullable()->comment('Pain Duration Lookups 57');
            $table->integer('temporal_aspects')->nullable()->comment('Temporal aspects Lookups 66');

            $table->integer('pain_scale')->nullable()->comment('Pain scale list 0-10');
            $table->integer('pain_bothersomeness')->nullable()->comment('Pain bothersomeness list 0-10');
            $table->integer('pain_intensity_during_rest')->nullable()->comment('pain intensity during rest list 0-10');
            $table->integer('pain_intensity_during_activity')->nullable()->comment('pain intensity during activity list 0-10');

            $table->integer('health_rate')->nullable()->comment('Health rate Lookups 70');

            $table->unsignedInteger('phq_nervous')->nullable()->comment('PHQ Feeling nervous, anxious or on edge');
            $table->foreign('phq_nervous')->references('id')->on('phq_options');

            $table->unsignedInteger('phq_worry')->nullable()->comment('PHQ Not being able to stop or control worrying');
            $table->foreign('phq_worry')->references('id')->on('phq_options');

            $table->unsignedInteger('phq_little_interest')->nullable()->comment('PHQ Little interest or pleasure in doing things');
            $table->foreign('phq_little_interest')->references('id')->on('phq_options');

            $table->unsignedInteger('phq_feelingdown')->nullable()->comment('PHQ Feeling down, depressed, or hopeless');
            $table->foreign('phq_feelingdown')->references('id')->on('phq_options');

            $table->unsignedInteger('pcs_thinking_hurts')->nullable()->comment('PCS When Im in pain I keep thinking about how much it hurts');
            $table->foreign('pcs_thinking_hurts')->references('id')->on('pcs_options');

            $table->unsignedInteger('pcs_overwhelms_pain')->nullable()->comment('PCS When Im in pain its awful and I feel that it overwhelms me');
            $table->foreign('pcs_overwhelms_pain')->references('id')->on('pcs_options');

            $table->unsignedInteger('pcs_afraid_pain')->nullable()->comment('PCS When Im in pain I become afraid that the pain will get worse');
            $table->foreign('pcs_afraid_pain')->references('id')->on('pcs_options');

            $table->integer('pcl5_score')->nullable()->comment('score free input');
            $table->boolean('lab_scan')->nullable()->comment('Scanned laboratory results');
            $table->boolean('image_scan')->nullable()->comment('Scanned Image results');
            $table->string('nurse_message')->nullable()->comment('Nurse Message');
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
        Schema::dropIfExists('baseline_nurse_consultations');
    }
}
