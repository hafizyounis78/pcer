<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowupPharmacistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followup_pharmacists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pain_file_id');
            $table->foreign('pain_file_id')->references('id')->on('pain_files');
            $table->date('follow_up_date');
            $table->unsignedInteger('followup_id');
            $table->foreign('followup_id')->references('id')->on('followup_patients');
            $table->string('specify_other_adverse_effects')->nullable()->comment('pharmcist other adverse effects specify');
            $table->string('specify_other_changes')->nullable()->comment('pharmacist Specify other changes');
            $table->string('pharm_message')->nullable()->comment('Pharm Message');
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
        Schema::dropIfExists('followup_pharmacists');
    }
}
