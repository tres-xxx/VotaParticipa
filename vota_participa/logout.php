<?php
session_start();
include('config.php');

if(!isset($_SESSION['user_id'])){
    header('Location: login.html');
    exit;
} 

$setAccess = "no";
$query = $connection->prepare("UPDATE usuario SET acceso=:setAccess  WHERE usuario=:username");
$query->bindParam("username", $_SESSION['usuario'], PDO::PARAM_STR);
$query->bindParam("setAccess", $setAccess, PDO::PARAM_STR);
$query->execute();

unset($_SESSION["user_id"]);
//unset($_SESSION["name"]);
header("Location:login.html");

?>