<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQutenzaScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qutenza_scores', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pain_file_id');
            $table->foreign('pain_file_id')->references('id')->on('pain_files');
            $table->unsignedInteger('qutenza_id');
            $table->foreign('qutenza_id')->references('id')->on('qutenza_treatments');
            $table->date('visit_date')->comment('visit_date');
            $table->integer('visit_type')->comment('1=baseline,2=followup');
            $table->unsignedInteger('followup_id');
            $table->integer('week')->comment('Weeks from 1-12');
            $table->integer('score')->comment('score from 1-10');
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
        Schema::dropIfExists('qutenza_scores');
    }
}
