<!DOCTYPE html>
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
                <ul>
                
    <li><a href="#input">Описать элемент html < input > с примером использования</a></li>
    <li><a href="https://github.com/stankin/oop/wiki/Лабораторная-работа-№5">Создать WIKI для лабораторной работы №5</a></li>
   <li><a href="#navigation"> Single Page Application </a></li>
   
    </ul>
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






<div>

 </div>

 </body>
 </html>
