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
        Schema::create('work_categories', function (Blueprint $table) {
            $table->increments('work_category_id');
            $table->string('work_category_name');
            $table->timestamps();
        });

        Schema::table('work_types', function (Blueprint $table) {
            // A category groups several work types: "पेयजल आपूर्ति" covers both
            // pipeline laying and hand pump installation, for instance.
            $table->unsignedInteger('work_category_id')->nullable()->after('work_type_name')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_types', function (Blueprint $table) {
            $table->dropColumn('work_category_id');
        });
        Schema::dropIfExists('work_categories');
    }
};
