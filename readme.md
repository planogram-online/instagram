# Управление аккаунтом Instagram


Установка
------------
~~~
mkdir instagram
cd instagram
git clone https://github.com/planogram-online/instagram.git .
php composer.phar install
chmod -R 777 storage
cp .env.example .env

#В файле .env нужно указать логин и пароль для доступа к базе данных

php artisan migrate
# установка завершена. 
~~~
Для работы с реальным API Instagram, нужно указать логин и пароль от аккаунта инстаграм и заменить
**APP_DEBUG=true** на **APP_DEBUG=false** 

Использование
------------
**Получение списка подписчиков.**


~~~
php artisan import_followers
~~~
После выполнения команды, в таблицу instagram_users будут добавлены все подписчики аккаунта

**Получение данных о постах.**


~~~
php artisan import_media
~~~
Получение данных о последних постах (лайки, комментарии, просмотры)
