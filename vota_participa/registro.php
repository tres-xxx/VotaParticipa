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

div.Dregistrar {
	width: 95%;
	display: block;
	margin-right: auto;
	margin-left: auto;
	text-align: center;
	background-image: url('img/blanco-80.png');
}
#nombre,#apellido,#documento,#apartamento,#torre{
	width: 85%;
	text-align: center;
}
.crearUsuario {
	padding: 2% 2%;
	border: none;
	background-color: #F7DC6F;
}
div.divBar {
	height: 5%;
}
</style>
<script language="javascript">
	window.setInterval("actualizarUC()", 10);
	function actualizarUC(){
		document.getElementById("usuario").value = document.getElementById("torre").value + "-" + document.getElementById("apartamento").value;
		document.getElementById("contra").value = document.getElementById("nombre").value.substring(0,2).toUpperCase()+ document.getElementById("documento").value;
		/*
		//var nombre = document.getElementById("nombre").value;
		var documento = document.getElementById("documento").value;
		if(document.getElementById("nombre").value == null){
			document.getElementById("contra").value = nombre+documento;
		}
		else{
			document.getElementById("contra").value = "";
		}*/
	}
</script>
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
<div class="Dregistrar" id="Dregistrar">
	<h2>Registro de usuario</h2>
	<form method="post" action=" <?php $_SERVER['PHP_SELF'] ?> ">
		Nombre <br>
		<input type="text" name="nombre" id="nombre" required>
		<br><br>
		Apellido <br>
		<input type="text" name="apellido" id="apellido" required>
		<br><br>
		Documento <br>
		<input type="text" name="documento" id="documento" required>
		<br><br>
		Torre <br>
		<input type="text" name="torre" id="torre" required>
		<br><br>
		Apartamento <br>
		<input type="text" name="apartamento" id="apartamento" required>
		<br><br>
		Usuario <br>
		<input type="text" name="usuario" id="usuario" readonly>
		<br><br>
		Contraseña <br>
		<input type="text" name="contra" id="contra" readonly>
		<br><br>
		<input type="submit" value='Crear usuario' name='crearUsuario' id='crearUsuario' class='crearUsuario'>
	</form>
</div>
<?php
	if(isset($_POST['crearUsuario'])){
		$tipoU = 2;
		$acceso = "no";
		$my_q = "INSERT INTO usuario";
		$my_q = $my_q . "(nombre,apellido,documento,apartamento,rol_numero,usuario,pwd,acceso) ";
		$my_q = $my_q . "VALUES ";
		$my_q = $my_q . "(:nombre,:apellido,:documento,:apartamento,:rol,:usuario,:pwd,:acceso)";
		$query = $connection->prepare($my_q);
		$query->bindParam("nombre", $_POST["nombre"], PDO::PARAM_STR);
		$query->bindParam("apellido", $_POST["apellido"], PDO::PARAM_STR);
		$query->bindParam("documento", $_POST["documento"], PDO::PARAM_STR);
		$query->bindParam("apartamento", $_POST["usuario"] , PDO::PARAM_STR);
		$query->bindParam("rol", $tipoU, PDO::PARAM_STR); // 2 => votante 1=> administrador
		$query->bindParam("usuario", $_POST["usuario"], PDO::PARAM_STR);
		$query->bindParam("pwd", $_POST["contra"], PDO::PARAM_STR);
		$query->bindParam("acceso", $acceso, PDO::PARAM_STR);
		$result = $query->execute();
		$mensaje = "";
		if($result){
			$mensaje = "Usuario creado correctamente";
		}
		else{
			$mensaje = "Hubo un error al crear el usuario";
		}
		/*echo "<!DOCTYPE html>";
		echo "<html>";
		echo "<head>";
		echo "<style>";
		echo "html {";
			echo "height: 100%;";
			echo "width: 100%;";
			echo "overflow: hidden;";
		echo "}";

		echo "body {
			height: 98%;
			width: 99%;
			background-image: url('img/high-angle-multiple-election-questionnaires.jpg');
			background-size: cover;
			overflow: scroll;
		}";
		echo "</head>
		<body>";*/
		echo "<script type='text/javascript'>alert('" . $mensaje . "');</script>";
		/*echo "</body>
		</html>";*/
		
	}
?>
</body>
</html>