<?php
include("includes/conexao.php");
$valorInicParam = $_POST['valor_inic_param'];
$salParam = $_POST['sal_param'];
$contMov = $_POST['conta_mov'];
$sqlValidacao = "SELECT id FROM parametros where conta_id = $contMov";
$queryValidacao = mysqli_query($serv, $sqlValidacao);
$row = mysqli_fetch_assoc($queryValidacao);
$retorno = "";
if($queryValidacao != null && $row['id'] != null)
{
   $id = $row['id'];
   //$sqlUpdate = "UPDATE parametros SET valor = '$valorInicParam' and salario = '$salParam' WHERE id = $id";
   
     //$erro =  "Error updating record: " . mysqli_error($conn);
   $sqlUpdate = "UPDATE `parametros` SET `valor` = '$valorInicParam'  WHERE `parametros`.`id` = $id";
   $query = mysqli_query($serv , $sqlUpdate);
   $sqlUpdate = "UPDATE `parametros` SET `salario` = '$salParam'  WHERE `parametros`.`id` = $id";
   $query = mysqli_query($serv , $sqlUpdate);
   $retorno = "Atualizado com sucesso!";
} 
else
{
	$sql = "INSERT INTO parametros (valor, salario, conta_id) VALUES ('$valorInicParam', '$salParam', $contMov)";
	$query = mysqli_query($serv , $sql);
	$retorno = "Inserido com sucesso!";
}
$response = array("data" => $retorno);
echo json_encode($response);
?>