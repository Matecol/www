<?php
        // validar sessao
        require_once BASE_DIR . '/util/sessao.php';

        validarSessao();
		
		// Testar permissao
		require_once BASE_DIR . '/util/permissao.php';
		$perm_incluir = testarPermissao('INCLUIR PARAMETROS DO SISTEMA');
		$perm_alterar = testarPermissao('ALTERAR PARAMETROS DO SISTEMA');
		$perm_excluir = testarPermissao('EXCLUIR PARAMETROS DO SISTEMA');

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
			$chave = $_GET['chave'];
			$empresa = $_GET['empresa'];
			$usuario = $_GET['usuario'];
 		
			if (!empty($chave)) {
				// Abrir nova conexão
				$conexao = new Conexao();

				$sql = "select * from parametros_sistema where chave='" . $chave . "' and empresa=" . $empresa . " and usuario=" . $usuario . ";";
				$result = $conexao->query($sql);
			
				// Abrir resultado
				$rows = pg_fetch_all($result);
			
				if ($rows == null) {
					return;
				}
			
				$chave = $rows[0]['chave'];
				$empresa = $rows[0]['empresa'];
				$usuario = $rows[0]['usuario'];
				$valor = $rows[0]['valor'];
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
								Cadastro de Parâmetros do Sistema
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
									    <!-- CHAVE -->
									    <div class="form-group col-md-6">
										    <label for="chave">Chave: <span class="label label-danger">Obrigatório</span></label>
    										<input type="text" class="form-control" id="chave" name="chave" autocomplete="off" maxlength="60" value="<?= $chave ?>" autofocus <?php permissao(); ?> <?php if ($_action == "alteracao"){ echo "readonly";} ?> required>
	    								</div>
										<!-- EMPRESA -->
									    <div class="form-group col-md-2">
										    <label for="empresa">Empresa: </label>
    										<input type="text" class="form-control" id="empresa" name="empresa" autocomplete="off" maxlength="2" value="<?= $empresa ?>" <?php permissao(); ?> <?php if ($_action == "alteracao"){ echo "readonly";} ?>>
	    								</div>
										<!-- USUARIO -->
									    <div class="form-group col-md-4">
										    <label for="usuario">Usuário: </label>
    										<input type="text" class="form-control" id="usuario" name="usuario" autocomplete="off" maxlength="60" value="<?= $usuario ?>" <?php permissao(); ?> <?php if ($_action == "alteracao"){ echo "readonly";} ?>>
	    								</div>
									</div>
									<div class="row">
		    							<!-- VALOR -->
			    						<div class="form-group col-md-12">
				    						<label for="valor">Valor: <span class="label label-danger">Obrigatório</span></label>
					    					<input type="text" class="form-control no-uppercase" id="valor" name="valor" autocomplete="off" value="<?= $valor ?>" <?php permissao(); ?> required>
						    			</div>
									</div>
									<div class="row">
						    			<!-- OBSERVACOES -->
							    		<div class="form-group col-md-12">
								     		<label for="observacoes">Observações: </label>
									     	<textarea rows="4" cols="50" type="text" class="form-control" id="observacoes" name="observacoes" autocomplete="off" maxlength="500" <?php permissao(); ?>><?= $observacoes ?></textarea>
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
									echo "<script>avisoAtencao('Sem permissão: INCLUIR PARAMETROS DO SISTEMA. Solicite ao administrador a liberação.');</script>";
								}
								
								if ($_action == 'alteracao' && $perm_alterar != 'S') {
									echo "<script>avisoAtencao('Sem permissão: ALTERAR PARAMETROS DO SISTEMA. Solicite ao administrador a liberação.');</script>";
								}
							?>
						</div>
						<!-- PAINEL DE BOTOES -->
						<div class="btn-control-bar">
							<div class="panel-heading">
								<button class="btn btn-success mob-btn-block <?php permissao(); ?>" onclick="submit('#chave');" <?php permissao(); ?>>
									<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
									 Salvar
								</button>
								<a href="<?= $_SERVER['HTTP_REFERER'] ?>">
									<button class="btn btn-warning mob-btn-block">
										<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
										 Cancelar
									</button>
								</a>
								<button class="btn btn-danger mob-btn-block" style="<?php if ($_action == "inclusao") { echo "display: none"; } ?>" data-toggle="modal" data-target="#modal" onclick="dialogYesNo('esubmit()', null, 'Excluir Parâmetro do Sistema', 'Deseja excluir este parâmetro ?', 'trash');" <?php if ($perm_excluir != 'S') { echo "disabled"; } ?>>
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
