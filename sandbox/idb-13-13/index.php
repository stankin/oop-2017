<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>СПИСОК ГРУППЫ ИДБ-13-13</title>
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
		<div id="results"></div>
    <table border="1px">
        <tr>
            <th>ФИО</th><th>М1</th><th>М2</th>
        </tr>
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
<a href="',$row['link'],'">',$row['name'],'</a> 
		</td>					
                 
<form id="form1"  method="POST" action="javascript:void(null);" onsubmit="updateinfo()">				
            <td><input id="M1" type="text" value=',$row['M1'],'></td>
            <td><input id="M2" type="text" value=',$row['M2'],'></td>
	    <td><button class="button">Отправить</button></td>
	    </form>
        </tr>';
	}
?>
    </table>
		
</body>
</html>
