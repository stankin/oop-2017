<!DOCTYPE html>
<html>
	<head> 
		<title>Субботин</title>
	</head>
<body>
	
  <?php session_start(); ?>



<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<form action="reg.php" method=POST>
<?php echo "Логин(*):" ?> <dr><dt><input type="text" name = "Login"> </dt></dr><br>
<?php echo "Пароль(*):" ?> <dr><dt><input type="text" name = "Password"> </dt></dr><br>
<?php echo "Имя(*):" ?> <dr><dt><input type="text" name = "Name"> </dt></dr><br>

<dr><dt><input type="submit" name = "btn_reg" value = "Зарегистрироваться"></dt></dr>

</form>


</body>
</html>
<?php 
if (isset($_POST['btn_reg'])) {
	
	if (isset($_POST['Login'])) { 
	$login = $_POST['Login']; 
	if ($login == '') { 
	unset($login); 
	} 
	} 
	if (isset($_POST['Password'])) { 
	$passin = $_POST['Password']; 
	if ($passin == '') { 
	unset($passin); 
	} 
	} 
	 
	if (isset($_POST['Name'])) { 
	$namein=$_POST['Name']; 
	if ($namein =='') { 
	unset($namein); 
	} 
	}
	

if (empty($login) or empty($passin) or empty($namein)) ) { 
	exit("Заполните все поля со звездочкой!");
} 
 


include ("motosalon.php");
$result_login = mysql_query("SELECT Login FROM registraciya WHERE Login = '$login'");
$reslog = mysql_fetch_assoc($result_login);

if (!empty($reslog["Login"])) {
     echo  " <br>Такой логин уже существует!";
exit;
}


$mot = mysql_query("INSERT INTO registraciya (Login, Password, Name) VALUES ('$login', '$passin', '$namein') ");
		$_SESSION['Login'] = $login;
echo '<script>window.location.href = "https://www.yandex.ru/";</script>';
}
}
?>
<br><form action="index.php" method="post"> 
<input type = "submit" class = "btn" value = "Назад" name = "back">
</form>
	
</body>	
</html>
