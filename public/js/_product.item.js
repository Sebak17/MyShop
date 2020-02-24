var changingFavorite = false;

$(document).ready(function () {

    $('#btnShoppingCartAdd').click(function () {
        addProductToShoppingCart();
    });

    $("[data-favorite]").click(function () {
        changeFavoriteStatus(($(this).attr("data-favorite") == 'true' ? true : false));
    });

});

function changeFavoriteStatus(b) {

    if(changingFavorite)
        return;

    changingFavorite = true;

    $.ajax({
        url: "/systemUser/changeFavoriteStatus",
        method: "POST",
        data: {
            id: $("[data-id]").attr("data-id"),
        },
        success: function (data) {
            if (data.success == true) {
                let o = $("[data-favorite]");

                if (!data.status) {
                    o.removeClass('fas')
                    o.addClass('far');
                    o.attr("data-favorite", false);
                } else {
                    o.removeClass('far')
                    o.addClass('fas');
                    o.attr("data-favorite", true);
                }
            }
        },
        error: function () {},
        complete: function() {
            changingFavorite = false;
        }
    });

}

function addProductToShoppingCart() {

    $.ajax({
        url: "/systemUser/addToShoppingCart",
        method: "POST",
        data: {
            id: $("[data-id]").attr("data-id"),
        },
        success: function (data) {
            if (data.success == true) {
                showAlertDismissible(AlertType.SUCCESS, Lang.PRODUCT_ADDED_TO_SHOPPINGCART);
            } else {
                if(data.msg != null)
                    showAlertDismissible(AlertType.ERROR, data.msg);
            }
        },
        error: function () {}
    });


}