<?php
include("includes/conexao.php");
$valorMov = $_POST['valor_mov'];
$tipoMov = $_POST['tipoMovito_mov'];
$contMov = $_POST['conta_mov'];
$dataMov = $_POST['data_mov'];
$idMov = $_POST['id_mov'];

if($idMov > 0)
{	
	$insertdate = date("Y-m-d", strtotime($_POST['data_mov']));
	$sql = "UPDATE movimentacao SET dataMovimentacao = '$insertdate', valor = $valorMov, tipoMovimentacao_id = $tipoMov WHERE id = $idMov";
	$query = mysqli_query($serv , $sql); 
}	
else
{
	$insertdate = date("Y-m-d", strtotime($_POST['data_mov']));
	$sql = "INSERT INTO movimentacao (dataMovimentacao, valor, tipoMovimentacao_id, conta_id) VALUES ('$insertdate', $valorMov, $tipoMov, $contMov)";
	$query = mysqli_query($serv , $sql); 
}

$response = array("data" => $sql);
echo json_encode($response);
?>