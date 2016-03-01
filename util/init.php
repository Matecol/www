<?php
	// Nome do diretorio base
	define ('BASE_DIR', __DIR__ . "/..");

	// Importar
	require_once 'conf.php';
	require_once 'logs.php';
	require_once 'criptografia.php';
	
	// Setar Timezone
	date_default_timezone_set(getConfig("timezone"));

	// Handler de erros	
	handlerLogInit();
	
	// Setar variaveis de ambiente
	ini_set('upload_tmp_dir', getConfig('upload_tmp_dir'));
	ini_set('upload_max_filesize', getConfig('upload_max_filesize'));
	ini_set('max_file_uploads', getConfig('max_file_uploads'));
	
	// Certificado Digital
	define('PRIVATE_KEY', "file://" . BASE_DIR . "/" . getConfig("private_key_file"));
	define('PUBLIC_KEY', "file://" . BASE_DIR . "/" . getConfig("public_key_file"));
	
	// Banco de dados
	define('DB_SERVER', getConfig("db_server"));
	define('DB_NAME', getConfig('db_name'));
	define('DB_USER', getConfig('db_user'));
	define('DB_PASSWORD', descriptografar(getConfig('db_password')));

?>
