function submit(refkey) {
	var query = $("form").serialize();
	
	avisoInfo("Aguarde por favor...");
	
	// fazer post
	$.post("acao.php", query, function (data) {
		avisoSucesso(data);
		
		// redirecionar para pagina anterior
		if ($(refkey).val() != null) {
			redirecionar("consulta.php?_ref=" + $(refkey).val().toUpperCase(), 1000);
		} else {
			redirecionar(document.referrer, 1000);
		}
		
	}).fail(postError);
} 

function permissoes(token) {
	redirecionar("/modulos/configuracoes/permissoes_usuario/consulta.php?usuario=" + $("#id").val() + "&token=" + token, 0);
	
}
