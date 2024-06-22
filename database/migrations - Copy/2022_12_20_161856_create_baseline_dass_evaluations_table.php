<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaselineDassEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baseline_dass_evaluations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pain_file_id');
            $table->foreign('pain_file_id')->references('id')->on('pain_files');
            $table->unsignedInteger('eval_question_id');
            $table->foreign('eval_question_id')->references('eval_question_id')->on('dass_evaluation_questions');
            $table->integer('eval_answer')->nullable()->comment('Did not apply to me at all=0,Applied to me to some degree or some of the time=1,Applied to me to a considerable degree or a good part of time=2,Applied to me very much or most of the time=3');
            $table->integer('eval_question_order');
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
        Schema::dropIfExists('baseline_dass_evaluations');
    }
}
