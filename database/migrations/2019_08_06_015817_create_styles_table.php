<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStylesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('styles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });
        Schema::create('style_translations', function (Blueprint $table) {
            // $table->increments('id');
            $table->bigIncrements('id');
            $table->bigInteger('style_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name');
            $table->text('description');
            $table->string('note');

            $table->unique(['style_id','locale'], 'sty_id_unique');
            $table->foreign('style_id', 'sty_id_foreign')->references('id')->on('styles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('style_translations');
        Schema::dropIfExists('styles');
    }
}
