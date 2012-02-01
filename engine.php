<?php
/* 
 * foozzi 2011 copyleft
 * License: GNU/GPL
 * V.1.0
 * GNU-Uploader
*/
if(!file_exists("./ifconfig.php")) exit("Файл ifconfig.php не найден");
include_once ("./ifconfig.php");
ini_set('display_errors',$err); 
error_reporting(E_ALL);

$userip = $_SERVER['REMOTE_ADDR'];
$file_name = $_FILES['filename']['name'];
$file_size = $_FILES['filename']['size'];
$error_flag = $_FILES["filename"]["error"]; 
$file_size = $file_size / 1048576; 
$time = time() - $kill_days * 86400; // Отсчитываем n-дней до удаления старых файлов
$time1 = time() - $kill_days1 * 86400; // Отсчитываем n-дней до удаления файлов со ссылками
/* Создаем базу данных для загрузок если ее нет */
if(!file_exists(db_upload)) 
{
	mkdir('./'.db_upload, 0777);
	}
/* Создаем каталог для загрузки если его нет */
if (!file_exists(dir_upload))
{
	mkdir('./'.dir_upload, 0777);
	}
/* Создаем каталог для файлов со ссылками если его нет */
if (!file_exists(link_files))
{
	mkdir('./'.link_files, 0777);
	}	
/* Проверка, выбран ли файл */	
if($file_size == 0) 
{
	echo "Вы не выбрали файл";
	die();
	}
/* Проверка файлов на разрешение */
if(isset($allowedtypes)) 
{
	$allowed = 0;
	foreach($allowedtypes as $ext)
	{
		if(substr($file_name, (0 - (strlen($ext) + 1))) == ".".$ext) 
		$allowed = 1;
		}
		if($allowed == 0) 
		{
			echo "Не разрешенный тип файла.</br>";
			echo "<a href='?".md5(microtime())."'>Попробовать еще</a>";
			die();
			}
}
/* Проверка размера файла */
if($file_size > $max_size) 
{
	echo "Файл слишком большой.</br>";
	echo "<a href='?".md5(microtime())."'>Попробовать еще</a>";
	die();
	}
/* Присваивание номера файлу */
$d=opendir(dir_upload);
$s=0;
while($e=readdir($d))
{
	if(is_file(dir_upload."/".$e)) 
     $s++;
  }
/* Преобразование имени */
$ext1 = pathinfo($file_name,PATHINFO_EXTENSION);
$file_name = rand(000000001, 999999991)."#".$s.".".$ext1;
/* Пишем загрузку в базу */
$filelist = fopen(db_upload, "a+"); // ("r" - считывать "w" - создавать "a" - добовлять к тексту)
fwrite($filelist, $s ."|". basename($_FILES['filename']['name']) ."|". $userip ."|". $date."|". $file_name . "|\n");			
/* Сотворим ссылку для скачивания файла :) */
$upload_link = "http://".$_SERVER["HTTP_HOST"].dirname ($_SERVER["PHP_SELF"]).'/'.dir_upload.'/'.$file_name;
/* Создаем .txt файл со ссылкой на загружаемый файл */
function createlinktxt() 
{
	global $txt_file_name, $upload_link;
	$txt_name = md5(rand(000001, 999999));
	$file = "./link/".$txt_name.".txt";
   $txtopen = fopen($file, 'w');  // ("r" - считывать "w" - создавать "a" - добовлять к тексту)
   fwrite($txtopen, $upload_link);
   fclose ($txtopen);
   $txt_file_name = "http://".$_SERVER["HTTP_HOST"].dirname ($_SERVER["PHP_SELF"]).'/'.'link'.'/'.$txt_name.".txt";
}
/* И собственно сама загрузка файла */
if($error_flag == 0) 
{
	if (move_uploaded_file($_FILES['filename']['tmp_name'], dir_upload."/".$file_name))
   {
   	/* Это уг над будем поменять */
	   echo "Файл загружен.<br>"; 
	   echo "Линки:<br>";
      echo "<input type='text' size=80 onclick='this.select()' value='".$upload_link."'><br><br>"; // Обычная ссылка
      echo "HTML Линк:<br>";
      echo "<input type='text' size=80 onclick='this.select()' value=\"<a href='".$upload_link."'>".$file_name."</a>\"><br><br>"; // HTML Ссылка
      echo "BB-Code Линк:<br>";
      echo "<input type='text' size=80 onclick='this.select()' value='[url]".$upload_link."[/url]'><br><br>"; // BB-Code Ссылка
      echo "Скачать .txt файл с вашей ссылкой.<br>";
      createlinktxt();
      echo "<input type='text' size=80 onclick='this.select()' value=".$txt_file_name."><br><br>"; // .txt файл со ссылкой
      echo "<a href='?".md5(microtime())."'>Загрузить другой файл</a>";
      
	}
   else 
   {
   	echo "Произошла неизвестная ошибка, файл не был загружен</br>";
	   echo "<a href='?".md5(microtime())."'>Попробовать еще</a>";
	   die();
	}
}
/* Еще проверки по флагам */		
if($error_flag == 1) 
{
	echo "Размер файла превышает заданный размер на сервере";
	die();
	}
	if($error_flag == 3) 
	{
		echo "При загрузке, была загружена лишь часть файла";
		die();
		}
		if($error_flag == 4) 
		{
			echo "Вы задали не верный путь к файлу";
			die();
			}	
/* Удаление старых файлов */			
$dir = scandir(dir_upload); // Получаем список папок и файлов
foreach($dir as $name) 
{
	if($name == '.' || $name == '..') continue;
    if(is_file(dir_upload.$name) == TRUE) // проверяем, действительно ли это файл
    {
    	$ftime = filemtime($path.$name); // получаем последнее время модификации файла
        if($ftime < $time) 
        {
        	unlink(dir_upload.$name); // удаляем файл
        }
    }
} 
/* Удаление файлов со ссылками */
$dir = scandir(link_files); // Получаем список папок и файлов
foreach($dir as $name) 
{
	if($name == '.' || $name == '..') continue;
    if(is_file(link_files.$name) == TRUE) // проверяем, действительно ли это файл
    {
    	$ftime = filemtime($path.$name); // получаем последнее время модификации файла
        if($ftime < $time1) 
        {
        	unlink(link_files.$name); // удаляем файл
        }
    }
}           
?>