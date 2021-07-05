<?php

session_start();
include('config.php');
 
if(!isset($_SESSION['user_id'])){
    header('Location: login.html');
    exit;
} 

	if(isset($_POST['crearVotacion'])){
		
		$cantidadOpciones = $_POST['opciones'];
		$pregunta = $_POST['pregunta'];
		$opciones = array(); # Array vacio
		for($i = 1; $i <= $cantidadOpciones; $i++){
			array_push($opciones, $_POST['opcion'.$i]);
		}
		$cantidadVotantes = $_POST['cantidadV']; # 0 = usuarios activos
		//$tiempo = array($_POST['hora'],$_POST['minuto']);
		$tiempo = $_POST['hora'] . ":" . $_POST['minuto'] . ":" . $_POST['segundo']; //hora:minuto:00
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
		$my_q = $my_q . "pregunta,cantidadVotantes,activa,horaCierre,tiempoCierre) ";
		$my_q = $my_q . "VALUES (";
		$my_q = $my_q . ":pregunta,:cantidadVotantes,:votacionInactiva,:tiempo,CURRENT_TIMESTAMP + INTERVAL '" . $tiempo . "' HOUR_SECOND)";
		$query = $connection->prepare($my_q);
		$query->bindParam("pregunta",$pregunta, PDO::PARAM_STR);
		$query->bindParam("cantidadVotantes",$cantidadVotantes, PDO::PARAM_STR);
		$query->bindParam("votacionInactiva",$votacionInactiva, PDO::PARAM_STR);
		$query->bindParam("tiempo",$tiempo,PDO::PARAM_STR); //hora:minuto:00
		$result = $query->execute();
		
		if($result){ // Insertar opciones para la pregunta
			/*$my_q = "SELECT numero ";
			$my_q = $my_q . "FROM votacionpregunta ";
			$my_q = $my_q . "WHERE id = LAST_INSERT_ID()";*/
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
				if($votacionInactiva = "Si"){
					// Crear evento para cerrar votacion segun tiempo establecido
					$my_q = "CREATE EVENT IF NOT EXISTS p" . $np . "cierre ";
					$my_q = $my_q . "ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL ";
					$my_q = $my_q . "'" . $tiempo . "' HOUR_SECOND ";
					$my_q = $my_q . "DO UPDATE votacionpregunta ";
					$my_q = $my_q . "SET activa='No' ";
					$my_q = $my_q . "WHERE numero=:numeroPregunta";
					echo $my_q;
					$query = $connection->prepare($my_q);
					$query->bindParam("numeroPregunta",$np, PDO::PARAM_STR);
					$query->execute();
					if(!$query){
						echo "Error al crear evento";
					}
				}
				header('Location: votacion.php');
			}
		}
		else{
			echo $my_q;
			echo "error en query";
		}
	}
?>