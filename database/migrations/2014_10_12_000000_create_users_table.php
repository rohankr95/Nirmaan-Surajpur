<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('login_id');
            $table->string('password');
            $table->string('name')->nullable();
            $table->string('designation')->nullable();
            $table->string('landline', 20)->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->integer('user_role_id');
            $table->unsignedBigInteger('office_id');
            $table->unsignedBigInteger('emp_id');
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
};
