<?php

session_start();
include('config.php');
 
if(!isset($_SESSION['user_id'])){
    header('Location: login.html');
    exit;
} 

	if(isset($_POST['enviarMensaje'])){
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
			header("Location: Chat.php");
        } else {
            echo '<p class="error">Algo salió mal con el mensaje enviado!</p>';
        }
		//mysql_close($connection);
	}
?>