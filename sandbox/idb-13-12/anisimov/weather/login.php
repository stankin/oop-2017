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

$db = new goDB($dbconfig);
$member = authOpenAPIMember();

if($member !== FALSE) {
  $mid = $member["id"];
  $city = array_key_exists('city',$_REQUEST) ? $_REQUEST['city'] : null;
  $res = $db->query("SELECT * FROM `users` WHERE  `vkid`=?i", array($mid), 'row');
  echo $res[0][2];
} else {
  echo "Moscow";
}

?>