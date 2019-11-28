<?php
include("includes/conexao.php");
$salario = $_POST['salario'];
$conta = $_POST['conta'];
$sqlValidacao = "SELECT id FROM tipomovimento where codigo = 'Salario'";
$queryValidacao = mysqli_query($serv, $sqlValidacao);
$row = mysqli_fetch_assoc($queryValidacao);
$retorno = "";
if($queryValidacao != null && $row['id'] != null)
{
	$tipoMov = $row['id'];
	$sqlMovimento = "INSERT INTO movimentacao (dataMovimentacao, valor, tipoMovimentacao_id, conta_id) VALUES (CURDATE(), $salario, $tipoMov, $conta)"; 
	$query = mysqli_query($serv , $sqlMovimento);
	$retorno = "Atualizado com sucesso!";
} 
else
{
	$sql = "INSERT INTO tipomovimento (codigo, descricao, tipoMovimentacao) VALUES ( 'Salario', 'Salario', 1)";
	$query = mysqli_query($serv , $sql);
	$sqlValidacao = "SELECT id FROM tipomovimento where codigo = 'Salario'";
	$queryValidacao = mysqli_query($serv, $sqlValidacao);
	$row = mysqli_fetch_assoc($queryValidacao);
	$tipoMov = $row['id'];
	$sqlMovimento = "INSERT INTO movimentacao (dataMovimentacao, valor, tipoMovimentacao_id, conta_id) VALUES (CURDATE(), $salario, $tipoMov, $conta)"; 
	$query = mysqli_query($serv , $sqlMovimento);
	$retorno = "Inserido com sucesso!";
}
$response = array("data" => $retorno);
echo json_encode($response);
?>