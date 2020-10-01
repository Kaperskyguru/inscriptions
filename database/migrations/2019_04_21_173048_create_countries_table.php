<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->index();
            $table->timestamps();
        });
        Schema::create('country_translations', function (Blueprint $table) {
            // $table->increments('id');
            $table->bigIncrements('id');
            $table->bigInteger('country_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name');

            $table->unique(['country_id','locale'], 'country_id_unique');
            $table->foreign('country_id', 'country_id_foreign')->references('id')->on('countries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country_translations');
        Schema::dropIfExists('countries');
    }
}
