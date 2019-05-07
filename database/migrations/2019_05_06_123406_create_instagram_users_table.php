<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstagramUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instagram_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('pk')->unsigned()->nullable()->unique()->comment('ID подписчика');
            $table->string('username')->nullable()->comment('Имя пользователя');
            $table->string('full_name')->nullable()->comment('Полное имя пользователя');
            $table->boolean('is_private')->nullable()->default('0')->comment('Приватный аккаунт');
            $table->text('profile_pic_url')->nullable()->comment('Ссылка на аватарку');
            $table->string('profile_pic_id')->nullable()->comment('Ссылка на аватарку');
            $table->boolean('is_verified')->nullable()->default('0')->comment('Проверенный аккаунт');
            $table->boolean('is_follower')->nullable()->default('0')->comment('Подписчик');
            $table->boolean('has_anonymous_profile_picture')->nullable()->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instagram_users');
    }
}
