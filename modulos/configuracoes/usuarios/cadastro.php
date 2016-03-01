<?php
        // validar sessao
        require_once BASE_DIR . '/util/sessao.php';

        validarSessao();
		
		// Testar permissao
		require_once BASE_DIR . '/util/permissao.php';
		$perm_incluir = testarPermissao('INCLUIR CADASTRO DE USUARIOS');
		$perm_alterar = testarPermissao('ALTERAR CADASTRO DE USUARIOS');
		$perm_excluir = testarPermissao('EXCLUIR CADASTRO DE USUARIOS');
		
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
			$id = $_GET['id'];
			if (!empty($id)) {

				// Abrir nova conexão
				$conexao = new Conexao();

				$sql = "select * from usuarios where id=" . $id;
				$result = $conexao->query($sql);
			
				// Abrir resultado
				$rows = pg_fetch_all($result);
			
				if ($rows == null) {
					return;
				}
			
				$id = $rows[0]['id'];
				$nome = $rows[0]['nome'];
				$login = $rows[0]['login'];
				$modelo = $rows[0]['modelo'];
				$empresa = $rows[0]['empresa'];
				$nivel = $rows[0]['nivel'];
				$externo = $rows[0]['externo'];
				$mobile = $rows[0]['mobile'];
				$telefone = $rows[0]['telefone'];
				$ramal = $rows[0]['ramal'];
				$email = $rows[0]['email'];
				$bloqueado = $rows[0]['bloqueado'];
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
								Cadastro de Usuários
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
    									<!-- NOME DO USUARIO -->
	    								<div class="form-group col-md-6">
		   									<label for="nome">Nome do Usuário: <span class="label label-danger">Obrigatório</span></label>
											<input type="text" class="form-control" id="nome" name="nome" autocomplete="off" maxlength="60" value="<?= $nome ?>" autofocus <?php permissao(); ?> required>
										</div>
										<!-- LOGIN -->
										<div class="form-group col-md-6">
											<label for="login">Login: <span class="label label-danger">Obrigatório</span></label>
											<input type="text" class="form-control" id="login" name="login" autocomplete="off" maxlength="60" value="<?= $login ?>" <?php permissao(); ?> <?php if ($_action == "alteracao"){ echo"readonly";} ?> required>
										</div>
									</div>
									<div class="row">
										<!-- SENHA -->
										<div class="form-group col-md-6">
											<label for="senha">Senha: <span class="label label-danger">Obrigatório</span></label>
											<input type="password" class="form-control" id="senha" name="senha" autocomplete="off" maxlength="20" value="<?= $senha ?>" <?php permissao(); ?> <?php if ($_action == "alteracao"){ echo"readonly";} ?> required>
										</div>
										<!-- CONFIRMACAO DA SENHA -->
										<div class="form-group col-md-6">
											<label for="confirmacao_senha">Confirme a Senha: <span class="label label-danger">Obrigatório</span></label>
											<input type="password" class="form-control" id="confirmacao_senha" name="confirmacao_senha" autocomplete="off" maxlength="20" value="<?= $confirmacao_senha ?>" <?php permissao(); ?> <?php if ($_action == "alteracao"){ echo"readonly";} ?> required>
										</div>
									</div>
									<div class="row">
										<!-- MODELO -->
										<div class="form-group col-md-6">
											<label for="modelo">Modelo: </label>
											<input type="number" inputmode="numeric" pattern="[0-9]*" class="form-control" id="modelo" name="modelo" autocomplete="off" min="0" max="999999" value="<?= $modelo ?>" <?php permissao(); ?>>
										</div>
										<!-- EMPRESA -->
										<div class="form-group col-md-6">
											<label for="empresa">Empresa: <span class="label label-danger">Obrigatório</span></label>
											<input type="text" class="form-control" id="empresa" name="empresa" autocomplete="off" maxlength="60" value="<?= $empresa ?>" <?php permissao(); ?> required>
										</div>
									</div>
									<div class="row">
										<!-- TELEFONE -->
										<div class="form-group col-md-6">
											<label for="telefone">Telefone:</label>
											<input type="text" inputmode="numeric" pattern="\([0-9]{2}\)[\s][0-9]{4}-[0-9]{4,5}" class="form-control telefone" id="telefone" name="telefone" autocomplete="off" value="<?= $telefone ?>" <?php permissao(); ?>>
										</div>
										<!-- RAMAL -->
										<div class="form-group col-md-6">
											<label for="ramal">Ramal:</label>
											<input type="number" inputmode="numeric" pattern="[0-9]{3}" class="form-control" id="ramal" name="ramal" autocomplete="off" min="0" max="999999" value="<?= $ramal ?>" <?php permissao(); ?>>
										</div>
									</div>
									<div class="row">
									    <!-- E-MAIL -->
										<div class="form-group col-md-6">
											<label for="email">E-Mail: <span class="label label-danger">Obrigatório</span></label>
											<input type="text" class="form-control" id="email" name="email" autocomplete="off" maxlength="60" value="<?= $email ?>" <?php permissao(); ?> required>
										</div>
									</div>
									<div class="row">
										<!-- NIVEL -->
										<div class="form-group col-md-3">
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
										<!-- EXTERNO -->
										<div class="form-group col-md-3">
											<label for="externo">Externo: </label>
											<select class="form-control" id="externo" name="externo" <?php permissao(); ?>>
											<?php
												$externo_a = array('N', 'S');
											
												foreach($externo_a as $e) {
													if ($e == $externo) {
														echo '<option value="' . $e . '" selected>' . (($e == "S") ? "SIM" : "NÃO") . '</option>'; 
													} else {
														echo '<option value="' . $e . '">' . (($e == "S") ? "SIM" : "NÃO") . '</option>';
													}
												}
											?>
											</select>
										</div>
										<!-- MOBILE -->
										<div class="form-group col-md-3">
											<label for="mobile">Mobile: </label>
											<select class="form-control" id="mobile" name="mobile" <?php permissao(); ?>>
											<?php
												$mobile_a = array('N', 'S');
											
												foreach($mobile_a as $m) {
													if ($m == $mobile) {
														echo '<option value="' . $m . '" selected>' . (($m == "S") ? "SIM" : "NÃO") . '</option>'; 
													} else {
														echo '<option value="' . $m . '">' . (($m == "S") ? "SIM" : "NÃO") . '</option>';
													}
												}
											?>
											</select>
										</div>
										<!-- BLOQUEADO -->
										<div class="form-group col-md-3">
											<label for="bloqueado">Bloqueado: </label>
											<select class="form-control" id="bloqueado" name="bloqueado" <?php permissao(); ?>>
											<?php
												$bloqueado_a = array('N', 'S');
												
												foreach($bloqueado_a as $b) {
													if ($b == $bloqueado) {
														echo '<option value="' . $b . '" selected>' . (($b == "S") ? "SIM" : "NÃO") . '</option>'; 
													} else {
														echo '<option value="' . $b . '">' . (($b == "S") ? "SIM" : "NÃO") . '</option>';
													}
												}
											?>
											</select>
										</div>
									</div>
									<div class="row">
										<!-- OBSERVACOES -->
										<div class="form-group col-md-12">
											<label for="observacoes">Observações: </label>
											<textarea rows="4" cols="50" type="text" class="form-control" id="observacoes" name="observacoes" autocomplete="off" maxlength="500" <?php permissao(); ?>><?= $observacoes ?></textarea>
										</div>
									</div>
									<input type="hidden" id="id" name="id" value="<?= $id ?>">
									<input type="hidden" name="_action" value="<?= $_action ?>">
								</form>
							</div>
						</div>
						<!-- PAINEL DE AVISO -->
						<div class="aviso">
							<?php
								if ($_action == 'inclusao' && $perm_incluir != 'S') {
									echo "<script>avisoAtencao('Sem permissão: INCLUIR CADASTRO DE USUARIOS. Solicite ao administrador a liberação.');</script>";
								}
								
								if ($_action == 'alteracao' && $perm_alterar != 'S') {
									echo "<script>avisoAtencao('Sem permissão: ALTERAR CADASTRO DE USUARIOS. Solicite ao administrador a liberação.');</script>";
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
								<?php
									if ($_action == "alteracao") {
										echo '
										<button class="btn btn-primary mob-btn-block" onclick="permissoes(\'' . assinarParametros('usuario=' . $_GET['id']) .  '\');">
											<span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
									 		Permissões
										</button>
										<button class="btn btn-info mob-btn-block" onclick="">
											<span class="glyphicon glyphicon-th" aria-hidden="true"></span>
									 		Programas
										</button>';
									}	
								?>
								<a href="<?= $_SERVER['HTTP_REFERER'] ?>">
									<button class="btn btn-warning mob-btn-block">
										<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
										 Cancelar
									</button>
								</a>
								<button class="btn btn-danger mob-btn-block" style="<?php if ($_action == "inclusao") { echo "display: none"; } ?>" data-toggle="modal" data-target="#modal" onclick="dialogYesNo('esubmit()', null, 'Excluir Usuário', 'Deseja excluir este usuário ?', 'trash');" <?php if ($perm_excluir != 'S') { echo "disabled"; } ?>>
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
