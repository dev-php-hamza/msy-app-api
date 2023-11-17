<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWelcomeMessageIntoOrderSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_settings', function (Blueprint $table) {
            $table->text('welcome_text')->nullable()->after('order_services_text');
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
            $table->dropColumn('welcome_text');
        });
    }
}
