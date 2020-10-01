<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_items', function (Blueprint $table) {
            $table->bigIncrements('id');


            $table->bigInteger('routine_id')->unsigned();
            $table->foreign('routine_id')->references('id')->on('routines')->onDelete('cascade');

            $table->bigInteger('event_id')->unsigned();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

            $table->bigInteger('organization_id')->unsigned();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->bigInteger('schedule_title_id')->unsigned()->nullable();
            $table->foreign('schedule_title_id')->references('id')->on('schedule_titles')->onDelete('cascade');

            $table->integer('block')->nullable();
            $table->integer('order')->nullable();
            $table->integer('position')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_items');
    }
}
