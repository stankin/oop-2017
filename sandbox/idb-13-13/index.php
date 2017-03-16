<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>СПИСОК ГРУППЫ ИДБ-13-13</title>
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
	<body>
		
    <table border="1px">
        <tr>
            <th>ФИО</th><th>М1</th><th>М2</th>
        </tr>
	    <form method="POST" id="form1" action="javascript:void(null);"  onsubmit="updateinfo()">
	<?php include('connect.php'); ?>
<?php	
 
$sql = mysql_query("SELECT * 
FROM  `idb1313` 
ORDER BY  `name` ASC 
LIMIT 0 , 30");
	    
	while($row = mysql_fetch_array($sql) )
	{
	echo '<tr>
            <td>
	   <img src="http://api.screenshotmachine.com/?key=76a292&url=http://paul.1gb.ru/stankin/oop/sandbox/idb-13-13/'.$row['link'].'">
<a href="',$row['link'],'">',$row['name'],'</a> 
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
	<tr><div id="results"></div> </tr>	 
	</form>	   
    </table>
			   
</body>
</html>
