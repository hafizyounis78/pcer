<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaselinePharmacistConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baseline_pharmacist_consultations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pain_file_id');
            $table->foreign('pain_file_id')->references('id')->on('pain_files');
            $table->date('visit_date')->comment('visit_date');
            $table->boolean('laboratory_outside_reference')->nullable()->comment('Laboratory values outside reference values');
            $table->string('laboratory_specify')->nullable()->comment('Specify (e.g. GFR 40)');
            $table->boolean('interactions')->nullable()->comment('Yes/No');
            $table->string('which_interactions')->nullable()->comment('Interactions Which?');
            $table->string('other_considereations')->nullable()->comment('Other considereations');
            $table->string('pharmacist_message')->nullable()->comment('Pharmacist Message');
            $table->unsignedInteger('org_id');
            $table->foreign('org_id')->references('id')->on('organizations');
            $table->unsignedInteger('created_by')->comment('User Id');
            $table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('baseline_pharmacist_consultations');
    }
}
