<?
include "config.php";

if($member !== FALSE) {
  $mid = $member["id"];
  $id = array_key_exists('id',$_REQUEST) ? $_REQUEST['id'] : null;
  $res = $db->query("SELECT url FROM rss WHERE `mid`=?i AND `id`=?i", array($mid, $id), 'el');
  echo json_encode(array("url"=>$res));
} else {
  echo "meh";
}

?>