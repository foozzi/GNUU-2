<?php
include ("./ifconfig.php");
ini_set('display_errors',$err); 
error_reporting(E_ALL);
include ("./functions.php");
if (@$_POST['upload'] == "") // Проверка запроса, если он пуст показываем форму, если в upload что то есть инклюдим файл с обработчиком
{
   echo 'Выберите файл | Максимальный размер '.$max_size.'MB | Запрещенно заливать исполняемые файлы<br><br>';
	echo '<form enctype="multipart/form-data" action="" method="post">';
	echo '<input type="file" name="filename" size="45"></br>';
	echo '<input type="submit" value=" Грузить! " name="upload">';
	echo '</form>';
	echo '<br>';
   count_files();
} 
else 
{
	include_once("./engine.php"); // загрузка
}
?>