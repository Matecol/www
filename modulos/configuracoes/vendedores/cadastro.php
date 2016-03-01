<?php
        // validar sessao
        require_once BASE_DIR . '/util/sessao.php';

        validarSessao();
		
		// Testar permissao
		require_once BASE_DIR . '/util/permissao.php';
		$perm_incluir = testarPermissao('INCLUIR CADASTRO DE VENDEDORES');
		$perm_alterar = testarPermissao('ALTERAR CADASTRO DE VENDEDORES');
		$perm_excluir = testarPermissao('EXCLUIR CADASTRO DE VENDEDORES');

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
			
			// Se passar id, abrir registro
			$id = $_GET['id'];
			if (!empty($id)) {
				// Abrir nova conexão
				$conexao = new Conexao();

				$sql = "select * from vendedores where id=" . $id;
				$result = $conexao->query($sql);
			
				// Abrir resultado
				$rows = pg_fetch_all($result);
			
				if ($rows == null) {
					return;
				}
			
				$id = $rows[0]['id'];
				$nome = $rows[0]['nome'];
				$empresa = $rows[0]['empresa'];
				$tipo = $rows[0]['tipo'];
				$comissao_pecas = $rows[0]['comissao_pecas'];
				$comissao_servicos = $rows[0]['comissao_servicos'];
				$situacao = $rows[0]['situacao'];
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
								Cadastro de Vendedores
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
									    <!-- NOME -->
									    <div class="form-group col-md-4">
										    <label for="nome">Nome: <span class="label label-danger">Obrigatório</span></label>
											<input type="text" class="form-control" id="nome" name="nome" autocomplete="off" maxlength="60" value="<?= $nome ?>" <?php permissao(); ?> required>
	    								</div>
										<!-- EMPRESA -->
										<div class="form-group col-md-4">
											<label for="empresa">Empresa: <span class="label label-danger">Obrigatório</span></label>
											<input type="text" class="form-control" id="empresa" name="empresa" autocomplete="off" maxlength="60" value="<?= $empresa ?>" <?php permissao(); ?> required>
										</div>
									</div>
									<div class="row">
										<!-- TIPO -->
									    <div class="form-group col-md-3">
											<label for="tipo">Tipo: </label>
											<select class="form-control" id="tipo" name="tipo" <?php permissao(); ?>>
											<option value="B" <?= ($tipo == "B") ? "selected" : "" ?>>Balcão</option>
											<option value="M" <?= ($tipo == "M") ? "selected" : "" ?>>Máquinas</option>
											<option value="O" <?= ($tipo == "O") ? "selected" : "" ?>>Outros</option>
											</select>
										</div>
										<!-- COMISSAO DE PECAS -->
									    <div class="form-group col-md-3">
										    <label for="comissao_pecas">Comissão de Peças: </label>
										    <input type="text" inputmode="numeric" pattern="[0-9]*" class="form-control" id="comissao_pecas" name="comissao_pecas" autocomplete="off" value="<?= $comissao_pecas ?>" <?php permissao(); ?>>
									    </div>
										<!-- COMISSAO DE SERVICOS -->
									    <div class="form-group col-md-3">
										    <label for="comissao_servicos">Comissão de Serviços: </label>
										    <input type="text" inputmode="numeric" pattern="[0-9]*" class="form-control" id="comissao_servicos" name="comissao_servicos" autocomplete="off" value="<?= $comissao_servicos ?>" <?php permissao(); ?>>
									    </div>
										<!-- SITUACAO -->
									    <div class="form-group col-md-3">
											<label for="situacao">Situação: </label>
											<select class="form-control" id="situacao" name="situacao" <?php permissao(); ?>>
											<option value="A" <?= ($situacao == "A") ? "selected" : "" ?>>Ativo</option>
											<option value="I" <?= ($situacao == "I") ? "selected" : "" ?>>Inativo</option>
											</select>
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
									echo "<script>avisoAtencao('Sem permissão: INCLUIR CADASTRO DE VENDEDORES. Solicite ao administrador a liberação.');</script>";
								}
								
								if ($_action == 'alteracao' && $perm_alterar != 'S') {
									echo "<script>avisoAtencao('Sem permissão: ALTERAR CADASTRO DE VENDEDORES. Solicite ao administrador a liberação.');</script>";
								}
							?>
						</div>
						<!-- PAINEL DE BOTOES -->
						<div class="btn-control-bar">
							<div class="panel-heading">
								<button class="btn btn-success mob-btn-block <?php permissao(); ?>" onclick="submit('#nome');" <?php permissao(); ?>>
									<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
									 Salvar
								</button>
								<a href="<?= $_SERVER['HTTP_REFERER'] ?>">
									<button class="btn btn-warning mob-btn-block">
										<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
										 Cancelar
									</button>
								</a>
								<button class="btn btn-danger mob-btn-block" style="<?php if ($_action == "inclusao") { echo "display: none"; } ?>" data-toggle="modal" data-target="#modal" onclick="dialogYesNo('esubmit()', null, 'Excluir Vendedor', 'Deseja excluir este Vendedor ?', 'trash');" <?php if ($perm_excluir != 'S') { echo "disabled"; } ?>>
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
