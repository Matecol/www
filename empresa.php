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
		<link rel="stylesheet" type="text/css" href="/assets/css/empresa.css" />
		<script type="text/javascript" src="/assets/js/jquery.js"></script>
		<script type="text/javascript" src="/assets/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/assets/js/principal.js"></script>
		<script type="text/javascript" src="/assets/js/empresa.js"></script>
		<title>SistemaWeb | Thiago Pereira</title> 
	</head>
	<body>
	<?php
		// selecionar empresa
		if ($_POST['_action'] == "selecionar") {
			@session_start();
			
			// Testar permissao
			require_once 'util/permissao.php';
			$perm = "ACESSAR A EMPRESA - " . ((intval($_POST['empresa']) < 10) ? "0" . $_POST['empresa'] : $_POST['empresa']);
			$vperm = testarPermissao($perm);
			if ($vperm != "S") {
				// Sem permissao
				if ($vperm != "S") {
					echo "<script>dialogSemPermissao('" . $perm . "');</script>";
				}
			} else {
				// setar empresa
				$_SESSION['empresa'] = $_POST['empresa'];
		
				require_once 'util/sessao.php';
				validarSessao();
	
				// redirecionar
				echo "<script>redirecionar('/', 0);</script>";
			
				return;
			}
			
		}
	
		// Essa pagina deve ser somente chamada pelo "login.php"
		if ($_POST['_action'] != "selecionar") {
			if (empty($_SERVER['HTTP_REFERER'])) {
				echo "<script>redirecionar('/login.php', 0);</script>";
				return;
			}
	
			$str = explode("/", $_SERVER['HTTP_REFERER']);
			$pagina = $str[count($str)-1];
	
			if ($pagina != "login.php") {
				echo "<script>redirecionar('/login.php', 0);</script>";
				return;
			}
		}
	?>
		<!-- CONTEUDO -->
		<div class="wrapper" role="main">
			<div class="container">
				<div class="row">
					<!-- AREA DE CONTEUDO -->
					<div class="col-md-2"></div>
					<div id="conteudo" class="col-md-8">
						<div class="panel panel-primary" id="painel-empresas">
							<!-- CABECALHO -->
							<div class="panel-heading">
								<span class="glyphicon glyphicon-lock"></span>
								Selecione a Empresa - SistemaWeb
							</div>
							<!-- CORPO -->
							<div class="panel-body">
								<form action="empresa.php" method="POST" role="form" id="tabela">
									<table class="table table-hover table-striped tabela-registro">
										<thead>
											<tr>
												<th>LJ</th>
												<th>Razão Social</th>
												<th>Cidade</th>
											</tr>
										</thead>
										<tbody>
											<?php
												require_once 'util/conexao.php';
												
												$conexao = new Conexao();
												
												$sql = "select A.*, B.municipio as nome_municipio from empresas A join municipios B on A.municipio = B.id;";
												$result = $conexao->query($sql);
												
												// Listar empresas
												$rows = pg_fetch_all($result);
												if ($rows != null) {
													foreach ($rows as $row) {
														echo "<tr onclick=\"selecionarEmpresa('" . $row['id'] . "');\">";
														echo "<td>" . (intval($row['id']) < 10 ? "0" . $row['id'] : $row['id']) . "</td>";
														echo "<td>" . $row['razaosocial'] . "</td>";
														echo "<td>" . $row['nome_municipio'] . "</td>";
														echo "</tr>";
													}
												}
											
											?>
										</tbody>
									</table>
									<input type="hidden" name="empresa" id="empresa">
									<input type="hidden" name="_action" value="selecionar">
								</form>
							</div>
						</div>
					</div>
					<div class="col-md-2"></div>
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
