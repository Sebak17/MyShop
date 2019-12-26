$( document ).ready(function() {
    searchEngine_bindKeys();
});

function searchEngine_bindKeys() {
	$("#searchBtn").click(function () {
		searchProducts();
	});

	$('#searchBox').keyup(function (event) {
		if (event.keyCode === 13)
			searchProducts();
    });
}

function searchProducts() {
	let val = $("#searchBox").val();
	val = val.replace(/[^\w\s]/gi, '');

	if(val.length < 3) {
		return;
	}

	window.location.href = "/produkty/?string=" + val;
}
