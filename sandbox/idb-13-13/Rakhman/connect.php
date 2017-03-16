<?php
$dblocation = "mysql96.1gb.ru";
$dbname = "gb_oop";
$dbuser = "gb_oop";
$dbpasswd = "b4b92bz6nmz";
$dbcnx = @mysql_connect($dblocation,$dbuser,$dbpasswd);
if (!$dbcnx) 
{
  echo( "<P>В настоящий момент сервер базы данных недоступен, поэтому 
            корректное отображение страницы невозможно.</P>" );
  exit();
}
if (!@mysql_select_db($dbname, $dbcnx)) 
{
  echo( "<P>В настоящий момент база данных недоступна, поэтому
            корректное отображение страницы невозможно.</P>" );
  exit();
}
?>