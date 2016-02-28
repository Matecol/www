<?php
        // validar sessao
        require_once BASE_DIR . '/util/sessao.php';

        validarSessao();
		
		// Testar permissao
		require_once BASE_DIR . '/util/permissao.php';
		$perm_incluir = testarPermissao('INCLUIR SOLICITACAO DE CREDITO');
		$perm_alterar = testarPermissao('ALTERAR SOLICITACAO DE CREDITO');
		$perm_aprovar = testarPermissao('APROVAR SOLICITACAO DE CREDITO');
		$perm_revogar = testarPermissao('REVOGAR SOLICITACAO DE CREDITO');
		$perm_cancelar = testarPermissao('CANCELAR SOLICITACAO DE CREDITO');
		
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
	    <script type="text/javascript" src="/assets/js/jquery.mask.min.js"></script>
		<script type="text/javascript" src="/assets/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/assets/js/principal.js"></script>
		<script type="text/javascript" src="assets/js/cadastro.js"></script>
		<title>SistemaWeb | Thiago Pereira</title> 
	</head>
	<body>
		<?php
			require_once BASE_DIR . '/util/conexao.php';
			
			$_action = "inclusao"; // por padrao, entrar no modo de inclusao
			$cliente = $_GET['cliente'];
			
			// Se passar id, abrir registro
			$id = $_GET['id'];
			if (! empty($id) && ! empty($cliente)) {
				// Abrir nova conexão
				$conexao = new Conexao();

				$sql = "select * from creditos_cliente where id=" . $id . " and cliente=" . $cliente;
				$result = $conexao->query($sql);
			
				// Abrir resultado
				$rows = pg_fetch_all($result);
			
				if ($rows == null) {
					return;
				}

				$tipo = $rows[0]['tipo'];
				$valor = $rows[0]['valor'];
				$status = $rows[0]['status'];
				$dt_solicitacao = $rows[0]['dt_solicitacao'];
				$usuario_solicitacao = $rows[0]['usuario_solicitacao'];
				$dt_revogacao = $rows[0]['dt_revogacao'];
				$usuario_revogacao = $rows[0]['usuario_revogacao'];
				$dt_aprovacao = $rows[0]['dt_aprovacao'];
				$usuario_aprovacao = $rows[0]['usuario_aprovacao'];
				$dt_cancelamento = $rows[0]['dt_cancelamento'];
				$usuario_cancelamento = $rows[0]['usuario_cancelamento'];
				$observacoes = $rows[0]['observacoes'];
				
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
								Solicitações de Crédito para Cliente
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
										<!-- VALOR -->
										<div class="form-group col-md-3">
											<label for="valor">Valor: <span class="label label-danger">Obrigatório</span></label>
										    <input type="text" class="form-control valor" id="valor" name="valor" data-mask="##.###.##0,00" data-mask-reverse="true" autocomplete="off" value="<?= $valor ?>" <?php permissao(); ?> <?php if ($_action == "alteracao"){ echo "readonly";} ?> autofocus required>
										</div>
										<!-- TIPO -->
										<div class="form-group col-md-2">
											<label for="tipo">Tipo:</label>
											<select class="form-control" id="tipo" name="tipo">
												<option value="T" <?= ($tipo == "T") ? "selected" : "" ?>>TODOS</option>
												<option value="C" <?= ($tipo == "C") ? "selected" : "" ?>>CHEQUE</option>
											</select>
										</div>
										<!-- OBSERVACOES -->
										<div class="form-group col-md-7">
											<label for="observacoes">Observações: <span class="label label-danger">Obrigatório</span></label>
											<input type="text" class="form-control" id="observacoes" name="observacoes" autocomplete="off" maxlength="60" value="<?= $observacoes ?>" <?php permissao(); ?> required>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-12">
											<h4>Dados da Solicitação de Crédito</h4>
											<hr>
										</div>
									</div>
									<!-- DATA E USUARIO DA SOLICITAÇÃO -->
									<div class="row">
										<div class="form-group col-md-3">
											<label for="dt_solicitacao">Data/Hora Solicitação:</label>
											<input type="text" class="form-control" id="dt_solicitacao" disabled>
										</div>
										<div class="form-group col-md-5">
											<label for="dt_solicitacao">Usuário que solicitou:</label>
											<input type="text" class="form-control" id="dt_solicitacao" disabled>
										</div>
									</div>
									<!-- DATA E USUARIO DA APROVAÇÃO -->
									<div class="row">
										<div class="form-group col-md-3">
											<label for="dt_aprovacao">Data/Hora Aprovação:</label>
											<input type="text" class="form-control" id="dt_aprovacao" disabled>
										</div>
										<div class="form-group col-md-5">
											<label for="dt_aprovacao">Usuário que aprovou:</label>
											<input type="text" class="form-control" id="dt_aprovacao" disabled>
										</div>
									</div>
									<!-- DATA E USUARIO DA REVOGAÇÃO -->
									<div class="row">
										<div class="form-group col-md-3">
											<label for="dt_revogacao">Data/Hora Revogação:</label>
											<input type="text" class="form-control" id="dt_revogacao" disabled>
										</div>
										<div class="form-group col-md-5">
											<label for="dt_revogacao">Usuário que revogou:</label>
											<input type="text" class="form-control" id="dt_revogacao" disabled>
										</div>
									</div>
									<!-- DATA E USUARIO DO CANCELAMENTO -->
									<div class="row">
										<div class="form-group col-md-3">
											<label for="dt_cancelamento">Data/Hora Cancelamento:</label>
											<input type="text" class="form-control" id="dt_cancelamento" disabled>
										</div>
										<div class="form-group col-md-5">
											<label for="dt_cancelamento">Usuário que cancelou:</label>
											<input type="text" class="form-control" id="dt_cancelamento" disabled>
										</div>
									</div>
									<input type="hidden" name="cliente" value="<?= $cliente ?>">
									<input type="hidden" name="_action" value="<?= $_action ?>">
								</form>
							</div>
						</div>
						<!-- PAINEL DE AVISO -->
						<div class="aviso">
							<?php
								if ($_action == 'inclusao' && $perm_incluir != 'S') {
									echo "<script>avisoAtencao('Sem permissão: INCLUIR SOLICITACAO DE CREDITO. Solicite ao administrador a liberação.');</script>";
								}
								
								if ($_action == 'alteracao' && $perm_alterar != 'S') {
									echo "<script>avisoAtencao('Sem permissão: ALTERAR SOLICITACAO DE CREDITO. Solicite ao administrador a liberação.');</script>";
								}
							?>
						</div>
						<!-- PAINEL DE BOTOES -->
						<div class="btn-control-bar">
							<div class="panel-heading">
								<button class="btn btn-success mob-btn-block <?php permissao(); ?>" onclick="submit('#cliente');" <?php permissao(); ?>>
									<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
									 Salvar
								</button>
								<a href="<?= $_SERVER['HTTP_REFERER'] ?>">
									<button class="btn btn-warning mob-btn-block">
										<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
										 Retornar
									</button>
								</a>
								<button class="btn btn-danger mob-btn-block" style="<?php if ($_action == "inclusao") { echo "display: none"; } ?>" data-toggle="modal" data-target="#modal" onclick="dialogYesNo('esubmit()', null, 'Excluir Créditos do Cliente', 'Deseja excluir este Crédito ?', 'trash');" <?php if ($perm_excluir != 'S') { echo "disabled"; } ?>>
									<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
									 Cancelar
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
