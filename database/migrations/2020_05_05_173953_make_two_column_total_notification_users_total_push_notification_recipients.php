<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeTwoColumnTotalNotificationUsersTotalPushNotificationRecipients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->integer('total_notification_users')->default(0)->after('country_id');
            $table->integer('total_push_notification_recipients')->default(0)->after('total_notification_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('total_notification_users', 'total_push_notification_recipients');
        });
    }
}
