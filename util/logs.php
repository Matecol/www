<?php
	require_once 'conf.php';
	
	function gravarLog($msg, $level) {
		// Testar nivel de log
		$levels = getConfig("log_levels");
		$ls = explode(",", $levels);
		$flag = FALSE;
		foreach ($ls as $l) {
			if ($level == trim($l)) {
				$flag = TRUE;
				break;
			}
		}
		
		if (! $flag) {
			return;
		}
		
		// Nome da pasta
		$dir = BASE_DIR . "/logs/" . date("Y/m/d");
		if (file_exists($dir) == FALSE) {
			mkdir($dir, 0777, true);
		}

		// Abrir arquivo e gravar
		$arq = fopen($dir . "/log.txt", "a");
		
		fwrite($arq, "Data/Hora....: " . date("d/m/Y H:i:s") . "\n");
		fwrite($arq, utf8_decode("Nível do Erro: " . $level . "\n"));
		fwrite($arq, "IP Remoto....: " . $_SERVER['REMOTE_ADDR'] . "\n");
		fwrite($arq, utf8_decode("Página.......: " . $_SERVER['REQUEST_URI'] . "\n"));
		fwrite($arq, utf8_decode("Descrição....:\n\n"));
		fwrite($arq, $msg . "\n");
		fwrite($arq, "=========================================================================================\n");

		// Fechar arquivo
		fclose($arq);
		
		// Enviar por e-mail
		$levels = getConfig("log_email_levels");
		$ls = explode(",", $levels);
		$flag = FALSE;
		foreach ($ls as $l) {
			if ($level == trim($l)) {
				$flag = TRUE;
				break;
			}
		}
		
		if (! $flag) {
			return;
		}
		
		$mail = new EnviarLogEmail($msg, $level);
		$mail->enviar();
		
	}
	
	// Thread para enviar e-mail
	class EnviarLogEmail {
		
		var $msg;
		var $level;
		
		public function __construct($msg, $level) {
			$this->msg = $msg;
			$this->level = $level;
		}
		
		public function enviar() {
			require_once 'mail/mail.php';
			
			// Criar e-mail
			$mail = new Mail();
			
			$usuariosenha = getConfig("log_email_from");
			$usuario = explode(",", $usuariosenha)[0];
			$senha = explode(",", $usuariosenha)[1];
			$mail->setUsuario($usuario, $senha);
			
			foreach (explode(",", getConfig("log_email_to")) as $email) {
				$mail->addDestinatario($email);
			}
			
			$mail->setAssunto(utf8_decode("Erro no sistema de nível " . $this->level));
			$mail->setCorpo(
				"<html><head><style>* { font-family: Courier; }</style></head><body>" .
				"Data/Hora....: " . date("d/m/Y H:i:s") . "<br>" .
				utf8_decode("Nível do Erro: ") . $this->level . "<br>" .
				"IP Remoto....: " . $_SERVER['REMOTE_ADDR'] . "<br>" .
				utf8_decode("Página.......: ") . $_SERVER['REQUEST_URI'] . "<br>" .
				utf8_decode("Descrição....:") . "<br><br>" .
				$this->msg . "<br></body></html>"
			);
			$mail->enviar();
			
		}
		
	}
	
	// Mostrar log
	function mostrarLog() {
		// Nome da pasta
		$dir = BASE_DIR . "/logs/" . date("Y/m/d");
		if (file_exists($dir) == FALSE) {
			mkdir($dir, 0777, true);
		}

		// Abrir arquivo e gravar
		$narq = $dir . "/log.txt";
		if (! file_exists($narq)) {
			return;
		}
		
		$arq = fopen($narq, "r");
		
		$log = "";
		$count = 0;
		while (! feof($arq)) {
			$linha = fgets($arq, 4096);
			$log .= $linha;
			if ($linha == "=========================================================================================\n") {
				$count++;
			}
		}
		
		// fechar arquivo
		fclose($arq);
		
		// retornar texto
		return utf8_encode($log) . "\nForam encontrados " . $count . " erros.\n";
	}
	
	function contarErrosLog() {
		// Nome da pasta
		$dir = BASE_DIR . "/logs/" . date("Y/m/d");
		if (file_exists($dir) == FALSE) {
			mkdir($dir, 0777, true);
		}

		// Abrir arquivo e gravar
		$narq = $dir . "/log.txt";
		if (! file_exists($narq)) {
			return;
		}
		
		$arq = fopen($narq, "r");
		
		$count = 0;
		while (! feof($arq)) {
			$linha = fgets($arq, 4096);
			if ($linha == "=========================================================================================\n") {
				$count++;
			}
		}
		
		// fechar arquivo
		fclose($arq);
		
		// retornar texto
		return $count;
	}
	
	// handler
	function handlerLog($errno, $errstr, $errfile, $errline) {
		$level = "";
		switch ($errno) {
			case E_ERROR: $level = "E_ERROR";break;
			case E_WARNING: $level = "E_WARNING";break;
			case E_PARSE: $level = "E_PARSE";break;
			case E_NOTICE: $level = "E_NOTICE";break;
			case E_CORE_ERROR: $level = "E_CORE_ERROR";break;
			case E_CORE_WARNING: $level = "E_CORE_WARNING";break;
			case E_COMPILE_ERROR: $level = "E_COMPILE_ERROR";break;
			case E_COMPILE_WARNING: $level = "E_COMPILE_WARNING";break;
			case E_USER_ERROR: $level = "E_USER_ERROR";break;
			case E_USER_WARNING: $level = "E_USER_WARNING";break;
			case E_USER_NOTICE: $level = "E_USER_NOTICE";break;
			case E_STRICT: $level = "E_STRICT";break;
			case E_RECOVERABLE_ERROR: $level = "E_RECOVERABLE_ERROR";break;
			default: "UNKNOWN";break;
		}
		
		gravarLog($level . " : " . $errstr, $level);
	}
	
	function handlerLogInit() {
		set_error_handler("handlerLog");
	}

?>
