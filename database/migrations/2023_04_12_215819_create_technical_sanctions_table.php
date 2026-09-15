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
        Schema::create('technical_sanctions', function (Blueprint $table) {
            $table->id('ts_id');
            $table->string('ts_no');
            $table->date('submission_date');
            $table->double('ts_amount');
            $table->date('approval_date')->nullable();
            $table->string('upload_file')->nullable();
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
        Schema::dropIfExists('technical_sanctions');
    }
};
