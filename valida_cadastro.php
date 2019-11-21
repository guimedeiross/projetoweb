<?php
include("includes/conexao.php");
$email = $_POST['email'];
$confirmaemail = $_POST['confirmaemail'];
$usuario = $_POST['username'];
$senha = $_POST['senha'];
$cripto = criptografa_senha($senha);
$sexo = $_POST['sexo'];
$errosUrl = "?";
if(!validaNomeExistente($email, $usuario, $serv))
{
	$errosUrl = $errosUrl."existe=true";
	header("Location: cadastro_usuario.html".$errosUrl);
}
else
{
	if($email != $confirmaemail)
	{
		$errosUrl = $errosUrl."email=true";
		header("Location: cadastro_usuario.html".$errosUrl);
	}
	else 
	{
		$errosUrl = $errosUrl."email=false";
		$sql = "INSERT INTO usuario (username, email, senha, sexo) VALUES ( '$usuario', '$email', '$cripto', $sexo)";
		$query = mysqli_query($serv , $sql); 
		if($query)
		{
			$errosUrl = $errosUrl."&sql=false";
			header("Location: cadastro_usuario.html".$errosUrl);	
		}
		else
		{
			$errosUrl = $errosUrl."&sql=true";
			header("Location: cadastro_usuario.html".$errosUrl);
		}
	}
}	

function criptografa_senha($senha) {
   $senhacrip = hash('sha256', $senha);
   return $senhacrip;
}
function validaNomeExistente($email, $usuario, $serv)
{
	$sqlValida = "SELECT COUNT(*) as TOTAL FROM usuario where username='$usuario' or email = '$email'";
	$query = mysqli_query($serv , $sqlValida); 
	$row = mysqli_fetch_assoc($query);
	if($row['TOTAL'] > 0)
	{
		return false;
	}	
	else
	{
		return true;
	}
}
?>
