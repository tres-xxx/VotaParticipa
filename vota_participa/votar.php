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
div.divBar {
	height: 5%;
}
div.Dpregunta{
	text-align: center;
}
input[type=submit] {
	padding: 1% 4%;
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
<div class="Dpregunta">
<?php
	if(isset($_POST['votarUsuario'])){
		$numeroPregunta = $_POST['votarUsuario'];
		echo "<br>";
		
		$my_q = "SELECT COUNT(*) AS votos ";
		$my_q = $my_q . "FROM voto ";
		$my_q = $my_q . "WHERE numeroPregunta=:numeroPregunta AND usuario=:usuario";
		$query = $connection->prepare($my_q);
		$query->bindParam("numeroPregunta",$numeroPregunta, PDO::PARAM_STR);
		$query->bindParam("usuario",$_SESSION['user_id'], PDO::PARAM_STR);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
		if($result){
			if($result['votos'] > 0 ){
				echo "<p style='text-align:center;'><i>Usted ya ha votado a la pregunta elegida</i></p><br><br>";
				echo "Hasta el momento se han realizado la siguiente cantidad de votos:<br>";
				$my_q = "SELECT COUNT(*) AS votosTotales, tiempoCierre ";
				$my_q = $my_q . "FROM voto,votacionpregunta ";
				$my_q = $my_q . "WHERE numeroPregunta=:numeroPregunta AND votacionpregunta.numero=voto.numeroPregunta";
				$query = $connection->prepare($my_q);
				$query->bindParam("numeroPregunta",$numeroPregunta, PDO::PARAM_STR);
				$query->execute();
				$result = $query->fetch(PDO::FETCH_ASSOC);
				if(!$result){ exit; }
				echo "<h1 style='text-align:center;'>" . $result['votosTotales'] . " </h1>";
				echo "<h2 style='text-align:center;'> Hora de cierre: " . $result['tiempoCierre'] . "</h2>";
			}
			else{
				$my_q = "SELECT pregunta ";
				$my_q = $my_q . "FROM votacionpregunta ";
				$my_q = $my_q . "WHERE numero=:numeroPregunta ";
				$query = $connection->prepare($my_q);
				$query->bindParam("numeroPregunta",$numeroPregunta, PDO::PARAM_STR);
				$query->execute();
				$result = $query->fetch(PDO::FETCH_ASSOC);
				if($result){
					echo "<h2>Pregunta: " . $result['pregunta'] . "</h2><br><br>";
				
					
					echo "<form method='post' action=" . $_SERVER['PHP_SELF'] . " >";
					
					echo "<input type='hidden' id='numeroPregunta' name='numeroPregunta' value='" . $numeroPregunta . "'>";
					
					$my_q = "SELECT * ";
					$my_q = $my_q . "FROM votacionopcion ";
					$my_q = $my_q . "WHERE votacionNumero=:numeroPregunta ";
					$my_q = $my_q . "ORDER BY votacionOpcion"; 
					$query = $connection->prepare($my_q);
					$query->bindParam("numeroPregunta",$numeroPregunta, PDO::PARAM_STR);
					$query->execute();
					if($query){
						$result = $query->fetch(PDO::FETCH_ASSOC);
						$i = 1;
						while($result){
							
							echo "<input type='radio' id='opcion" . $result['votacionOpcion'] . "' name='opcionElegida' value='" . $result['votacionOpcion'] . "' style='font-size:24px;' ";
							if($i == 1){
								echo "checked";
							}
							echo ">";
							echo "<label for='opcion" . $result['votacionOpcion'] . "' style='font-size:24px;'>" . $result['opcion'] . "</label><br>";
							$result = $query->fetch(PDO::FETCH_ASSOC);
							$i = $i + 1;
						}
						
					}
					else{
						echo $my_q;
						echo "error en query de votacion";
					}
					echo "<br>";
					echo "<input type='submit' value='Votar' name='crearVoto' id='crearVoto'>";
					
					echo "</form>";
					/*
					$cantidadOpciones = $_POST['opciones'];
					$pregunta = $_POST['pregunta'];
					$opciones = array(); # Array vacio
					for($i = 1; $i <= $cantidadOpciones; $i++){
						array_push($opciones, $_POST['opcion'.$i]);
					}
					$cantidadVotantes = $_POST['cantidadV']; # 0 = usuarios activos
					//$tiempo = array($_POST['hora'],$_POST['minuto']);
					$tiempo = $_POST['hora'] . ":" . $_POST['minuto'] . ":00"; //hora:minuto:00
					$votacionInactiva = $_POST['inactiva'];
					
					echo $cantidadOpciones . "<br>";
					echo $pregunta . "<br>";
					echo $opciones[0];
					
					if($cantidadVotantes == "0"){
						echo "<br>En cantidadVotantes<br>";
						$my_q = "SELECT COUNT(*) AS conteo ";
						$my_q = $my_q . "FROM usuario ";
						$my_q = $my_q . "WHERE acceso = 'si' AND rol_numero = 2";
						$query = $connection->prepare($my_q);
						$query->execute();
						$result = $query->fetch(PDO::FETCH_ASSOC);
						if($result){
							$cantidadVotantes = $result['conteo'];
						}
						else{
							echo $my_q;
							echo "error en query de conteo";
						}
					}
					echo "<br>cantidad votantes " . $cantidadVotantes;
					for($i = 0; $i < $cantidadOpciones; $i++){
						echo "<br> opcion" . $i . " = " . $opciones[$i];
					}
					
					
					$my_q = "INSERT INTO votacionPregunta(";
					$my_q = $my_q . "pregunta,cantidadVotantes,activa,horaCierre) ";
					$my_q = $my_q . "VALUES (";
					$my_q = $my_q . ":pregunta,:cantidadVotantes,:votacionInactiva,:tiempo)";
					$query = $connection->prepare($my_q);
					$query->bindParam("pregunta",$pregunta, PDO::PARAM_STR);
					$query->bindParam("cantidadVotantes",$cantidadVotantes, PDO::PARAM_STR);
					$query->bindParam("votacionInactiva",$votacionInactiva, PDO::PARAM_STR);
					$query->bindParam("tiempo",$tiempo,PDO::PARAM_STR); //hora:minuto:00
					$result = $query->execute();
					
					if($result){ // Insertar opciones para la pregunta
						/*$my_q = "SELECT numero ";
						$my_q = $my_q . "FROM votacionpregunta ";
						$my_q = $my_q . "WHERE id = LAST_INSERT_ID()";
						$my_q = "SELECT MAX(numero) AS numero FROM votacionpregunta"; // Obtener ultima pregunta creada
						//$my_q = "SELECT LAST_INSERT_ID()";
						$query = $connection->prepare($my_q);
						//$query->bindParam("pregunta",$pregunta, PDO::PARAM_STR);
						$query->execute();
						$result = $query->fetch(PDO::FETCH_ASSOC);
						if($result){
							$np = $result['numero'];
							//$np = $connection->insert_id;
							foreach ($opciones as $op){
								$my_q = "INSERT INTO votacionopcion(";
								$my_q = $my_q . "opcion,votacionNumero) ";
								$my_q = $my_q . "VALUES (";
								$my_q = $my_q . ":opcion,:numeroPregunta)";
								$query = $connection->prepare($my_q);
								$query->bindParam("opcion",$op, PDO::PARAM_STR);
								$query->bindParam("numeroPregunta",$np, PDO::PARAM_STR);
								$result = $query->execute();
								if(!$result){
									exit(1); // Error en query
								}
							}
							unset($op);
							header('Location: votacion.php');
						}
					}
					else{
						echo $my_q;
						echo "error en query";
					}
					*/
				}
				else{
					echo $my_q;
					echo "Error en query pregunta";
				}
			}
		}
	}
	else{
		if(isset($_POST['crearVoto'])){
			$query = $connection->prepare("INSERT INTO voto(usuario,votacionNumero,numeroPregunta) VALUES (:usuario,:votacionNumero,:numeroPregunta)");
			$query->bindParam("usuario", $_SESSION['user_id'], PDO::PARAM_STR);
			$query->bindParam("votacionNumero", $_POST['opcionElegida'], PDO::PARAM_STR);
			$query->bindParam("numeroPregunta",$_POST['numeroPregunta'], PDO::PARAM_STR);
			$result = $query->execute();
	 
			if ($result) {
				echo '<p class="success">Voto realizado exitosamente</p>';
			} else {
				echo '<p class="error">Algo salió mal con el voto!</p>';
			}
		}
	}
?>
</div>

</body>
</html>