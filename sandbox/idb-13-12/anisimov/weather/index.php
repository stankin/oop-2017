<?
error_reporting(0);
include "vk.php";
$member = authOpenAPIMember();

if($member !== FALSE) {
 // Continue
} else {
	header( 'Refresh: 0; url=login.html' );
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
    <!--link rel="stylesheet" href="css/awsm.min.css"-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.4.1/themes/prism-solarizedlight.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.4.1/prism.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.4.1/components/prism-scss.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="js/weather.js"></script>
  </head>
  <body>
  <header>
      <h1>Проектирование ИС</h1>
	  <div id="me"></div>
      <p>Лабораторные работы</p>
  </header>
    <main>
    <article>
	   <section>
		 <h2><a id="tasks" href="#tasks" aria-hidden="true"></a>Задания</h2>
			  <dl id="wea">

			  </dl>
	   </section>
    </article>
    </main>
  </body>
</html>