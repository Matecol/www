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
function consultarMunicipio(source, target) {
	var id = $(source).val();
	if (id == "") {
		return;
	}
	
	$(target).val("AGUARDE...");
	
	$.post("/modulos/configuracoes/municipios/acao.php", {_action : 'consultar', id : id}, function (data) {
		if (data == "false") {
			$(target).val("MUNICÍPIO NÃO CADASTRADO");
			return;
		}
		
		var dados = JSON.parse(data);
		
		$(target).val(dados[0].municipio + " / " + dados[0].uf);
		
	}).fail(postError);
}

// consultar cliente
function consultarCliente() {
	var cnpj = tratarNumero($("#cnpj").val());
	if (cnpj == "") {
		return;
	}
	
	if (! validarCpfCnpj(cnpj)) {
		return;
	}
	
	$.post("acao.php", {_action : 'consultar', cnpj : cnpj}, function (data) {
		if (data == "false") {
			return;
		}
		
		var dados = JSON.parse(data);
		
		[].forEach.call(Object.keys(dados[0]), function(key) {
			$("#" + key).val(dados[0][key]);
		})
		
		$("#_action").val("inclusaodup");
		// consultarMunicipio("#municipio_entrega", "#nome_municipio_entrega");
		// consultarMunicipio("#municipio_cobranca", "#nome_municipio_cobranca");
		
	});
}

// Restaurar municipios
function restaurarMunicipios(link, target) {
	if (target == "municipio_entrega") {
		restaurarCadastro(link + "&target=" + target, '#municipio_entrega');
	}
	
	if (target == "municipio_cobranca") {
		restaurarCadastro(link + "&target=" + target, '#municipio_cobranca');
	}
	
	if ($("#municipio_entrega").val() != "") {
		consultarMunicipio('#municipio_entrega', "#nome_municipio_entrega");
	}
	
	if ($("#municipio_cobranca").val() != "") {
		consultarMunicipio('#municipio_cobranca', "#nome_municipio_cobranca");
	}
	
	$("#" + target).focus();
	
}

// Testar CPF/CNPJ
function testarCpfCnpj() {
	if ($("#cnpj").val().toUpperCase() == "ISENTO") {
		$("#form-group-cnpj").removeClass("has-error");
		$("#popover-cnpj").popover("destroy");
		return;
	}

	if ($("#cnpj").val() == "") {
		$("#form-group-cnpj").removeClass("has-error");
		$("#popover-cnpj").popover("destroy");
		return;
	}
	
	var cpfcnpj = tratarNumero($("#cnpj").val());
	
	if (! validarCpfCnpj(cpfcnpj)) {
		$("#form-group-cnpj").addClass("has-error");
		$("#popover-cnpj").popover("show");
		$("#cnpj").focus();
		return;
	} else {
		$("#form-group-cnpj").removeClass("has-error");
		$("#popover-cnpj").popover("destroy");
		aplicarMascara();
	}
}

// Remover mascara
function removerMascara() {
	$("#cnpj").unmask();
}

// Aplicar mascara CPF/CNPJ
function aplicarMascara() {
	var mascara = "";
	
	switch ($("#cnpj").val().length) {
		case 11: mascara = "000.000.000-00";break;
		case 14: mascara = "00.000.000/0000-00";break; 
	}
	
	if (mascara != "") {
		$("#cnpj").mask(mascara);
	}
	
}

/* copiar endereco entrega -> endereco cobranca */
function toggleCobranca() {
	if ($("#checkboxCobranca").is(":checked")) {
		copiarCobranca();
	} else {
		limparCobranca();
	}
}

function copiarCobranca() {
	$("#endereco_cobranca").val($("#endereco_entrega").val());
	$("#bairro_cobranca").val($("#bairro_entrega").val());
	$("#cep_cobranca").val($("#cep_entrega").val());
	$("#municipio_cobranca").val($("#municipio_entrega").val());
	$("#nome_municipio_cobranca").val($("#nome_municipio_entrega").val());
	$("#telefone_cobranca").val($("#telefone_entrega").val());
	$("#celular_cobranca").val($("#celular_entrega").val());
	
	$(".cobranca").attr('readonly', 'readonly');
}

function limparCobranca() {
	$(".cobranca").val("");
	$(".cobranca").removeAttr("readonly");
}


