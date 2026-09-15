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
        Schema::create('work_closeds', function (Blueprint $table) {
            $table->id();
            $table->date('close_date');
            $table->string('remark')->nullable();
            $table->unsignedBigInteger('work_id')->index();
            $table->foreign('work_id')->references('work_id')->on('works')->onDelete('cascade');
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
        Schema::dropIfExists('work_closeds');
    }
};
