<?php 
session_start();
include "libs/lib_bd.php";
$db = new safeMysql();

$count = $_POST['countMess'];

$data = $db->getOne("SELECT COUNT(*) FROM `mess`");

if($count<$data) echo "a" else echo "b";

?>