<?php
	echo '
		<div class="rodape">
			<div class="row">
				<!-- EMPRESA -->
				<div class="col-xs-6 col-md-5">
					<span class="hidden-xs">
						<span class="glyphicon glyphicon-home"></span>
				';
				
	$conexao = new Conexao();
	
	// Empresa
	$result = $conexao->query("select * from empresas where id=" . $_SESSION['empresa']);
	
	// DESKTOP
	echo 'Empresa: <b>' . pg_fetch_all($result)[0]['razaosocial'] . ' ( LOJA - ' . (($_SESSION['empresa'] < 10) ? "0" . $_SESSION['empresa'] : $_SESSION['empresa']) . ')</b></span>';
	
	// MOBILE
	echo '<span class="hidden-sm hidden-md hidden-lg"><b>' . pg_fetch_all($result)[0]['nomefantasia'] . ' ( LOJA - ' . (($_SESSION['empresa'] < 10) ? "0" . $_SESSION['empresa'] : $_SESSION['empresa']) . ')</b></span></span>';
				
	echo '
				</div>
				<!-- USUARIO -->
				<div class="col-xs-6 col-md-3">
					<span class="hidden-xs">
						<span class="glyphicon glyphicon-user"></span>  
				';
				
	// Usuario
	$result = $conexao->query("select * from usuarios where id=" . $_SESSION['id']);
	
	// DESKTOP
	echo 'Usuário: <b>' . pg_fetch_all($result)[0]['nome'] . '</b></span>';
	
	// LOGIN
	echo '<span class="hidden-sm hidden-md hidden-lg">Usuário: <b>' . pg_fetch_all($result)[0]['login'] . '</b></span></span>';
				
	echo '
				</div>
				<!-- COPYRIGHT -->
				<div class="hidden-xs col-md-4">
					<span class="glyphicon glyphicon-copyright-mark"></span> THIAGO & RAPHAEL SISTEMAS <span class="glyphicon glyphicon-earphone"></span> <b>(65)9963-2977</b> / <b>(65)9602-0786</b>
				</div>
			</div>
		</div>
	';
?>
