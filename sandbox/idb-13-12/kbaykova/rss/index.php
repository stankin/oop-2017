<?
include "config.php";

if($member !== FALSE) {
 // Continue
} else {
	header( 'Refresh: 0; url=login.html' );
	die();
}

  $mid = $member["id"];
  $res = $db->query("SELECT * FROM rss WHERE  `mid`=?i", array($mid), 'assoc');
  
  if ($_POST["name"] && $_POST["url"]) {
	   $res = $db->query("INSERT INTO rss (`name`, `url`, `mid`) VALUES (?, ?, ?i)", array($_POST["name"], $_POST["url"], $mid), 'ar');
	   header("Refresh:0");
  }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
	<meta http-equiv="pragma" content="no-cache" />
    <title>Проектирование ИС</title>
    <link rel="stylesheet" href="css/min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.4.1/themes/prism-solarizedlight.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.4.1/prism.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.4.1/components/prism-scss.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
	<script src="js/jquery.rss.min.js"></script>
	<script src="js/rss.js"></script>
  </head>
  <body>
  <div class="container">
  <h1>RSS-потоки</h1>
  <table class="table">
  <thead>
  <tr><th>#</th><th>Имя</th><th>URL потока</th><th>Действия</th></tr></thead>
  <? foreach ($res as $r) {
  echo "<tbody><tr><td>".$r["id"]."</td><td>".$r["name"]."</td><td>".$r["url"]."</td><td><a href =  '#' class = 'view' data-id='".$r["id"]."' onclick = 'readRSS(".$r["id"].")'>Просмотр новостей</a></td></tr>";
  } ?>
  </tbody></table>
  <div id="rss"></div>
   <div class="row"><form action="" method="POST">
  Название потока: <input type="text" name="name"><br>
  URL RSS: <input type="text" name="url"><br>
  <input type="submit" value="Добавить">
	</form></div>
  </div>
  </body>
</html>