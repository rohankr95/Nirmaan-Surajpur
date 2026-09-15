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
        Schema::create('works', function (Blueprint $table) {
            $table->id('work_id');
            $table->string('work_name')->nullable();
            $table->integer('units_of_work')->nullable();
            $table->integer('work_type_id');
            $table->integer('scheme_id');
            $table->bigInteger('office_id');
            $table->bigInteger('department_id')->nullable();
            $table->integer('location_type_id');
            $table->integer('village_id')->nullable();
            $table->bigInteger('grampanchayat_id')->nullable();
            $table->bigInteger('block_id')->nullable();
            $table->integer('ward_id')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->integer('ts_id')->nullable();
            $table->integer('as_id')->nullable();
            $table->integer('tender_id')->nullable();
            $table->integer('work_status')->default(0);
            $table->integer('work_stage')->nullable();
            $table->integer('financial_year_id');
            $table->string('created_by')->nullable();
            $table->date('created_at')->useCurrent();
            $table->string('updated_by')->nullable();
            $table->date('updated_at')->useCurrent();
            $table->string('deleted_by')->nullable();
            // $table->date('deleted_at')->useCurrent()->nullable();
            $table->softDeletes()->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('works');
    }
};
