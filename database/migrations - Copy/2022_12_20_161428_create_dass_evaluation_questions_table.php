<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDassEvaluationQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dass_evaluation_questions', function (Blueprint $table) {
            $table->increments('eval_question_id');
            $table->string('eval_question_class')->comment('A=Anxiety,D=Depression,S=Stress');
            $table->string('eval_question')->comment('');
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
        Schema::dropIfExists('dass_evaluation_questions');
    }
}
