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
        Schema::create('work_progress', function (Blueprint $table) {
            $table->id('wp_id');
            $table->date('estimated_completion_date')->nullable();
            $table->integer('work_status_id');
            $table->integer('mb_stages_id');
            $table->double('expenditure_amount')->nullable();
            $table->string('upload_file')->nullable();
            $table->date('status_update_date');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('work_id');
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
        Schema::dropIfExists('work_progress');
    }
};
