<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEntriesAndRoutineCountToCategoryInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_invoices', function (Blueprint $table) {
            //
            $table->bigInteger('entries')->default(0);
            $table->bigInteger('routine_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_invoices', function (Blueprint $table) {
            //
            $table->removeColumn('entries');
            $table->removeColumn('routine_count');
        });
    }
}
