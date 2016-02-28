<?php
	function parseConfig() {
		return parse_ini_file(BASE_DIR . "/conf/sistemaweb.ini");
	}
	
	function getConfig($name) {
		return parseConfig()[$name];
	}
?>
