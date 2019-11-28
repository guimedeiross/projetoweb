<?php
include("includes/conexao.php"); 
$codMov = $_POST['cod_mov'];
$descMov = $_POST['desc_mov'];
$tipoMov = $_POST['tipo_mov'];
echo "".$codMov;
$sql = "INSERT INTO tipomovimento (codigo, descricao, tipoMovimentacao) VALUES ( '$codMov', '$descMov', $tipoMov)";
$query = mysqli_query($serv , $sql); 

$response = array("success" => true);
echo json_encode($response);
?>