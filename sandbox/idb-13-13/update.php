<?php

include('connect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$parm = $_POST;
	echo('Информация сохранена<br>');
} else {
	$parm = $_GET;
	echo('Метод GET ('.count($parm).'):<br>');
}
foreach($parm as $k=>$v) {
	$param=substr($k, 0, 2);
	$value=trim(stripslashes(htmlspecialchars($v)));
	$id=substr($k, 2, 2);
	if (!empty($value))
	{
		$sqlstring="UPDATE idb1313 SET ".$param."=".$value." where id=".$id;
		$sql= mysql_query($sqlstring);
	}
	
}

?>
