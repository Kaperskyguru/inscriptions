<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('email');

            $table->bigInteger('event_type_id')->unsigned();
            $table->foreign('event_type_id')->references('id')->on('event_types')->onDelete('cascade');

            //$table->index('user_id');

            $table->timestamps();
        });
        Schema::create('event_translations', function (Blueprint $table) {
            // $table->increments('id');
            $table->bigIncrements('id');
            $table->bigInteger('event_id')->unsigned();
            $table->string('locale')->index();

            $table->string('city');

            $table->unique(['event_id','locale'], 'e_id_unique');
            $table->foreign('event_id', 'e_id_foreign')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_translations');
        Schema::dropIfExists('events');
    }
}
