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
        Schema::create('tenders', function (Blueprint $table) {
            $table->id('tender_id');
            $table->string('tender_no');
            $table->date('tender_release_date')->nullable();
            $table->date('tender_opening_date');
            $table->date('work_order_date');
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
        Schema::dropIfExists('tenders');
    }
};
