<?php
session_start();
include('config.php');
 
if(!isset($_SESSION['user_id'])){
    header('Location: login.html');
    exit;
} 
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=0.9">
<!--<meta http-equiv="refresh" content="1">-->
<style>
html {
	height: 100%;
	width: 100%;
	overflow: hidden;
}

body {
	height: 98%;
	width: 99%;
	background-image: url('img/high-angle-multiple-election-questionnaires.jpg');
	background-size: cover;
	overflow: scroll;
}

ul {
	list-style-type: none;
	margin: 0;
	padding: 0;
	overflow: hidden;
	background-color: #333;
}

li {
	float: left;
}

li a {
	display: block;
	color: white;
	text-align: center;
	padding: 14px 16px;
	text-decoration: none;
}

li a:hover:not(.active){
	background-color: #111;
}

.active {
	background-color: #04AA6D;
}

.container {
  border: 2px solid #dedede;
  background-color: #f1f1f1;
  border-radius: 5px;
  padding: 10px;
  margin: 10px 0;
}

.darker {
  border-color: #ccc;
  background-color: #ddd;
}

.container::after {
  content: "";
  clear: both;
  display: table;
}

.container img {
  float: left;
  max-width: 60px;
  width: 100%;
  margin-right: 20px;
  border-radius: 50%;
}

.container img.right {
  float: right;
  margin-left: 20px;
  margin-right:0;
}

.time-right {
  float: right;
  color: #aaa;
}

.time-left {
  float: left;
  color: #999;
}

div.scroll {
	width: 100%;
	height:80%;
	overflow-x: hidden;
	overflow-y: auto;
	display: flex;
	flex-direction: column;
}

div.divBar {
	height: 5%;
}

div.divMessages {
	height: 60%;
	http-equiv="refresh"
	content="1"
}

div.divInputMessage {
	height:30%;
}

div.divUpdate {
	position: relative;
	display: inline-block;
}

#TmensajeN {
	visibility: hidden; 
}

input[type=text], select{
	width: 100%;
	height: 60px;
	margin: 8px 0;
	display: inline-block;
	overflow-y: auto;
	overflow-x: hidden;
	border: 1px solid #ccc;
	border-radius: 2px;
	box-sizing: border-box;
}

input[type=submit] {
	width: 100%;
	background-color: #4CAF50;
	color: white;
	padding: 14px 20px;
	margin: 8px 0;
	border: none;
	border-radius: 2px;
	cursor:pointer;
}

</style>
<script language="javascript">
	//window.setInterval("refreshDiv()", 1000);
	//function refreshDiv(){
	//	$("buttonUpdate").click(
		//document.getElementById("divUpdate").innerHTML += '<object type="text/html" data="updateMessages.php"></object>';
		//document.getElementById("mensajes").innerHTML = document.getElementById("mensajes").innerHTML + '<object type="text/html" data="updateMessages.php" width="100%" height="90%"></object>';
		//document.getElementById("mensajes").appendChild('<object type="text/html" data="updateMessages.php" class="object_m" id="object_m" width="100%" height="90%"></object>');
		//$( "mensajes" ).load(window.location.href + " mensajes");
		/*var cantidadM = "<?php $query_sql = 'SELECT * FROM mensaje WHERE numero > ' . strval($_SESSION['mensaje_conteo']) . ' ORDER BY numero ASC' ; $query = $connection->prepare($query_sql); $query->execute(); echo $query->rowCount(); ?>";
		var cantidadMp = "<?php echo $_SESSION['mensaje_conteo']; ?>";
		if(cantidadM > cantidadMp){
		//document.getElementById("mensajes").innerHTML = cantidadM;
			document.getElementById("mensajes").innerHTML += '<object type="text/html" data="updateMessages.php" class="object_m" id="object_m" width="100%" height="90%"></object>';
		}
		/*$.ajax({
                type: "GET",
                url: "updateMessages.php" ,
                data: { h: "michael" },
                success : function() { 

                    // here is the code that will run on client side after running clear.php on server

                    // function below reloads current page
                    location.reload();

                }
        });*/
	//}
	function updateM(){
		document.getElementById("scroll").innerHTML = '<object type="text/html" data="updateMessages.php" class="object_m" id="object_m" width="100%" height="100%"></object>';
		var n_men_prev = "<?php $_SESSION['mensaje_conteo'] ?>";
	}
</script>
<script type="text/javascript" src="javaScript/jquery-3.6.0.js"></script>
<script>
	setInterval(function(){
		//$(document).ready(function(){
			$.post("nuevoMensaje.php", 
			function(resultado){
				//alert(#resultado);
				//var t = document.getElementById("TmensajeN");
				if(resultado == "Si"){
					//t.style.display= "block";
					//$('#respJQ').html(resultado);
					$('#TmensajeN').css("visibility","visible");
				}else{
					$('#TmensajeN').css("visibility","hidden");
				}
				//$('#respJQ').html(resultado);
			});
			/*
			function(resultado){
				if(resultado == "Si"){
					alert("Data expired");
				}
			}
			*/
		//}
		/*$.ajax({
			type: "POST",
			url: "nuevoMensaje.php",
			data: "3",
			success: function(){
				alert("hola");
			}
		});*/
	}, 3000);
</script>
</head>
<body>
<?php
	/*if(isset($_POST['enviarMensaje'])){
		$id_usuario = $_SESSION['user_id'];
		$usuario = $_SESSION['usuario'];
		$mensaje = $_POST['message-chat'];
		$query = $connection->prepare("INSERT INTO mensaje(texto,id_usuario,usuario) VALUES (:mensaje,:id_usuario,:usuario)");
		$query->bindParam("id_usuario", $id_usuario, PDO::PARAM_STR);
		$query->bindParam("mensaje", $mensaje, PDO::PARAM_STR);
		$query->bindParam("usuario", $usuario, PDO::PARAM_STR);
		$result = $query->execute();
 
        if ($result) {
            //echo '<p class="success">Your message was successful!</p>';
        } else {
            echo '<p class="error">Algo salió mal con el mensaje enviado!</p>';
        }
		//mysql_close($connection);
	}*/
?>

<div class="divBar">

<ul>
	<li><a href="home.php">Inicio</a></li>
	<li><a href="votacion.php">Votacion</a></li>
	<?php
		if($_SESSION['usuario_tipo'] == 1){ # Usuario administrador
			echo "<li><a href='registro.php'>Registro</a></li>";
		}
	?>
	<li><a href="Chat.php">Chat</a></li>
	<li style="float:right"><a class="active" href="logout.php">Cerrar sesión</a></li>
</ul>

</div>

<div class="divMessages" id="divMessages">

<h2>Mensajes en el chat</h2>

<div class="scroll" id="scroll">

<div id="mensajes">

<?php
	//include('config.php');
	//include('updateMessages.php');
	
	$query = $connection->prepare("SELECT * FROM mensaje ORDER BY numero DESC");
	$query->execute();
	$color_div = 0;
	$_SESSION['mensaje_conteo'] = 0;
	foreach ($query as $m){
		if($color_div%2 == 0){
			echo "<div class='container'>";
		}
		else{
			echo "<div class='container darker'>";
		}
		echo "<p><i>". $m['usuario'] . "</i></p>";
		echo "<p>". $m['texto'] . "</p>";
		echo "<span class='time-left'>". $m['fecha'] . "</span>";
		echo "</div>";
		$color_div = $color_div + 1;
		if($m['numero'] > $_SESSION['mensaje_conteo']){
			$_SESSION['mensaje_conteo'] = $m['numero'];
		}
	}
	//**/
?>

</div>

</div>

</div>

<div class="divInputMessage">

<div class="divUpdate" id="divUpdate">

<button id="buttonUpdate" name="buttonUpdate" onclick="updateM()">Actualizar</button> 
<p id='TmensajeN' name='TmensajeN'><i><b>*Hay nuevos mensajes</b></i></p>

</div>
<div id="respJQ"></div>

<h4>Escribe tu mensaje</h4>

<!--<form method="post" action="?php $_PHP_SELF ?>">-->
<form method="post" action="sendMessage.php">
	<input type="text" id="message-chat" name="message-chat" placeholder="Mensaje..." style="width:100%" required> <br> <br>
	<input type="submit" value="Enviar" name="enviarMensaje" id="enviarMensaje">
</form>

</div>

</body>
</html>