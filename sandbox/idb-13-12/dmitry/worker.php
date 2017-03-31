<?
error_reporting(0);
include_once "init.php";
$vkid = $auth["id"];
$act = array_key_exists('act',$_REQUEST) ? $_REQUEST['act'] : null;
$hash = array_key_exists('hash',$_REQUEST) ? $_REQUEST['hash'] : null;

switch ($act) {
	case "fav":
		$db->query ("INSERT IGNORE INTO fav (mid, hash) VALUES (?i, ?)", array($vkid, $hash), "ar");
	break;
	case "unfav":
		$db->query ("DELETE FROM fav WHERE mid = ?i AND hash = ?", array($vkid, $hash), "ar");
	break;
	default:
		$favs = $db->query ("SELECT hash FROM fav WHERE mid = ?i", array($vkid), "col");
		echo json_encode($favs);
	break;
}
?>