<?php

session_start();
include('config.php');
 
if(!isset($_SESSION['user_id'])){
    header('Location: login.html');
    exit;
} 

$query = $connection->prepare("SELECT numero FROM mensaje ORDER BY numero DESC LIMIT 1");
$result = $query->execute();
if(!$result){
	exit;
}
$result = $query->fetch(PDO::FETCH_ASSOC);
if(!$result){
	exit;
}
//echo $_SESSION['mensaje_conteo'];
if($result['numero'] != $_SESSION['mensaje_conteo']){
	
	echo "Si";
}
else{
	echo "No";
}

	//if(isset($_POST['nuevoMensaje'])){
		/*$id_usuario = $_SESSION['user_id'];
		$usuario = $_SESSION['usuario'];
		$mensaje = $_POST['message-chat'];
		$query = $connection->prepare("INSERT INTO mensaje(texto,id_usuario,usuario) VALUES (:mensaje,:id_usuario,:usuario)");
		$query->bindParam("id_usuario", $id_usuario, PDO::PARAM_STR);
		$query->bindParam("mensaje", $mensaje, PDO::PARAM_STR);
		$query->bindParam("usuario", $usuario, PDO::PARAM_STR);
		$result = $query->execute();
 
        if ($result) {
            //echo '<p class="success">Your message was successful!</p>';
			header("Location: Chat.php");
        } else {
            echo '<p class="error">Algo salió mal con el mensaje enviado!</p>';
        }*/
		//$resultado = "Si";
		//echo $resultado;
		//mysql_close($connection);
	//}
	/*else{
		echo "hello";
	}*/
?>