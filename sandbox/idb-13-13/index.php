<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список ИДБ-13-13</title>
	<link href="style.css" rel="stylesheet" media="all">
	<script type="text/javascript" src="Rakhman/js/jquery-2.0.3.min.js"></script>
		    <script type="text/javascript" language="javascript">
     	function updateinfo() {
     	  var msg   = $('#form1').serialize();
            $.ajax({
              type: 'POST',
              url: 'update.php',
              data: msg,
              success: function(data) {
                $('#results').html(data);
              },
              error:  function(xhr, str){
    	    alert('Возникла ошибка: ' + xhr.responseCode);
              }
            });
     
        }
		
	
    </script>
	
</head>
<body><div class="container">

		<div class="calendar-container">

			<header>
				
				<div class="day">ИДБ</div>
				<div class="month">13-13</div>

			</header>
            <table class="calendar">
				
				<thead>

					<tr>
						<td width="500">Фио</td>
						<td width="200">Модуль 1</td>
						<td width="200">Модуль 2</td>

					</tr>

				</thead>

	<tbody>

					  <form method="POST" id="form1" action="javascript:void(null);"  onsubmit="updateinfo()">
	<?php include('connect.php'); ?>
<?php	
 
$sql = mysql_query("SELECT * 
FROM  `idb1313` 
ORDER BY  `name` ASC 
LIMIT 0 , 30");
	    
	while($row = mysql_fetch_array($sql) )
	{
	echo '<tr class="border_bottom">
            <td>
	  <div><img src="http://api.screenshotmachine.com/?key=76a292&url=http://paul.1gb.ru/stankin/oop/sandbox/idb-13-13/'.$row['link'].'"></div>
<div><a href="',$row['link'],'">',$row['name'],'</a> </div>
		</td>		
            <td><input name="M1',$row['id'],'" type="text" value="',$row['M1'],'"></td>
            <td><input name="M2',$row['id'],'" type="text" value="',$row['M2'],'"></td>
	    
        </tr>';
	}
?>
						  
		    <tr>
			    <td>Пароль:</td>
			    <td><input name="pass" type="password"></td>
			    <td><button class="button">Отправить</button></td>     
        </tr>
	 </form>	
		   <tr><td></td><td></td><td><div id="results"></div></td> </tr>	 
					 </tbody>
	   
</table>	    
		

			<div class="ring-left"></div>
			<div class="ring-right"></div>

		</div> 

	</div>
</body>
</html>
