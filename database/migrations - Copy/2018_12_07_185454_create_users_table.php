<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('national_id')->comment('National ID');
            $table->string('name')->comment('User Name');
          //  $table->string('mobile',10)->unique();
          //  $table->integer('district')->comment('lookups code1');
            $table->string('address')->nullable();
            $table->string('email',128)->unique();
            $table->string('password');
            $table->boolean('isActive')->default(1);
            $table->unsignedInteger('org_id')->comment('Organizations');
            $table->foreign('org_id')->references('id')->on('organizations');
            $table->integer('user_type_id')->comment('lookups code 7');
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
