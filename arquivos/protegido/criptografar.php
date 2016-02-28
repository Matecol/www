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
		<title>SistemaWeb | Thiago Pereira</title> 
	</head>
	<body>
		<h2>Criptografar texto</h2>
		<form action="#" method="POST">
			<h4>Digite o texto a ser criptografado:</h4>
			<input type="text" name="texto" size="40" autocomplete="off">
			<br><br>
			<input type="submit" value="Criptografar">
			<input type="hidden" name="acao" value="enc">
		</form>
		<h4>Resultado:</h4>
		<?php
			if (! empty($_POST['texto']) && $_POST['acao'] == "enc") {
				require_once BASE_DIR . '/util/criptografia.php';
				
				echo '<textarea rows="10" cols="80" readonly>' . criptografar($_POST['texto']) . '</textarea>';
			}
		?>
		<hr>
		<h2>Descriptografar texto</h2>
		<form action="#" method="POST">
			<h4>Digite o texto a ser descriptografado:</h4>
			<input type="text" name="texto" size="40" autocomplete="off">
			<br><br>
			<input type="submit" value="Descriptografar">
			<input type="hidden" name="acao" value="desc">
		</form>
		<h4>Resultado:</h4>
		<?php
			if (! empty($_POST['texto']) && $_POST['acao'] == "desc") {
				require_once BASE_DIR . '/util/criptografia.php';
				
				echo '<textarea rows="10" cols="80" readonly>' . descriptografar($_POST['texto']) . '</textarea>';
			}
		?>
	</body>
</html>