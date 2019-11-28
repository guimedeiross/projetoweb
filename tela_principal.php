<?php
	//<meta charset="utf-8">
    //<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   // <!-- Bootstrap CSS -->
	session_start();
	include("includes/conexao.php");
	include("validaSession.php");
	$contaId = getContaUsuario($_SESSION['userId'], $serv);
	$valorSaldo = 0;
	$valorInicial = getValorInicial($serv, $contaId);
	$salario = getSalario($serv, $contaId);
	$tableMove = buildTableMovimentacao($serv, $valorSaldo, $valorInicial);
	function getValorInicial($serv, $contaId){
		
		$sqlValorInicial = "SELECT valor FROM parametros where conta_id = '$contaId'";
		$query = mysqli_query($serv , $sqlValorInicial);
		$row = mysqli_fetch_assoc($query);
		return $row['valor'];
	}
	function getSalario($serv, $contaId){
		
		$sqlValorInicial = "SELECT salario FROM parametros where conta_id = '$contaId'";
		$query = mysqli_query($serv , $sqlValorInicial);
		$row = mysqli_fetch_assoc($query);
		return $row['salario'];
	}
    function buildTableMovimentacao($serv, $valorSaldo, $valorInicial)
	{
		$tabela = "<div class='col-sm-12'>
		<table id='movimentacaoTable'>
		<tr>
				<th>Tipo</td>
				<th>Valor</td>
				<th>Data</td>
				<th>Editores</th>
		</tr>";
		//
		$contaId = getContaUsuario($_SESSION['userId'], $serv);
		$sqlValida = "SELECT dataMovimentacao, valor, tipoMovimentacao_id, id FROM movimentacao where conta_id = '$contaId'";
		$query = mysqli_query($serv , $sqlValida);
		while($movimento = mysqli_fetch_array($query))
		{
			
			$tipoMovto = getTipoMovimentacao($serv, $movimento['tipoMovimentacao_id']);
			$gambi = "'".$movimento["dataMovimentacao"]."'";
			$tabela = $tabela.'<tr>
				<td>'.$tipoMovto.'</td>
				<td>'.$movimento["valor"].'</td>
				<td>'.$movimento["dataMovimentacao"].'</td>
				<td><button type="button" class="btn btn-default" onclick="editarMovimento('.$movimento["valor"].', '.$gambi.', '.$movimento["id"].')">Editar</button>
				<button type="button" class="btn btn-default" onclick="excluirMovimento('.$movimento["id"].')">Excluir</button>
				</td>
			</tr>';	
			$valorMovimento = $movimento['valor'];
			if(strpos($tipoMovto, 'Saída') == true)
			{
				
				$valorSaldo = ($valorSaldo - $valorMovimento);
				
			}	
			else if (strpos($tipoMovto, 'Entrada') == true)
			{
				
				$valorSaldo = ($valorSaldo + $valorMovimento);
				
			}
		}
		
		$tabela =$tabela."</table>";
		if($valorSaldo <= 0){
			$tabela = $tabela."<label> Saldo:".($valorInicial + $valorSaldo);
		}
		else{
			$tabela = $tabela."<label> Saldo:".($valorInicial + $valorSaldo);
		}
		return $tabela;
	}	
	function getTipoMovimentacao($serv, $tipoMovimentacao)
	{
		$sqlValida = "SELECT codigo, tipoMovimentacao FROM tipomovimento where id = '$tipoMovimentacao'";
		$query = mysqli_query($serv , $sqlValida); 
		$row = mysqli_fetch_assoc($query);
		$retorno = $row['codigo']; 
		if($row['tipoMovimentacao'] == 0)
		{
			$retorno = $retorno." - Saída ";
		}
		else 
		{
			$retorno = $retorno." - Entrada ";
		}
		return $retorno;
	}	
	function getContaUsuario($usuario, $serv)
	{
		$sqlUsuario = "SELECT id FROM conta where usuario_id = '$usuario'";
		$query = mysqli_query($serv , $sqlUsuario); 
		$row = mysqli_fetch_assoc($query);
		return $row['id']; 
	}	
	function getOptionsTipoMovimentacao($serv)
	{
		$option = "";
		$sqlTipMov = "select id, codigo from tipomovimento";
		$query = mysqli_query($serv , $sqlTipMov); 
		while($query != null && $tipoMovimento = mysqli_fetch_array($query))
		{
			$option = $option."<option value=".$tipoMovimento['id'].">".$tipoMovimento['codigo']."</option>";
		}
		return $option; 
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
		echo "<label><strong>{$_SESSION['username']}</strong></label>";
	echo"</div>";
	echo"<div class='col-sm-12'>
		<button type='button' id='addTipoMovimento' class='btn btn-success'>Adicionar Tipo Movimentação</button>
		<button type='button' id='manipulaTipoMov' class='btn btn-danger'>Editar Tipo Movimentação</button>
		<button type='button' id='addMovimento' class='btn btn-success'>Adicionar Movimentação</button>
		<button type='button' id='addSalario' class='btn btn-success'>Adicionar Salário</button>
		<button type='button' id='parametros' class='btn btn-danger'>Parâmetros</button>
		<button type='button' id='manipulaUsuario' class='btn btn-danger'>Usuários</button>
		<a class='btn btn-danger' href='logout.php' role='button'>Logout</a>
	</div>";
	echo"<div id='tabelaMovimentacao' >".$tableMove;
	echo"</div>";
	
	echo "<div id='modalTipoMovimentacao' class='modal col-sm-6'>
			<div class='modal-content'>
				<div class='modal-header'>
					<h4 class='header-text'>Novo Tipo Movimentação</h4>
					<span class='close' id='closeModal'>&times;</span>
				</div>
			<form id='formTipoMovimento'>
			<div class='modal-body col-sm-12'>
				<label for='lcod_mov'>Movimentação</label>
				<input name='cod_mov' type='text' class='form-control' placeholder='Ex: Supermercado' required>
				<label for='ldesc_mov'>Descrição da Movimentação</label>
				<input name='desc_mov' type='text' class='form-control' placeholder='Ex: Frutas e Carnes' required>
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
	echo "<div id='modalMovimentacao' class='modal col-sm-6'>
			<div class='modal-content'>
				<div class='modal-header'>
					<h4 class='header-text'>Nova Movimentação</h4>
					<span class='close' id= 'closeMovModal'>&times;</span>
				</div>
			<form id='formMovimento'>
			<div class='modal-body col-sm-12'>
				<input type='hidden' name='conta_mov' value=".$contaId.">
				<input type='hidden' name='id_mov' id='id_mov' value='-1'>
				<label for='lcod_mov'>Data Movimentação</label>
				<input name='data_mov' id='data_mov' type='date' class='form-control' placeholder='Ex: Data da Movimentação' required>
				<label for='ldesc_mov'>Valor Movimentação</label>
				<input name='valor_mov' id='valor_mov' type='number' step='0.01' class='form-control' placeholder='Valor da Movimentação' required>
				<br/>
				
				<fieldset class='form-group'>
					<div class='row'>
						<legend class='col-form-label col-sm-6 pt-0'>Tipo de Movimentação</legend>
						<div class='col-sm-10'>
							<select class='form-control' name='tipoMovito_mov' id='tipoMovito_mov'>
							".getOptionsTipoMovimentacao($serv)."
							</select>
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
	echo "<div id='modalParametros' class='modal col-sm-6'>
			<div class='modal-content'>
				<div class='modal-header'>
					<h4 class='header-text'>Nova Movimentação</h4>
					<span class='close' id= 'closeParamModal'>&times;</span>
				</div>
			<form id='formParametro'>
			<div class='modal-body col-sm-12'>
				<input type='hidden' name='conta_mov' value=".$contaId.">
				<label for='lcod_mov'>Salário</label>
				<input name='sal_param' value='".$salario."' type='number' step='0.01' class='form-control' placeholder='Ex: Valor do Salário' required>
				<label for='ldesc_mov'>Valor Inicial</label>
				<input name='valor_inic_param' value='".$valorInicial."' type='number' step='0.01' class='form-control' placeholder='Valor Inicial' required>
				<br/>
			</div>
			 <div class='modal-footer'>
				<button type='submit' class='btn btn-primary'>Cadastrar</button>
			</div>	
			</form>
			</div>
		  </div>";	  
	echo "</body>";
	echo "<script type='text/javascript'>
	
	
		function excluirMovimento(id)
		{
			 $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'deletaDadosMovimentacao.php',
				async: true,
                data: {id:id},
                success: function(retorno) {
                }
            }).done(function(data){
				//alert(data.data);
				location.reload();
			});
		}
		function editarMovimento(valor, dataMov, id)
		{
			$('#valor_mov').val(valor);
			$('#data_mov').val(dataMov);
			$('#id_mov').val(id);
			$('#addMovimento').trigger('click');
		}
		$('#addSalario').on('click', function(e){
			var salario = '".$salario."';
			var conta = '".$contaId."';
			alert(salario);
			 $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'adicionarSalario.php',
				async: true,
                data: {salario:salario, conta:conta},
                success: function(retorno) {
                }
            }).done(function(data){
				//alert(data.data);
				location.reload();
			});
		});
	
		var modal = document.getElementById('modalTipoMovimentacao');
		var modalMov = document.getElementById('modalMovimentacao');
		var modalParam = document.getElementById('modalParametros');
		var btn = document.getElementById('addTipoMovimento');
		var btnMov = document.getElementById('addMovimento');
		var btnParam = document.getElementById('parametros');
		var span = document.getElementById('closeModal');
		var spanMov = document.getElementById('closeMovModal');
		var spanParam = document.getElementById('closeParamModal');
		btn.onclick = function() {
			modal.style.display = 'block';
		}
		btnMov.onclick = function() {
			modalMov.style.display = 'block';
		}
		btnParam.onclick = function() {
			modalParam.style.display = 'block';
		}
		span.onclick = function() {
			modal.style.display = 'none';
		}
		spanMov.onclick = function() {
			modalMov.style.display = 'none';
		}
		spanParam.onclick = function() {
			modalParam.style.display = 'none';
		}
		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = 'none';
			}
			else if(event.target == modalMov)
			{
				modalMov.style.display = 'none';
			}
			else if(event.target == modalParam)
			{
				modalParam.style.display = 'none';
			}
		}
		$('#formTipoMovimento').on('submit', function(e){
			e.preventDefault();	
			var dados = $('#formTipoMovimento').serialize();
			 $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'salvaDadosTipoMovimentacao.php',
				async: true,
                data: dados,
                success: function(retorno) {
                    alert('Dados salvos com sucesso!');
					$(span).trigger('click');
                }
            }).done(function(data){
				location.reload();
			});
			location.reload();
		});
		$('#formMovimento').on('submit', function(e){
			e.preventDefault();	
			var dados = $('#formMovimento').serialize();
			 $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'salvaDadosMovimentacao.php',
				async: true,
                data: dados,
                success: function(retorno) {
                    alert('Dados salvos com sucesso!');
					$(spanMov).trigger('click');
                }
            }).done(function(data){
				$('#valor_mov').val('');
				$('#data_mov').val('');
				$('#id_mov').val('-1');
				location.reload();
			});
		});
		$('#formParametro').on('submit', function(e){
			e.preventDefault();	
			var dados = $('#formParametro').serialize();
			 $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'salvaDadosParametro.php',
				async: true,
                data: dados,
                success: function(retorno) {
					$(spanParam).trigger('click');
                }
            }).done(function(data){
				//alert(data.data);
				location.reload();
			});
		});
		$('#manipulaTipoMov').on('click', function(e){
			window.open('http://localhost/projeto/manipulaTipoMovimentos.php')
		});
		$('#manipulaUsuario').on('click', function(e){
			window.open('http://localhost/projeto/manipulaUsuarios.php')
		});
		";
		
		
	echo "</script>";	
?>