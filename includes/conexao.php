<?php 
error_reporting (E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);
$host = "localhost";
$user = "root"; 
$pass = ""; 
$banco = "id11511078_web"; 
$serv = mysqli_connect($host, $user, $pass,$banco) 
        or 
		die("Impossível conectar-se ao servidor :".$host); 
?> 