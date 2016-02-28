<?php

	$_action = $_GET['_action'];
	
	switch($_action) {
		case "url": urlAvatar();break;
		default: return;
	}

	// obter url do avatar
	function urlAvatar() {
		require_once 'conexao.php';
		require_once 'util.php';
		
		$conexao = new Conexao();
		$id = pg_fetch_all($conexao->query("select * from usuarios where login='" . tratarTexto($_GET['login']) . "';"))[0]['id'];
		
		$url = "/uploads/avatar/" . (md5("id=" . $id . "/UPLOAD")) . "/avatar.png";
 
		if (((int) $id) != 0 && file_exists(BASE_DIR . $url)) {
			echo $url;
		} else {
			echo "/uploads/avatar/avatar.png";
		}
	}
	
?>
