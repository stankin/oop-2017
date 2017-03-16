<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$parm = $_POST;
	echo('Метод POST ('.count($parm).'):<br>');
} else {
	$parm = $_GET;
	echo('Метод GET ('.count($parm).'):<br>');
}
foreach($parm as $k=>$v) {
	echo('<b>'.$k.'</b> = '.trim(stripslashes(htmlspecialchars($v))).'<br>');
}
include('connect.php');
$sql= mysql_query("UPDATE idb1313 SET M1 = '".$_POST['M1']."', M2 = '".$_POST['M2']."' where id = ".$_POST['id']."");
	echo "UPDATE idb1313 SET M1 = '".$_POST['M1']."', M2 = '".$_POST['M2']."' where id = ".$_POST['id']."";
?>
