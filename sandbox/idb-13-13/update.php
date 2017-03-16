<?php

include('connect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$parm = $_POST;
	echo('Метод POST ('.count($parm).'):<br>');
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
	echo "UPDATE idb1313 SET ",$param," = ",$value," where id = ",$id," <br>";
	echo('<b>'.$k.'</b> = '.trim(stripslashes(htmlspecialchars($v))).'<br><br>');
	}
	
}
//$_POST['id']=
//$sql= mysql_query("UPDATE idb1313 SET M1 = '', M2 = '' where id = "");
	//echo "UPDATE idb1313 SET M1 = '".$_POST['M1',$_POST['id']]."', M2 = '".$_POST['M2',$_POST['id']]."' where id = ".$_POST['id']."";
?>
