<?php
        // validar sessao
        require_once BASE_DIR . '/util/sessao.php';

        validarSessao();
		
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
					<div class="col-md-3"></div>
					<div id="conteudo" class="col-xs-12 col-md-4">
						<!-- FORMULARIO -->
						<div class="panel panel-primary">
							<div class="panel-heading">
								Alterar Senha
							</div>
							<div class="panel-body">
								<form role="form">
									<div class="row">
									    <!-- SENHA ATUAL -->
									    <div class="form-group col-md-12">
										    <label for="senha_atual">Senha Atual: <span class="label label-danger">Obrigatório</span></label>
    										<input type="password" class="form-control" id="senha_atual" name="senha_atual" autocomplete="off" maxlength="20" value="<?= $senha_atual ?>" autofocus required>
	    								</div>
									</div>
									<div class="row">
										<!-- NOVA SENHA -->
									    <div class="form-group col-md-12">
										    <label for="nova_senha">Nova Senha: <span class="label label-danger">Obrigatório</span></label>
    										<input type="password" class="form-control" id="nova_senha" name="nova_senha" autocomplete="off" maxlength="20" value="<?= $nova_senha ?>" required>
	    								</div>
									</div>
									<div class="row">
										<!-- CONFIRMAÇÃO DA NOVA SENHA -->
									    <div class="form-group col-md-12">
										    <label for="confirmacao_senha">Confirme a Senha: <span class="label label-danger">Obrigatório</span></label>
    										<input type="password" class="form-control" id="confirmacao_senha" name="confirmacao_senha" autocomplete="off" maxlength="20" value="<?= $confirmacao_senha ?>" required>
	    								</div>
									</div>
									<input type="hidden" name="id" value="<?= $id ?>">
									<input type="hidden" name="_action" value="<?= $_action ?>">
								</form>
							</div>
						</div>
						<!-- PAINEL DE AVISO -->
						<div class="aviso">
						</div>
						<!-- PAINEL DE BOTOES -->
						<div class="btn-control-bar">
							<div class="panel-heading">
								<button class="btn btn-success mob-btn-block" onclick="submit('#nome');">
									<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
									 Salvar
								</button>
								<a href="<?= $_SERVER['HTTP_REFERER'] ?>">
									<button class="btn btn-warning mob-btn-block">
										<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
										 Cancelar
									</button>
								</a>
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
