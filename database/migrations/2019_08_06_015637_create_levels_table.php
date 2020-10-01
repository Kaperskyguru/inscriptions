<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });
        Schema::create('level_translations', function (Blueprint $table) {
            // $table->increments('id');
            $table->bigIncrements('id');
            $table->bigInteger('level_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name');

            $table->unique(['level_id','locale'], 'lev_id_unique');
            $table->foreign('level_id', 'lev_id_foreign')->references('id')->on('levels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('level_translations');
        Schema::dropIfExists('levels');
    }
}
