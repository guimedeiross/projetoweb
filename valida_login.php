<?php
include("includes/conexao.php"); 
$usuario = $_POST['username'];
$senha = $_POST['senha'];
$cripto = criptografa_senha($senha);
$errosUrl = "?";

function criptografa_senha($senha) {
   $senhacrip = hash('sha256', $senha);
   return $senhacrip;
}

	if(empty($usuario) || empty($senha)){
		echo "Campo senha ou usuario está vazio</br>";
	}
	
	else{
		
		$sqlValida = "SELECT username,senha FROM usuario WHERE username = '$usuario' && senha = '$cripto'";
		$query = mysqli_query($serv , $sqlValida); 
		$row = mysqli_fetch_assoc($query);
		
		if($row['username'] == $usuario && $row['senha'] == $cripto)
		{
			echo "Entrou";
		}	
		else
		{
			echo "Acesso errado";
		}
	}

?>