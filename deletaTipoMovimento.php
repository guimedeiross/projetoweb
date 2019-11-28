<?php
include("includes/conexao.php");
$id = $_POST['id'];
$sqlValida = "SELECT COUNT(*) as TOTAL from movimentacao WHERE tipoMovimentacao_id = $id";
$query = mysqli_query($serv , $sqlValida); 
$row = mysqli_fetch_assoc($query);
if($row['TOTAL'] > 0)
{
	$response = array("data" => "Há movimentações referenciando esse Tipo Movimento!");
}
else
{
	$sql = "DELETE FROM tipomovimento WHERE id = $id";
	$query = mysqli_query($serv , $sql); 
	$response = array("data" => "Excluido com sucesso!");
}
echo json_encode($response);
?>