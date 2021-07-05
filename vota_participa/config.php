<?php

define('usuario','root');
define('pwd','');
define('host', 'localhost');
define('database', 'votacion_db');

try {
	$connection = new PDO("mysql:host=".host.";dbname=".database,usuario,pwd);
} catch (PDOException $e) {
	exit("Error: ".$e->getMessage());
}
/*define('db_server','localhost');
define('db_username','root');
define('db_password','');
define('db_database','votacion_db');

$conn=mysqli_connect(db_server,db_username,db_password,db_database);
if($conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
}else{
	echo "Connection succeed";
}*/

?>