<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // work_progress.upload_file only ever held one file per status update;
        // this table lets a single update carry several photos/bills while
        // leaving existing single-file rows exactly as they are.
        Schema::create('work_progress_images', function (Blueprint $table) {
            $table->id('wp_image_id');
            $table->unsignedBigInteger('work_progress_id');
            $table->string('file_path');
            $table->timestamps();

            $table->foreign('work_progress_id')->references('wp_id')->on('work_progress')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_progress_images');
    }
};
