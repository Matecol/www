<?php
	require_once 'logs.php';
	
	// testar variavel
	if (! isset($_FILES)) {
		return;
	}
	
	$filename = $_POST['filename'];
	if (empty($filename)) {
		return;
	}
	
	if (explode("{original}", $filename)[0] != $filename) {
		$filename = str_replace("{original}", $_FILES['file']['name'], $filename);
	}
	
	// Gravar arquivo
	$dir = BASE_DIR . getConfig("upload_base_dir") . "/" . $filename;
	
	if ($_FILES['file']['error'] == UPLOAD_ERR_OK) {
		move_uploaded_file($_FILES['file']['tmp_name'], $dir);
		
		gravarLog("Arquivo '" . $dir . "' foi feito o upload com sucesso.", "INFO");
		
		echo '<script>window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	}
	
?>
