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

#BrealizarV, #BvotacionesActivas, #BvotacionesInactivas, #BvotacionesCerradas {
	width: 50%;
}

div.realizarV {
	width: 95%;
	background-image: url('img/blanco-80.png');
	margin-bottom: 10px;
}

div.votacionesActivas, div.votacionesInactivas, div.votacionesCerradas {
	width: 95%;
	background-image: url('img/blanco-60.png');
	margin-bottom: 10px;
}

div.centrarS {
	text-align: center;
	margin-bottom: 10px;
}

div.divBar {
	height: 5%;
}

input[id=cerrarVotacion] {
	background-color: #FF4500;
	border: none;
	padding: 5px 5px;
	color: white;
}

input[id=activarVotacion] {
	background-color: #008000;
	color: white;
	padding: 5px 5px;
	border: none;
}

#crearVotacion{
	background-color: #4CAF50;
	padding: 5px 15px;
	border: none;
	color: white;
}

.active {
	background-color: #04AA6D;
}
</style>
<script language="javascript">
	function establecerOpciones(){
		var n_op = document.getElementById("cantidadOp").value;
		document.getElementById("opciones").value = n_op;
		document.getElementById("opcionesF").innerHTML = "";
		//var divOP = document.getElementById("opcionesF").innerHTML;
		for(i = 1; i <= n_op; i++){
			//divOP += "hola";
			document.getElementById("opcionesF").innerHTML += ("Opción " + i + ": <input type='text' id='opcion" + i + "'  name='opcion" + i + "' required><br>");
		}
	}
	function ocultarSeccion(s){
		//var s = 1;
		s = s-1;
		var div_s = ["realizarV","votacionesActivas","votacionesInactivas","votacionesCerradas"];
		//if(s == 1){
			var x = document.getElementById(div_s[s]);
			if (x.style.display === "none") {
				x.style.display = "block"; // mostrar seccion
			}
			else{
				x.style.display = "none"; // ocultar seccion
			}
		//}		
	}
	function ocultarSeccionU(s){
		var div_s = ["votacionesCerradas"];
		var x = document.getElementById(div_s[s]);
		if (x.style.display === "none") {
			x.style.display = "block"; // mostrar seccion
		}
		else{
			x.style.display = "none"; // ocultar seccion
		}
	}
</script>
<script type="text/javascript" src="javaScript/jquery-3.6.0.js"></script>
<script>
	//$.noConflict();
	$(document).ready(function(){
		$("form[id='FcerrarV']").submit(function(){
			if(confirm("¿Esta seguro de cerrar la votación?\n Si la votación tiene al menos un voto no podrá ser activada de nuevo.")){
				return true;
			}
			else{
				return false;
			}
		});
	});
	$(document).ready(function(){
		$("form[id='FcrearV']").submit(function(){
			if(confirm("¿Crear votación con valores establecidos?")){
				return true;
			}
			else{
				return false;
			}
		});
	});
	/*window.onload = function() {
    if (window.jQuery) {  
        // jQuery is loaded  
        alert("Yeah!");
    } else {
        // jQuery is not loaded
        alert("Doesn't Work");
    }
	}
	*/
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

<?php
/*<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
<form name="test" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
*/
	if(isset($_POST['cerrarVotacion'])){
		$my_q = "SELECT * FROM votacionpregunta WHERE numero=:numeroPregunta";
		$query = $connection->prepare($my_q);
		$query->bindParam("numeroPregunta", $_POST['numeroPregunta'], PDO::PARAM_STR);
		$result = $query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
		if(!$result){
			echo "Error en consulta votacion al activar";
			exit;
		}
		if($result['activa'] == "Si"){
			$query = $connection->prepare("UPDATE votacionPregunta SET activa='No' WHERE numero=:numeroPregunta");
			$query->bindParam("numeroPregunta", $_POST['numeroPregunta'], PDO::PARAM_STR);
			$query->execute();
			if(!$query){
				echo "Error al cerrar votación";
			}
			else{
				echo "<h4> Votación cerrada exitosamente</h4>";
			}
		}
	}
	else{
	if(isset($_POST['activarVotacion'])){
		$query = $connection->prepare("UPDATE votacionPregunta SET activa='Si' WHERE numero=:numeroPregunta");
		$query->bindParam("numeroPregunta", $_POST['numeroPregunta'], PDO::PARAM_STR);
		$query->execute();
		if(!$query){
			echo "Error al activar votación";
			exit;
		}
		$my_q = "SELECT horaCierre FROM votacionpregunta WHERE numero=:numeroPregunta";
		$query = $connection->prepare($my_q);
		$query->bindParam("numeroPregunta",$_POST['numeroPregunta'], PDO::PARAM_STR);
		$result = $query->execute();
		if(!$result){
			echo "Error en consulta votacion al activar";
			exit;
		}
		$result = $query->fetch(PDO::FETCH_ASSOC);
		$my_q = "CREATE EVENT IF NOT EXISTS p" . $_POST['numeroPregunta'] . "cierre ";
		$my_q = $my_q . "ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL '";
		$my_q = $my_q . $result['horaCierre'] . "' HOUR_SECOND ";
		$my_q = $my_q . "DO UPDATE votacionpregunta ";
		$my_q = $my_q . "SET activa='No' ";
		$my_q = $my_q . "WHERE numero=:numeroPregunta";
		$query = $connection->prepare($my_q);
		$query->bindParam("numeroPregunta",$_POST['numeroPregunta'], PDO::PARAM_STR);
		$query->execute();
		if(!$query){
			echo $my_q;
			echo "Error al crear evento";
			exit;
		}
		else{
			echo "<h4> Votación activada exitosamente</h4>";
		}
	}
	else{
		$votacionesActivas = array(); // Arreglo con la informacion de las votaciones
		if($_SESSION['usuario_tipo'] == 2){ # Usuario votante
			//if(isset($_POST['enviarMensaje']))
			//echo "<h4>Votaciones en curso</h4>";
			echo "<br>";
			echo "<button onclick='ocultarSeccion(2)' id='BvotacionesActivas'>Votaciones en curso</button><br><br>";
			echo "<div id='votacionesActivas' style='display:none'>";	
			$my_q = "SELECT * ";
			$my_q = $my_q . "FROM votacionpregunta ";
			$my_q = $my_q . "WHERE activa = 'Si'";
			$query = $connection->prepare($my_q);
			$query->execute();
			if($query){ 
				$result = $query->fetch(PDO::FETCH_ASSOC);
				if(!$result){
					echo "No hay votaciones en curso en este momento.";
					echo "<br><br>";
				}
				else{
					echo "<form method='post' action='votar.php'>";
					while ($result){
						echo "<button name='votarUsuario' value='" . $result['numero'] . "'>" . $result['pregunta'] . "</button><br>";
						$result = $query->fetch(PDO::FETCH_ASSOC); // Siguiente fila
					}
					echo "</form>";
					echo "<br><br>";
				}
			}
			else{
				echo $my_q;
				echo "error en query de votaciones activas";
			}
			echo "</div>";
			
			echo "<button onclick='ocultarSeccion(4)' id='BvotacionesCerradas'>Votaciones Cerradas</button><br><br>";
			echo "<div id='votacionesCerradas' style='display:none'>";
			//echo "<h4>Votaciones cerradas</h4>";
			echo "<form method='post' action='resultados.php'>";
			/*while ($result){
				echo "<button name='votarUsuario' value='" . $result['numero'] . "'>" . $result['pregunta'] . "</button><br>";
				$result = $query->fetch(PDO::FETCH_ASSOC); // Siguiente fila
			}*/
			
			$my_q = "SELECT * ";
			$my_q = $my_q . "FROM votacionpregunta ";
			$my_q = $my_q . "WHERE activa = 'No' ";
			$my_q = $my_q . "AND EXISTS ";
			$my_q = $my_q . "(SELECT votacionNumero FROM voto WHERE voto.numeroPregunta = votacionpregunta.numero) ";
			$my_q = $my_q . "ORDER BY numero";
			$query = $connection->prepare($my_q);
			$query->execute();
			if($query){
				$result = $query->fetch(PDO::FETCH_ASSOC);
				if(!$result){
					echo "No hay votaciones cerradas en el momento.";
				}
				else{
					echo "Ver resultados de las siguientes votaciones: <br><br>";
					while ($result){
						echo "<button name='verVotacion' value='" . $result['numero'] . "'>" . $result['pregunta'] . "</button><br>";
						$result = $query->fetch(PDO::FETCH_ASSOC); // Siguiente fila
					}
				}
			}
			else{
				echo $my_q;
				echo "error en query de votaciones cerradas";
			}
			//echo "<input type='submit' value='Ver votación' name='verVotacion' id='verVotacion'>";
			echo "</form>";
			echo "</div>";
		}
		else{
			if($_SESSION['usuario_tipo'] == 1){ # Usuario admin
			#<input type="text" id="message-chat" name="message-chat" placeholder="Mensaje..." style="width:100%" required> <br> <br>
			#<input type="submit" value="Enviar" name="enviarMensaje" id="enviarMensaje">
				echo "<br>";
				echo "<button onclick='ocultarSeccion(1)' id='BrealizarV'>Crear votación</button><br><br>";
				//echo "<h4>Realizar votación</h4>";
				echo "<div class= 'realizarV' id='realizarV' style='display:none'>";
				echo "<br>";
				echo "Cantidad de opciones (máximo 20) <input type='number' id='cantidadOp' name='cantidadOp' min='2' max='20' value='2'> <button id='establecerOp' name='establecerOp' onclick='establecerOpciones()'>Establecer</button><br>";
				echo "<form method='post' action='crearVotacion.php' id='FcrearV'>";
				echo "<h4>Introduzca pregunta</h4>";
				echo "<input type='text' id='pregunta' name='pregunta' placeholder='Introduzca la pregunta...' style='width:80%' required><br>";
				echo "<h4>Escriba las opciones</h4>";
				echo "<input type='hidden' id='opciones' name='opciones' value='2'>";
				echo "<div id='opcionesF'>";
				echo "Opción 1: <input type='text' id='opcion1' name='opcion1' required><br>";
				echo "Opción 2: <input type='text' id='opcion2' name='opcion2' required><br>";
				echo "</div>";
				echo "<br>";
				echo "Cantidad de votantes (0 = usuarios activos): <input type='number' id='cantidadV' name='cantidadV' min='0' max='2000' value='0'><br>";
				echo "Duración (hh:mm:ss): ";
				echo "<input type='number' id='hora' name='hora' min='0' max='23' placeholder='15' required>:";
				echo "<input type='number' id='minuto' name='minuto' min='0' max='59' placeholder='30' required>:";
				echo "<input type='number' id='segundo' name='segundo' min='0' max='59' placeholder='15' required><br>";
				echo "<br>";
				echo "Crear votacion activa? (al seleccionar 'no' la votación se crea pero no aparece disponible para los usuarios):";
				echo "<input type='radio' id='inactivaNo' name='inactiva' value='No'>";
				echo "<label for='inactivaNo'>No</label>";
				echo "<input type='radio' id='inactivaSi' name='inactiva' value='Si' checked>";
				echo "<label for='inactivaSi'>Si</label><br><br>";
				echo "<div class='centrarS'>";
				echo "<input type='submit' value='Crear votación' name='crearVotacion' id='crearVotacion'>";
				echo "</div>";
				echo "</form>";
				echo "</div>";
				
				echo "<button onclick='ocultarSeccion(2)' id='BvotacionesActivas'>Votaciones Activas</button><br><br>";
				echo "<div class='votacionesActivas' id='votacionesActivas' style='display:none'>";
				//echo "<h4>Votaciones activas</h4>";
				$my_q = "SELECT * ";
				$my_q = $my_q . "FROM votacionpregunta ";
				$my_q = $my_q . "WHERE activa = 'Si'";
				$query = $connection->prepare($my_q);
				$query->execute();
				if($query){ 
					$result = $query->fetch(PDO::FETCH_ASSOC);
					if(!$result){
						echo "No hay votaciones activas en el momento.";
						echo "<br><br>";
					}
					while ($result){
						echo "<b>Votación</b> - " . $result['pregunta'] . "<br>";
						echo "Duración inicial: " . $result['horaCierre'] . "<br>";
						echo "Cierre: " . $result['tiempoCierre'] . "<br>";
						echo "Cantidad de votantes: " . $result['cantidadVotantes'] . "<br>";
						$my_q = "SELECT COUNT(*) AS votos ";
						$my_q = $my_q . "FROM voto ";
						$my_q = $my_q . "WHERE numeroPregunta=:pregunta ";
						$my_q = $my_q . "GROUP BY numeroPregunta ";
						$query2 = $connection->prepare($my_q);
						$query2->bindParam("pregunta", $result['numero'], PDO::PARAM_STR);
						$query2->execute();
						$result2 = $query2->fetch(PDO::FETCH_ASSOC);
						echo "Cantidad de votos realizados: ";
						if(!$result2){
							echo "0 <br>";
						}
						else{
							echo $result2['votos'] . "<br>";
						}
						echo "<form method='post' action=" . $_SERVER['PHP_SELF'] . " id='FcerrarV'>";
						echo "<input type='text' id='numeroPregunta' name='numeroPregunta' value='" . $result['numero'] . "' hidden>";
						echo "<input type='submit' value='Cerrar votación' name='cerrarVotacion' id='cerrarVotacion'>";
						echo "</form>";
						echo "<br>";
						$result = $query->fetch(PDO::FETCH_ASSOC); // Siguiente fila
					}
					echo "<br>";
				}
				else{
					echo $my_q;
					echo "error en query de votaciones activas";
				}
				echo "</div>";
				
				echo "<button onclick='ocultarSeccion(3)' id='BvotacionesInactivas'>Votaciones Inactivas</button><br><br>";
				echo "<div class='votacionesInactivas' id='votacionesInactivas' style='display:none'>";
				//echo "<h4>Votaciones inactivas</h4>";
				$my_q = "SELECT * ";
				$my_q = $my_q . "FROM votacionpregunta ";
				$my_q = $my_q . "WHERE activa = 'No'";
				$my_q = $my_q . "AND NOT EXISTS ";
				$my_q = $my_q . "(SELECT votacionNumero FROM voto WHERE voto.numeroPregunta = votacionpregunta.numero)";
				$query = $connection->prepare($my_q);
				$query->execute();
				if($query){ 
					$result = $query->fetch(PDO::FETCH_ASSOC);
					if(!$result){
						echo "No hay votaciones inactivas en el momento.";
						echo "<br><br>";
					}
					while ($result){
						echo "<b>Votación</b> - " . $result['pregunta'] . "<br>";
						echo "Tiempo: " . $result['horaCierre'] . "<br>";
						echo "Cantidad de votantes: " . $result['cantidadVotantes'] . "<br>";
						echo "<form method='post' action=" . $_SERVER['PHP_SELF'] . " id='FactivarV'>";
						echo "<input type='text' id='numeroPregunta' name='numeroPregunta' value='" . $result['numero'] . "' hidden>";
						echo "<input type='submit' value='Activar votación' name='activarVotacion' id='activarVotacion'>";
						echo "</form>";
						echo "<br>";
						$result = $query->fetch(PDO::FETCH_ASSOC); // Siguiente fila
					}
				}
				else{
					echo $my_q;
					echo "error en query de votaciones activas";
				}
				echo "</div>";
				
				echo "<button onclick='ocultarSeccion(4)' id='BvotacionesCerradas'>Votaciones Cerradas</button><br><br>";
				echo "<div class='votacionesCerradas' id='votacionesCerradas' style='display:none'>";
				//echo "<h4>Votaciones inactivas</h4>";
				$my_q = "SELECT * ";
				$my_q = $my_q . "FROM votacionpregunta ";
				$my_q = $my_q . "WHERE activa = 'No' ";
				$my_q = $my_q . "AND EXISTS ";
				$my_q = $my_q . "(SELECT votacionNumero FROM voto WHERE voto.numeroPregunta = votacionpregunta.numero) ";
				$my_q = $my_q . "ORDER BY numero DESC";
				$query = $connection->prepare($my_q);
				$query->execute();
				if($query){ 
					$result = $query->fetch(PDO::FETCH_ASSOC);
					if(!$result){
						echo "No se ha realizado ninguna votación aún.";
						echo "<br><br>";
					}
					while ($result){
						echo "<b>Votación</b> - " . $result['pregunta'] . "<br>";
						echo "Tiempo: " . $result['tiempoCierre'] . "<br>";
						echo "Cantidad de votantes: " . $result['cantidadVotantes'] . "<br>";
						echo "<form method='post' action='resultados.php'>";
						echo "<button name='verVotacion' value='" . $result['numero'] . "'>Resultados</button><br>";
						echo "<br>";
						$result = $query->fetch(PDO::FETCH_ASSOC); // Siguiente fila
					}
				}
				else{
					echo $my_q;
					echo "error en query de votaciones activas";
				}
				echo "</div>";			
			}
		}
	}
	}
		
?>

</body>
</html>