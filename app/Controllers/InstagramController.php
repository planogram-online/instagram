<?php

namespace App\Controllers;

use App\Models\InstagramComment;
use App\Models\InstagramLike;
use App\Models\InstagramMedia;
use App\Models\InstagramUser;

class InstagramController extends Controller
{
    private $instagram;
    private $user_id;
    private $rank_token;

    public function __construct()
    {
        $this->log("Подключение к серверу Instagram");
        // Если боевой режим, подключаемся к серверу
        if (!env("APP_DEBUG")) {
            try {
                $this->instagram = new \InstagramAPI\Instagram(false, false);
                $this->instagram->login(env("INSTAGRAM_LOGIN"), env("INSTAGRAM_PASSWORD"));
                $this->user_id = $this->instagram->people->getUserIdForName('planogram.online');
                $this->rank_token = \InstagramAPI\Signatures::generateUUID();
                $this->log("Подключение успешно установлено");
            } catch (\Exception $e) {
                $this->log('Error connecting to Instagram: ' . $e->getMessage(), true);
            }
        }
    }

    /**
     * Получение списка подписчиков
     * @return bool
     */
    public function importFollowers()
    {
        $this->log("Начало импорта списка подписчиков");
        try {
            InstagramUser::where("is_follower", "=", "1")
                ->update(['is_follower' => 0]);

            $followers = $this->getFollowersData();
            foreach ($followers as $follower) {
                $existing_user = $this->saveUser($follower);
                $existing_user->is_follower = 1;
                $existing_user->save();
            }
            $this->log("Подписчики успешно загружены");

        } catch (\Exception $e) {
            $this->log("Error import followers: " . $e->getMessage(), true);
        }
        return true;
    }

    /**
     * Импорт материалов из инстаграмм
     * @return bool
     */
    public function importMedia()
    {
        $this->log("Начало импорта данных о постах");
        $medias = $this->getMediaData();

        foreach ($medias as $item) {
            $author = $this->saveUser($item['user']);
            $media = $this->saveMedia($item);

            foreach ($item['likers'] as $liker) {
                $liker = $this->saveUser($liker);

                $like = InstagramLike::where("media_pk", "=", $media->pk)
                    ->where("liker_pk", "=", $liker->pk)
                    ->get()
                    ->first();
                if (!$like) {
                    $like = new InstagramLike();
                    $like->liker_pk = $liker->pk;
                    $like->media_pk = $media->pk;
                    $like->save();
                }
            }

            $comments = $this->getCommentsData($item['pk']);

            foreach ($comments as $comment) {
                $this->saveUser($comment['user']);

                $comment['media_pk'] = $item['pk'];
                $comment['author_pk'] = $comment['user_id'];
                $this->saveComment($comment);
            }
        }
        $this->log("Импорт данных о постах успешно завершен");

        return true;
    }

    private function getCommentsData($media_id)
    {
        $this->log("Получение списка комментариев для поста " . $media_id);

        // Если боевой режим, делаем запрос к серверу
        if (!env("APP_DEBUG")) {
            $comments = $this->instagram->media->getComments($media_id)->getPreviewComments();
            $this->saveData('instagram/comments', $comments);
            return $comments;
        // иначе получаем тестовые данные
        } else {
            return $this->getData('instagram/comments');
        }
    }

    private function getMediaData()
    {
        $this->log("Получение списка постов");

        // Если боевой режим, делаем запрос к серверу
        if (!env("APP_DEBUG")) {
            $result = [];
            $media = $this->instagram->timeline->getSelfUserFeed()->getItems();
            foreach ($media as $item) {
                $item = $item->asArray();
                $result[] = $item;
            }

            $this->saveData('instagram/media', $result);
            return $result;
        // иначе получаем тестовые данные
        } else {
            return $this->getData('instagram/media');
        }
    }

    /**
     * Получение списка подписчиков из инстаграм
     * @return array
     */
    private function getFollowersData()
    {
        $this->log("Получение списка подписчиков");

        // Если боевой режим, делаем запрос к серверу
        if (!env("APP_DEBUG")) {
            $data = $this->instagram->people
                ->getFollowers($this->user_id, $this->rank_token)
                ->getUsers();
            $users = [];

            foreach ($data as $row) {
                $users[] = $row->asArray();
            }
            $this->saveData('instagram/users', $users);
            return $users;
        // иначе получаем тестовые данные
        } else {
            return $this->getData('instagram/users');
        }
    }

    /**
     * Сохранения пользователя инстаграм в базу данных
     * @param $user
     * @return InstagramUser
     */
    private function saveUser($user)
    {
        $check_user = InstagramUser::where("pk", "=", $user['pk'])
            ->get()
            ->first();
        if (!$check_user) {
            $check_user = new InstagramUser();
        }
        $check_user->fill($user);
        $check_user->save();
        return $check_user;
    }

    /**
     * Сохранение материала в базу данных
     * @param $media
     * @return InstagramMedia
     */
    private function saveMedia($media)
    {
        $check_media = InstagramMedia::where("pk", "=", $media['pk'])
            ->get()
            ->first();
        if (!$check_media) {
            $check_media = new InstagramMedia();
        }
        $check_media->fill($media);
        $check_media->author_pk = $media['user']['pk'];
        $check_media->save();

        return $check_media;
    }

    /**
     * Сохранение комментария в базу данных
     * @param $comment
     * @return InstagramComment
     */
    private function saveComment($comment)
    {
        $check_comment = InstagramComment::where("pk", "=", $comment['pk'])
            ->get()
            ->first();
        if (!$check_comment) {
            $check_comment = new InstagramComment();
        }
        $check_comment->fill($comment);
        $check_comment->save();
        return $check_comment;
    }
}
