<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });
        Schema::create('organization_type_translations', function (Blueprint $table) {
            // $table->increments('id');
            $table->bigIncrements('id');
            $table->bigInteger('organization_type_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name');

            $table->unique(['organization_type_id','locale'], 'ot_id_unique');
            $table->foreign('organization_type_id', 'ot_id_foreign')->references('id')->on('organization_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organization_type_translations');
        Schema::dropIfExists('organization_types');
    }
}
