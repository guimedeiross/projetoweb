<?php
include("includes/conexao.php"); 
$codMov = $_POST['cod_mov'];
$descMov = $_POST['desc_mov'];
$tipoMov = $_POST['tipo_mov'];
$id = $_POST['id_mov'];
if($id < 0)
{
	$sql = "INSERT INTO tipomovimento (codigo, descricao, tipoMovimentacao) VALUES ( '$codMov', '$descMov', $tipoMov)";
	$query = mysqli_query($serv , $sql); 
}
else
{
	$sql = "UPDATE tipomovimento SET codigo = '$codMov', descricao = '$descMov', tipoMovimentacao = $tipoMov WHERE id = $id";
	$query = mysqli_query($serv , $sql); 
}
$response = array("success" => true);
echo json_encode($response);
?>