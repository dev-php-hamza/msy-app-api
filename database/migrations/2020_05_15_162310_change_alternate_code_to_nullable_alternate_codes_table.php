<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAlternateCodeToNullableAlternateCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alternate_codes', function (Blueprint $table) {
            $table->string('alternate_code')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alternate_codes', function (Blueprint $table) {
            $table->string('alternate_code')->nullable(false)->change();
        });
    }
}
