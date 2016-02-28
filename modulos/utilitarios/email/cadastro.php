<?php
        // validar sessao
        require_once BASE_DIR . '/util/sessao.php';

        validarSessao();
		
		// Testar permissao
		require_once BASE_DIR . '/util/permissao.php';
		$perm_incluir = testarPermissao('ENVIAR E-MAIL');
		
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
								Enviar E-Mail
							</div>
							<!-- REGRAS DE PERMISSAO -->
							<?php
								function permissao() {
									global $_action, $perm_incluir, $perm_alterar;
									
									if ($_action == "inclusao" && $perm_incluir != "S") {
										echo "readonly";
										return;
									}
							   	}
							?>
							
							<!-- VERIFICAR SE O PARAMETRO ESTA CADASTRADO, E PEGAR EMAIL DE ENVIO -->
							<?php						   
                                // Abrir nova conexão
                                $conexao = new Conexao();
   
                                $test=0;
   
                                // Pegar dados de forma hierarquica - Parametro EMAIL_PADRAO
                                // Primeiro  - verifica se existe email especifico do usuario para a empresa atual
                                $sql = "";
   						        $sql = "select * from parametros_sistema where usuario=" . $_SESSION['id'] . " and empresa=" . $_SESSION['empresa'] . " and chave='EMAIL_PADRAO'";
   							    $result = $conexao->query($sql);
			                     
   							    // Abrir resultado
   						        $rows = pg_fetch_all($result);
   
   						        if ($rows != null) {
   							        $test=1;
   							    }
   
   							    if ($test == 0){
      							    // Segundo  - verifica se existe email especifico do usuario, utilizado idependentemente da empresa.
      						        $sql = "";
      						        $sql = "select * from parametros_sistema where usuario=" . $_SESSION['id'] . " and empresa=99 and chave='EMAIL_PADRAO'";
      							    $result = $conexao->query($sql);
	
       							    //Abrir resultado
      					            $rows = pg_fetch_all($result);
   
      							    if ($rows != null) {
   	  							        $test=1;
      							    }
   							    }
   
   							    if ($test == 0){
      							    // Terceiro  - verifica se existe email padrão para a empresa atual.
      						        $sql = "";	
      						        $sql = "select * from parametros_sistema where usuario=999 and empresa=" . $_SESSION['empresa'] . " and chave='EMAIL_PADRAO'";
      							    $result = $conexao->query($sql);
			
      							    //Abrir resultado
      							    $rows = pg_fetch_all($result);
   
      							    if ($rows != null) {
   	  								    $test=1;
      							    }
   							    }
   
   							    if ($test == 0){
      							    // Quarto  - verifica se existe email padrão idependente de usuario ou empresa.
      						        $sql = "";
      						        $sql = "select * from parametros_sistema where usuario=999 and empresa=99 and chave='EMAIL_PADRAO'";
      						        $result = $conexao->query($sql);
			
      						        //Abrir resultado
      						        $rows = pg_fetch_all($result);
   
         						    if ($rows != null) {
   	     							    $test=1;
      	    					    }
   							    }
    
   	   					        //Se chegou aqui com valor 0 significa que esse parametro não esta cadastrado.
   							    if ($test == 0){
      						        http_response_code(400);
      						        echo "Parâmetro: EMAIL_PADRAO não cadastrado.";
      							    return;
   							    }
   
   							    $valor = $rows[0]['valor'];
   
   							    //Separa campos
   							    $valores = explode(",", $valor);
                           
   							    //Pega Usuário e senha do emitente
   							    $emitente = trim($valores[0]);
   							    $senha_emitente = trim($valores[1]);
							?>
							   
							<div class="panel-body">
								<form role="form">
									<!-- BARRA DE BOTOES -->
									<div class="row">
										<div class="col-md-4">
											<div class="btn-control-bar">
												<div class="panel-heading">
													<button class="btn btn-default mob-btn-block" onclick="redirecionar('anexos.php?id=<?= urlencode($_GET['id']) ?>', 0)">
														<span class="glyphicon glyphicon-file" aria-hidden="true"></span>
											 			Anexos
													</button>
												</div>
											</div>
										</div>	
										<div class="col-md-8">
											 <span><h4>Este e-mail será enviado como: <?= $emitente ?> </h4></span>
										</div>	
  									</div>
									<div class="row">
										<!-- DESTINATARIO -->
										<div class="col-md-9">
											<div class="form-group">
												<label for="destinatario">Destinatários: <span class="label label-danger">Obrigatório</span></label>
												<input type="text" class="form-control no-uppercase" id="destinatario" name="destinatario" autocomplete="off" maxlength="60" value="<?= $destinatario ?>" autofocus <?php permissao(); ?>>
												<h6>Se houver mais de um destinatário, os separe por vírgula.</h6>
											</div>				
										</div>
										<!-- CC -->
										<div class="col-md-3 cc-cco">
											<div class="row">
												<div class="form-group col-md-6">
													<label></label>
													<button class="btn btn-default mob-btn-block" onclick="$('#divCC').slideToggle();">
														<span class="glyphicon glyphicon-file" aria-hidden="true"></span>
											 			CC
													</button>
												</div>
												<div class="form-group col-md-6">
													<label></label>
													<button class="btn btn-default mob-btn-block" onclick="$('#divCCO').slideToggle();">
														<span class="glyphicon glyphicon-file" aria-hidden="true"></span>
											 			CCO
													</button>
												</div>
											</div>
										</div>
									</div>
									<div class="row" id="divCC" style="display:none;">
										<!-- DESTINATARIO CC -->
										<div class="col-md-9">
											<div class="form-group">
												<label for="destinatariocc">Destinatários CC: </label>
												<input type="text" class="form-control no-uppercase" id="destinatariocc" name="destinatariocc" autocomplete="off" maxlength="60" value="<?= $destinatariocc ?>" <?php permissao(); ?>>
												<h6>Se houver mais de um destinatário, os separe por vírgula.</h6>
											</div>				
										</div>
									</div>
									<div class="row" id="divCCO" style="display:none;">
										<!-- DESTINATARIO CCO -->
										<div class="col-md-9">
											<div class="form-group">
												<label for="destinatariobcc">Destinatários CCO: </label>
												<input type="text" class="form-control no-uppercase" id="destinatariobcc" name="destinatariobcc" autocomplete="off" maxlength="60" value="<?= $destinatariobcc ?>" <?php permissao(); ?>>
												<h6>Se houver mais de um destinatário, os separe por vírgula.</h6>
											</div>				
										</div>
									</div>
									<div class="row">
										<!-- ASSUNTO -->
										<div class="form-group col-md-12">
											<label for="assunto">Assunto: <span class="label label-danger">Obrigatório</span></label>
											<input type="text" class="form-control no-uppercase" id="assunto" name="assunto" autocomplete="off" maxlength="60" value="<?= $assunto ?>" <?php permissao(); ?>>
										</div>
									</div>	
									<div class="row">
										<!-- CORPO -->
										<div class="form-group col-md-12">
											<label for="corpo">Corpo: <span class="label label-danger">Obrigatório</span></label>
											<textarea rows="20" cols="50" type="text" class="form-control" id="corpo" name="corpo" autocomplete="off" maxlength="500" value="<?= $corpo ?>" <?php permissao(); ?>></textarea>
										</div>
									</div>
									<input type="hidden" id="id" name="id" value="<?= time() ?>">
									<input type="hidden" id="emitente" name="emitente" value="<?= $emitente ?>">
									<input type="hidden" id="senha_emitente" name="senha_emitente" value="<?= $senha_emitente ?>">
									<input type="hidden" id="_action" name="_action" value="<?= $_action ?>">
								</form>
							</div>
						</div>
						<!-- PAINEL DE AVISO -->
						<div class="aviso">
							<?php
								if ($_action == 'inclusao' && $perm_incluir != 'S') {
									echo "<script>avisoAtencao('Sem permissão: ENVIAR E-MAIL. Solicite ao administrador a liberação.');</script>";
								}
							?>
						</div>
						<!-- PAINEL DE BOTOES -->
						<div class="btn-control-bar">
							<div class="panel-heading">
								<button class="btn btn-success mob-btn-block <?php permissao(); ?>" onclick="submit('#razaosocial');" <?php permissao(); ?>>
									<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
									 Enviar
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
