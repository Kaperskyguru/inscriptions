<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventTypeIdCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('categories', function (Blueprint $table) {
            //
            $table->bigInteger('event_type_id')->unsigned()->default(1);
            $table->foreign('event_type_id')->references('id')->on('event_types')->onDelete('cascade');

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
        //
    }
}
