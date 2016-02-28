<?php

	// destruir sessoes anteriores
	@session_start();
	@session_destroy();
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
		<link rel="stylesheet" type="text/css" href="/assets/css/login.css" />
		<script type="text/javascript" src="/assets/js/jquery.js"></script>
		<script type="text/javascript" src="/assets/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/assets/js/principal.js"></script>
		<script type="text/javascript" src="/assets/js/login.js"></script>
		<title>SistemaWeb | Thiago Pereira</title> 
	</head>
	<body>
		<!-- CONTEUDO -->
		<div class="wrapper" role="main">
			<div class="container">
				<!-- LOGO -->
				<div class="row">
					<div class="col-md-4"></div>
					<div class="col-md-4">
						<img class="media-object" src="assets/imagens/logo.png"></img>
					</div>
					<div class="col-md-4"></div>
				</div>
				<div class="row">
					<!-- AREA DE CONTEUDO -->
					<div class="col-md-4"></div>
					<div id="conteudo" class="col-md-4">
						<div class="panel panel-primary" id="painel-login">
							<!-- CABECALHO -->
							<div class="panel-heading">
								<span class="glyphicon glyphicon-lock"></span>
								Login - SistemaWeb
							</div>
							<!-- CORPO -->
							<div class="panel-body">
								<form action="login.php" method="POST" role="form">
									<div class="row">
										<div class="col-md-12">
											<div align="center">
												<img class="avatar" id="avatar" src="/uploads/avatar/avatar.png">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<div class="input-group">
													<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
													<input class="form-control" type="text" maxlength="20" id="login" name="login" autocomplete="off" placeholder="USUÁRIO" onblur="carregarAvatar();" autofocus>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<div class="input-group">
													<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
													<input class="form-control" type="password" maxlength="20" name="senha" autocomplete="off" placeholder="SENHA">
												</div>
											</div>
										</div>
									</div>
									<!-- PAINEL DE AVISO -->
									<div class="aviso">
									</div>
									<!-- BOTAO DE LOGAR -->
									<div class="form-group">
										<button class="btn btn-success mob-btn-block" style="width: 100%" onclick="fullscreen();">
											<span class="glyphicon glyphicon-share-alt"></span>
											Entrar
										</button>
									</div>
									<input type="hidden" name="_action" value="login">
								</form>
							</div>
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
			</div>
		</div>
		<!-- RODAPE -->
		<footer>
			<div class="container">
				<div class="row">
					
				</div>
			</div>
		</footer>
	</body>
</html>
<?php
	if ($_POST['_action'] == "login") {

		/* fazer login */
		require_once 'util/conexao.php';
		require_once 'util/util.php';
		require_once 'util/sessao.php';

		$login = tratarTexto($_POST['login']);
		$senha = tratarTexto($_POST['senha']);

		function logar($login, $senha) {
		
			if (empty($login)) {
				return "Informe o login";
			}
		
			if (empty($senha)) {
				return "Informe a senha";
			}
		
			// validar login
			$conexao = new Conexao();
		
			$sql = "select * from usuarios where login='" . $login . "' and senha='" . sha1($senha) . "';";
			$result = $conexao->query($sql);

			if (! pg_num_rows($result) > 0) {
				return "Login ou Senha incorretos.";
			}
		
			// IP do cliente e User-Agent
			$ip = $_SERVER['REMOTE_ADDR'];
			$useragent = $_SERVER['HTTP_USER_AGENT'];
		
			// Gravar id do usuario e data/hora
			$rows = pg_fetch_all($result);
		
			$id = $rows[0]['id'];
			$_SESSION['id'] = $id;
		
			// Empresa vinculada ao usuario
			$empresa = $rows[0]['empresa'];
			if (! empty($empresa)) {
				$_SESSION['empresa'] = $empresa;
			}
		
			// Gerar checksum
			$checksum = sha1("SISTEMAWEB?user=" . $id . "&timestamp=" . time() . "/PHP");
			$_SESSION['checksum'] = $checksum; 
				
			// Criar sessao
			$sql = "insert into sessoes (usuario, useragent, ip, checksum) values (" . $id . ", '" . $useragent . "', '" . $ip . "', '" . $checksum . "');";
		
			$flag = 0;
			$result = $conexao->query($sql) or $flag = 1;
		
			// Validar sessao
			$validacao = validarSessao();
			if ($flag == 1 || $validacao == 1) {
				return "Falha ao criar sessão. Tente novamente mais tarde ou contate o suporte.";
			}
		
			// Pegar empresa se nao tiver
			if ($validacao == 2) {
				return "OK-EMPR";
			} else {
				return "OK";
			}
	
		}
	
		// logar
		$msg = logar($login, $senha);
	
		switch($msg) {
			case "OK"; echo "<script>redirecionar('/', 0);</script>";break;
			case "OK-EMPR"; echo "<script>redirecionar('/empresa.php', 0);</script>";break;
			default: echo "<script>avisoErro('" . $msg . "');</script>"; 
		}
	}
?>