<?php

require_once 'conexao.php';
require_once 'sessao.php';

// validarSessao();

if (! empty($_GET['permissao'])) {
	echo testarPermissao($_GET['testarPermissao']);
}

// testar a permissao
function testarPermissao($permissao) {
	
	// abrir conexao
	$conexao = new Conexao();
	
	// testar nivel do usuario (nivel 9 sempre tem permissao)
	$sql = "select nivel from usuarios where id=" . $_SESSION['id'] . ";";
	$nivel = pg_fetch_all($conexao->query($sql))[0]['nivel'];
	if (intval($nivel) == 9) {
		return "S";
	}
	
	// obter id da permissao
	$sql = "select * from permissoes where descricao='" . $permissao . "';";
	$idperm = pg_fetch_all($conexao->query($sql))[0]['id'];
	
	if (empty($idperm)) {
		// cadastrar permissao quando nao existir
		$sql = "insert into permissoes (descricao) values ('" . $permissao . "');";
		$conexao->query($sql);
		return "N";
	}
	
	// consultar permissao
	$sql = "select * from permissoes_usuario where usuario=" . $_SESSION['id'] . " and permissao=" . $idperm . ";";
	
	$valor = pg_fetch_all($conexao->query($sql))[0]['valor'];
	if (empty($valor)) {
		return "N";
	}
	
	if (empty($valor)) {
		return "S";
	} else {
		return $valor;
	}
	
}

?>
