<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>СПИСОК ГРУППЫ ИДБ-13-13</title>
</head>
	<body>
    <table border="1px">
        <tr>
            <th>ФИО</th><th>М1</th><th>М2</th>
        </tr>
	<?php include('connect.php'); ?>
<?php	
$sql = "SELECT * 
FROM  `idb1313` 
ORDER BY  `name` ASC 
LIMIT 0 , 30";

	while($row = mysql_fetch_array($sql)|| die(mysql_error()) )
	{
	echo '<tr>
            <td>
<a href="',$row['link'],'">',$row['name'],'</a> 
		</td>
            <td>',$row['M1'],'</td>
            <td>',$row['M2'],'</td>
        </tr>';
	}
?>
    </table>
		
		
		<table border="1px">
        <tr>
            <th>ФИО</th><th>М1</th><th>М2</th>
        </tr>
        <tr>
            <td>
<a href="Blokhin">Блохин М.С.</a> 
		</td>
            <td> 45 </td>
            <td></td>
        </tr>
        <tr>
		
	  <td>
<a href="Lagutenko">Лагутенко Д</a> 
		</td>
            <td> 40 </td>
            <td></td>
        </tr>
        <tr>	
		
	 <td>
<a href="Rakhman">Рахман А</a> 
		</td>
            <td> </td>
            <td></td>
        </tr>
        <tr>	
		
            <td>
<a href="Kovyzina">Ковызина Н.А.</a>
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>
<a href="Spiridonova">Спиридонова Е.Н.</a>
            </td>
            <td> 45 </td>
            <td></td>
        </tr>
        <tr>
            <td>
<a href="Zakharova">Захарова А.С.</a>
            </td>
            <td> 40 </td>
            <td></td>
        </tr>
        <tr>
            <td>
<a href="leontev">Леонтьев И.Н.</a>
             </td>
            <td> 50 </td>
            <td></td>
        </tr>
        <tr>
            <td>
<a href="shgi">Шарохин Г.И.</a>
             </td>
            <td> 40 </td>
            <td></td>
        </tr>
        
        <tr>
            <td>
<a href="Voeikov">Воейков А.Р.</a>
            </td>
            <td> 35 </td>
            <td></td>
        </tr>
        
        <tr>
            <td>
<a href="Antipov">Антипов А.А.</a>
             </td>
            <td> 50 </td>
            <td></td>
        </tr>

        <tr>
            <td>
<a href="Sautin">Саутин Д.С.</a>
             </td>
            <td> 45 </td>
            <td></td>
        </tr>
                <tr>
            <td>
<a href="Kulaev">Кулаев Д.А.</a>
             </td>
            <td></td>
            <td></td>
        </tr>
        
          <tr>
            <td>
<a href="Bartel">Бартель М.В.</a>
             </td>
            <td> 45 </td>
            <td></td>
        </tr>
        
         <tr>
            <td>
<a href="Fedoseeva">Федосеева А.С.</a>
             </td>
            <td> 50 </td>
            <td></td>
        </tr>
         <tr>
            <td>
<a href="IvanyutinJakob">Иванютин Я.А.</a>
             </td>
            <td></td>
            <td></td>
        </tr>
         <tr>
            <td>
<a href="Morozevich">Морозевич В.С.</a>
             </td>
            <td>40</td>
            <td></td>
        </tr>
        
         <tr>
            <td>
<a href="Vinogradova">Виноградова М.Б.</a>
             </td>
            <td> 50 </td>
            <td></td>
        </tr>
       
        <tr>
            <td>
<a href="Subbotin">Субботин П.М.</a>
             </td>
            <td> 50 </td>
            <td></td>
        </tr>
        
        <tr>
            <td>
<a href="Guts">Гуц М.А.</a>
             </td>
            <td> 45 </td>
            <td></td>
        </tr>
        <tr>
            <td>
<a href="Morozevich">Морозевич В.С.</a>
             </td>
            <td></td>
            <td></td>
        </tr>
 <tr>
            <td>
<a href="Pechnikov">Печников Н.А.</a>
             </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>
<a href="Orta">Орта Надточий Э.Х.</a>
             </td>
            <td> 45 </td>
            <td></td>
        </tr>
        <tr>
            <td>
<a href="Antonov">Антонов И.</a>
             </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>
<a href="Orlova">Орлова О. И.</a>
		      </td>
            <td></td>
            <td></td>
        </tr>
   	
    </table>
</body>
</html>
