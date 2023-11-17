<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCsEmailAddressesColumnToOrderSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_settings', function (Blueprint $table) {
            $table->text('cc_email_addresses')->nullable()->after('primary_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_settings', function (Blueprint $table) {
            $table->dropColumn('cc_email_addresses');
        });
    }
}
