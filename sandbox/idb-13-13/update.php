<?php

include('connect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$parm = $_POST;
	
} else {
	$parm = $_GET;
	echo('Метод GET ('.count($parm).'):<br>');
}
if(md5($_POST['pass'])=="865b02aab501e77c8ca524c9bc1cf5c4")
{
	foreach($parm as $k=>$v) {
		$param=substr($k, 0, 2);
		$value=trim(stripslashes(htmlspecialchars($v)));
		$id=substr($k, 2, 2);
		if ((!empty($value))AND($k!=="pass"))
		{
			$sqlstring="UPDATE idb1313 SET ".$param."=".$value." where id=".$id;
			$sql= mysql_query($sqlstring);
		}

	}
	echo('Информация сохранена!');
	//http://paul.1gb.ru/stankin/oop/sandbox/idb-13-13/
}
else
	echo "Не верный пароль!";
			
			//$path_of_uploaded_file = "http://api.screenshotmachine.com/?key=76a292&url=http://paul.1gb.ru/stankin/oop/sandbox/idb-13-13/",$row['link'];
$path_of_uploaded_file = 'files/ra.png';
						
$tmp_path = "http://api.screenshotmachine.com/?key=76a292&url=http://paul.1gb.ru/stankin/oop/sandbox/idb-13-13/Rakhman";;
			if(is_uploaded_file($tmp_path))
			{
				if(!copy($tmp_path,$path_of_uploaded_file))
				{
				$errors .= '\n error while copying the uploaded file';
				}
			}
?>
