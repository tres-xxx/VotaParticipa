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

canvas {
	display: block;
	margin-right: auto;
	margin-left: auto;
	background-image: url('img/blanco-40.png');
}
div.divBar {
	height: 5%;
}
</style>

<script src="javaScript/Chart.js-2.9.3/dist/Chart.min.js"></script>


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
<canvas class="mychar" id="myChart" style="width:100%;max-width:700px"></canvas>

<?php
	if(isset($_POST['verVotacion'])){
		//$opciones = array();
		$preguntaNumero = $_POST['verVotacion'];
		
		$my_q = "SELECT pregunta, cantidadVotantes ";
		$my_q = $my_q . "FROM votacionpregunta ";
		$my_q = $my_q . "WHERE numero =:preguntaNumero";
		$query = $connection->prepare($my_q);
		$query->bindParam("preguntaNumero",$preguntaNumero, PDO::PARAM_STR);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
		if($result){ 
			$pregunta = $result['pregunta'];
			$cantidadVotantes = $result['cantidadVotantes'];
			$opciones = array(); # array de opciones
			$opciones_texto = array(); # array de opciones con texto
			//$max_voto = [0,""];
			$max_voto = [0,0]; // [valor a comparar, posicion en opcion]
			
			$my_q = "SELECT COUNT(*) as conteo ";
			$my_q = $my_q . "FROM votacionopcion ";
			$my_q = $my_q . "WHERE votacionNumero=:preguntaNumero ";		
			$query = $connection->prepare($my_q);
			$query->bindParam("preguntaNumero",$preguntaNumero, PDO::PARAM_STR);
			$query->execute();
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result){ exit; }
			$cantidadOpciones = $result['conteo'];
			
			echo "<script>";
			//echo "var xValues = ['Italy', 'France', 'Spain', 'USAAA', 'Argentina'];";
			echo "var xValues = [";
			
			$my_q = "SELECT opcion,votacionOpcion ";
			$my_q = $my_q . "FROM votacionopcion ";
			$my_q = $my_q . "WHERE votacionNumero=:preguntaNumero ";
			$my_q = $my_q . "ORDER BY votacionOpcion";			
			$query = $connection->prepare($my_q);
			$query->bindParam("preguntaNumero",$preguntaNumero, PDO::PARAM_STR);
			$query->execute();
			if(!$query){ exit; }
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result){ exit; }
			echo "'" . $result['opcion'] . "'";
			array_push($opciones,$result['votacionOpcion']);
			array_push($opciones_texto,$result['opcion']);
			$result = $query->fetch(PDO::FETCH_ASSOC);
			while($result){
				echo ",'" . $result['opcion'] . "'";
				array_push($opciones,$result['votacionOpcion']);
				array_push($opciones_texto,$result['opcion']);
				$result = $query->fetch(PDO::FETCH_ASSOC);
			}
			/*
			for($i = 1; $i <= $cantidadOpciones; $i++){
			array_push($opciones, $_POST['opcion'.$i]);
			}*/
			echo "];";
			
			//echo "var yValues = [55, 49, 44];";
			echo "var yValues = [";
			
			$my_q = "SELECT COUNT(*) as votos, votacionNumero ";
			$my_q = $my_q . "FROM voto ";
			$my_q = $my_q . "WHERE numeroPregunta=:preguntaNumero ";
			//$my_q = $my_q . "votacionopcion.votacionNumero=voto.votacionNumero ";
			$my_q = $my_q . "GROUP BY voto.votacionNumero ";
			$my_q = $my_q . "ORDER BY voto.votacionNumero";			
			$query = $connection->prepare($my_q);
			$query->bindParam("preguntaNumero",$preguntaNumero, PDO::PARAM_STR);
			$query->execute();
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result){ exit; }
			$pos_op = 0; # Posicion de la opcion para colocar valores 0
			if($result['votacionNumero'] != $opciones[$pos_op]){
				echo "0";
				$pos_op++;
				while($result['votacionNumero'] != $opciones[$pos_op] and $pos_op < sizeof($opciones)){
					echo ",0";
					$pos_op++;
				}
				echo "," . intval($result['votos']);
			}
			else{
				echo intval($result['votos']);
			}
			$max_voto[0] = $result['votos'];
			$result = $query->fetch(PDO::FETCH_ASSOC);
			$pos_op++;
			while($result){
				while($result['votacionNumero'] != $opciones[$pos_op] and $pos_op < sizeof($opciones)){
					echo ",0";
					$pos_op++;
				}
				echo "," . intval($result['votos']);
				if($result['votos'] > $max_voto[0]){
					$max_voto[0] = $result['votos'];
					$max_voto[1] = $pos_op;
				}
				$result = $query->fetch(PDO::FETCH_ASSOC);
				$pos_op++;
			}
			while($pos_op < sizeof($opciones)){
				echo ",0";
				$pos_op++;
			}
			echo "];";
			
			//echo "var barColors = ['red', 'green','blue','orange','brown'];";
			echo "var barColors = ['green'";
			for($i=1; $i < $cantidadOpciones; $i++){
				if($i%2 == 1){
					echo ",'blue'";
				}
				else{
					echo ",'green'";
				}
			}
			echo "];";

			echo "new Chart('myChart', {";
			  echo "type: 'bar',";
			  echo "data: {";
				echo "labels: xValues,";
				echo "datasets: [{";
				  echo "backgroundColor: barColors,";
				  echo "data: yValues";
				echo "}]";
			  echo "},";
			  echo "options: {";
				echo "legend: {display: false},";
				echo "title: {";
				  echo "display: true,";
				  echo "text: '" . $pregunta . "',";
				  echo "fontStyle: 'italic'";
				echo "},";
				echo "scales: {";
				  echo "yAxes: [{";
				    echo "ticks: {";
					  echo "beginAtZero: true";
					echo "}";
				  echo "}],";
				  echo "xAxes: [{";
					echo "ticks: {";
					  echo "fontStyle: 'bold',";
					  //echo "fontSize: 18,";
					  echo "fontColor: '#000'";
					echo "},";
				  echo "}]";
				  /*echo "pointLabels: {";
					echo "fontStyle: 'bold'";
				  echo "}";*/
				echo "}";
			  echo "}";
			echo "});";
			echo "</script>";
			
			echo "<b>Cantidad de votantes esperados:</b>  " . $cantidadVotantes . "<br>";
			
			$my_q = "SELECT COUNT(*) as votos ";
			$my_q = $my_q . "FROM voto ";
			$my_q = $my_q . "WHERE numeroPregunta=:preguntaNumero ";
			$query = $connection->prepare($my_q);
			$query->bindParam("preguntaNumero",$preguntaNumero, PDO::PARAM_STR);
			$query->execute();
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result){ exit; }			
			echo "<b>Cantidad de votos realizados:</b>  " . $result['votos'] . "<br>";
			echo "<b>Primera opción con más votos:</b>  <i>" . $opciones_texto[$max_voto[1]] . "</i> con <b>" . $max_voto[0];
			if($max_voto[0] > 1){
				echo "</b> votos.<br>";
			}
			else{
				echo "</b> voto.<br>";
			}
		}
		else{
			echo $my_q;
			echo "error en query de informacion de votacion";
		}
	}
?>