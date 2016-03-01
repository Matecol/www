<?php
        // validar sessao
        require_once BASE_DIR . '/util/sessao.php';

        validarSessao();
		
		// Testar permissao
		require_once BASE_DIR . '/util/permissao.php';
		$perm_incluir = testarPermissao('INCLUIR CADASTRO DE ENVIO DE MENSAGENS');
		$perm_alterar = testarPermissao('ALTERAR CADASTRO DE ENVIO DE MENSAGENS');
		$perm_excluir = testarPermissao('EXCLUIR CADASTRO DE ENVIO DE MENSAGENS');
		
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
			
			// Se passar id, abrir registro
			$usuario = $_GET['usuario'];
			$nome_mensagem = $_GET['nome_mensagem'];
			if (!empty($usuario) && !empty($nome_mensagem)) {
				// Abrir nova conexão
				$conexao = new Conexao();

				$sql = "select * from envio_mensagens where usuario=" . $usuario . " and nome_mensagem='" . $nome_mensagem ."';";
				$result = $conexao->query($sql);
			
				// Abrir resultado
				$rows = pg_fetch_all($result);
			
				if ($rows == null) {
					return;
				}

				$usuario = $rows[0]['usuario'];
				$nome_mensagem = $rows[0]['nome_mensagem'];	
				$descricao = $rows[0]['descricao'];
				$valor = $rows[0]['valor'];
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
								Cadastro de Envio de Mensagens
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
										<!-- USUARIO -->
										<div class="form-group col-md-6">
											<div class="row">
												<div class="col-md-4">
													<!-- CODIGO USUARIO -->
													<label for="usuario">Código: </label >
													<div class="input-group">
														<input type="text" pattern="[0-9]*" class="form-control" id="usuario" name="usuario" data-mask="00000" autocomplete="off" value="<?= $usuario ?>" onblur="consultarUsuario();" <?php permissao(); ?> <?php if ($_action == "alteracao"){ echo "readonly";} ?> autofocus required>
														<span class="input-group-btn">
															<button class="btn btn-primary" <?php permissao(); ?> onclick="abrirConsulta('/modulos/configuracoes/usuarios/consulta.php', '<?= time(); ?>');"><span class="glyphicon glyphicon-search"></span></button>
														</span>
													</div>
												</div>
												<!-- DESCRICAO USUARIO -->
												<div class="col-md-8">
													<label for="nome_usuario">Usuário: <span class="label label-danger">Obrigatório</span> </label>
													<input type="text" class="form-control" id="nome_usuario" autocomplete="off" maxlength="60" value="<?= $nome_usuario ?>"  disabled>
												</div>
											</div>
										</div>
										<!-- NOME MENSAGEM -->
										<div class="form-group col-md-6">
											<label for="nome_mensagem">Nome da Mensagem: <span class="label label-danger">Obrigatório</span></label>
										    <input type="text" class="form-control" id="nome_mensagem" name="nome_mensagem" autocomplete="off" value="<?= $nome_mensagem ?>" <?php permissao(); ?> required <?php if ($_action == "alteracao"){ echo "readonly";} ?>>
										</div>
									</div>
									<div class="row">
										<!-- DESCRICAO -->
										<div class="form-group col-md-6">
											<label for="descricao">Descrição: <span class="label label-danger">Obrigatório</span></label>
											<input type="text" class="form-control" id="descricao" name="descricao" autocomplete="off" maxlength="60" value="<?= $descricao ?>" <?php permissao(); ?> required>
										</div>
										<!-- VALOR -->
										<div class="form-group col-md-3">
											<label for="valor">Valor: </label>
											<select class="form-control" id="valor" name="valor" <?php permissao(); ?>>
											<?php
												$valor_a = array('N', 'S');
											
												foreach($valor_a as $v) {
													if ($v == $valor) {
														echo '<option value="' . $v . '" selected>' . (($v == "S") ? "SIM" : "NÃO") . '</option>'; 
													} else {
														echo '<option value="' . $v . '">' . (($v == "S") ? "SIM" : "NÃO") . '</option>';
													}
												}
											?>
											</select>
										</div>
									</div>
									<input type="hidden" name="_action" value="<?= $_action ?>">
								</form>
							</div>
						</div>
						<!-- PAINEL DE AVISO -->
						<div class="aviso">
							<?php
								if ($_action == 'inclusao' && $perm_incluir != 'S') {
									echo "<script>avisoAtencao('Sem permissão: INCLUIR CADASTRO DE ENVIO DE MENSAGENS. Solicite ao administrador a liberação.');</script>";
								}
								
								if ($_action == 'alteracao' && $perm_alterar != 'S') {
									echo "<script>avisoAtencao('Sem permissão: ALTERAR CADASTRO DE ENVIO DE MENSAGENS. Solicite ao administrador a liberação.');</script>";
								}
							?>
						</div>
						<!-- PAINEL DE BOTOES -->
						<div class="btn-control-bar">
							<div class="panel-heading">
								<button class="btn btn-success mob-btn-block <?php permissao(); ?>" onclick="submit('#usuario');" <?php permissao(); ?>>
									<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
									 Salvar
								</button>
								<a href="<?= $_SERVER['HTTP_REFERER'] ?>">
									<button class="btn btn-warning mob-btn-block">
										<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
										 Cancelar
									</button>
								</a>
								<button class="btn btn-danger mob-btn-block" style="<?php if ($_action == "inclusao") { echo "display: none"; } ?>" data-toggle="modal" data-target="#modal" onclick="dialogYesNo('esubmit()', null, 'Excluir Mensagem', 'Deseja excluir esta Mensagem ?', 'trash');" <?php if ($perm_excluir != 'S') { echo "disabled"; } ?>>
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
