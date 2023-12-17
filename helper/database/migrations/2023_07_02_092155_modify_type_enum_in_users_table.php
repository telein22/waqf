<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTypeEnumInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $comment = 'أنواع المستخدمين ضمن المنصة: admin => مدير النظام, subscriber => يشمل الطالب والمحاضر(كل من يقدم خدمة أو يستفيد من خدمة), entity => جهات, جمعيات أو مؤسسات';
        \DB::statement("ALTER TABLE `users` CHANGE `type` `type` ENUM('admin','subscriber','entity') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '{$comment}'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
