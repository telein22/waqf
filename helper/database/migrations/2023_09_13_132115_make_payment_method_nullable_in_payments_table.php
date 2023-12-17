<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakePaymentMethodNullableInPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE `payments` CHANGE `method` `method` ENUM('apple','visa','free','stc','mada') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE `payments` CHANGE `method` `method` ENUM('apple','visa','free','stc','mada') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    }
}
