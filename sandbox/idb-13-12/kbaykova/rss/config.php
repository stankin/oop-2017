<?
error_reporting(0);
include "libs/godb.php";
include "libs/vk.php";

$dbconfig = array(
    'host'     => '127.0.0.1',
    'username' => 'root',
    'passwd'   => '',
    'dbname'   => 'github',
    /*'debug'    => true,*/
    'charset'  => 'utf8'
);

$db = new goDB($dbconfig);
$member = authOpenAPIMember();