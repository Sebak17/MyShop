$( document ).ready(function() {
    searchEngine_bindKeys();
});

function searchEngine_bindKeys() {
	$("#searchBtn").click(function () {
		searchOffers();
	});

	$('#searchBox').keyup(function (event) {
		if (event.keyCode === 13)
			searchOffers();
    });
}

function searchOffers() {
	let val = $("#searchBox").val();
	val = val.replace(/[^\w\s]/gi, '');

	if(val.length < 3) {
		return;
	}

	window.location.href = "/produkty/?string=" + val;
}
