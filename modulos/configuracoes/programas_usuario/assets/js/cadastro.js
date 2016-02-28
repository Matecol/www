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

// consultar municipio
function consultarUsuario() {
	var usuario = $("#usuario").val();
	if (usuario == null) {
		return;
	}
	
	$("#nome_usuario").val("AGUARDE...");
	
	$.post("/modulos/configuracoes/usuarios/acao.php", {_action : 'consultar', usuario : usuario}, function (data) {
		if (data == "false") {
			$("#nome_usuario").val("USUÁRIO NÃO CADASTRADO");
			return;
		}
		
		var dados = JSON.parse(data);
		
		$("#nome_usuario").val(dados[0].nome);
		
	}).fail(postError);
}

// consultar permissao
function consultarPermissao() {
	var permissao = $("#permissao").val();
	if (permissao == null) {
		return;
	}
	
	$("#nome_permissao").val("AGUARDE...");
	
	$.post("/modulos/configuracoes/permissoes/acao.php", {_action : 'consultar', permissao : permissao}, function (data) {
		if (data == "false") {
			$("#nome_permissao").val("PERMISSÃO NÃO CADASTRADO");
			return;
		}
		
		var dados = JSON.parse(data);
		
		$("#nome_permissao").val(dados[0].descricao);
		
	}).fail(postError);
}

