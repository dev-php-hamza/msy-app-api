<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupportEmailsintoCustomerCaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_cares', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('customer_feedback_email')->nullable()->after('email');
            $table->string('massy_card_support_email')->nullable()->after('customer_feedback_email');
            $table->string('massy_app_tech_support_email')->nullable()->after('massy_card_support_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_cares', function (Blueprint $table) {
            $table->string('email')->change();
            $table->dropColumn(['customer_feedback_email','massy_card_support_email', 'massy_app_tech_support_email']);
        });
    }
}
