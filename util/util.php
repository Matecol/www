<?php

function tratarTexto($texto) {
	return strtoupper(str_replace("'", "", trim($texto)));
}

function tratarTextoMinusculo($texto) {
	return strtolower(str_replace("'", "", trim($texto)));
}

function tratarTextoSimples($texto) {
	return str_replace("'", "", trim($texto));
}

function tratarNumero($texto) {
    return preg_replace("/[^0-9]/", "", trim($texto));
}

function tratarChave($valor) {
	return (isset($valor) && ! empty($valor)) ? (int) $valor : "null";
}

/* validar CPF/CNPJ */
function validarCpfCnpj($cpfcnpj) {
	switch(strlen($cpfcnpj)) {
		case 11: return validarCpf($cpfcnpj);break;
		case 14: return validarCnpj($cpfcnpj);break;
		default: return FALSE;
	}
}

/* validar CPF */
function validarCpf($cpf) {
	if (strlen($cpf) != 11) {
		return FALSE;
	}
	
	$array = str_split($cpf);
	$d1 = intval($array[0]);
	$d2 = intval($array[1]);
	$d3 = intval($array[2]);
	$d4 = intval($array[3]);
	$d5 = intval($array[4]);
	$d6 = intval($array[5]);
	$d7 = intval($array[6]);
	$d8 = intval($array[7]);
	$d9 = intval($array[8]);
	$d10 = intval($array[9]);
	$d11 = intval($array[10]);
	
	$dv1=0;
	$dv2=0;
	
	$soma = (($d1*10)+($d2*9)+($d3*8)+($d4*7)+($d5*6)+($d6*5)+($d7*4)+($d8*3)+($d9*2));
	if (($soma%11) < 2) {
		$dv1 = 0;
	} else {
		$dv1 = 11-($soma%11);
	}
	
	if ($dv1 != $d10) {
		return FALSE;
	}
	
	$soma = (($d1*11)+($d2*10)+($d3*9)+($d4*8)+($d5*7)+($d6*6)+($d7*5)+($d8*4)+($d9*3)+($dv1*2));
	if (($soma%11) < 2) {
		$dv2 = 0;
	} else {
		$dv2 = 11-($soma%11);
	}
	
	if ($dv2 != $d11) {
		return FALSE;
	}
	
	return TRUE;
	
}

/* validar CNPJ */
function validarCnpj($cnpj) {
	if (strlen($cnpj) != 14) {
		return FALSE;
	}
	
	$array = str_split($cnpj);
	$d1 = intval($array[0]);
	$d2 = intval($array[1]);
	$d3 = intval($array[2]);
	$d4 = intval($array[3]);
	$d5 = intval($array[4]);
	$d6 = intval($array[5]);
	$d7 = intval($array[6]);
	$d8 = intval($array[7]);
	$d9 = intval($array[8]);
	$d10 = intval($array[9]);
	$d11 = intval($array[10]);
	$d12 = intval($array[11]);
	$d13 = intval($array[12]);
	$d14 = intval($array[13]);
	
	$dv1 = 0;
	$dv2 = 0;
	
	$soma = (($d1*5)+($d2*4)+($d3*3)+($d4*2)+($d5*9)+($d6*8)+($d7*7)+($d8*6)+($d9*5)+($d10*4)+($d11*3)+($d12*2));
	if (($soma%11) < 2) {
		$dv1 = 0;
	} else {
		$dv1 = 11-($soma%11);
	}
	
	if ($dv1 != $d13) {
		return FALSE;
	}
	
	$soma = (($d1*6)+($d2*5)+($d3*4)+($d4*3)+($d5*2)+($d6*9)+($d7*8)+($d8*7)+($d9*6)+($d10*5)+($d11*4)+($d12*3)+($dv1*2));
	if (($soma%11) < 2) {
		$dv2 = 0;
	} else {
		$dv2 = 11-($soma%11);
	}
	
	if ($dv2 != $d14) {
		return FALSE;
	}
	
	return TRUE;
	
}

function formatarCpfCnpj($cpfcnpj) {
	switch (strlen($cpfcnpj)) {
		case 11: return formatarCpf($cpfcnpj);break;
		case 14: return formatarCnpj($cpfcnpj);break;
		default: return $cpfcnpj;
	}
}

function formatarCpf($cpf) {
	$array = str_split($cpf);
	return $array[0] . $array[1] . $array[2] . "." . $array[3] . $array[4] . $array[5] . "." . $array[6] . $array[7] . $array[8] . "-" . $array[9] . $array[10];
}

function formatarCnpj($cnpj) {
	$array = str_split($cnpj);
	return $array[0] . $array[1] . "." . $array[2] . $array[3] . $array[4] . "." . $array[5] . $array[6] . $array[7] . "/" . $array[8] . $array[9] . $array[10] . $array[11] . "-" . $array[12] . $array[13];
}

// Validar E-Mail
// Define uma função que poderá ser usada para validar e-mails usando regexp
function validaEmail($email) {
	$conta = "^[a-zA-Z0-9\._-]+@";
	$domino = "[a-zA-Z0-9\._-]+.";
	$extensao = "([a-zA-Z]{2,4})$";
	
	$pattern = $conta.$domino.$extensao;
	
	if (ereg($pattern, $email))
		return true;
	else
		return false;
}

// Assinar URL
function assinarURL() {
	$query = explode("&token=", $_SERVER['QUERY_STRING'])[0];
	return sha1($query . $_SESSION['checksum'] . '/SIGNATURE');
}

function assinarParametros($param) {
	return sha1($param . $_SESSION['checksum'] . '/SIGNATURE');
}

function testarAssinaturaURL() {
	// nao testar quando nao tiver parametros
	if (empty($_SERVER['QUERY_STRING'])) {
		return TRUE;
	}
	
	if (empty(explode("link=", $_SERVER['QUERY_STRING'])[0]) || explode("link=", $_SERVER['QUERY_STRING'])[0] == "?") {
		return TRUE;
	}
	
	$token = $_GET['token'];
	if (assinarURL() != $token) {
		
	    // echo "<script>window.location.href='/'</script>";
		return FALSE;
	} else {
		return TRUE;
	}
}


?>
