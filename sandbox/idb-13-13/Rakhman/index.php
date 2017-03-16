<!DOCTYPE html>
  <?php include('connect.php'); ?>
<html lang="ru">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width">
    <title>Тег <input> и Single Page Application</title>
    <link href="css/style.css" rel="stylesheet" media="all">
	        <script type="text/javascript" src="js/jquery-2.0.3.min.js"></script>

    
    
	    <script type="text/javascript" language="javascript">
     	function call() {
     	  var msg   = $('#form1').serialize();
            $.ajax({
              type: 'POST',
              url: 'dummy.php',
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
     <script type="text/javascript" language="javascript">
	function jsoncall(){
var user = '{ "name": "Ален", "age": 21, "group": "ИДБ-13-13"}';
user = JSON.parse(user);
$('#json').html(user.age);
}
  </script>
  
  <script type="text/javascript" language="javascript">
function xmlcall(){		
$.ajax({
        type: "POST", 
        url: "data.xml", 
        dataType: "xml", 
        success: function(data) {
          var myage=$(data).find('student').find('group').html();
			$('#xml').html(myage);
        }
    });
}
  </script>
</head>

<body class="welcome">

	<a href="#navigation" class="navcontrol menu"><span class="visuallyhidden">Skip to navigation</span>
		<span class="box-lined-r box">
			Single Page Application
            
			<span class="panel-r"></span>
			<span class="panel-b-r"></span>
		</span>
	</a>

	<nav role="navigation" id="navigation">
		<div class="scroll">
					   <center>  <?php
             include('game.php');
               ?>
               </center>
						
          <link href="css/style.css" rel="stylesheet" media="all">
		</div>

  


	  <a href="#main" class="navcontrol close">
			<span class="visuallyhidden">Back to content</span>
			<span class="box-lined-l box">
				✕
				<span class="panel-l"></span>
				<span class="panel-b-l"></span>
			</span>
		</a>

	</nav>


	<div class="stripe"></div>

	<main>

		<div class="content">
			<div class="details">
            
				<p class="m-head">_______</p>
				<p class="s-head">Ален Рахман</p>
                <p class="s-head">ИДБ-13-13</p>
			</div>
			<div class="box intro">
				<h1>
               
                
    <a href="#input">Описать элемент html < input > с примером использования</a><br>
    <a href="https://github.com/stankin/oop/wiki/Лабораторная-работа-№5">Создать WIKI для лабораторной работы №5</a><br>
    <a href="#navigation"> Single Page Application </a><br>
   
   
</h1>
              
				<div class="panel-r"></div>
				<div class="panel-b-r"></div>
			</div>
			<div id="input" class="box logo">
				<img src="css/logo.png">
				<div class="panel-bg-r"></div>
				<div class="panel-bg-b-r"></div>
			</div>
			<div class="box meta">
        
            <div id="results"></div>
             <form method="POST" id="form1" action="javascript:void(null);"  onsubmit="call()">
					<div id="mc_embed_signup_scroll">
						<div class="indicates-required">
							<div class="mc-field-group">
<input name="email" class="required email"  placeholder="Ваш email" type="email">
<button name="subscribe" class="button">Подписаться</button>
							</div>
						
							
						</div>
					</div>
			  </form>
				<h2>Css и Html код:</h2>
               <code> 
<pre class="css" style="font-family:monospace;">  input <span style="color: #00AA00;">&#123;</span>
    <span style="color: #000000; font-weight: bold;">margin-bottom</span><span style="color: #00AA00;">:</span> <span style="color: #933;">.65em</span><span style="color: #00AA00;">;</span>
    <span style="color: #000000; font-weight: bold;">padding</span><span style="color: #00AA00;">:</span> <span style="color: #933;">0.25em</span> <span style="color: #933;">.65em</span><span style="color: #00AA00;">;</span>
    <span style="color: #000000; font-weight: bold;">border</span><span style="color: #00AA00;">:</span> <span style="color: #cc66cc;">0</span><span style="color: #00AA00;">;</span>
    <span style="color: #000000; font-weight: bold;">border-bottom</span><span style="color: #00AA00;">:</span> <span style="color: #933;">5px</span> <span style="color: #993333;">solid</span> <span style="color: #cc00cc;">#000</span><span style="color: #00AA00;">;</span>
    <span style="color: #000000; font-weight: bold;">background</span><span style="color: #00AA00;">:</span> <span style="color: #cc00cc;">#FFF</span><span style="color: #00AA00;">;</span>
    <span style="color: #000000; font-weight: bold;">font-size</span><span style="color: #00AA00;">:</span> <span style="color: #933;">1em</span><span style="color: #00AA00;">;</span>
    <span style="color: #000000; font-weight: bold;">font-family</span><span style="color: #00AA00;">:</span> <span style="color: #ff0000;">&quot;Circular&quot;</span><span style="color: #00AA00;">,</span> Helvetica<span style="color: #00AA00;">,</span> <span style="color: #993333;">sans-serif</span><span style="color: #00AA00;">;</span>
    <span style="color: #000000; font-weight: bold;">line-height</span><span style="color: #00AA00;">:</span> <span style="color: #933;">1.4em</span><span style="color: #00AA00;">;</span>
    <span style="color: #000000; font-weight: bold;">border-radius</span><span style="color: #00AA00;">:</span> <span style="color: #cc66cc;">0</span><span style="color: #00AA00;">;</span>
    <span style="color: #00AA00;">&#125;</span>
&nbsp;
  button <span style="color: #00AA00;">&#123;</span>
    <span style="color: #000000; font-weight: bold;">border-top</span><span style="color: #00AA00;">:</span> <span style="color: #933;">1.5px</span> <span style="color: #993333;">solid</span> <span style="color: #cc00cc;">#FFFF57</span><span style="color: #00AA00;">;</span>
    <span style="color: #000000; font-weight: bold;">border-bottom</span><span style="color: #00AA00;">:</span> <span style="color: #933;">1.5px</span> <span style="color: #993333;">solid</span> <span style="color: #cc00cc;">#FFFF57</span><span style="color: #00AA00;">;</span>
    <span style="color: #000000; font-weight: bold;">background</span><span style="color: #00AA00;">:</span> <span style="color: #cc00cc;">#FF4377</span><span style="color: #00AA00;">;</span>
    <span style="color: #000000; font-weight: bold;">color</span><span style="color: #00AA00;">:</span> <span style="color: #cc00cc;">#FFF</span><span style="color: #00AA00;">;</span>
    <span style="color: #000000; font-weight: bold;">cursor</span><span style="color: #00AA00;">:</span> <span style="color: #993333;">pointer</span><span style="color: #00AA00;">;</span>
    <span style="color: #00AA00;">&#125;</span>
&nbsp;
  button<span style="color: #3333ff;">:hover</span><span style="color: #00AA00;">,</span>
  button<span style="color: #3333ff;">:focus</span><span style="color: #00AA00;">,</span>
  button<span style="color: #3333ff;">:active </span><span style="color: #00AA00;">&#123;</span>
    <span style="color: #000000; font-weight: bold;">background</span><span style="color: #00AA00;">:</span> <span style="color: #cc00cc;">#0166FF</span>
    <span style="color: #00AA00;">&#125;</span></pre>
    								
 <pre class="html4strict" style="font-family:monospace;">  <span style="color: #009900;">&lt;<span style="color: #000000; font-weight: bold;">input</span> <span style="color: #000066;">name</span><span style="color: #66cc66;">=</span><span style="color: #ff0000;">&quot;email&quot;</span> <span style="color: #000066;">class</span><span style="color: #66cc66;">=</span><span style="color: #ff0000;">&quot;required email&quot;</span>  </span>
<span style="color: #009900;">placeholder<span style="color: #66cc66;">=</span><span style="color: #ff0000;">&quot;Ваш email&quot;</span> <span style="color: #000066;">type</span><span style="color: #66cc66;">=</span><span style="color: #ff0000;">&quot;email&quot;</span>&gt;</span>
  <span style="color: #009900;">&lt;<span style="color: #000000; font-weight: bold;">button</span> <span style="color: #000066;">name</span><span style="color: #66cc66;">=</span><span style="color: #ff0000;">&quot;subscribe&quot;</span> <span style="color: #000066;">class</span><span style="color: #66cc66;">=</span><span style="color: #ff0000;">&quot;button&quot;</span>&gt;</span>
Подписаться<span style="color: #009900;">&lt;<span style="color: #66cc66;">/</span><span style="color: #000000; font-weight: bold;">button</span>&gt;</span></pre>
    </code>
				<div class="panel-l"></div>
				<div class="panel-b-l"></div>
			</div>
<h2 class="speaker-head">Используем JSON и XML</h2>

	<div class="w-speakers">
				<div class="box w-speaker w-vitaly">
					<h3>					
                    <div id="json"></div>
<form method="POST" action="javascript:void(null);" onsubmit="jsoncall()">				
<button class="button">Показать возвраст</button>	
</form></h3>
                   	<h2>JSON, Javascirpt, HTML:</h2>
					<code>
                    	<pre class="javascript" style="font-family:monospace;"><span style="color: #000066; font-weight: bold;">function</span> jsoncall<span style="color: #009900;">&#40;</span><span style="color: #009900;">&#41;</span><span style="color: #009900;">&#123;</span>
<span style="color: #000066; font-weight: bold;">var</span> user <span style="color: #339933;">=</span> <span style="color: #3366CC;">'{
&quot;name&quot;: &quot;Ален&quot;,
&quot;age&quot;: 22,
&quot;group&quot;: &quot;ИДБ-13-13&quot;
}'</span><span style="color: #339933;">;</span>
user <span style="color: #339933;">=</span> JSON.<span style="color: #660066;">parse</span><span style="color: #009900;">&#40;</span>user<span style="color: #009900;">&#41;</span><span style="color: #339933;">;</span>
$<span style="color: #009900;">&#40;</span><span style="color: #3366CC;">'#json'</span><span style="color: #009900;">&#41;</span>.<span style="color: #660066;">html</span><span style="color: #009900;">&#40;</span>user.<span style="color: #660066;">age</span><span style="color: #009900;">&#41;</span><span style="color: #339933;">;</span>
<span style="color: #009900;">&#125;</span></pre>
<pre class="html4strict" style="font-family:monospace;"><span style="color: #009900;">&lt;<span style="color: #000000; font-weight: bold;">div</span> <span style="color: #000066;">id</span><span style="color: #66cc66;">=</span><span style="color: #ff0000;">&quot;json&quot;</span>&gt;&lt;<span style="color: #66cc66;">/</span><span style="color: #000000; font-weight: bold;">div</span>&gt;</span>
<span style="color: #009900;">&lt;<span style="color: #000000; font-weight: bold;">form</span> <span style="color: #000066;">method</span><span style="color: #66cc66;">=</span><span style="color: #ff0000;">&quot;POST&quot;</span></span>
<span style="color: #009900;"><span style="color: #000066;">action</span><span style="color: #66cc66;">=</span><span style="color: #ff0000;">&quot;javascript:void(null);&quot;</span></span>
<span style="color: #009900;"><span style="color: #000066;">onsubmit</span><span style="color: #66cc66;">=</span><span style="color: #ff0000;">&quot;jsoncall()&quot;</span>&gt;</span>				
<span style="color: #009900;">&lt;<span style="color: #000000; font-weight: bold;">button</span> <span style="color: #000066;">class</span><span style="color: #66cc66;">=</span><span style="color: #ff0000;">&quot;button&quot;</span>&gt;</span>
Показать возвраст<span style="color: #009900;">&lt;<span style="color: #66cc66;">/</span><span style="color: #000000; font-weight: bold;">button</span>&gt;</span>	
<span style="color: #009900;">&lt;<span style="color: #66cc66;">/</span><span style="color: #000000; font-weight: bold;">form</span>&gt;</span></pre>

                    </code>
					<div class="panel-r"></div>
					<div class="panel-b-r"></div>
				</div>
				<div class="box w-speaker w-dietrich">
										<h3>					<div id="xml"></div>
                  <form method="POST"
action="javascript:void(null);" 
onsubmit="xmlcall()">				
<button class="button">
Показать группу</button>	
</form>
</h3>
                   	<h2><a href="data.xml">XML</a>, Javascirpt, HTML:</h2>
					<code>
<pre class="javascript" style="font-family:monospace;"><span style="color: #000066; font-weight: bold;">function</span> xmlcall<span style="color: #009900;">&#40;</span><span style="color: #009900;">&#41;</span><span style="color: #009900;">&#123;</span>		
$.<span style="color: #660066;">ajax</span><span style="color: #009900;">&#40;</span><span style="color: #009900;">&#123;</span>
type<span style="color: #339933;">:</span> <span style="color: #3366CC;">&quot;POST&quot;</span><span style="color: #339933;">,</span> 
url<span style="color: #339933;">:</span> <span style="color: #3366CC;">&quot;data.xml&quot;</span><span style="color: #339933;">,</span> 
dataType<span style="color: #339933;">:</span> <span style="color: #3366CC;">&quot;xml&quot;</span><span style="color: #339933;">,</span> 
success<span style="color: #339933;">:</span> <span style="color: #000066; font-weight: bold;">function</span><span style="color: #009900;">&#40;</span>data<span style="color: #009900;">&#41;</span> <span style="color: #009900;">&#123;</span>
<span style="color: #000066; font-weight: bold;">var</span> myage<span style="color: #339933;">=</span>$<span style="color: #009900;">&#40;</span>data<span style="color: #009900;">&#41;</span>.
<span style="color: #660066;">find</span><span style="color: #009900;">&#40;</span><span style="color: #3366CC;">'student'</span><span style="color: #009900;">&#41;</span>.
<span style="color: #660066;">find</span><span style="color: #009900;">&#40;</span><span style="color: #3366CC;">'group'</span><span style="color: #009900;">&#41;</span>.<span style="color: #660066;">html</span><span style="color: #009900;">&#40;</span><span style="color: #009900;">&#41;</span><span style="color: #339933;">;</span>
$<span style="color: #009900;">&#40;</span><span style="color: #3366CC;">'#xml'</span><span style="color: #009900;">&#41;</span>.<span style="color: #660066;">html</span><span style="color: #009900;">&#40;</span>myage<span style="color: #009900;">&#41;</span><span style="color: #339933;">;</span>
<span style="color: #009900;">&#125;</span>
<span style="color: #009900;">&#125;</span><span style="color: #009900;">&#41;</span><span style="color: #339933;">;</span>
<span style="color: #009900;">&#125;</span></pre>
<pre class="html4strict" style="font-family:monospace;"><span style="color: #009900;">&lt;<span style="color: #000000; font-weight: bold;">div</span> <span style="color: #000066;">id</span><span style="color: #66cc66;">=</span><span style="color: #ff0000;">&quot;xml&quot;</span>&gt;&lt;<span style="color: #66cc66;">/</span><span style="color: #000000; font-weight: bold;">div</span>&gt;</span>
<span style="color: #009900;">&lt;<span style="color: #000000; font-weight: bold;">form</span> <span style="color: #000066;">method</span><span style="color: #66cc66;">=</span><span style="color: #ff0000;">&quot;POST&quot;</span></span>
<span style="color: #009900;"><span style="color: #000066;">action</span><span style="color: #66cc66;">=</span><span style="color: #ff0000;">&quot;javascript:void(null);&quot;</span> </span>
<span style="color: #009900;"><span style="color: #000066;">onsubmit</span><span style="color: #66cc66;">=</span><span style="color: #ff0000;">&quot;xmlcall()&quot;</span>&gt;</span>				
<span style="color: #009900;">&lt;<span style="color: #000000; font-weight: bold;">button</span> <span style="color: #000066;">class</span><span style="color: #66cc66;">=</span><span style="color: #ff0000;">&quot;button&quot;</span>&gt;</span>
Показать группу<span style="color: #009900;">&lt;<span style="color: #66cc66;">/</span><span style="color: #000000; font-weight: bold;">button</span>&gt;</span>	
<span style="color: #009900;">&lt;<span style="color: #66cc66;">/</span><span style="color: #000000; font-weight: bold;">form</span>&gt;</span></pre>

                    </code>
					<div class="panel-r"></div>
					<div class="panel-b-r"></div>
				</div>
				
		</div>




<div>

 </div>

 </body>
 </html>