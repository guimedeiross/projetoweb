<?php
include("includes/conexao.php");
include("validaSession.php");
function buildTableMovimentacao($serv)
	{
		$tabela = "<div class='col-sm-12'>
		<table id='movimentacaoTable'>
		<tr>
				<th>Código</td>
				<th>Descrição</td>
				<th>Tipo</td>
				<th>Editores</th>
		</tr>";
		$sqlValida = "SELECT id, codigo, descricao, tipoMovimentacao FROM tipomovimento WHERE 1";
		$query = mysqli_query($serv , $sqlValida);
		while($movimento = mysqli_fetch_array($query))
		{
			$codigo = "'".$movimento["codigo"]."'";
			$descricao = "'".$movimento["descricao"]."'";
			$tabela = $tabela.'<tr>
				<td>'.$movimento["codigo"].'</td>
				<td>'.$movimento["descricao"].'</td>
				<td>'.validaTipo($movimento["tipoMovimentacao"]).'</td>
				<td><button type="button" class="btn btn-default" onclick="editarTipoMovimento('.$codigo.','.$descricao.','.$movimento["id"].')">Editar</button>
				<button type="button" class="btn btn-default" onclick="excluirTipoMovimento('.$movimento["id"].')">Excluir</button>
				</td>
			</tr>';	
		}
		
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
		echo "<label><strong>Tipo de Movimentações</strong></label>";
	echo"</div>";
	echo"<div class='col-sm-12'>
		<button type='button' id='addTipoMovimento' class='btn btn-success'>Adicionar Tipo Movimentação</button>";
	echo"<div id='tabelaTipoMovimentacao' >".buildTableMovimentacao($serv);
	echo"</div>";
	echo "<div id='modalTipoMovimentacao' class='modal col-sm-6'>
			<div class='modal-content'>
				<div class='modal-header'>
					<h4 class='header-text'>Novo Tipo Movimentação</h4>
					<span class='close' id='closeModal'>&times;</span>
				</div>
			<form id='formTipoMovimento'>
			<div class='modal-body col-sm-12'>
				<input type='hidden' name='id_mov' id='id_mov' value='-1'>
				<label for='lcod_mov'>Movimentação</label>
				<input name='cod_mov' id='cod_mov'type='text' class='form-control' placeholder='Ex: Supermercado' required>
				<label for='ldesc_mov'>Descrição da Movimentação</label>
				<input name='desc_mov' id='desc_mov' type='text' class='form-control' placeholder='Ex: Frutas e Carnes' required>
				<br/>
				
				<fieldset class='form-group'>
					<div class='row'>
						<legend class='col-form-label col-sm-6 pt-0'>Tipo de Movimentação</legend>
						<div class='col-sm-10'>
							<div class='form-check'>
								<input class='form-check-input' type='radio' name='tipo_mov' id='gridRadios1' value='0' checked>
								<label class='form-check-label' for='gridRadios1'>
									Gasto (-)
								</label>
							</div>
							<div class='form-check'>
								<input class='form-check-input' type='radio' name='tipo_mov' id='gridRadios2' value='1'>
								<label class='form-check-label' for='gridRadios2'>
									Ganho (+)
								</label>
							</div>
						</div>
					</div>
				</fieldset>
			</div>
			 <div class='modal-footer'>
				<button type='submit' class='btn btn-primary'>Cadastrar</button>
			</div>	
			</form>
			</div>
		  </div>";
	echo "<script type='text/javascript'>
		function excluirTipoMovimento(id)
		{
			 $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'deletaTipoMovimento.php',
				async: true,
                data: {id:id},
                success: function(retorno) {
                }
            }).done(function(data){
				alert(data.data);
				location.reload();
			});
		}
		$('#formTipoMovimento').on('submit', function(e){
			e.preventDefault();	
			var dados = $('#formTipoMovimento').serialize();
			 $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'updateDadosTipoMovimentacao.php',
				async: true,
                data: dados,
                success: function(retorno) {
                    alert('Dados salvos com sucesso!');
					$(span).trigger('click');
                }
            }).done(function(data){
				$('#desc_mov').val('');
				$('#cod_mov').val('');
				$('#id_mov').val('-1');
				location.reload();
			});
		});
		function editarTipoMovimento(codigo, descricao, id)
		{
			$('#desc_mov').val(descricao);
			$('#cod_mov').val(codigo);
			$('#id_mov').val(id);
			$('#addTipoMovimento').trigger('click');
		}
		var modal = document.getElementById('modalTipoMovimentacao');
		var btn = document.getElementById('addTipoMovimento');
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