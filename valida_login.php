<?php
include("includes/conexao.php"); 
$usuario = $_POST['username'];
$senha = $_POST['senha'];

$usuarionobanco = "guilherme"; /*Usuario apenas para teste, tem que buscar do banco de dados*/
$senhanobanco = criptografa_senha("gui"); /*Senha apenas para teste, tem que buscar do banco de dados*/


/*Essa validação de conter numeros ou estar vazio tem que ser feita na hora do cadastro de usuario*/
if(contem_numero($usuario) || empty($usuario)){
	echo "Tem Numero ou está vazia</br> Acesso Negado";
}/*Essa validação de conter numeros ou estar vazio tem que ser feita na hora do cadastro de usuario*/

else{
	if(criptografa_senha($senha) == $senhanobanco && $usuario == $usuarionobanco){
		echo "Liberado para acesso</br></br>";
	}
	else{
		echo "Usuario ou senha incorretos</br> Acesso Negado";
	}
}

function criptografa_senha($senha) {
   $senhacrip = hash('sha256', $senha);
   return $senhacrip;
}


function contem_numero($usuario) {
   return is_numeric(filter_var($usuario, FILTER_SANITIZE_NUMBER_INT));
}


?>