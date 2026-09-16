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
        Schema::table('works', function (Blueprint $table) {
            // What the work was sanctioned for. Amounts previously existed only
            // on the sanction rows, so a work had no figure of its own.
            $table->decimal('sanction_amount', 15, 2)->nullable()->after('financial_year_id');
        });

        Schema::create('work_payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->unsignedBigInteger('work_id')->index();
            // Each figure is reported by a different party: the district
            // releases funds, the department spends them, the engineer
            // evaluates the work done. Keeping them as one ledger with a type
            // means the totals cannot drift from the entries behind them.
            $table->enum('payment_type', ['released', 'expenditure', 'evaluation']);
            $table->decimal('amount', 15, 2);
            $table->date('payment_date');
            $table->integer('financial_year_id')->nullable();
            $table->integer('instalment_no')->nullable();
            $table->string('mb_no')->nullable();
            $table->date('mb_date')->nullable();
            $table->string('remark')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();

            $table->foreign('work_id')->references('work_id')->on('works')->onDelete('cascade');
            $table->index(['work_id', 'payment_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_payments');
        Schema::table('works', function (Blueprint $table) {
            $table->dropColumn('sanction_amount');
        });
    }
};
