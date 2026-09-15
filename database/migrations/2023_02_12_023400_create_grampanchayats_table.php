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
        Schema::create('grampanchayats', function (Blueprint $table) {
            $table->id('grampanchayat_id');
            $table->string('grampanchayat_name',50);
            $table->string('grampanchayat_name_en',50);
            $table->integer('block_id');
            $table->integer('subdivision_id');
            $table->integer('district_id');
            $table->integer('assembly_constituency_id');
            $table->integer('state_id');
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
        Schema::dropIfExists('grampanchayats');
    }
};
