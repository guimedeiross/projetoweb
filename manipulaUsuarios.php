<?php
include("includes/conexao.php");
include("validaSession.php");
function buildTableMovimentacao($serv)
	{
		$tabela = "<div class='col-sm-12'>
		<table id='movimentacaoTable'>
		<tr>
				<th>Username</td>
				<th>E-mail</td>
				<th>Editores</th>
		</tr>";
		$sqlValida = "SELECT id, username, email FROM usuario WHERE 1";
		$query = mysqli_query($serv , $sqlValida);
		while($movimento = mysqli_fetch_array($query))
		{
			$username = "'".$movimento["username"]."'";
			$email = "'".$movimento["email"]."'";
			$tabela = $tabela.'<tr>
				<td>'.$movimento["username"].'</td>
				<td>'.$movimento["email"].'</td>
				<td><button type="button" class="btn btn-default" onclick="editarUsuario('.$username.','.$email.','.$movimento["id"].')">Editar</button>			
				</td>
			</tr>';	
		}
		//<button type="button" class="btn btn-default" onclick="excluirTipoMovimento('.$movimento["id"].')">Excluir</button>
		$tabela =$tabela."</table>";
		return $tabela;
	}	
	function validaTipo($tipo)
	{
		$retorno = "";
		if($tipo == 0)
		{
			$retorno = "Gasto";
		}
		else
		{
			$retorno = "Ganho";
		}
		return $retorno;
	}
	echo "<link rel='stylesheet' href='css/bootstrap.min.css'>";
	echo "<link rel='stylesheet' href='css/modal.css'>";
	echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>";
	echo "<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js'></script>";
	echo "<style>
		table {
		font-family: arial, sans-serif;
		border-collapse: collapse;
		width: 100%;
		}
		
		td, th {
		border: 1px solid #dddddd;
		text-align: left;
		padding: 8px;
		}
		
		tr:nth-child(even) {
		background-color: #dddddd;
		}
	</style>";
	echo "<body style='background-color:#C4F7FF;'>";
	echo "<div class='col-sm-12' id='header'>";
		echo "<label><strong>Usuários - Não é possivel excluir usuários por questão de Histórico e relacionamento com a conta e movimentações</strong></label>";
	echo"</div>";
	echo"<div class='col-sm-12'>
		<button type='button' id='usuario' class='btn btn-success' hidden></button>";
	echo"<div id='tabelaTipoMovimentacao' >".buildTableMovimentacao($serv);
	echo"</div>";
	echo "<div id='modalUsuario' class='modal col-sm-6'>
			<div class='modal-content'>
				<div class='modal-header'>
					<h4 class='header-text'>Usuário</h4>
					<span class='close' id='closeModal'>&times;</span>
				</div>
			<form id='formUsuario'>
			<div class='modal-body col-sm-12'>
				<input type='hidden' name='id_user' id='id_user' value='-1'>
				<label for='lusername'>Username</label>
				<input name='username' id='username'type='text' class='form-control' required>
				<label for='lemail'>Email</label>
				<input name='email' id='email' type='email' class='form-control' required>
				<br/>
			</div>
			 <div class='modal-footer'>
				<button type='submit' class='btn btn-primary'>Alterar</button>
			</div>	
			</form>
			</div>
		  </div>";
	echo "<script type='text/javascript'>
		$('#formUsuario').on('submit', function(e){
			e.preventDefault();	
			var dados = $('#formUsuario').serialize();
			 $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'updateDadosUsuario.php',
				async: true,
                data: dados,
                success: function(retorno) {
                    alert('Dados salvos com sucesso!');
					$(span).trigger('click');
                }
            }).done(function(data){
				$('#username').val('');
				$('#email').val('');
				$('#id_user').val('-1');
				location.reload();
			});
		});
		function editarUsuario(username, email, id)
		{
			$('#username').val(username);
			$('#email').val(email);
			$('#id_user').val(id);
			$('#usuario').trigger('click');
		}
		var modal = document.getElementById('modalUsuario');
		var btn = document.getElementById('usuario');
		var span = document.getElementById('closeModal');
		btn.onclick = function() {
			modal.style.display = 'block';
		}
		span.onclick = function() {
			modal.style.display = 'none';
		}
		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = 'none';
			}
		}
	";			
	echo "</script>";	
?>