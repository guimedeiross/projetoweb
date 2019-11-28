<?php
include("includes/conexao.php");
$id = $_POST['id'];
$sql = "DELETE FROM movimentacao WHERE id = $id";
$query = mysqli_query($serv , $sql); 
$response = array("data" => $sql);
echo json_encode($response);
?>