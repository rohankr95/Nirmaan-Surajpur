<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // The work-order section is now offered as an optional add-on below
        // the tender form rather than gated behind its own mandatory stage,
        // so none of its fields -- agreement_date included -- can stay
        // required. Raw SQL avoids adding doctrine/dbal just for this.
        DB::statement('ALTER TABLE agreements MODIFY agreement_date DATE NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE agreements MODIFY agreement_date DATE NOT NULL');
    }
};
