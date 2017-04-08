<?php
session_start();
include "libs/lib_bd.php";
$db = new safeMysql();

if(isset($_POST['mess']) && $_POST['mess']!="" && $_POST['mess']!=" ")
{
    session_start();
    $mess = $_POST['mess'];
    $name = $_SESSION['login'];
    
    $db -> query("INSERT INTO `mess`(`name`, `mess`) VALUES('$name', '$mess')");
}
?>
