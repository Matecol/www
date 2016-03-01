<?php
        // validar sessao
        require_once BASE_DIR . '/util/sessao.php';

        validarSessao();
		
		// Testar permissao
		require_once BASE_DIR . '/util/permissao.php';
		$perm_incluir = testarPermissao('INCLUIR CADASTRO DE PROGRAMAS');
		$perm_alterar = testarPermissao('ALTERAR CADASTRO DE PROGRAMAS');
		$perm_excluir = testarPermissao('EXCLUIR CADASTRO DE PROGRAMAS');
		
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

				$sql = "select * from programas where id=" . $id;
				$result = $conexao->query($sql);
			
				// Abrir resultado
				$rows = pg_fetch_all($result);
			
				if ($rows == null) {
					return;
				}
			
				$id = $rows[0]['id'];
				$nome = $rows[0]['nome'];
				$modulo = $rows[0]['modulo'];
				$pasta = $rows[0]['pasta'];
				$agrupamento = $rows[0]['agrupamento'];
				$indice = $rows[0]['indice'];
			    $nivel = $rows[0]['nivel'];
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
								Cadastro de Programas
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
											<label for="nome">Nome do Programa: <span class="label label-danger">Obrigatório</span></label>
											<input type="text" class="form-control no-uppercase" id="nome" name="nome" autocomplete="off" maxlength="60" value="<?= $nome ?>" autofocus <?php permissao(); ?> required>
										</div>
										<!-- MODULO -->
										<div class="form-group col-md-4">
											<label for="modulo">Modulo: <span class="label label-danger">Obrigatório</span></label>
											<select class="form-control" id="modulo" name="modulo" <?php permissao(); ?> required>
											<?php										
												$conexao = new Conexao();
												$result = $conexao->query("select * from modulos order by nome");
												$rows = pg_fetch_all($result);
											
												if ($rows != null) {
													
													foreach ($rows as $row) {
														if ($row['id'] == $modulo) {	
															echo '<option value="' . $row['id'] . '" selected>' . $row['nome'] . '</option>';
														} else {
															echo '<option value="' . $row['id'] . '">' . $row['nome'] . '</option>';
														}
													}
												
												}
											?>
											</select>
										</div>
										<!-- PASTA -->
										<div class="form-group col-md-4">
											<label for="pasta">Pasta/Arquivo: <span class="label label-danger">Obrigatório</span></label>
											<input type="text" class="form-control no-uppercase" id="pasta" name="pasta" autocomplete="off" maxlength="60" value="<?= $pasta ?>" <?php permissao(); ?> required>
										</div>
									</div>
									<div class="row">
										<!-- AGRUPAMENTO -->
										<div class="form-group col-md-4">
											<label for="agrupamento">Agrupamento: </label>
											<input type="text" class="form-control no-uppercase" id="agrupamento" name="agrupamento" autocomplete="off" maxlength="60" value="<?= $agrupamento ?>" <?php permissao(); ?> required>
										</div>
										<!-- INDICE -->
										<div class="form-group col-md-4">
											<label for="indice">Índice: <span class="label label-danger">Obrigatório</span></label>
											<input type="number" inputmode="numeric" pattern="[0-9]*" class="form-control" id="indice" name="indice" autocomplete="off" min="0" max="999999" value="<?= $indice ?>" <?php permissao(); ?> required>
										</div>
										<!-- NIVEL -->
										<div class="form-group col-md-4">
											<label for="nivel">Nível: </label>
											<select class="form-control" id="nivel" name="nivel" <?php permissao(); ?>>
											<?php
												$nivels = array('01', '02', '03', '04', '05');
									
												foreach($nivels as $u) {
													if ($u == $nivel) {
														echo '<option value="' . $u . '" selected>' . $u . '</option>';
													} else {
														echo '<option value="' . $u . '">' . $u . '</option>';
													}
												}
											?>
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
									echo "<script>avisoAtencao('Sem permissão: INCLUIR CADASTRO DE PROGRAMAS. Solicite ao administrador a liberação.');</script>";
								}
								
								if ($_action == 'alteracao' && $perm_alterar != 'S') {
									echo "<script>avisoAtencao('Sem permissão: ALTERAR CADASTRO DE PROGRAMAS. Solicite ao administrador a liberação.');</script>";
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
								<button class="btn btn-danger mob-btn-block" style="<?php if ($_action == "inclusao") { echo "display: none"; } ?>" data-toggle="modal" data-target="#modal" onclick="dialogYesNo('esubmit()', null, 'Excluir Programa', 'Deseja excluir este programa ?', 'trash');" <?php if ($perm_excluir != 'S') { echo "disabled"; } ?>>
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
