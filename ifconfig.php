<?php
/* Название сайта */
$site_name = "GNUU";
/* Максимальный размер файла в мб. */
$max_size = 8;
/* Папка для загрузки */
if(!defined ("dir_upload"))  define ("dir_upload", "uploads");
/* Константы баз данных */
if(!defined ("db_upload")) define ("db_upload", "./db/upload.db");
/* Каталог для файлов со ссылками */
if(!defined ("link_files")) define ("link_files", "link");
/* Путь к папке загрузки */
$upload_path = dirname (__FILE__).dir_upload;
/* Массив с разрешенными файлами */
$allowedtypes = array("zip","rar","7z","tar","gz","jpg","png","deb","rpm");
/* Имя админа */
$admin_login = "foozzi";
/* Пароль админа */
$passwd_admin = "798477";
/* Настройки времени на сервере */
$date = date('l jS \of F Y h:i:s A');
/* Промежуток дней, через который надо удалять файлы */
$kill_days = 30;
/* Промежуток дней, через который надо удалять файлы с ссылками */
$kill_days1 = 1;
/* Включить ошибки - 1; Выключить ошибки - 0; */
$err = 1;
?>