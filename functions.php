<?php
include("./ifconfig.php");
function count_files() 
{
	$d=@opendir(dir_upload);
   if(!$d) die("Каталог ".dir_upload." не найден! Попробуйте загрузить файл, папка создастся автоматически.");
   $s=0;
   while($e=readdir($d))
   {
   	if(is_file(dir_upload."/".$e)) $s++;
   }
echo "В хранилище уже ".$s." файл-(а)";
}
?>