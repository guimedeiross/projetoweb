<?php
include("includes/conexao.php");
$email = $_POST['email'];
$confirmaemail = $_POST['confirmaemail'];
$usuario = $_POST['username'];
$senha = $_POST['senha'];
$sexo = $_POST['sexo'];

if($email != $confirmaemail){
	echo "ERRO (Emails não coincidem !!)</br>";
}
else{
	echo "Email OK</br>";
	$emailnobanco = $confirmaemail;
	echo $confirmaemail, "</br>";
}

if(contem_numero($usuario)){
	echo "ERRO (Tem Numero !!)</br>";
}
else{
	echo "Usuario OK</br>";
	$usuarionobanco = $usuario;
	echo $usuario, "</br>";
}

if(strlen($senha) <= 6){
	echo "ERRO (Tamanho minimo da senha tem que ser de 6 caracteres) !!</br>";
}
else{
	echo "Senha OK</br>";
	$senhanobanco = criptografa_senha($senha);
	echo $senhanobanco, "</br>";
}

if($sexo == 0){
	echo "Masculino";
}
else{
	echo "Feminino";
}

function criptografa_senha($senha) {
   $senhacrip = hash('sha256', $senha);
   return $senhacrip;
}

$sql = "INSERT INTO usuario VALUES (1,2,'$usuarionobanco', '$emailnobanco', '$senhanobanco','$sexo')"; 
		
	$query = mysqli_query($serv,$sql); 
		if ($query) 
				echo "Sucesso ao inserir usuario no banco"; 
        else 
                echo "Erro ao inserir registro"; 

function contem_numero($usuario) {
   return is_numeric(filter_var($usuario, FILTER_SANITIZE_NUMBER_INT));
}


?>