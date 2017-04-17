<?
//error_reporting(0);
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
  echo $city;
  $res = $db->query("SELECT city FROM `users` WHERE `vkid`=?i", array($mid), 'el');
  echo $res[0][2];
  if ($res && $res[0][2] != ($city)) {
	    $res = $db->query("UPDATE `users` SET  `city`=? WHERE `vkid`=?i", array(($city), $mid), 'ar');
  } else {
	  $res = $db->query("INSERT INTO `users` (`vkid`, `city`) VALUES (?i, ?)", array($mid, ($city)), 'ar');
  }
} else {
  echo "meh";
}

?>