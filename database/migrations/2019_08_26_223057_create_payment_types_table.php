<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });
        
        Schema::create('payment_type_translations', function (Blueprint $table) {
            // $table->increments('id');
            $table->bigIncrements('id');
            $table->bigInteger('payment_type_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name');

            $table->unique(['payment_type_id','locale'], 'pay_id_unique');
            $table->foreign('payment_type_id', 'pay_id_foreign')->references('id')->on('payment_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_type_translations');
        Schema::dropIfExists('payment_types');
    }
}
