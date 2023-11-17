<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('order_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->text('quantity_text')->nullable();
            $table->text('pickup_customer_notice_text')->nullable();
            $table->text('delivery_customer_notice_text')->nullable();
            $table->text('order_services_text')->nullable();
            $table->text('completion_text')->nullable();
            $table->string('primary_email')->nullable();
            $table->decimal('minimum_order_price', 15, 2)->nullable();
            $table->integer('country_id')->unsigned();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
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
        Schema::dropIfExists('order_settings');
    }
}
