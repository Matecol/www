function submit(refkey) {
	var query = $("form").serialize();
	
	avisoInfo("Aguarde por favor...");
	
	// fazer post
	$.post("acao.php", query, function (data) {
		avisoSucesso(data);
		
		// redirecionar para pagina anterior
		if ($(refkey).val() != null) {
			redirecionar("consulta.php?_ref=" + $(refkey).val(), 1000);
		} else {
			redirecionar(document.referrer, 1000);
		}
		
	}).fail(postError);
} 
