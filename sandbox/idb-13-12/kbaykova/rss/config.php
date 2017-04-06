<?
error_reporting(0);
include "libs/godb.php";
include "libs/vk.php";

$dbconfig = array(
    'host'     => 'mysql96.1gb.ru',
    'username' => 'gb_oop',
    'passwd'   => 'b4b92bz6nmz',
    'dbname'   => 'gb_oop',
    'debug'    => false,
    'charset'  => 'utf8'
);

$db = new goDB($dbconfig);
$member = authOpenAPIMember();
