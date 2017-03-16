<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$parm = $_POST;
} else {
	$parm = $_GET;
	echo('Метод GET ('.count($parm).'):<br>');
}
foreach($parm as $k=>$v) {
	echo('<b>'.$k.'</b> = '.trim(stripslashes(htmlspecialchars($v))).'<br>');
}
?>
