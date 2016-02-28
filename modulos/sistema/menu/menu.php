<?php
		echo '
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#bs-navbar">
						<span class="sr-only">Toogle Navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/index.php"><span style="color: #aaaaaa; font-weight: bold;">Sistema</span> <span style="color: #777777">Web</span></a>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-navbar">
					<!-- MENU -->
					<ul class="nav navbar-nav">
						<!-- MENU "Início" -->
						<li><a href="/index.php">Início</a></li>';
						
						$conexao = new Conexao();
						// $conexao->query("SET CLIENT_ENCODING TO UTF8");
						
						$sql = "select * from modulos order by indice";
						$result = $conexao->query($sql);
						$rows = pg_fetch_all($result);
						
						if ($rows != null) {
							foreach ($rows as $row) {
								echo '<!-- MENU "' . $row['nome'] . '" -->';
								echo '<li class="dropdown">';
								echo '<a class="dropdown-toggle" href="#" data-toggle="dropdown">' . $row['nome'] . '<b class="caret"></b></a>';
								echo '<ul class="dropdown-menu">';
							
								$sql2 = "select * from programas where modulo=" . $row['id'] . " order by indice, agrupamento";
								$result2 = $conexao->query($sql2);
								$rows2 = pg_fetch_all($result2);
							
								if ($rows2 == null) {
									echo "</ul></a></li>";
									continue;
								}
							
								$agrupamento_a = "";
								foreach ($rows2 as $row2) {
									if ($row2['agrupamento'] != $agrupamento_a) {
										$agrupamento_a = $row2['agrupamento'];
										echo '<li class="dropdown-header">' . $agrupamento_a . '</li>';
									}
								
									echo '<li><a href="/modulos/' . $row['pasta'] . '/' . $row2['pasta'] . '">' . $row2['nome'] . '</a></li>';
								}
							
								echo "</ul>";
							}
						}
						
						echo '</li>';
						
					// Módulo do usuário
					$usuario_a = pg_fetch_all($conexao->query("select * from usuarios where id=" . $_SESSION['id']))[0]['login'];
						
					echo '
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
						    <a class="dropdown-toggle" href="#" data-toggle="dropdown"><span><img class="avatar" id="avatar-menu" src="' . (file_exists(BASE_DIR . "/uploads/avatar/" . md5("id=" . $_SESSION['id'] . "/UPLOAD") . "/avatar.png") ? "/uploads/avatar/" . md5("id=" . $_SESSION['id'] . "/UPLOAD") . "/avatar.png" : "/uploads/avatar/avatar.png") . '"></img> ' . $usuario_a . '<b class="badge notificacao">5</b></span></a>
							<ul class="dropdown-menu">
								<li><a href="/modulos/configuracoes/alterar_senha/cadastro.php">Trocar Senha</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="/login.php"><span class="glyphicon glyphicon-off"></span> Sair</a></li>
							</ul>
						</li>
					</ul>
					<!-- PESQUISA -->
					<form class="navbar-form navbar-right" role="search">
						<div class="input-group">
							<input class="form-control" type="text" placeholder="Pesquisar" id="pesquisa_menu">
							<span class="input-group-btn">
								<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-search"></span></button>
							</span>
						</div>						
					</form>
				</div>
			</div>
		</nav>';
		
//		unset($agrupamento_a);
?>