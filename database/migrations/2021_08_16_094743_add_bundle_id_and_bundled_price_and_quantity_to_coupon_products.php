<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBundleIdAndBundledPriceAndQuantityToCouponProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('coupon_products', function (Blueprint $table) {
            $table->integer('bundle_id')->unsigned()->nullable()->after('product_id');
            $table->string('bundled_price')->nullable()->after('product_id');
            $table->integer('quantity')->nullable()->after('product_id');
            $table->foreign('bundle_id')->references('id')->on('bundles')->onDelete('cascade');
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
        Schema::table('coupon_products', function (Blueprint $table) {
            $table->dropForeign(['bundle_id']);
            $table->dropColumn('bundle_id');
            $table->dropColumn('bundled_price');
            $table->dropColumn('quantity');
        });
    }
}
