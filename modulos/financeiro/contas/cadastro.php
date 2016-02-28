<?php
        // validar sessao
        require_once BASE_DIR . '/util/sessao.php';

        validarSessao();
		
		// Testar permissao
		require_once BASE_DIR . '/util/permissao.php';
		$perm_incluir = testarPermissao('INCLUIR CADASTRO DE CONTAS DO MOVIMENTO FINANCEIRO');
		$perm_alterar = testarPermissao('ALTERAR CADASTRO DE CONTAS DO MOVIMENTO FINANCEIRO');
		$perm_excluir = testarPermissao('EXCLUIR CADASTRO DE CONTAS DO MOVIMENTO FINANCEIRO');
		
		// Testar assinatura da URL
		require_once BASE_DIR . '/util/util.php';
		testarAssinaturaURL();

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no">
		<link rel="shortcut icon" type="image/png" href="/assets/imagens/favicon.png"/>
		<link rel="apple-touch-icon" type="image/png" href="/assets/imagens/favicon.png"/>
		<link rel="stylesheet" type="text/css" href="/assets/bootstrap/css/bootstrap.min.css"/>
		<link rel="stylesheet" type="text/css" href="/assets/css/principal.css" />
		<link rel="stylesheet" type="text/css" href="assets/css/cadastro.css" />
		<script type="text/javascript" src="/assets/js/jquery.js"></script>
		<script type="text/javascript" src="/assets/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/assets/js/principal.js"></script>
		<script type="text/javascript" src="assets/js/cadastro.js"></script>
		<title>SistemaWeb | Thiago Pereira</title> 
	</head>
	<body>
		<?php
			require_once BASE_DIR . '/util/conexao.php';
			
			$_action = "inclusao"; // por padrao, entrar no modo de inclusao
			
			// Se passar id, abrir registro
			$id = $_GET['id'];
			if (!empty($id)) {
				// Abrir nova conexão
				$conexao = new Conexao();

				$sql = "select * from nome where id=" . $id;
				$result = $conexao->query($sql);
			
				// Abrir resultado
				$rows = pg_fetch_all($result);
			
				if ($rows == null) {
					return;
				}
			
				$id = $rows[0]['id'];
				$municipio = $rows[0]['municipio'];
				$uf = $rows[0]['uf'];
				$ibge = $rows[0]['ibge'];
				$_action = "alteracao";
			}
			
		?>
		<!-- MENU -->
		<?php
		    require_once BASE_DIR . '/modulos/sistema/menu/menu.php';
		    require_once BASE_DIR . '/modulos/sistema/sidebar/sidebar.php';			
		?>
		<!-- CONTEUDO -->
		<div class="wrapper" role="main">
			<div class="container">
				<div class="row">
					<!-- SIDEBAR -->
					<div class="col-md-2">						
					</div>
					<!-- AREA DE CONTEUDO -->
					<div id="conteudo" class="col-xs-12 col-md-10">
						<!-- FORMULARIO -->
						<div class="panel panel-primary">
							<div class="panel-heading">
								Cadastro de Contas do Movimento Financeiro
							</div>
							<!-- REGRAS DE PERMISSAO -->
							<?php
								function permissao() {
									global $_action, $perm_incluir, $perm_alterar;
									
									if ($_action == "inclusao" && $perm_incluir != "S") {
										echo "readonly";
										return;
									}
									if ($_action == "alteracao" && $perm_alterar != "S") {
										echo "readonly";
										return;
									}
								}
							?>
							<div class="panel-body">
								<form role="form">
									<div class="row">
    									<!-- LOJA -->
	    								<div class="form-group col-md-6">
		     								<label for="loja">Loja: <span class="label label-danger">Obrigatório</span></label>
			    							<input type="text" class="form-control" id="loja" name="loja" autocomplete="off" maxlength="60" value="<?= $loja ?>" autofocus <?php permissao(); ?> required>
				    					</div>
										<!-- NOME -->
	    								<div class="form-group col-md-6">
		     								<label for="nome">Nome: <span class="label label-danger">Obrigatório</span></label>
			    							<input type="text" class="form-control" id="nome" name="nome" autocomplete="off" maxlength="60" value="<?= $nome ?>" <?php permissao(); ?> required>
				    					</div>
										<!-- TIPO -->
	    								<div class="form-group col-md-6">
		     								<label for="tipo">Tipo: <span class="label label-danger">Obrigatório</span></label>
			    							<input type="text" class="form-control" id="tipo" name="tipo" autocomplete="off" maxlength="60" value="<?= $tipo ?>" <?php permissao(); ?> required>
				    					</div>
										<!-- CATEGORIA -->
	    								<div class="form-group col-md-6">
		     								<label for="categoria">Categoria: <span class="label label-danger">Obrigatório</span></label>
			    							<input type="text" class="form-control" id="categoria" name="categoria" autocomplete="off" maxlength="60" value="<?= $categoria ?>" <?php permissao(); ?> required>
				    					</div>
										<!-- TRANSFERE SALDO -->
	    								<div class="form-group col-md-6">
		     								<label for="transfere_saldo">Transfere Saldo: <span class="label label-danger">Obrigatório</span></label>
			    							<input type="text" class="form-control" id="transfere_saldo" name="transfere_saldo" autocomplete="off" maxlength="60" value="<?= $transfere_saldo ?>" <?php permissao(); ?> required>
				    					</div>
										<!-- BANCO -->
	    								<div class="form-group col-md-6">
		     								<label for="banco">Banco: <span class="label label-danger">Obrigatório</span></label>
			    							<input type="text" class="form-control" id="banco" name="banco" autocomplete="off" maxlength="60" value="<?= $banco ?>" <?php permissao(); ?> required>
				    					</div>
										<!-- AGENCIA -->
	    								<div class="form-group col-md-6">
		     								<label for="agencia">Agência: <span class="label label-danger">Obrigatório</span></label>
			    							<input type="text" class="form-control" id="agencia" name="agencia" autocomplete="off" maxlength="60" value="<?= $agencia ?>" <?php permissao(); ?> required>
				    					</div>
										<!-- CONTA CORRENTE -->
	    								<div class="form-group col-md-6">
		     								<label for="conta_corrente">Conta Corrente: <span class="label label-danger">Obrigatório</span></label>
			    							<input type="text" class="form-control" id="conta_corrente" name="conta_corrente" autocomplete="off" maxlength="60" value="<?= $conta_corrente ?>" <?php permissao(); ?> required>
				    					</div>
										<!-- LANCAMENTO MANUAL -->
	    								<div class="form-group col-md-6">
		     								<label for="lancamento_manual">Lançamento Manual: <span class="label label-danger">Obrigatório</span></label>
			    							<input type="text" class="form-control" id="lancamento_manual" name="lancamento_manual" autocomplete="off" maxlength="60" value="<?= $lancamento_manual ?>" <?php permissao(); ?> required>
				    					</div>
										<!-- CODIGO BANCO -->
	    								<div class="form-group col-md-6">
		     								<label for="codigo_banco">Código Banco: <span class="label label-danger">Obrigatório</span></label>
			    							<input type="text" class="form-control" id="codigo_banco" name="codigo_banco" autocomplete="off" maxlength="60" value="<?= $codigo_banco ?>" <?php permissao(); ?> required>
				    					</div>
									</div>
									<input type="hidden" name="id" value="<?= $id ?>">
									<input type="hidden" name="_action" value="<?= $_action ?>">
								</form>
							</div>
						</div>
						<!-- PAINEL DE AVISO -->
						<div class="aviso">
							<?php
								if ($_action == 'inclusao' && $perm_incluir != 'S') {
									echo "<script>avisoAtencao('Sem permissão: INCLUIR CADASTRO DE MUNICIPIO. Solicite ao administrador a liberação.');</script>";
								}
								
								if ($_action == 'alteracao' && $perm_alterar != 'S') {
									echo "<script>avisoAtencao('Sem permissão: ALTERAR CADASTRO DE MUNICIPIO. Solicite ao administrador a liberação.');</script>";
								}
							?>
						</div>
						<!-- PAINEL DE BOTOES -->
						<div class="btn-control-bar">
							<div class="panel-heading">
								<button class="btn btn-success mob-btn-block <?php permissao(); ?>" onclick="submit('#municipio');" <?php permissao(); ?>>
									<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
									 Salvar
								</button>
								<a href="consulta.php">
									<button class="btn btn-warning mob-btn-block">
										<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
										 Cancelar
									</button>
								</a>
								<button class="btn btn-danger mob-btn-block" style="<?php if ($_action == "inclusao") { echo "display: none"; } ?>" data-toggle="modal" data-target="#modal" onclick="dialogYesNo('esubmit()', null, 'Excluir Município', 'Deseja excluir este município ?', 'trash');" <?php if ($perm_excluir != 'S') { echo "disabled"; } ?>>
									<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
									 Excluir
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- RODAPE -->
		<footer>
			<div class="container">
				<?php
					require_once BASE_DIR . '/modulos/sistema/rodape/rodape.php';
				?>
			</div>
		</footer>
	</body>
</html>
