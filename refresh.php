<?php

// This file passes the content of the Readme.md file in the same directory
// through the Markdown filter. You can adapt this sample code in any way
// you like.

// Install PSR-0-compatible class autoloader
spl_autoload_register(function($class){
	require preg_replace('{\\\\|_(?!.*\\\\)}', DIRECTORY_SEPARATOR, ltrim($class, '\\')).'.php';
});

// Get Markdown class
//use \Michelf\Markdown;

// Read file and pass content through the Markdown parser
$text = file_get_contents('Readme.md');
//$html = Markdown::defaultTransform($text);

/* gets url */
function get_content_from_github($url) {
	$token = 'acffc6236ed353a8223e8b0289cb94b1c553060f';
	$curl_token_auth = 'Authorization: token ' . $token;
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,1);
    	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);

// We set the right headers: any user agent type, and then the custom token header part that we generated
    	curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: OOP', $curl_token_auth));
	
	$content = curl_exec($ch);
	if($content===false) $content = 'Ошибка curl: ' . curl_error($ch);
	curl_close($ch);
	return $content;
}

/* gets the contents of a file if it exists, otherwise grabs and caches */
function get_repo_json($file,$plugin) {
	//vars
	$current_time = time(); $expire_time = 24 * 60 * 60; $file_time = filemtime($file);
	//decisions, decisions
	if(file_exists($file) && ($current_time - $expire_time < $file_time)) {
		//echo 'returning from cached file';
		return json_decode(file_get_contents($file));
	}
	else {
		//$json = array();
		//$u =  "https://api.github.com/users/okoff/orgs";
		//$json['orgs'] = json_decode(get_content_from_github($u),true);
		//$json['commit'] = json_decode(get_content_from_github('http://github.com/api/v2/json/commits/list/darkwing/'.$plugin.'/master'),true);
		//$json['readme'] = json_decode(get_content_from_github('http://github.com/api/v2/json/blob/show/darkwing/'.$plugin.'/'.$json['commit']['commits'][0]['parents'][0]['id'].'/Docs/'.$plugin.'.md'),true);
		//$json['js'] = json_decode(get_content_from_github('http://github.com/api/v2/json/blob/show/darkwing/'.$plugin.'/'.$json['commit']['commits'][0]['parents'][0]['id'].'/Source/'.$plugin.'.js'),true);
		file_put_contents($file,json_encode($json));
		return $json;
	}
}

//$cache_path = $_SERVER['DOCUMENT_ROOT'].'/plugin-cache/';
//$cache_file = $plugin.'-github.txt';
//$github_json = get_repo_json($cache_path.$cache_file,$plugin);

		$github_json = array();
		//$u =  "https://api.github.com/users/okoff/repos";
		//$u =  "https://api.github.com/orgs/stankin/repos";
		$u =  "https://api.github.com/repos/stankin/oop/commits";
		$github_json['repos'] = get_content_from_github($u);
		$u = "https://api.github.com/repos/stankin/oop/contents";
		$github_json['contents'] = get_content_from_github($u);


/* build json */
if($github_json) {
	
	//get markdown
	//include($_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/walshbook3/PHP-Markdown-Extra-1.2.4/markdown.php');
	//include('Michelf/MarkdownExtra.inc.php');
	
	//build content
	//$content = '<p>'.$github_json['repo']['repository']['description'].'</p>';
	//$content.= '<h2>MooTools JavaScript Class</h2><pre class="js">'.$github_json['js']['blob']['data'].'</pre><br />';
	//$content.= trim(str_replace(array('<code>','</code>'),'',$github_json['readme']['blob']['data']));
	
	//$content = var_export(json_decode($github_json['repos']));
	//$c = json_decode($github_json['repos']['list']);
	$content.= '<br><table>';
	$r = json_decode($github_json['repos']); 
	if(is_object($r)) {
		$r = get_object_vars($r);
	}
	foreach ($r as $k=>$e) {    
		$content.= '<tr><td><b>'.$k;
		if(is_object($e)) {
			$content.='<td><table>';
			foreach (get_object_vars($e) as $k1=>$e1) {
				$content.='<tr><td><b>'.$k1.'<td>'.print_r($e1,true);
			}
			$content.='</table>';
		} else {
		  	$content.='<td>'.$e;//print_r($e,true);
		}
	}	
	$content.='</table>'; 	
	file_put_contents('./sandbox/o.txt',$content);

}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>PHP Markdown Lib - Readme</title>
    </head>
    <body>
		<?php
			// Put HTML content in the document
			echo $content;
		?>
    </body>
</html>
