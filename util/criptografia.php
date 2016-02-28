<?php

// Criptografar texto com a chave pública
function criptografar($texto) {
	$pubkey = openssl_pkey_get_public(PUBLIC_KEY);
	if (! $pubkey) {
		return NULL;
	}
	
	$r = openssl_public_encrypt($texto, $criptografado, $pubkey);
	if (! $r) {
		return NULL;
	}
	
	return base64_encode($criptografado);
}

// Descriptografar texto com a chave privada
function descriptografar($texto) {
	$privkey = openssl_pkey_get_private(PRIVATE_KEY);
	if (! $privkey) {
		return NULL;
	}
	
	$r = openssl_private_decrypt(base64_decode($texto), $descriptografado, $privkey);
	if (! $r) {
		return NULL;
	}
	
	return $descriptografado;
}

?>
