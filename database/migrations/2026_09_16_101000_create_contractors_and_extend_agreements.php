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
        Schema::create('contractors', function (Blueprint $table) {
            $table->increments('contractor_id');
            $table->string('contractor_name');
            $table->string('contact_person')->nullable();
            $table->string('mobile', 15)->nullable();
            $table->string('registration_no')->nullable();
            $table->string('address')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // The agreement row held only a date and a remark. It becomes the work
        // order record: who the work was awarded to, under what number, for
        // how much, with the order document attached.
        Schema::table('agreements', function (Blueprint $table) {
            $table->string('work_order_no')->nullable()->after('agreement_date');
            $table->date('work_order_date')->nullable()->after('work_order_no');
            $table->decimal('work_order_amount', 15, 2)->nullable()->after('work_order_date');
            $table->unsignedInteger('contractor_id')->nullable()->after('work_order_amount')->index();
            $table->string('upload_file')->nullable()->after('contractor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agreements', function (Blueprint $table) {
            $table->dropColumn(['work_order_no', 'work_order_date', 'work_order_amount', 'contractor_id', 'upload_file']);
        });
        Schema::dropIfExists('contractors');
    }
};
