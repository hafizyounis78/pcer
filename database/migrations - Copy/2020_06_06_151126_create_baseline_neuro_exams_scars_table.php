<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaselineNeuroExamsScarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baseline_neuro_exams_scars', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pain_file_id');
            $table->foreign('pain_file_id')->references('id')->on('pain_files');
            $table->integer('side_nerve_id')->nullable()->comment('lookups 138');
            $table->integer('side_detail_id')->nullable()->comment('lookups from 138 to 161');
            $table->integer('light_touch')->nullable()->comment('Light touch lookups 49');
            $table->integer('pinprick')->nullable()->comment('Pinprick (needle) lookups 49');
            $table->integer('warmth')->nullable()->comment('lookups 53');
            $table->integer('cold')->nullable()->comment('lookups 53');
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
        Schema::dropIfExists('baseline_neuro_exams_scars');
    }
}
