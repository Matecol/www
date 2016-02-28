<?php
	echo '
	<div class="hidden-xs hidden-sm">
		<div class="collapse navbar-collapse">
			<ul class="nav navbar sidebar">
				<h3><span style="margin-left: 15px;"><span style="color: #666666; font-weight: bold;">BEM</span> <span style="color: #444444">VINDO</span></span></h3>
				<li><a href="#"><b>Página Inicial</b></a></li>
				<li class="sidebar-dropdown" id="dropdown"><a href="#" onclick="abrirSubmenu(\'#dropdown\');">Dropdown<span class="glyphicon glyphicon-menu-down" style="float: right"></span></a>
					<ul class="nav navbar">
						<li><a href="#">Teste 1</a></li>
						<li><a href="#">Teste 2</a></li>
						<li><a href="#">Teste 3</a></li>
					</ul>
				</li>
				<li class="sidebar-dropdown" id="dropdown2"><a href="#" onclick="abrirSubmenu(\'#dropdown2\');">Dropdown 2<span class="glyphicon glyphicon-menu-down" style="float: right"></span></a>
					<ul class="nav navbar">
						<li><a href="#">Teste 1</a></li>
						<li><a href="#">Teste 2</a></li>
						<li><a href="#">Teste 3</a></li>
					</ul>
				</li>
				<li><a href="/modulos/sistema/configuracao/">Configurações Sistema</a></li>
				<li><a href="/modulos/sistema/logs/">Registros de Log';
				
				// contagem de erros da log
				$errosLog = (int)contarErrosLog();
				if ($errosLog > 0) {
					echo '<span class="badge notificacao">' . $errosLog . '</span>';
				}
				
		echo '
				</a></li>
				<li><a href="#">Ajuda</a></li>
			</ul>
		</div>
	</div>';
?>
