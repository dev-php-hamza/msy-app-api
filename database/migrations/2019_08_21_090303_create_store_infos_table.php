<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('store_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->unsigned();
            $table->string('phone_number')->nullable();
            $table->string('website')->nullable();
            $table->string('primary_category');
            $table->string('sunday_hours_from')->nullable();
            $table->string('sunday_hours_to')->nullable();
            $table->string('monday_hours_from')->nullable();
            $table->string('monday_hours_to')->nullable();
            $table->string('tuesday_hours_from')->nullable();
            $table->string('tuesday_hours_to')->nullable();
            $table->string('wednesday_hours_from')->nullable();
            $table->string('wednesday_hours_to')->nullable();
            $table->string('thursday_hours_from')->nullable();
            $table->string('thursday_hours_to')->nullable();
            $table->string('friday_hours_from')->nullable();
            $table->string('friday_hours_to')->nullable();
            $table->string('saturday_hours_from')->nullable();
            $table->string('saturday_hours_to')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_infos');
    }
}
