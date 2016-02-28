function carregarAvatar() {
	var login = $("#login").val();
	$.ajax({
		method: "GET",
		data: {_action:"url", login: login},
		url: "/util/avatar.php",
		success: function (data) {
			if ($("#avatar").attr("src") != data) {
				$("#avatar").attr("src", data);
			}
		}
	});
}
