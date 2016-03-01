<?php
        // validar sessao
        require_once BASE_DIR . '/util/sessao.php';

        validarSessao();
		
		// Testar permissao
		require_once BASE_DIR . '/util/permissao.php';
		$perm_incluir = testarPermissao('INCLUIR CADASTRO DE EMPRESAS');
		$perm_alterar = testarPermissao('ALTERAR CADASTRO DE EMPRESAS');
		$perm_excluir = testarPermissao('EXCLUIR CADASTRO DE EMPRESAS');

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

				$sql = "select * from empresas where id=" . $id;
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
				$endereco = $rows[0]['endereco'];
				$bairro = $rows[0]['bairro'];
				$cep = $rows[0]['cep'];
				$municipio = $rows[0]['municipio'];
				$telefone = $rows[0]['telefone'];
				$fax = $rows[0]['fax'];
				$email = $rows[0]['email'];
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
								Cadastro de Empresas
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
								    		<label for="razaosocial">Razão Social: <span class="label label-danger">Obrigatório</span></label>
									    	<input type="text" class="form-control" id="razaosocial" name="razaosocial" autocomplete="off" maxlength="60" value="<?= $razaosocial ?>" <?php permissao(); ?> required>
									    </div>
										<!-- NOME FANTASIA -->
									    <div class="form-group col-md-6">
										    <label for="nomefantasia">Nome Fantasia: <span class="label label-danger">Obrigatório</span></label>
    										<input type="text" class="form-control" id="nomefantasia" name="nomefantasia" autocomplete="off" maxlength="60" value="<?= $nomefantasia ?>" <?php permissao(); ?> required>
	    								</div>
									</div>
									<div class="row">
										<!-- CNPJ -->
										<div class="form-group col-md-4">
			    							<label for="cnpj">CNPJ: <span class="label label-danger">Obrigatório</span></label>
				    					    <input type="text" inputmode="numeric" pattern="[0-9]*" class="form-control cnpj" id="cnpj" name="cnpj" autocomplete="off" value="<?= $cnpj ?>" autofocus <?php permissao(); ?> required>
					    				</div>
										<!-- INSCRICAO ESTADUAL -->
    									<div class="form-group col-md-4">
	    									<label for="ie">Incrição Estadual: <span class="label label-danger">Obrigatório</span></label>
		    							    <input type="text" inputmode="numeric" pattern="[0-9]*" class="form-control ie" id="ie" name="ie" autocomplete="off" min="0" max="999999" value="<?= $ie ?>" <?php permissao(); ?> required>
			     						</div>
										 <!-- INSCRICAO MUNICIPAL -->
				    					<div class="form-group col-md-4">
					    					<label for="im">Inscrição Municipal: </label>
						    			    <input type="text" inputmode="numeric" pattern="[0-9]*" class="form-control" id="im" name="im" autocomplete="off" min="0" max="999999" value="<?= $im ?>" <?php permissao(); ?>>
							    		</div>
									</div>
									<div class="row">
										<!-- ENDERECO -->
										<div class="form-group col-md-6">
									    	<label for="endereco">Endereço: <span class="label label-danger">Obrigatório</span></label>
										    <input type="text" class="form-control" id="endereco" name="endereco" autocomplete="off" maxlength="60" value="<?= $endereco ?>" <?php permissao(); ?> required>
    									</div>
										<!-- BAIRRO -->
    									<div class="form-group col-md-6">
    										<label for="bairro">Bairro: <span class="label label-danger">Obrigatório</span></label>
	     									<input type="text" class="form-control" id="bairro" name="bairro" autocomplete="off" maxlength="60" value="<?= $bairro ?>" <?php permissao(); ?> required>
		    							</div>			    						
									</div>
								    <div class="row">
										<!-- CEP -->	
										<div class="form-group col-md-6">
				    						<label for="cep">CEP: <span class="label label-danger">Obrigatório</span></label>
					     				    <input type="text" inputmode="numeric" pattern="[0-9]*" class="form-control cep" id="cep" name="cep" autocomplete="off" value="<?= $cep ?>" <?php permissao(); ?> required>
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
												<!-- DESCRICAO MUNICIPIO -->
												<div class="col-md-8">
													<label for="nome_municipio">Município: </label>
													<input type="text" class="form-control" id="nome_municipio" autocomplete="off" maxlength="60" value="<?= $nome_municipio ?>"  disabled>
												</div>
											</div>
										</div>
								    </div>
									<div class="row">
										<!-- TELEFONE -->
    									<div class="form-group col-md-6">
	    									<label for="telefone">Telefone: <span class="label label-danger">Obrigatório</span></label>
		    							    <input type="text" inputmode="numeric" pattern="[0-9]*" class="form-control telefone" id="telefone" name="telefone" autocomplete="off" value="<?= $telefone ?>" <?php permissao(); ?> required>
			    						</div>
										<!-- FAX -->
				     					<div class="form-group col-md-6">
					    					<label for="fax">Fax: </label>
						    			    <input type="text" inputmode="numeric" pattern="[0-9]*" class="form-control telefone" id="fax" name="fax" autocomplete="off" value="<?= $fax ?>" <?php permissao(); ?>>
							    		</div>
									</div>
									<div class="row">
										<!-- E-MAIL -->
										<div class="form-group col-md-6">
											<label for="email">E-Mail: </label>
											<input type="text" class="form-control" id="email" name="email" autocomplete="off" maxlength="60" value="<?= $email ?>" <?php permissao(); ?>>
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
									echo "<script>avisoAtencao('Sem permissão: INCLUIR CADASTRO DE EMPRESAS. Solicite ao administrador a liberação.');</script>";
								}
								
								if ($_action == 'alteracao' && $perm_alterar != 'S') {
									echo "<script>avisoAtencao('Sem permissão: ALTERAR CADASTRO DE EMPRESAS. Solicite ao administrador a liberação.');</script>";
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
								<button class="btn btn-danger mob-btn-block" style="<?php if ($_action == "inclusao") { echo "display: none"; } ?>" data-toggle="modal" data-target="#modal" onclick="dialogYesNo('esubmit()', null, 'Excluir Empresa', 'Deseja excluir este empresa ?', 'trash');" <?php if ($perm_excluir != 'S') { echo "disabled"; } ?>>
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
