<?php

// iniciar sessao
@session_start();

function validarSessao() {
	
	date_default_timezone_set("America/Cuiaba");
	
	// verificar os parametros da sessao
	if (empty($_SESSION['id']) || empty($_SESSION['checksum'])) {
		echo "<script>window.location.href = '/login.php';</script>";
	}
	
	// Se a pagina de referencia for do login e não tiver passado a empresa, entao retornar 2
	if (empty($_SESSION['empresa']) && ($_SERVER['PHP_SELF'] == "/login.php" || $_SERVER['PHP_SELF'] == "/util/permissao.php")) {
		return 2;
	}
	
	if (empty($_SESSION['empresa'])) {
		echo "<script>window.location.href = '/login.php';</script>";
		return;
	}
	
	// conetar com o banco de dados
	require_once 'conexao.php';
	
	$conexao = new Conexao();
	
	$id = $_SESSION['id'];
	$checksum = $_SESSION['checksum'];
	
	// verificar se a sessao existe
	$sql = "select * from sessoes where usuario=" . $id . " and checksum='" . $checksum . "';";
	$result = $conexao->query($sql);
	
	if (! pg_num_rows($result) > 0) {
		echo "<script>window.location.href = '/login.php';</script>";
		return;
	}
	
	// testar validade
	$rows = pg_fetch_all($result);
	$dt_atual = new DateTime(date("Y-m-d H:i:s"));
	$dt_vencimento = new DateTime($rows[0]['dt_vencimento']);
	$intervalo = $dt_vencimento->diff($dt_atual);
	
	$minutos = $intervalo->format("%I");

	if ($minutos > 10) {
		echo "<script>window.location.href = '/login.php';</script>";
		return;
	}
	
	// atualizar sessao
	$sql = "update sessoes set contador=contador+1 where usuario=" . $_SESSION['id'] . " and checksum='" . $_SESSION['checksum'] . "';";
	$result = $conexao->query($sql);
	
	return 0;
	
}

?>
