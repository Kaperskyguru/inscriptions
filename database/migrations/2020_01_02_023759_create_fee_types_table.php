<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('price');
            $table->timestamps();
        });
       
        
        Schema::create('fee_type_translations', function (Blueprint $table) {
            // $table->increments('id');
            $table->bigIncrements('id');
            $table->bigInteger('fee_type_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name');

            $table->unique(['fee_type_id','locale'], 'fee_id_unique');
            $table->foreign('fee_type_id', 'fee_id_foreign')->references('id')->on('fee_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fee_type_translations');
        Schema::dropIfExists('fee_types');
    }
}
