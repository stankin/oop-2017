<?
error_reporting(0);
include "godb.php";
include "vk.php";

$dbconfig = array(
    'host'     => '127.0.0.1',
    'username' => 'admin_pr',
    'passwd'   => '',
    'dbname'   => 'admin_pr',
    /*'debug'    => true,*/
    'charset'  => 'utf8'
);

$city = array_key_exists('city',$_REQUEST) ? $_REQUEST['city'] : "Moscow";
$apiurl = "http://api.openweathermap.org/data/2.5/weather?q=$city&units=metric&appid=7126634b78768e8e320d0f2dccecc33f&lang=ru";
$weaarr = array();

if (!file_exists("cache/{$city}_".date("d").".json")) {
	$wea = file_get_contents ($apiurl);
	$weaarr = json_decode($wea, true);
		$weather = array("city"=>$city, "temp_min"=>$weaarr["main"]["temp_min"], "temp_max"=>$weaarr["main"]["temp_max"], 
"desc"=>$weaarr["weather"][0]["description"], "wind"=>$weaarr["wind"]["speed"], "icon"=>$weaarr["weather"][0]["icon"]);
	file_put_contents("cache/{$city}_".date("d").".json", json_encode($weather));
}
else {
	$wea = file_get_contents ("cache/{$city}_".date("d").".json");
	$weather = json_decode($wea, true);
}

echo json_encode($weather);


?>