<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomizationColumnsToCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->integer('user_id')->after('id')->nullable();
            $table->string('entity_type')->after('user_id')->nullable();
            $table->integer('entity_id')->after('entity_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('entity_id');
            $table->dropColumn('entity_type');
        });
    }
}
