<?php
        // validar sessao
        require_once BASE_DIR . '/util/sessao.php';

        validarSessao();
		
		// Testar permissao
		require_once BASE_DIR . '/util/permissao.php';
		$perm_incluir = testarPermissao('INCLUIR CONTATO NA AGENDA');
		$perm_alterar = testarPermissao('ALTERAR CONTATO DA AGENDA');
		$perm_excluir = testarPermissao('EXCLUIR CONTATO DA AGENDA');
		
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
	<body <?= (! empty($_GET['id'])) ? 'onload="consultarMunicipio();"' : "" ?> <?php if (! empty($_GET['link'])) { echo "onload=\"restaurarCadastro('" . $_GET['link'] . "', '#municipio'); consultarMunicipio(); \""; } ?>>
		<?php
			require_once BASE_DIR . '/util/conexao.php';
			
			$_action = "inclusao"; // por padrao, entrar no modo de inclusao
			
			// Se passar id, abrir registro
			$id = $_GET['id'];
			if (!empty($id)) {
				// Abrir nova conexão
				$conexao = new Conexao();

				$sql = "select * from agenda where id=" . $id;
				$result = $conexao->query($sql);
			
				// Abrir resultado
				$rows = pg_fetch_all($result);
			
				if ($rows == null) {
					return;
				}
			
				$id = $rows[0]['id'];
				$razaosocial = $rows[0]['razaosocial'];
				$endereco = $rows[0]['endereco'];
				$bairro = $rows[0]['bairro'];
				$cep = $rows[0]['cep'];
				$municipio = $rows[0]['municipio'];
				$cidade = $rows[0]['cidade'];
				$telefone = $rows[0]['telefone'];
				$celular = $rows[0]['celular'];
				$email = $rows[0]['email'];
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
								Agenda Telefônica
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
										<!-- RAZAO SOCIAL -->
										<div class="form-group col-md-6">
											<label for="razaosocial">Nome: <span class="label label-danger">Obrigatório</span></label>
											<input type="text" class="form-control" id="razaosocial" name="razaosocial" autocomplete="off" maxlength="60" value="<?= $razaosocial ?>" autofocus <?php permissao(); ?> required>
										</div>
										<!-- ENDERECO -->
										<div class="form-group col-md-6">
											<label for="endereco">Endereço: </label>
											<input type="text" class="form-control" id="endereco" name="endereco" autocomplete="off" maxlength="60" value="<?= $endereco ?>" <?php permissao(); ?>>
										</div>
									</div>
									<div class="row">
										<!-- BAIRRO -->									
										<div class="form-group col-md-6">
											<label for="bairro">Bairro: </label>
											<input type="text" class="form-control" id="bairro" name="bairro" autocomplete="off" maxlength="60" value="<?= $bairro ?>" <?php permissao(); ?>>
										</div>
										<!-- MUNICIPIO -->
										<div class="form-group col-md-6">
											<div class="row">
												<div class="col-md-4">
													<!-- CODIGO MUNICIPIO -->
													<label for="municipio">Código: </label >
													<div class="input-group">
														<input type="numeric" pattern="[0-9]*" class="form-control" id="municipio" name="municipio" data-mask="00000" autocomplete="off" value="<?= $municipio ?>" onblur="consultarMunicipio();" <?php permissao(); ?>>
														<span class="input-group-btn">
															<button class="btn btn-primary" <?php permissao(); ?> onclick="abrirConsulta('/modulos/configuracoes/municipios/consulta.php', '<?= time(); ?>');"><span class="glyphicon glyphicon-search"></span></button>
														</span>
													</div>
												</div>
												<!-- NOME MUNICIPIO -->
												<div class="col-md-8">
													<label for="nome_municipio">Município: </label>
													<input type="text" class="form-control" id="nome_municipio" autocomplete="off" maxlength="60" value="<?= $nome_municipio ?>"  disabled>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<!-- CEP -->
										<div class="form-group col-md-2">
											<label for="cep">CEP: </label>
										    <input type="text" inputmode="numeric" pattern="[0-9]*" class="form-control cep" id="cep" name="cep" autocomplete="off" value="<?= $cep ?>" <?php permissao(); ?>>
										</div>
										<div class="form-group col-md-3">
										<!-- TELEFONE -->
											<label for="telefone">Telefone: <span class="label label-danger">Obrigatório</span></label>
											<input type="text" inputmode="numeric" pattern="\([0-9]{2}\)[\s][0-9]{4}-[0-9]{4,5}" class="form-control telefone" id="telefone" name="telefone" autocomplete="off" value="<?= $telefone ?>" <?php permissao(); ?> required>
										</div>
										<!-- CELULAR -->
										<div class="form-group col-md-3">
											<label for="celular">Celular: </label>
											<input type="text" inputmode="numeric" pattern="\([0-9]{2}\)[\s][0-9]{4}-[0-9]{4,5}" class="form-control telefone" id="celular" name="celular" autocomplete="off" value="<?= $celular ?>" <?php permissao(); ?>>
										</div>
										<!-- E-MAIL -->
									    <div class="form-group col-md-4">
											<label for="email">E-mail: </label>
											<input type="email" class="form-control" id="email" name="email" autocomplete="off" maxlength="60" value="<?= $email ?>" <?php permissao(); ?>>
										</div>
									</div>
									<div class="row">
										<!-- OBSERVACOES -->
										<div class="form-group col-md-12">
											<label for="observacoes">Observações: </label>
											<textarea rows="4" cols="50" type="text" class="form-control" id="observacoes" name="observacoes" autocomplete="off" maxlength="500" value="<?= $observacoes ?>" <?php permissao(); ?>></textarea>
										</div>
									</div>
									<input type="hidden" id="id" name="id" value="<?= $id ?>">
									<input type="hidden" id="_action" name="_action" value="<?= $_action ?>">
								</form>
							</div>
						</div>
						<!-- PAINEL DE AVISO -->
						<div class="aviso">
							<?php
								if ($_action == 'inclusao' && $perm_incluir != 'S') {
									echo "<script>avisoAtencao('Sem permissão: INCLUIR CONTATO NA AGENDA. Solicite ao administrador a liberação.');</script>";
								}
								
								if ($_action == 'alteracao' && $perm_alterar != 'S') {
									echo "<script>avisoAtencao('Sem permissão: ALTERAR CONTATO DA AGENDA. Solicite ao administrador a liberação.');</script>";
								}
							?>
						</div>
						<!-- PAINEL DE BOTOES -->
						<div class="btn-control-bar">
							<div class="panel-heading">
								<button class="btn btn-success mob-btn-block <?php permissao(); ?>" onclick="submit('#razaosocial');" <?php permissao(); ?>>
									<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
									 Salvar
								</button>
								<a href="<?= $_SERVER['HTTP_REFERER'] ?>">
									<button class="btn btn-warning mob-btn-block">
										<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
										 Cancelar
									</button>
								</a>
								<button class="btn btn-danger mob-btn-block" style="<?php if ($_action == "inclusao") { echo "display: none"; } ?>" data-toggle="modal" data-target="#modal" onclick="dialogYesNo('esubmit()', null, 'Excluir Contato', 'Deseja excluir este contato ?', 'trash');" <?php if ($perm_excluir != 'S') { echo "disabled"; } ?>>
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
