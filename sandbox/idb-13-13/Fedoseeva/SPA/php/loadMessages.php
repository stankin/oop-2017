<?php 
	session_start();
	include "libs/lib_bd.php";
	$db = new safeMysql();
	
	$data = $db -> getAll("SELECT * FROM `mess` ORDER BY `id`");

	foreach ($data as $row): 

	if ($row['name'] == $_SESSION['login'])
		$block = '_my';
	else
		$block = '_other';
	
		?>
					<div class="view__block view__block<?=$block?>">
						<div class="view__avatar"></div>
						<div class="view__message">
							<?=$row['mess']?>
							<span class="name<?=$block?>"><?=$row['name']?></span>
						</div>
					</div>
	<? endforeach

?>