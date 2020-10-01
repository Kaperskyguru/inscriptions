<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_titles', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('schedule_id')->unsigned();
            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');

            $table->integer('order')->nullable();
            $table->integer('position')->nullable();

            $table->timestamps();
        });


        Schema::create('schedule_title_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('schedule_title_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name');

            $table->unique(['schedule_title_id','locale'], 'st_id_unique');
            $table->foreign('schedule_title_id', 'st_id_foreign')->references('id')->on('schedule_titles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_title_translations');
        Schema::dropIfExists('schedule_titles');
    }
}
