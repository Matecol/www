<?php
        // validar sessao
        require_once BASE_DIR . '/util/sessao.php';

        validarSessao();
		
		// Testar permissao
		require_once BASE_DIR . '/util/permissao.php';
		$perm_incluir = testarPermissao('INCLUIR CADASTRO DE CLIENTES');
		$perm_alterar = testarPermissao('ALTERAR CADASTRO DE CLIENTES');
		$perm_excluir = testarPermissao('EXCLUIR CADASTRO DE CLIENTES');
		$perm_cnpj = testarPermissao('ALTERAR CNPJ DO CADASTRO DE CLIENTES');
		
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
	<body <?php
			if (! empty($_GET['link']) && ! empty($_GET['target']))  {
				echo 'onload="restaurarMunicipios(\'' . $_GET['link'] . '\', \'' . $_GET['target'] . '\');"';	
			} else if (! empty($_GET['id'])) {
				echo 'onload="consultarMunicipio(\'#municipio_entrega\', \'#nome_municipio_entrega\');consultarMunicipio(\'#municipio_cobranca\', \'#nome_municipio_cobranca\'); aplicarMascara();"';
			}
		?>>
		<?php
			require_once BASE_DIR . '/util/conexao.php';
			require_once BASE_DIR . '/util/criptografia.php';
			
			$_action = "inclusao"; // por padrao, entrar no modo de inclusao
			
			// Se passar id, abrir registro
			$id = descriptografar($_GET['id']);
			if (!empty($id)) {
				// Abrir nova conexão
				$conexao = new Conexao();

				$sql = "select * from clientes where id=" . $id;
				$result = $conexao->query($sql);
			
				// Abrir resultado
				$rows = pg_fetch_all($result);
			
				if ($rows == null) {
					return;
				}
			
				$id = $rows[0]['id'];
				$cnpj = $rows[0]['cnpj'];
				$ie = $rows[0]['ie'];
				$im = $rows[0]['im'];
				$razaosocial = $rows[0]['razaosocial'];
				$nomefantasia = $rows[0]['nomefantasia'];
				$endereco_entrega = $rows[0]['endereco_entrega'];
				$bairro_entrega = $rows[0]['bairro_entrega'];
				$cep_entrega = $rows[0]['cep_entrega'];
				$municipio_entrega = $rows[0]['municipio_entrega'];
				$telefone_entrega = $rows[0]['telefone_entrega'];
				$celular_entrega = $rows[0]['celular_entrega'];
				$endereco_cobranca = $rows[0]['endereco_cobranca'];
				$bairro_cobranca = $rows[0]['bairro_cobranca'];
				$cep_cobranca = $rows[0]['cep_cobranca'];
				$municipio_cobranca = $rows[0]['municipio_cobranca'];
				$telefone_cobranca = $rows[0]['telefone_cobranca'];
				$celular_cobranca = $rows[0]['celular_cobranca'];
				$email01 = $rows[0]['email01'];
				$email02 = $rows[0]['email02'];
				$situacao = $rows[0]['situacao'];
				$autorizado_comprar = $rows['autorizado_comprar'];
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
								Cadastro de Clientes/Fornecedores
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
								
								function cnpjPermissao() {
									global $_action, $perm_cnpj;
									
									if ($_action == "alteracao" && $perm_cnpj != "S") {
										echo "readonly";
										return;
									}
								}
							?>
							<div class="panel-body">
								<form role="form">
									<!-- BARRA DE BOTOES -->
									<div class="btn-control-bar">
										<div class="panel-heading">
											<button class="btn btn-default mob-btn-block" onclick="redirecionar('../titulos_receber/consulta.php?id=<?= urlencode($_GET['id']) ?>', 0)">
												<span class="glyphicon glyphicon-usd" aria-hidden="true"></span>
												<span class="glyphicon glyphicon-triangle-left" aria-hidden="true" style="margin-left: -7px"></span>
									 			Títulos à Receber
											</button>
											<button class="btn btn-default mob-btn-block" onclick="redirecionar('anexos.php?id=<?= urlencode($_GET['id']) ?>', 0)">
												<span class="glyphicon glyphicon-usd" aria-hidden="true"></span>
												<span class="glyphicon glyphicon-triangle-right" aria-hidden="true" style="margin-left: -7px"></span>
									 			Títulos à Pagar
											</button>
											<button class="btn btn-default mob-btn-block" onclick="redirecionar('/modulos/clientes/creditos_cliente/consulta.php?cliente=<?= urlencode($_GET['id']) ?>', 0)">
												<span class="glyphicon glyphicon-usd" aria-hidden="true"></span>
									 			Liberação de Crédito
											</button>
											<button class="btn btn-default mob-btn-block" onclick="redirecionar('anexos.php?id=<?= urlencode($_GET['id']) ?>', 0)">
												<span class="glyphicon glyphicon-file" aria-hidden="true"></span>
									 			Anexos
											</button>
										</div>
									</div>
									<!-- DADOS PRINCIPAIS -->
									<div class="row">
										<div class="form-group col-md-12">
											<h4>Dados Principais</h4>
											<hr>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-4 has-feedback" id="form-group-cnpj">
											<label for="cnpj">CPF/CNPJ: <span class="label label-danger">Obrigatório</span></label>
											<input type="text" inputmode="numeric" pattern="\([0-9]{2}\)[\s][0-9]{4}-[0-9]{4,5}" class="form-control" id="cnpj" name="cnpj" autocomplete="off" maxlength="18" value="<?= $cnpj ?>" onfocus="removerMascara();" onblur="consultarCliente();testarCpfCnpj();" <?php if ($_action == "inclusao" && empty($_GET['target'])) { echo "autofocus"; } ?> <?php permissao(); ?> <?php cnpjPermissao(); ?>>
											<a href="#" data-toggle="popover" data-content="CPF/CNPJ inválido" data-trigger="focus" data-placement="bottom" id="popover-cnpj" tabindex="99"></a>
											<h6>Digite <b>ISENTO</b> caso não houver.</h6>
										</div>
										<div class="form-group col-md-4">
											<label for="ie">Inscrição Estadual / RG:</label>
											<input type="text" class="form-control" id="ie" name="ie" autocomplete="off" maxlength="20" value="<?= $ie ?>" <?php permissao(); ?>>
											<h6>Digite <b>ISENTO</b> caso não houver.</h6>
										</div>
										<div class="form-group col-md-4">
											<label for="im">Inscrição Municipal:</label>
											<input type="text" class="form-control" id="im" name="im" autocomplete="off" maxlength="20" value="<?= $im ?>" <?php permissao(); ?>>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-6">
											<label for="razaosocial">Razão Social: <span class="label label-danger">Obrigatório</span></label>
											<input type="text" class="form-control" id="razaosocial" name="razaosocial" autocomplete="off" maxlength="80" value="<?= $razaosocial ?>" <?php if ($_action == "alteracao" && empty($_GET['target'])) { echo "autofocus"; } ?> <?php permissao(); ?> required>
										</div>
										<div class="form-group col-md-6">
											<label for="endereco">Nome Fantasia: </label>
											<input type="text" class="form-control" id="nomefantasia" name="nomefantasia" autocomplete="off" maxlength="60" value="<?= $nomefantasia ?>" <?php permissao(); ?>>
										</div>
									</div>
									<!-- ENDERECO DE ENTREGA -->
									<div class="row">
										<div class="form-group col-md-12">
											<h4>Endereço de Entrega <span class="label label-danger">Obrigatório</span></h4>
											<hr>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-5">
											<label for="endereco_entrega">Endereço: </label>
											<input type="text" class="form-control" id="endereco_entrega" name="endereco_entrega" autocomplete="off" maxlength="60" value="<?= $endereco_entrega ?>" <?php permissao(); ?>>
										</div>
										<div class="form-group col-md-4">
											<label for="bairro_entrega">Bairro: </label>
											<input type="text" class="form-control" id="bairro_entrega" name="bairro_entrega" autocomplete="off" maxlength="60" value="<?= $bairro_entrega ?>" <?php permissao(); ?>>
										</div>
										<div class="form-group col-md-3">
											<label for="cep_entrega">CEP: </label>
											<input type="text" class="form-control cep" id="cep_entrega" name="cep_entrega" autocomplete="off" data-mask="00000-000" maxlength="9" value="<?= $cep_entrega ?>" <?php permissao(); ?>>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-6">
											<div class="row">
												<div class="col-md-4">
													<label for="municipio_entrega">Código: </label >
													<div class="input-group">
														<input type="numeric" pattern="[0-9]*" class="form-control" data-mask="000000" id="municipio_entrega" name="municipio_entrega" autocomplete="off" value="<?= $municipio_entrega ?>" onblur="consultarMunicipio('#municipio_entrega', '#nome_municipio_entrega');" <?php permissao(); ?>>
														<span class="input-group-btn">
															<button class="btn btn-primary" <?php permissao(); ?> onclick="abrirConsulta('/modulos/configuracoes/municipios/consulta.php', '<?= time() . '&target=municipio_entrega'; ?>');"><span class="glyphicon glyphicon-search"></span></button>
														</span>
													</div>
												</div>
												<div class="col-md-8">
													<label for="nome_municipio_entrega">Município: </label>
													<input type="text" class="form-control" id="nome_municipio_entrega" autocomplete="off" maxlength="60" value="<?= $nome_municipio_entrega ?>"  disabled>
												</div>
											</div>
										</div>
										<div class="form-group col-md-3">
											<label for="telefone_entrega">Telefone: </label>
											<input type="text" inputmode="numeric" pattern="\([0-9]{2}\)[\s][0-9]{4}-[0-9]{4,5}" class="form-control" data-mask="(00)0000-00009" id="telefone_entrega" name="telefone_entrega" autocomplete="off" value="<?= $telefone_entrega ?>" <?php permissao(); ?> required>
										</div>
										<div class="form-group col-md-3">
											<label for="celular_entrega">Celular: </label>
											<input type="text" inputmode="numeric" pattern="\([0-9]{2}\)[\s][0-9]{4}-[0-9]{4,5}" class="form-control" data-mask="(00)0000-00009" id="celular_entrega" name="celular_entrega" autocomplete="off" value="<?= $celular_entrega ?>" <?php permissao(); ?>>
										</div>
									</div>
									<!-- ENDERECO DE COBRANCA -->
									<div class="row">
										<div class="form-group col-md-12">
											<h4>Endereço de Cobrança <span class="label label-danger">Obrigatório</span></h4>
											<hr>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-12">
											<input type="checkbox" id="checkboxCobranca" onclick="toggleCobranca();"> O endereço de cobrança é o mesmo de entrega
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-5">
											<label for="endereco_cobranca">Endereço: </label>
											<input type="text" class="form-control cobranca" id="endereco_cobranca" name="endereco_cobranca" autocomplete="off" maxlength="60" value="<?= $endereco_cobranca ?>" <?php permissao(); ?>>
										</div>
										<div class="form-group col-md-4">
											<label for="bairro_cobranca">Bairro: </label>
											<input type="text" class="form-control cobranca" id="bairro_cobranca" name="bairro_cobranca" autocomplete="off" maxlength="60" value="<?= $bairro_cobranca ?>" <?php permissao(); ?>>
										</div>
										<div class="form-group col-md-3">
											<label for="cep_cobranca">CEP: </label>
											<input type="text" class="form-control cep cobranca" id="cep_cobranca" name="cep_cobranca" data-mask="00000-000" autocomplete="off" maxlength="9" value="<?= $cep_cobranca ?>" <?php permissao(); ?>>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-6">
											<div class="row">
												<div class="col-md-4">
													<label for="municipio_cobranca">Código: </label >
													<div class="input-group">
														<input type="numeric" pattern="[0-9]*" class="form-control cobranca" data-mask="000000" id="municipio_cobranca" name="municipio_cobranca" autocomplete="off" value="<?= $municipio_cobranca ?>" onblur="consultarMunicipio('#municipio_cobranca', '#nome_municipio_cobranca');" <?php permissao(); ?>>
														<span class="input-group-btn">
															<button class="btn btn-primary cobranca" <?php permissao(); ?> onclick="abrirConsulta('/modulos/configuracoes/municipios/consulta.php', '<?= time() . '&target=municipio_cobranca'; ?>');"><span class="glyphicon glyphicon-search"></span></button>
														</span>
													</div>
												</div>
												<div class="col-md-8">
													<label for="nome_municipio_cobranca">Município: </label>
													<input type="text" class="form-control cobranca" id="nome_municipio_cobranca" autocomplete="off" maxlength="60" value="<?= $nome_municipio_cobranca ?>"  disabled>
												</div>
											</div>
										</div>
										<div class="form-group col-md-3">
											<label for="telefone_cobranca">Telefone: </label>
											<input type="text" inputmode="numeric" pattern="\([0-9]{2}\)[\s][0-9]{4}-[0-9]{4,5}" class="form-control cobranca" data-mask="(00)0000-00009" id="telefone_cobranca" name="telefone_cobranca" autocomplete="off" value="<?= $telefone_cobranca ?>" <?php permissao(); ?> required>
										</div>
										<div class="form-group col-md-3">
											<label for="celular_cobranca">Celular: </label>
											<input type="text" inputmode="numeric" pattern="\([0-9]{2}\)[\s][0-9]{4}-[0-9]{4,5}" class="form-control cobranca" data-mask="(00)0000-00009" id="celular_cobranca" name="celular_cobranca" autocomplete="off" value="<?= $celular_cobranca ?>" <?php permissao(); ?>>
										</div>
									</div>
									<!-- OUTROS -->
									<div class="row">
										<div class="form-group col-md-12">
											<h4>Outras Informações</h4>
											<hr>
										</div>
									</div>
									<div class="row">
								    	<div class="form-group col-md-4">
											<label for="email01">E-mail Principal: </label>
											<input type="email" class="form-control" id="email01" name="email01" autocomplete="off" maxlength="60" value="<?= $email01 ?>" <?php permissao(); ?>>
										</div>
										<div class="form-group col-md-4">
											<label for="email02">E-mail Secundário: </label>
											<input type="email" class="form-control" id="email02" name="email02" autocomplete="off" maxlength="60" value="<?= $email02 ?>" <?php permissao(); ?>>
										</div>
										<div class="form-group col-md-4">
											<label for="autorizado_comprar">Autorizado a Comprar: </label>
											<input type="text" class="form-control" id="autorizado_comprar" name="autorizado_comprar" autocomplete="off" maxlength="60" value="<?= $autorizado_comprar ?>" <?php permissao(); ?>>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-12">
											<label for="observacoes">Observações: </label>
											<textarea rows="4" cols="50" type="text" class="form-control" id="observacoes" name="observacoes" autocomplete="off" maxlength="500" <?php permissao(); ?>><?= $observacoes ?></textarea>
										</div>
									</div>
									<input type="hidden" id="id" name="id" value="<?= criptografar($id) ?>">
									<input type="hidden" id="_action" name="_action" value="<?= criptografar($_action) ?>">
								</form>
							</div>
						</div>
						<!-- PAINEL DE AVISO -->
						<div class="aviso">
							<?php
								if ($_action == 'inclusao' && $perm_incluir != 'S') {
									echo "<script>avisoAtencao('Sem permissão: INCLUIR CADASTRO DE CLIENTES. Solicite ao administrador a liberação.');</script>";
								}
								
								if ($_action == 'alteracao' && $perm_cnpj != 'S') {
									echo "<script>avisoAtencao('Sem permissão: ALTERAR CNPJ DO CADASTRO DE CLIENTES. Solicite ao administrador a liberação.');</script>";
								}
								
								if ($_action == 'alteracao' && $perm_alterar != 'S') {
									echo "<script>avisoAtencao('Sem permissão: ALTERAR CADASTRO DE CLIENTES. Solicite ao administrador a liberação.');</script>";
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
								<a href="consulta.php">
									<button class="btn btn-warning mob-btn-block">
										<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
										 Cancelar
									</button>
								</a>
								<button class="btn btn-danger mob-btn-block" style="<?php if ($_action == "inclusao") { echo "display: none"; } ?>" data-toggle="modal" data-target="#modal" onclick="dialogYesNo('esubmit()', null, 'Excluir Cadastro de Clientes', 'Deseja excluir o cadastro deste cliente ?', 'trash');" <?php if ($perm_excluir != 'S') { echo "disabled"; } ?>>
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
