<?php
session_start();
 
if(!isset($_SESSION['user_id'])){
    header('Location: login.html');
    exit;
} 
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=0.9">
<style>
html {
	height: 100%;
	width: 100%;
	overflow: hidden;
}

body {
	height: 100%;
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
div.intro {
	width: 95%;
	height: 90%;
	background-image: url('img/blanco-40.png');
}
div.divBar {
	height: 5%;
}

</style>
</head>
<body>
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
<br>
<div class="intro">
<h4>Sistema de votación de selección única.</h4><br><br>


<?php
	if($_SESSION['usuario_tipo'] == 1){
		echo "En la sección de <i>votación</i> se puede crear una votación, cerrar una votación activa, o activar una votación ya creada sin votos.";
	}
	else{
		echo "En la sección de <i>votación</i> está habilitada la opción para votar y ver los resultados de las votaciones.";
	}
?>
<br><br>
En la sección de <i>chat</i> se puede enviar mensajes a otros usuarios que estén conectados. Para enviar un mensaje debe dar click en <i>enviar</i>. Los mensajes no se actualizan de manera automática, para actualizar la caja de mensajes debe dar click en <i>Actualizar</i>.<br><br>
<?php
	if($_SESSION['usuario_tipo'] == 1){
		echo "En la sección de <i>registro</i> se puede registrar a un votante y de ser necesario modificar los datos de un usuario ya creado. Se sugiere cambiar clave de usuario 'admin'.";
	}
?>
<br><br><br>
</div>
Imagen de fondo obtenida de: <a href='https://www.freepik.es/fotos/verificacion'>Foto de Verificación creado por freepik - www.freepik.es</a>
</body>
</html>