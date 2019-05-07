<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstagramCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instagram_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('pk')->unsigned()->nullable()->index()->comment('ID комментария');
            $table->bigInteger('media_pk')->unsigned()->nullable()->comment('ID материала');
            $table->bigInteger('author_pk')->unsigned()->nullable()->comment('ID автора комментария');
            $table->text('text')->nullable()->comment('Текст комментария');
            $table->integer('comment_like_count')->nullable()->unsigned()->default(0)->comment('Количество лайков');
            $table->timestamps();

            $table->foreign('media_pk')->references('pk')->on('instagram_media');
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
        Schema::dropIfExists('instagram_comments');
    }
}
