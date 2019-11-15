<?php
$email = $_POST['email'];
$confirmaemail = $_POST['confirmaemail'];
$usuario = $_POST['username'];
$senha = $_POST['senha'];
$sexo = $_POST['sexo'];

$sql = "CREATE TABLE Usuarios (
id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
email VARCHAR(30) NOT NULL,
username VARCHAR(30) NOT NULL,
senha VARCHAR(50),
sexo INT(2),
reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

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


function contem_numero($usuario) {
   return is_numeric(filter_var($usuario, FILTER_SANITIZE_NUMBER_INT));
}


?>