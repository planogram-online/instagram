<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstagramMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instagram_media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('pk')->unsigned()->nullable()->index()->comment('ID материала');
            $table->bigInteger('author_pk')->unsigned()->nullable()->comment('ID автора');
            $table->tinyInteger('media_type')->unsigned()->nullable()->comment('Тип материала');
            $table->integer('like_count')->unsigned()->nullable()->default(0)->comment('Количество лайков');
            $table->integer('view_count')->unsigned()->nullable()->default(0)->comment('Количество просмотров');
            $table->timestamps();

            $table->foreign('author_pk')->references('pk')->on('instagram_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instagram_media');
    }
}
