<?php

@session_start();

class Conexao {
     var $conn;
	 
	 public function __construct() {
	 		$this->conn = pg_connect("host=" . DB_SERVER . " user=" . DB_USER . " password=" . DB_PASSWORD . " dbname=" . DB_NAME);
			pg_query($this->conn, "SET sistemaweb.usuario='" . $_SESSION['id'] . "'");
			pg_query($this->conn, "SET sistemaweb.pagina='" . $_SERVER['HTTP_REFERER'] . "'");
			pg_query($this->conn, "SET TIME ZONE '" . getConfig("timezone") . "'");
	 }
	 
	 public function getConnection() {
	 		return $this->conn;
	 }

	 public function query($query) {
			$result = pg_query($this->conn, $query);
			
			// GRAVAR LOG SE HOUVER ERRO
			if ($result == FALSE) {
				require_once 'logs.php';
				gravarLog(pg_last_error($this->conn) . "\nQUERY: \"" . $query . "\"", "ERROR");
			}
			
			return $result;
	 }
	 	 
}
?>
