<?php
        // validar sessao
        require_once BASE_DIR . '/util/sessao.php';

        validarSessao();
		
		// Testar permissao
		require_once BASE_DIR . '/util/permissao.php';
		$perm_incluir = testarPermissao('INCLUIR CADASTRO DE VEICULOS');
		$perm_alterar = testarPermissao('ALTERAR CADASTRO DE VEICULOS');
		$perm_excluir = testarPermissao('EXCLUIR CADASTRO DE VEICULOS');

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

				$sql = "select * from veiculos where id=" . $id;
				$result = $conexao->query($sql);
			
				// Abrir resultado
				$rows = pg_fetch_all($result);
			
				if ($rows == null) {
					return;
				}
			
				$id = $rows[0]['id'];
				$placa = $rows[0]['placa'];
				$municipio_placa = $rows[0]['municipio_placa'];
				$uf_placa = $rows[0]['uf_placa'];
				$descricao = $rows[0]['descricao'];
				$renavan = $rows[0]['renavan'];
				$chassi = $rows[0]['chassi'];
				$marca = $rows[0]['marca'];
				$modelo = $rows[0]['modelo'];
				$ano_modelo = $rows[0]['ano_modelo'];
				$ano_fabricacao = $rows[0]['ano_fabricacao'];
				$cor = $rows[0]['cor'];
				$combustivel = $rows[0]['combustivel'];
				$tipo = $rows[0]['tipo'];
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
								Cadastro de Veículos
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
										<!-- DESCRICAO -->
									    <div class="form-group col-md-12">
										    <label for="descricao">Descricao: <span class="label label-danger">Obrigatório</span></label>
    										<input type="text" class="form-control" id="descricao" name="descricao" autocomplete="off" maxlength="50" value="<?= $descricao ?>" autofocus <?php permissao(); ?> required>
	    								</div>
									</div>
									<div class="row">
									    <!-- PLACA -->
									    <div class="form-group col-md-4">
										    <label for="placa">Placa: </label>
    										<input type="text" class="form-control" id="placa" name="placa" autocomplete="off" data-mask="SSS-0000" value="<?= $placa ?>"  <?php permissao(); ?> >
	    								</div>
										<!-- MUNICIPIO DA PLACA -->
									    <div class="form-group col-md-6">
										    <label for="municipio_placa">Município: </label>
    										<input type="text" class="form-control" id="municipio_placa" name="municipio_placa" autocomplete="off" maxlength="50" value="<?= $municipio_placa ?>"  <?php permissao(); ?> >
	    								</div>
										<!-- UF DA PLACA -->
									    <div class="form-group col-md-2">
										    <label for="uf_placa">UF: </label>
    										<input type="text" class="form-control" id="uf_placa" name="uf_placa" autocomplete="off" maxlength="2" value="<?= $uf_placa ?>"  <?php permissao(); ?> >
	    								</div>
									</div>
									<div class="row">
										<!-- RENAVAN -->
									    <div class="form-group col-md-6">
										    <label for="renavan">Renavan: </label>
    										<input type="text" class="form-control" id="renavan" name="renavan" autocomplete="off" maxlength="30" value="<?= $renavan ?>"  <?php permissao(); ?> >
	    								</div>
										<!-- CHASSI -->
									    <div class="form-group col-md-6">
										    <label for="chassi">Chassi: </label>
    										<input type="text" class="form-control" id="chassi" name="chassi" autocomplete="off" maxlength="30" value="<?= $chassi ?>"  <?php permissao(); ?> >
	    								</div>
									</div>
									<div class="row">
										<!-- MARCA -->
									    <div class="form-group col-md-6">
										    <label for="marca">Marca: </label>
    										<input type="text" class="form-control" id="marca" name="marca" autocomplete="off" maxlength="30" value="<?= $marca ?>"  <?php permissao(); ?> >
	    								</div>
										<!-- MODELO -->
									    <div class="form-group col-md-6">
										    <label for="modelo">Modelo: </label>
    										<input type="text" class="form-control" id="modelo" name="modelo" autocomplete="off" maxlength="30" value="<?= $modelo ?>"  <?php permissao(); ?> >
	    								</div>
									</div>
									<div class="row">
										<!-- ANO FABRICACAO -->
									    <div class="form-group col-md-3">
										    <label for="ano_fabricacao">Ano Fabricação: </label>
										    <input type="text" inputmode="numeric" pattern="[0-9]*" class="form-control" id="ano_fabricacao" name="ano_fabricacao" data-mask="0000" autocomplete="off" value="<?= $ano_fabricacao ?>" <?php permissao(); ?>>
	    								</div>
										<!-- ANO MODELO -->
									    <div class="form-group col-md-3">
										    <label for="ano_modelo">Ano Modelo: </label>
										    <input type="text" inputmode="numeric" pattern="[0-9]*" class="form-control" id="ano_modelo" name="ano_modelo" data-mask="0000" autocomplete="off" value="<?= $ano_modelo ?>" <?php permissao(); ?>>
	    								</div>
										<!-- COR -->
									    <div class="form-group col-md-3">
										    <label for="cor">Cor: </label>
    										<input type="text" class="form-control" id="cor" name="cor" autocomplete="off" maxlength="60" value="<?= $cor ?>"  <?php permissao(); ?> >
	    								</div>
										<!-- COMBUSTIVEL -->
									    <div class="form-group col-md-3">
											<label for="combustivel">Combustível: </label>
											<select class="form-control" id="combustivel" name="combustivel" <?php permissao(); ?>>
											<option value="1" <?= ($combustivel == "1") ? "selected" : "" ?>>Gasolina</option>
											<option value="2" <?= ($combustivel == "2") ? "selected" : "" ?>>Etanol</option>
											<option value="3" <?= ($combustivel == "3") ? "selected" : "" ?>>Diesel</option>
											<option value="4" <?= ($combustivel == "4") ? "selected" : "" ?>>Flex</option>
											<option value="5" <?= ($combustivel == "5") ? "selected" : "" ?>>Gás</option>
											</select>
										</div>
									</div>
									<div class="row">
										<!-- TIPO -->
									    <div class="form-group col-md-3">
											<label for="tipo">Combustível: </label>
											<select class="form-control" id="tipo" name="tipo" <?php permissao(); ?>>
											<option value="1" <?= ($tipo == "1") ? "selected" : "" ?>>Moto</option>
											<option value="2" <?= ($tipo == "2") ? "selected" : "" ?>>Carro</option>
											<option value="3" <?= ($tipo == "3") ? "selected" : "" ?>>Caminhão</option>
											<option value="4" <?= ($tipo == "4") ? "selected" : "" ?>>Carreta</option>
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
									<input type="hidden" name="id" value="<?= $id ?>">
									<input type="hidden" name="_action" value="<?= $_action ?>">
								</form>
							</div>
						</div>
						<!-- PAINEL DE AVISO -->
						<div class="aviso">
							<?php
								if ($_action == 'inclusao' && $perm_incluir != 'S') {
									echo "<script>avisoAtencao('Sem permissão: INCLUIR CADASTRO DE VEICULOS. Solicite ao administrador a liberação.');</script>";
								}
								
								if ($_action == 'alteracao' && $perm_alterar != 'S') {
									echo "<script>avisoAtencao('Sem permissão: ALTERAR CADASTRO DE VEICULOS. Solicite ao administrador a liberação.');</script>";
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
								<button class="btn btn-danger mob-btn-block" style="<?php if ($_action == "inclusao") { echo "display: none"; } ?>" data-toggle="modal" data-target="#modal" onclick="dialogYesNo('esubmit()', null, 'Excluir Veículo', 'Deseja excluir este Veículo ?', 'trash');" <?php if ($perm_excluir != 'S') { echo "disabled"; } ?>>
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
