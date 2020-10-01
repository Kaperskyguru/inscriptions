<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('address');
            $table->string('city');
            $table->string('zipcode');
            $table->string('phone');
            $table->string('locale');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('country_id')->unsigned();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');

            $table->bigInteger('state_id')->unsigned();
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');

            $table->bigInteger('organization_type_id')->unsigned();
            $table->foreign('organization_type_id')->references('id')->on('organization_types')->onDelete('cascade');

            $table->index('user_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizations', function (Blueprint $table) {
            $table->dropForeign('lists_user_id_foreign');
            $table->dropForeign('lists_country_id_foreign');
            $table->dropForeign('lists_state_id_foreign');
            $table->dropIndex('lists_user_id_index');
            $table->dropColumn('user_id');
            $table->dropColumn('country_id');
            $table->dropColumn('state_id');
        });
    }
}
