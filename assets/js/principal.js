/* erro de post */
function postError(xhr, status, error) {
	var errorCode = xhr.status;
	if (errorCode == 400) {
		var txt = xhr.responseText.split("~")[0];
		var popup = xhr.responseText.split("~")[1];
				
		if (popup != null) {
			$("#" + popup).parent().append('<a href="#" data-toggle="popover" data-trigger="focus" id="popover-' + popup + '" data-placement="bottom" tabindex="99"></a>');
			$("#popover-" + popup).attr("data-content", txt);
			$("#popover-" + popup).popover("show");
			$("#" + popup).parent().addClass("has-error");
			$("#" + popup).blur(function() {
				$("#popover-" + popup).popover("destroy");
				$("#" + popup).parent().removeClass("has-error");
			});
			$(".aviso").empty();			
			$("#" + popup).focus();
		} else {
			avisoErro(txt);
		}
	} else if (errorCode == 401) {
		avisoAtencao(xhr.responseText);
	} else {		
		avisoErro("Falha na requisição. Tente novamente mais tarde ou contate o suporte. (" + status + " " + xhr.status + ": " + error + ")");
	}
}

/* esubmit - submit para exclusao */
function esubmit() {
	$("input[name=_action]").val("exclusao");
	submit();
}

/* redirecionar */
function redirecionar(url, sleep) {
	setTimeout(function() {
		window.location.href = url;
	}, sleep)
}

/* abrir consulta */
function abrirConsulta(url, link) {
	// Salvar dados atuais do formulario
	var dados = JSON.stringify($('form').serializeArray());
	var chave = "link-data-" + link;
	sessionStorage.setItem(chave, dados);
	
	chave = "link-call-" + link;
	sessionStorage.setItem(chave, location.href);
	
	// Abrir consulta
	redirecionar(url + "?link=" + link, 0);
}

/* selecionar cadastro */
function selecionarCadastro(id, link) {
	sessionStorage.setItem("link-ret-" + link, id);
	redirecionar((sessionStorage.getItem("link-call-" + link)).split("?")[0] + "?link=" + link, 0);
}

/* restaurar cadastro */
function restaurarCadastro(link, alvo) {
	var chave = "link-data-" + link;
	var dados = JSON.parse(sessionStorage.getItem(chave));
	
	dados.forEach(function (element, index, array) {
		$("#" + element['name']).val(element['value']);
	});
	
	var ret = sessionStorage.getItem("link-ret-" + link);
	if (ret != null) {
		$(alvo).val(ret);
	}
	
	// apagar dados
	sessionStorage.removeItem("link-data-" + link);
	sessionStorage.removeItem("link-call-" + link);
	sessionStorage.removeItem("link-ret-" + link);
	
}

/* adicionar mascaras */
function adicionarMascaras() {
	
	// Telefone
	if (typeof $(".telefone").mask == 'function') {
		$(".telefone").mask("(00) 0000-00009");
	}
	
	// CEP
	if (typeof $(".cep").mask == 'function') {
		$(".cep").mask("00000-000");
	}
	
	//CPF
	if (typeof $(".cpf").mask == 'function') {
		$(".cpf").mask("000.000.000-00");
	}
	
	//CNPJ
	if (typeof $(".cnpj").mask == 'function') {
       $(".cnpj").mask("00.000.000/0000-00");
	}
	
	// Mascara persolizado
	$('body').find('input[data-mask]').each(function() {
		if (typeof $(this).mask == 'function') {
			$(this).mask($(this).attr('data-mask'));
		}
	});
	
	$('body').find('input[data-mask-reverse=true]').each(function() {
		if (typeof $(this).mask == 'function') {
			$(this).mask($(this).attr('data-mask'), {reverse: true});
		}
	});

}

/* somente numero */
function somenteNumero() {
	$(".numero").keyup(function(e) {
		var keycode = e.keyCode;
		var valid = 
        			(keycode > 47 && keycode < 58)   || // number keys
        			keycode == 32 || keycode == 13   || // spacebar & return key(s) (if you want to allow carriage returns)
        			(keycode > 64 && keycode < 91)   || // letter keys
        			(keycode > 95 && keycode < 112)  || // numpad keys
        			(keycode > 185 && keycode < 193) || // ;=,-./` (in order)
        			(keycode > 218 && keycode < 223);   // [\]' (in order)

		if (valid) {
			$(this).val(tratarNumero($(this).val()));
		}
	});
}

/* abrir submenu */
function abrirSubmenu(menu) {
	$(".sidebar-dropdown:not(" + menu + ") ul").slideUp(100);
	$(".sidebar-dropdown:not(" + menu + ")").removeClass("sidebar-dropdown-active");
	$(menu + " ul").slideToggle(200);
	
	if (! $(menu).hasClass("sidebar-dropdown-active")) {
		$(menu).addClass("sidebar-dropdown-active");
	} else {
		$(menu).removeClass("sidebar-dropdown-active");
	}
	
}

/**********/
$(document).ready(function () {
	adicionarMascaras();
	somenteNumero();
});

/*$("#txtNome").blur(function(){
    alert("O input perdeu o foco.");
});*/

/* tratar numero */
function tratarNumero(val) {
	if (val.length == 0) {
		return;
	}
	
	var r = "";
	var x = 0;
	
	for (;x<val.length;x++) {
		switch (val.charAt(x)) {
			case "0":
			case "1":
			case "2":
			case "3":
			case "4":
			case "5":
			case "6":
			case "7":
			case "8":
			case "9": r+=val.charAt(x);break;
			default: continue;
		}
	}
	
	return r;
} 

/* validar CPF/CNPJ */
function validarCpfCnpj(cpfcnpj) {
	switch (cpfcnpj.length) {
		case 11: return validarCpf(cpfcnpj);break;
		case 14: return validarCnpj(cpfcnpj);break;
		default: return false;
	}
}

function validarCpf(cpf) {
	if (cpf.length != 11) {
		return false;
	}
	
	var d1 = parseInt(cpf.charAt(0));
	var d2 = parseInt(cpf.charAt(1));
	var d3 = parseInt(cpf.charAt(2));
	var d4 = parseInt(cpf.charAt(3));
	var d5 = parseInt(cpf.charAt(4));
	var d6 = parseInt(cpf.charAt(5));
	var d7 = parseInt(cpf.charAt(6));
	var d8 = parseInt(cpf.charAt(7));
	var d9 = parseInt(cpf.charAt(8));
	var d10 = parseInt(cpf.charAt(9));
	var d11 = parseInt(cpf.charAt(10));
	
	var dv1 = 0;
	var dv2 = 0;
	
	var soma = ((d1*10)+(d2*9)+(d3*8)+(d4*7)+(d5*6)+(d6*5)+(d7*4)+(d8*3)+(d9*2));
	if ((soma % 11) < 2) {
		dv1 = 0;
	} else {
		dv1 = 11-(soma%11);
	}
	
	if (d10 != dv1) {
		return false;
	}
	
	var soma = ((d1*11)+(d2*10)+(d3*9)+(d4*8)+(d5*7)+(d6*6)+(d7*5)+(d8*4)+(d9*3)+(dv1*2));
	if ((soma % 11) < 2) {
		dv2 = 0;
	} else {
		dv2 = 11-(soma%11);
	}
	
	if (d11 != dv2) {
		return false;
	}
	
	return true;
	
}

function validarCnpj(cnpj) {
	if (cnpj.length != 14) {
		return false;
	}
	
	var d1 = parseInt(cnpj.charAt(0));
	var d2 = parseInt(cnpj.charAt(1));
	var d3 = parseInt(cnpj.charAt(2));
	var d4 = parseInt(cnpj.charAt(3));
	var d5 = parseInt(cnpj.charAt(4));
	var d6 = parseInt(cnpj.charAt(5));
	var d7 = parseInt(cnpj.charAt(6));
	var d8 = parseInt(cnpj.charAt(7));
	var d9 = parseInt(cnpj.charAt(8));
	var d10 = parseInt(cnpj.charAt(9));
	var d11 = parseInt(cnpj.charAt(10));
	var d12 = parseInt(cnpj.charAt(11));
	var d13 = parseInt(cnpj.charAt(12));
	var d14 = parseInt(cnpj.charAt(13));
	
	var dv1 = 0;
	var dv2 = 0;
	
	var soma = ((d1*5)+(d2*4)+(d3*3)+(d4*2)+(d5*9)+(d6*8)+(d7*7)+(d8*6)+(d9*5)+(d10*4)+(d11*3)+(d12*2));
	if ((soma % 11) < 2) {
		dv1 = 0;
	} else {
		dv1 = 11-(soma%11);
	}
	
	if (d13 != dv1) {
		return false;
	}
	
	var soma = ((d1*6)+(d2*5)+(d3*4)+(d4*3)+(d5*2)+(d6*9)+(d7*8)+(d8*7)+(d9*6)+(d10*5)+(d11*4)+(d12*3)+(dv1*2));
	if ((soma % 11) < 2) {
		dv2 = 0;
	} else {
		dv2 = 11-(soma%11);
	}
	
	if (d14 != dv2) {
		return false;
	}
	
	return true;
	
}

/* dialog's */
function dialogYesNo(evtyes, evtno, title, message, icon) {
	$('#modal').remove();
	$('body').append('\
			<div id="modal" class="modal fade" role="dialog">\
				<div class="modal-dialog">\
					<div class="modal-content">\
						<div class="modal-header">\
							<button type="button" class="close" data-dismiss="modal">&times;</button>\
							<h4 class="modal-title"><span class="glyphicon glyphicon-' + icon + '"></span> ' + title + '</h4>\
						</div>\
						<div class="modal-body">\
							<p>' + message + '</p>\
						</div>\
						<div class="modal-footer">\
							<button type="button" class="btn btn-success" data-dismiss="modal" onclick="' + evtyes + '">Sim</button>\
							<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="' + evtno + '">Não</button>\
						</div>\
					</div>\
				</div>\
			</div>\
	');
}

function dialogSemPermissao(permissao) {
	$('#modal').remove();
	$('body').append('\
			<div id="modal" class="modal fade" role="dialog">\
				<div class="modal-dialog">\
					<div class="modal-content">\
						<div class="modal-header">\
							<button type="button" class="close" data-dismiss="modal">&times;</button>\
							<h4 class="modal-title"><span class="glyphicon glyphicon-alert"></span> Sem Permissão</h4>\
						</div>\
						<div class="modal-body">\
							<h4>' + permissao + '</h4>\
							<h6>Solicite liberação ao administrador.</h6>\
						</div>\
						<div class="modal-footer">\
							<button type="button" class="btn btn-success" data-dismiss="modal">OK</button>\
						</div>\
					</div>\
				</div>\
			</div>\
	');
	$('#modal').modal('toggle');
}

/* painel de mensagens */
function avisoSucesso(msg) {
	$(".aviso").empty();
	$(".aviso").append("<div class=\"alert alert-success\" role=\"alert\"><span class=\"glyphicon glyphicon-ok-sign\" aria-hidden=\"true\"></span> "+msg+"</div>");
	$(".aviso").slideDown();
}

function avisoInfo(msg) {
	$(".aviso").empty();
	$(".aviso").append("<div class=\"alert alert-info\" role=\"alert\"><span class=\"glyphicon glyphicon-info-sign\" aria-hidden=\"true\"></span> "+msg+"</div>");
	$(".aviso").slideDown();
}

function avisoAtencao(msg) {
	$(".aviso").empty();
	$(".aviso").append("<div class=\"alert alert-warning\" role=\"alert\"><span class=\"glyphicon glyphicon-warning-sign\" aria-hidden=\"true\"></span> "+msg+"</div>");
	$(".aviso").slideDown();
}

function avisoErro(msg) {
	$(".aviso").empty();
	$(".aviso").append("<div class=\"alert alert-danger\" role=\"alert\"><span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span> "+msg+"</div>");
	$(".aviso").slideDown();
}

