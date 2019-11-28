<?php
include("includes/conexao.php");
$username = $_POST['username'];
$email = $_POST['email'];
$id = $_POST['id_user'];
if($id > 0)
{	
	$insertdate = date("Y-m-d", strtotime($_POST['data_mov']));
	$sql = "UPDATE usuario SET username = '$username', email = '$email' WHERE id = $id";
	$query = mysqli_query($serv , $sql); 
}	
$response = array("data" => $sql);
echo json_encode($response);
?>