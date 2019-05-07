<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstagramLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instagram_likes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('liker_pk')->unsigned()->nullable()->comment('ID лайкера');
            $table->bigInteger('media_pk')->unsigned()->nullable()->comment('ID контента');
            $table->timestamps();

            $table->foreign('liker_pk')->references('pk')->on('instagram_users');
            $table->foreign('media_pk')->references('pk')->on('instagram_media');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instagram_likes');
    }
}
