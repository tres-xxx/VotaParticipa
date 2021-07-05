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
body,html {
	height: 100%;
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

.container {
  border: 2px solid #dedede;
  background-color: #f1f1f1;
  border-radius: 5px;
  padding: 10px;
  margin: 10px 0;
}

.darker {
  border-color: #ccc;
  background-color: #ddd;
}

.container::after {
  content: "";
  clear: both;
  display: table;
}

.container img {
  float: left;
  max-width: 60px;
  width: 100%;
  margin-right: 20px;
  border-radius: 50%;
}

.container img.right {
  float: right;
  margin-left: 20px;
  margin-right:0;
}

.time-right {
  float: right;
  color: #aaa;
}

.time-left {
  float: left;
  color: #999;
}

div.scroll {
	height:100%;
	overflow-x: hidden;
	overflow-y: auto;
	display: flex;
	flex-direction: column-reverse;
}

div.divBar {
	height: 5%;
}

div.mensajes{
	height: 100%;
	width: 100%;
}

div.divMessages {
	height: 60%;
	http-equiv="refresh"
	content="1"
}

div.divInputMessage {
	height:30%;
}

input[type=text], select{
	width: 100%;
	height: 60px;
	margin: 8px 0;
	display: inline-block;
	overflow-y: auto;
	overflow-x: hidden;
	border: 1px solid #ccc;
	border-radius: 2px;
	box-sizing: border-box;
}

input[type=submit] {
	width: 100%;
	background-color: #4CAF50;
	color: white;
	padding: 14px 20px;
	margin: 8px 0;
	border: none;
	border-radius: 2px;
	cursor:pointer;
}
</style>
</head>
<body>
<!--<div class="scroll" id="scroll">-->
<div class="mensajes" id="mensajes">
<?php
	//if(isset($_POST['actualizarChat'])){
		//session_start();
		//include('config.php');
		
		$query_sql = "SELECT * FROM mensaje ";
		//$query_sql = $query_sql . "WHERE numero > " . strval($_SESSION['mensaje_conteo']) . " ";
		$query_sql = $query_sql . "ORDER BY numero DESC";
		//echo $query_sql;
		$query = $connection->prepare($query_sql);
		$query->execute();
		$color_div = 0;
		foreach ($query as $m){
			if($color_div%2 == 0){
				echo "<div class='container'>";
			}
			else{
				echo "<div class='container darker'>";
			}
			echo "<p><i>". $m['usuario'] . "</i></p>";
			echo "<p>". $m['texto'] ."</p>";
			echo "<span class='time-left'>". $m['fecha'] . "</span>";
			echo "</div>";
			$color_div = $color_div + 1;
			if($m['numero'] > $_SESSION['mensaje_conteo']){
				$_SESSION['mensaje_conteo'] = $m['numero'];
			}
		}
		
		//mysql_close($connection);
	//}
?>
</div>
<!--</div>-->
</body>
</html>