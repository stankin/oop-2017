<?
//error_reporting(0);
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
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