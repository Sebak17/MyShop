$( document ).ready(function() {
	
	$('#btnShoppingCartAdd').click(function() {
		addProductToShoppingCart();
	});

    $("[data-favorite]").click(function() {
        changeFavoriteStatus( ($(this).attr("data-favorite") == 'true' ? true : false) );
        
    });

});

function changeFavoriteStatus(b) {
    
    let o = $("[data-favorite]");

    if(b) {
        o.removeClass('fas')
        o.addClass('far');
        o.attr("data-favorite", false);
    } else {
        o.removeClass('far')
        o.addClass('fas');
        o.attr("data-favorite", true);
    }


}

function addProductToShoppingCart() {

	$.ajax({
        url: "/systemUser/addToShoppingCart",
        method: "POST",
        success: function (data) {
            if (data.success == true) {
                showAlertDismissible(AlertType.SUCCESS, Lang.PRODUCT_ADDED_TO_SHOPPINGCART);
            }
        },
        error: function () {
        }
    });
	

}