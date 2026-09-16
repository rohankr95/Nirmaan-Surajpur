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
            $table->integer('ward_id')->nullable();
            $table->integer('ts_id')->nullable();
            $table->integer('as_id')->nullable();
            $table->integer('tender_id')->nullable();
            $table->integer('work_status')->default(0);
            $table->integer('work_stage')->nullable();
            $table->integer('employee_id')->default(0);
            $table->integer('financial_year_id');
            $table->integer('tenderChecked')->default(0);
            $table->integer('stages')->nullable();
            $table->integer('stage_days')->nullable();

            // Planned schedule: a start/end target for each stage of the lifecycle.
            $table->date('dpr_startDate')->nullable();
            $table->date('dpr_endDate')->nullable();
            $table->date('ts_startDate')->nullable();
            $table->date('ts_endDate')->nullable();
            $table->date('as_startDate')->nullable();
            $table->date('as_endDate')->nullable();
            $table->date('tender_startDate')->nullable();
            $table->date('tender_endDate')->nullable();
            $table->date('workOrder_startDate')->nullable();
            $table->date('workOrder_endDate')->nullable();
            $table->date('agreement_startDate')->nullable();
            $table->date('agreement_endDate')->nullable();
            $table->date('workStart_startDate')->nullable();
            $table->date('workStart_endDate')->nullable();
            $table->date('stage_startDate')->nullable();
            $table->date('stage_endDate')->nullable();
            $table->date('workComplete_endDate')->nullable();

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
