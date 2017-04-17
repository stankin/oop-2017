<?php 
	session_start();
	include "libs/lib_bd.php";
	$db = new safeMysql();

	$data = $db->getOne("SELECT COUNT(*) FROM `mess`");

	echo $data;

 ?>