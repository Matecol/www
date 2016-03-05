<?php
        // validar sessao
        require_once BASE_DIR . '/util/sessao.php';
        validarSessao();
		
		// Testar permissao
		require_once BASE_DIR . '/util/permissao.php';
		$perm_incluir = testarPermissao('INCLUIR CADASTRO DE GERENCIADOR');
		$perm_alterar = testarPermissao('ALTERAR CADASTRO DE GERENCIADOR');
		$perm_excluir = testarPermissao('EXCLUIR CADASTRO DE GERENCIADOR');
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
				$sql = "select * from gerenciador where id=" . $id;
				$result = $conexao->query($sql);
			
				// Abrir resultado
				$rows = pg_fetch_all($result);
			
				if ($rows == null) {
					return;
				}
			
				$id = $rows[0]['id'];
				$empresa = $rows[0]['empresa'];
                $conta = $rows[0]['conta'];
                $data = $rows[0]['data'];
                $saldo_inicial = $rows[0]['saldo_inicial'];
                $saldo_abertura = $rows[0]['saldo_abertura'];
                $valor_entrada = $rows[0]['valor_entrada'];
                $valor_saida = $rows[0]['valor_saida'];
                $saldo_encerramento = $rows[0]['saldo_encerramento'];
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
								Cadastro de Gerenciador
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
										<!-- EMPRESA -->
										<div class="form-group col-md-6">
											<div class="row">
												<div class="col-md-4">
													<!-- CODIGO EMPRESA -->
													<label for="empresa">Empresa: </label >
													<div class="input-group">
														<input type="numeric" pattern="[0-9]*" class="form-control" id="empresa" name="empresa" data-mask="00000" autocomplete="off" value="<?= $empresa ?>" onblur="consultarEmpresa();" <?php permissao(); ?>>
														<span class="input-group-btn">
															<button class="btn btn-primary" <?php permissao(); ?> onclick="abrirConsulta('/modulos/configuracoes/empresas/consulta.php', '<?= time(); ?>');"><span class="glyphicon glyphicon-search"></span></button>
														</span>
													</div>
												</div>
												<!-- DESCRICAO EMPRESA -->
												<div class="col-md-8">
													<label for="nome_empresa">Nome da Empresa: </label>
													<input type="text" class="form-control" id="nome_empresa" autocomplete="off" maxlength="60" value="<?= $nome_empresa ?>"  disabled>
												</div>
											</div>
										</div>
                                        <!-- CONTA -->
										<div class="form-group col-md-6">
											<div class="row">
												<div class="col-md-4">
													<!-- CODIGO CONTA -->
													<label for="conta">Conta: </label >
													<div class="input-group">
														<input type="numeric" pattern="[0-9]*" class="form-control" id="conta" name="conta" data-mask="00000" autocomplete="off" value="<?= $conta ?>" onblur="consultarConta();" <?php permissao(); ?>>
														<span class="input-group-btn">
															<button class="btn btn-primary" <?php permissao(); ?> onclick="abrirConsulta('/modulos/configuracoes/linhas/consulta.php', '<?= time(); ?>');"><span class="glyphicon glyphicon-search"></span></button>
														</span>
													</div>
												</div>
												<!-- DESCRICAO LINHA -->
												<div class="col-md-8">
													<label for="nome_linha">Nome da Linha: </label>
													<input type="text" class="form-control" id="nome_linha" autocomplete="off" maxlength="60" value="<?= $nome_linha ?>"  disabled>
												</div>
											</div>
										</div> 
                                     </div>
                                     <div class="row">
                                        <!-- Saldo Abertura -->
									    <div class="form-group col-md-4">
										    <label for="saldo_abertura">Saldo de Abertura: <span class="label label-danger">Obrigatório</span></label>
										    <input type="text" class="form-control" id="saldo_abertura" name="saldo_abertura" autocomplete="off" maxlength="60" value="<?= $saldo_abertura ?>" autofocus <?php permissao(); ?> required>
									    </div>                                      
                                        <!-- Saldo inicial -->
									    <div class="form-group col-md-4">
										    <label for="saldo_inicial">Saldo Inicial: <span class="label label-danger">Obrigatório</span></label>
										    <input type="text" class="form-control" id="saldo_inicial" name="saldo_inicial" autocomplete="off" maxlength="60" value="<?= $saldo_inicial ?>" autofocus <?php permissao(); ?> required>
									    </div>                                        
                                        <!-- valor de entrada -->
									    <div class="form-group col-md-4">
										    <label for="valor_entrada">Valor de Entrada: <span class="label label-danger">Obrigatório</span></label>
										    <input type="text" class="form-control" id="valor_entrada" name="valor_entrada" autocomplete="off" maxlength="60" value="<?= $valor_entrada ?>" autofocus <?php permissao(); ?> required>
									    </div>
                                     </div>
                                     <div class="row">
                                        <!-- valor de saida -->
									    <div class="form-group col-md-4">
										    <label for="valor_saida">Valor de Saida: <span class="label label-danger">Obrigatório</span></label>
										    <input type="text" class="form-control" id="valor_saida" name="valor_saida" autocomplete="off" maxlength="60" value="<?= $valor_saida ?>" autofocus <?php permissao(); ?> required>
									    </div>
                                        <!-- saldo encerramento -->
									    <div class="form-group col-md-4">
										    <label for="saldo_encerramento">Saldo Encerramento: <span class="label label-danger">Obrigatório</span></label>
										    <input type="text" class="form-control" id="saldo_encerramento" name="saldo_encerramento" autocomplete="off" maxlength="60" value="<?= $saldo_encerramento ?>" autofocus <?php permissao(); ?> required>
									    </div>
                                        <!-- Data -->
									    <div class="form-group col-md-4">
										    <label for="data">Data: <span class="label label-danger">Obrigatório</span></label>
										    <input type="text" class="form-control" id="data" name="data" autocomplete="off" maxlength="60" value="<?= $data ?>" autofocus <?php permissao(); ?> required>
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
									echo "<script>avisoAtencao('Sem permissão: INCLUIR CADASTRO DE GERENCIADOR. Solicite ao administrador a liberação.');</script>";
								}
								
								if ($_action == 'alteracao' && $perm_alterar != 'S') {
									echo "<script>avisoAtencao('Sem permissão: ALTERAR CADASTRO DE GERENCIADOR. Solicite ao administrador a liberação.');</script>";
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
								<button class="btn btn-danger mob-btn-block" style="<?php if ($_action == "inclusao") { echo "display: none"; } ?>" data-toggle="modal" data-target="#modal" onclick="dialogYesNo('esubmit()', null, 'Excluir empresa', 'Deseja excluir este empresa ?', 'trash');" <?php if ($perm_excluir != 'S') { echo "disabled"; } ?>>
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