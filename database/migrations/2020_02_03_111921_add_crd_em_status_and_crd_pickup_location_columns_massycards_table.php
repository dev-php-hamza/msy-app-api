<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCrdEmStatusAndCrdPickupLocationColumnsMassycardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('massycards', function (Blueprint $table) {
            $table->string('crd_pickup_location')->nullable()->after('user_id');
            $table->boolean('crd_em_status')->default(0)->after('crd_pickup_location');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('massycards', function (Blueprint $table) {
            $table->dropColumn(['crd_pickup_location', 'crd_em_status']);
        });
    }
}
