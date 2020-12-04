<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocNumberToDancerRoutineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dancer_routine', function (Blueprint $table) {
            //
            $table->bigInteger('doc_number')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dancer_routine', function (Blueprint $table) {
            //
            $table->removeColumn('doc_number');
        });
    }
}
