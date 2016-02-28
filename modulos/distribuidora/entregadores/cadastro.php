<?php
        // validar sessao
        require_once BASE_DIR . '/util/sessao.php';

        validarSessao();
		
		// Testar permissao
		require_once BASE_DIR . '/util/permissao.php';
		$perm_incluir = testarPermissao('INCLUIR CADASTRO DE ENTREGADORES');
		$perm_alterar = testarPermissao('ALTERAR CADASTRO DE ENTREGADORES');
		$perm_excluir = testarPermissao('EXCLUIR CADASTRO DE ENTREGADORES');

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

				$sql = "select * from entregadores where id=" . $id;
				$result = $conexao->query($sql);
			
				// Abrir resultado
				$rows = pg_fetch_all($result);
			
				if ($rows == null) {
					return;
				}
			
				$id = $rows[0]['id'];
				$nome = $rows[0]['nome'];
				$cpf = $rows[0]['cpf'];
				$telefone = $rows[0]['telefone'];
				$cnh = $rows[0]['cnh'];
				$categoria_cnh = $rows[0]['categoria_cnh'];
				$comissao = $rows[0]['comissao'];
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
								Cadastro de Entregadores
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
									    <!-- NOME -->
									    <div class="form-group col-md-6">
										    <label for="nome">Nome: <span class="label label-danger">Obrigatório</span></label>
    										<input type="text" class="form-control" id="nome" name="nome" autocomplete="off" maxlength="50" value="<?= $nome ?>" autofocus <?php permissao(); ?> required>
	    								</div>
									</div>
									<div class="row">
										<!-- CPF -->
									    <div class="form-group col-md-6">
										    <label for="cpf">CPF: <span class="label label-danger">Obrigatório</span></label>
                                            <input type="text" inputmode="numeric" pattern="\([0-9]{2}\)[\s][0-9]{4}-[0-9]{4,5}" class="form-control" id="cpf" name="cpf" autocomplete="off" maxlength="18" value="<?= $cpf ?>">
	    								</div>
										<!-- TELEFONE -->
									    <div class="form-group col-md-6">
										    <label for="telefone">Telefone: </label>
                                            <input type="text" inputmode="numeric" pattern="\([0-9]{2}\)[\s][0-9]{4}-[0-9]{4,5}" class="form-control" data-mask="(00)0000-00009" id="telefone" name="telefone" autocomplete="off" value="<?= $telefone ?>" <?php permissao(); ?>>
	    								</div>
									</div>
									<div class="row">
										<!-- CNH -->
									    <div class="form-group col-md-4">
										    <label for="cnh">CNH: </label>
    										<input type="text" class="form-control" id="cnh" name="cnh" autocomplete="off" maxlength="11" value="<?= $cnh ?>"  <?php permissao(); ?> >
	    								</div>
										<!-- CATEGORIA_CNH -->
									    <div class="form-group col-md-3">
											<label for="categoria_cnh">Combustível: </label>
											<select class="form-control" id="categoria_cnh" name="categoria_cnh" <?php permissao(); ?>>
											<option value="A" <?= ($categoria_cnh == "A") ? "selected" : "" ?>>A</option>
											<option value="B" <?= ($categoria_cnh == "B") ? "selected" : "" ?>>B</option>
											<option value="C" <?= ($categoria_cnh == "C") ? "selected" : "" ?>>C</option>
											<option value="D" <?= ($categoria_cnh == "D") ? "selected" : "" ?>>D</option>
											<option value="E" <?= ($categoria_cnh == "E") ? "selected" : "" ?>>E</option>
											<option value="AB" <?= ($categoria_cnh == "AB") ? "selected" : "" ?>>AB</option>
											<option value="AC" <?= ($categoria_cnh == "AC") ? "selected" : "" ?>>AC</option>
											<option value="AD" <?= ($categoria_cnh == "AD") ? "selected" : "" ?>>AD</option>
											<option value="AE" <?= ($categoria_cnh == "AE") ? "selected" : "" ?>>AE</option>
											</select>
										</div>
										<!-- COMISSAO -->
									    <div class="form-group col-md-4">
										    <label for="comissao">Comissão: </label>
    										<input type="text" class="form-control" id="comissao" name="comissao" autocomplete="off" value="<?= $comissao ?>"  <?php permissao(); ?> >
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
									echo "<script>avisoAtencao('Sem permissão: INCLUIR CADASTRO DE ENTREGADORES. Solicite ao administrador a liberação.');</script>";
								}
								
								if ($_action == 'alteracao' && $perm_alterar != 'S') {
									echo "<script>avisoAtencao('Sem permissão: ALTERAR CADASTRO DE ENTREGADORES. Solicite ao administrador a liberação.');</script>";
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
								<a href="consulta.php">
									<button class="btn btn-warning mob-btn-block">
										<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
										 Cancelar
									</button>
								</a>
								<button class="btn btn-danger mob-btn-block" style="<?php if ($_action == "inclusao") { echo "display: none"; } ?>" data-toggle="modal" data-target="#modal" onclick="dialogYesNo('esubmit()', null, 'Excluir Entregador', 'Deseja excluir este Entregador ?', 'trash');" <?php if ($perm_excluir != 'S') { echo "disabled"; } ?>>
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
