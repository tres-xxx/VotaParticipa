<?php
 
include('config.php');
session_start();

//if (isset($_POST['login'])) {
if($_SERVER["REQUEST_METHOD"]=="POST"){ 
    $username = $_POST['uname'];
    $password = $_POST['psw'];
	$access = "no";
 
    //$query = $connection->prepare("SELECT * FROM usuario WHERE usuario=:username AND acceso=:access");
	$query = $connection->prepare("SELECT * FROM usuario WHERE usuario=:username");
    $query->bindParam("username", $username, PDO::PARAM_STR);
	//$query->bindParam("access", $access, PDO::PARAM_STR);
    $query->execute();
 
    $result = $query->fetch(PDO::FETCH_ASSOC);
 
    if (!$result) {
        //echo '<p class="error">Username password combination is wrong!</p>';
		header('Location: login.html');
    } else {
        //if (password_verify($password, $result['documento'])) {
		if ($password == $result['pwd']) {
            $_SESSION['user_id'] = $result['id'];
			$_SESSION['usuario'] = $result['usuario'];
			$_SESSION['usuario_tipo'] = $result['rol_numero'];
			$_SESSION['mensaje_conteo'] = 0;
			
			$setAccess = "si";
			$query = $connection->prepare("UPDATE usuario SET acceso=:setAccess  WHERE usuario=:username");
			$query->bindParam("username", $username, PDO::PARAM_STR);
			$query->bindParam("setAccess", $setAccess, PDO::PARAM_STR);
			$query->execute();
			
			header('Location: home.php');
            //echo '<p class="success">Congratulations, you are logged in!</p>';
        } else {
			//echo "Password=".$result['documento']."<br>";
			//echo "PassUser=".$password."<br>";
            //echo '<p class="error">Username password combination is wrong!</p>';
			header('Location: login.html');
        }
    }
}
else {
	echo 'ERROR';
}
//mysql_close($connection);
 
?>