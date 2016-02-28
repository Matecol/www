function submit(refkey) {
	var query = $("form").serialize();
	
	avisoInfo("Aguarde enviando...");
	
	// fazer post
	$.post("acao.php", query, function (data) {
		avisoSucesso(data);
		
	}).fail(postError);
} 