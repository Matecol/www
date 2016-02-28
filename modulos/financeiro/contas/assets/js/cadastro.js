function submit(refkey) {
	var query = $("form").serialize();
	
	avisoInfo("Aguarde por favor...");
	
	// fazer post
	$.post("acao.php", query, function (data) {
		avisoSucesso(data);
		
		// pegar link
		var link = document.referrer.split("link=")[1];
		
		// redirecionar para pagina anterior
		if ($(refkey).val() != null) {
			if (link != null) {
				redirecionar("consulta.php?link=" + link + "&_ref=" + $(refkey).val().toUpperCase(), 1000);
			} else {
				redirecionar("consulta.php?_ref=" + $(refkey).val().toUpperCase(), 1000);
			}
			
		} else {
			if (link != null) {
				redirecionar(document.referrer + "?link=" + link, 1000);
			} else {
				redirecionar(document.referrer, 1000);
			}
			
		}
		
	}).fail(postError);
} 
