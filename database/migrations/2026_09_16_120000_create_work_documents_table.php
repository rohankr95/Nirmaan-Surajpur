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
        Schema::create('work_documents', function (Blueprint $table) {
            $table->id('document_id');
            $table->unsignedBigInteger('work_id')->index();
            // uc  — utilisation certificate
            // cc  — completion certificate
            // rwh — rain water harvesting compliance
            $table->enum('doc_type', ['uc', 'cc', 'rwh', 'other']);
            $table->string('file_path');
            $table->string('reference_no')->nullable();
            $table->date('document_date')->nullable();
            $table->string('remark')->nullable();
            $table->integer('uploaded_by')->nullable();
            $table->timestamps();

            $table->foreign('work_id')->references('work_id')->on('works')->onDelete('cascade');
            $table->index(['work_id', 'doc_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_documents');
    }
};
