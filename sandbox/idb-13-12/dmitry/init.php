<?
//error_reporting(0);
include_once "libs/godb.php";
include_once "libs/vk.php";

$auth = authOpenAPIMember();
$dbconfig = array(
    'host'     => 'mysql96.1gb.ru',
    'username' => 'gb_oop',
    'passwd'   => 'b4b92bz6nmz',
    'dbname'   => 'gb_oop',
    'debug'    => false,
    'charset'  => 'utf8'
);
$db = new goDB($dbconfig);

if (!$auth) {
	http_response_code(403);
	exit();
}
?>