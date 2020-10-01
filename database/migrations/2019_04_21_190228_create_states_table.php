<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('country_id')->unsigned();
            $table->string('code')->index();
            
            $table->timestamps();
        });
        Schema::create('state_translations', function (Blueprint $table) {
            // $table->increments('id');
            $table->bigIncrements('id');
            $table->bigInteger('state_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name');

            $table->unique(['state_id','locale'], 'state_id_unique');
            $table->foreign('state_id', 'state_id_foreign')->references('id')->on('states')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('state_translations');
        Schema::dropIfExists('states');
    }
}
