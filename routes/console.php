<?php

use \App\Controllers\InstagramController;

Artisan::command('import_followers', function () {
    $c = new InstagramController();
    $c->importFollowers();
})->describe('Импорт списка подписчиков');

Artisan::command('import_media', function () {
    $c = new InstagramController();
    $c->importMedia();
})->describe('Импорт постов');
