function loading(message) {
	setTimeout(function () {$("#loading").html('<img src="/public/image/loading.gif" alt="chargement..."></img><span id="loadingtext">' + message + '</span>');}, 300);
	$("#loading").show();
}