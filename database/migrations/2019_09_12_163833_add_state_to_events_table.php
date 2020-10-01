<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStateToEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            //
            $table->bigInteger('state_id')->unsigned()->default(57);
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');

            // Artisan::call('db:seed', [
            //     '--class' => AddSHEventTableSeeder::class
            // ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            //
            //$table->dropColumn('state_id');
        });
    }
}
