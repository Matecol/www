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
		<link rel="stylesheet" type="text/css" href="assets/css/anexos.css" />
		<script type="text/javascript" src="/assets/js/jquery.js"></script>
		<script type="text/javascript" src="/assets/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/assets/js/principal.js"></script>
		<script type="text/javascript" src="assets/js/anexos.js"></script>
		<title>SistemaWeb | Thiago Pereira</title> 
	</head>
	<body>
		<?php
			require_once BASE_DIR . '/util/criptografia.php';
		
			$enc = md5("id=" . descriptografar($_GET['id']) . "/UPLOAD");
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
						<!-- PAINEL -->
						<div class="panel panel-primary">
							<div class="panel-heading">
								Anexos do Cliente
							</div>
							<div class="panel-body">
								<!-- ENVIAR ARQUIVO -->
								<form action="/util/upload.php" method="POST" enctype="multipart/form-data">
									<div class="form">
										<div class="row">
											<div class="col-md-5">
												<div class="input-group">
													<input type="hidden" name="filename" value="clientes/<?= $enc ?>/{original}">
													<input type="file" name="file">
													<button class="btn btn-primary" onclick="enviar();">
														<span class="glyphicon glyphicon-cloud-upload"></span>
														Enviar
													</button>
												</div>
											</div>
										</div>
									</div>
								</form>
								<!-- LISTAGEM DE ARQUIVOS -->
								<table class="table table-hover table-striped tabela-registro" id="tabela">
									<thead>
										<tr>
											<th>Nome do Arquivo</th>
											<th>Tipo</th>
											<th>Tamanho</th>
										</tr>
									</thead>
									<tbody style="font-family: Courier">
										<?php										
											$dir = BASE_DIR . getConfig("upload_base_dir") . "/clientes/" . $enc;
											if (! file_exists($dir)) {
												mkdir($dir);
											}
											
											$arquivos = scandir($dir);
											
											foreach ($arquivos as $arquivo) {
												if ($arquivo == "." || $arquivo == "..") {
													continue;
												}
												echo "<tr onclick=\"download('" . getConfig("upload_base_dir") . "/clientes/" . $enc . "/" . $arquivo . "');\">";
												echo "<td>" . $arquivo . "</td>";
												
												// Extensão do arquivo
												$ext = explode(".", $arquivo);
												$extensao = strtoupper($ext[count($ext)-1]);
												if ($extensao == strtoupper($arquivo)) {
													$extensao = "-";
												}
												echo "<td>" . $extensao . "</td>";
												
												// Tamanho do arquivo
												$bytes = filesize($dir . "/" . $arquivo);
												$tam = 0;
												$sufixo = "";
												if ($bytes < 1024) {
													$sufixo = "B";
													$tam = $bytes;
												} else if (($bytes / 1024) < 1024) {
													$sufixo = "KB";
													$tam = round($bytes / 1024, 2);
												} else if ((($bytes / 1024) / 1024) < 1024) {
													$sufixo = "MB";
													$tam = round($bytes / 1024 / 1024, 2);
												}
												
												echo "<td>" . $tam . $sufixo . "</td>";
												
												echo "</tr>";
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- PAINEL DE AVISO -->
					<div class="aviso">
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
