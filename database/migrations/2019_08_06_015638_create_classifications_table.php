<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('minage');
            $table->integer('maxage');
            $table->timestamps();
        });
        Schema::create('classification_translations', function (Blueprint $table) {
            // $table->increments('id');
            $table->bigIncrements('id');
            $table->bigInteger('classification_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name');
            $table->string('schedule_title');

            $table->unique(['classification_id','locale'], 'classilo_id_unique');
            $table->foreign('classification_id', 'classi_id_foreign')->references('id')->on('classifications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classification_translations');
        Schema::dropIfExists('classifications');
    }
}
