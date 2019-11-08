$( document ).ready(function() {
	
	$('#btnBasketAdd').click(function() {
		addProductToBasket();
	});

});

function addProductToBasket() {

	$.ajax({
        url: "/systemUser/addToBasket",
        method: "POST",
        success: function (data) {
            if (data.success == true) {

            }
        },
        error: function () {
        }
    });
	

}