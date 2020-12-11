<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEntriesAndRoutineCountToCategoryCreditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_credits', function (Blueprint $table) {
            $table->bigInteger('routines')->default(0);
            $table->bigInteger('entries')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_credits', function (Blueprint $table) {
            //
        });
    }
}
