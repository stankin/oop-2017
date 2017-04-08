<?php 

	session_start();
	include "libs/lib_bd.php";
	$db = new safeMysql();

	$userName = $_POST['userName'];

	$db -> query("INSERT INTO `user`(`name`) VALUES('$userName')");

	$_SESSION['login'] = $userName;

	echo '<div class="screen chat">
			<div class="chat__header">
				<sapn class="header__name">Тестовое задание</sapn>
			</div>
			<div class="chat__view">
			</div>
			<div class="chat__type">
				<textarea name="" id="" cols="30" rows="10" class="type__input" placeholder="Type message. . ."></textarea>
				<button class="type__button"></button>
			</div>
		</div>';

?>