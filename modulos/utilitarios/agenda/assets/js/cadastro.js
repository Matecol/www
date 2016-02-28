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
function consultarMunicipio() {
	var id = $("#municipio").val();
	if (id == null) {
		return;
	}
	
	$("#nome_municipio").val("AGUARDE...");
	
	$.post("/modulos/configuracoes/municipios/acao.php", {_action : 'consultar', id : id}, function (data) {
		if (data == "false") {
			$("#nome_municipio").val("MUNICÍPIO NÃO CADASTRADO");
			return;
		}
		
		var dados = JSON.parse(data);
		
		$("#nome_municipio").val(dados[0].municipio + " / " + dados[0].uf);
		
	}).fail(postError);
}
