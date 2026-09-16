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
        Schema::table('log_activities', function (Blueprint $table) {
            // The record the entry is about. Previously the id was concatenated
            // into `subject` as text, so a record's history could not be queried.
            $table->string('subject_type', 64)->nullable()->after('subject');
            $table->unsignedBigInteger('subject_id')->nullable()->after('subject_type');
            // Denormalised so a work's whole trail — including its sanctions,
            // tender and progress entries — reads with one indexed lookup.
            $table->unsignedBigInteger('work_id')->nullable()->after('subject_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_activities', function (Blueprint $table) {
            $table->dropColumn(['subject_type', 'subject_id', 'work_id']);
        });
    }
};
