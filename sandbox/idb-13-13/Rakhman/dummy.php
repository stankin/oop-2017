<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$parm = $_POST;
	
} else {
	$parm = $_GET;
}
foreach($parm as $k=>$v) {
	echo('Вы успешно подписались!<br>'.trim(stripslashes(htmlspecialchars($v))).' добавлен в базу рассылки.');
}
?>