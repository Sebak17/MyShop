var products = [];

window.onload = function () {

    loadProducts();

    $("#btnNext").click(function () {
        goToNextStep();
    });
}

function goToNextStep() {
    $.ajax({
        url: "/systemUser/confirmShoppingCart",
        method: "POST",
        success: function (data) {
            if (data.success == true) {
                window.location.href = data.url;
            } else {
                showShoppingCartProductsFails(data.msg_fail, data.fails);
                loadProducts();
            }
        },
        error: function () {

        }
    });
}

function reloadLocalData() {
    displayList();
    updateSummary();
}

function displayList() {
    let list = "";

    products.forEach(function (item, index) {
        list += String.raw `
                            <tr class="row" data-id="` + item.id + `">
                                <td class="col-4 col-sm-5 col-md-6 col-lg-5">
                                    <a href="/produkt?id=` + item.id + `"><h5>` + item.name + `</h5></a>
                                </td>
                                <td class="col-3 col-sm-2 col-md-2 col-lg-2">
                                    <h5>` + item.price.toFixed(2) + ` ` + cfg_currency + `</h5>
                                </td>
                                <td class="col-2 col-sm-2 col-md-1 col-lg-2">
                                    <input type="number" class="form-control form-control-sm text-center" value="` + item.amount + `" data-product-amount="">
                                </td>
                                <td class="col-2 col-sm-2 col-md-2 col-lg-2">
                                    <h5 data-price-final="">` + rePrice(parseInt(item.amount) * parseFloat(item.price)).toFixed(2) + ` ` + cfg_currency + `</h5>
                                </td>
                                <td class="col-1 col-sm-1 col-md-1 col-lg-1">
                                    <button class="btn btn-danger btn-sm" data-btn-delete><i class="fas fa-times-circle"></i></button>
                                </td>                                
                            </tr>
                            `;
    });

    $("#productsList").html(list);

    bindElementsInList();
}

function bindElementsInList() {

    $("[data-product-amount]").each(function (index) {
        $(this).change(function () {
            let newAmount = parseInt($(this).val());

            let id = parseInt($(this).parent().parent().attr('data-id'));

            let product = getProductByID(id);

            if (product === null)
                return;

            if (newAmount > 10) {
                $(this).val(product.amount)
                showAlert(AlertType.ERROR, Lang.TOO_MANY_ITEMS);
                return;
            }

            if (newAmount <= 0) {
                $(this).val(product.amount)
                return;
            }


            product.amount = newAmount;

            let newPrice = rePrice(parseInt(newAmount) * parseFloat(product.price));

            $(this).parent().parent().find('[data-price-final]').html(newPrice.toFixed(2) + " " + cfg_currency);

            updateSummary();
            updateShoppingCart();
        })
    });

    $("[data-btn-delete]").each(function (index) {
        $(this).click(function () {

            let id = parseInt($(this).parent().parent().attr('data-id'));

            let product = getProductByID(id);

            products = arrayRemove(products, product);

            reloadLocalData();
            updateShoppingCart();
        })
    });
}

function updateShoppingCart() {
    let _data = [];

    products.forEach(function (item, key) {
        _data.push({
            id: item.id,
            amount: item.amount,
        });
    });

    $.ajax({
        url: "/systemUser/updateShoppingCart",
        method: "POST",
        data: {
            products: toObject(_data),
        },
        success: function (data) {
            if (data.success == true) {
                showShoppingCartProductsFails(data.msg_fail, data.fails);

                loadProducts();
            }
        },
        error: function () {}
    });
}

function loadProducts() {
    $.ajax({
        url: "/systemUser/loadShoppingCartProducts",
        method: "POST",
        success: function (data) {
            if (data.success == true) {
                products = [];

                showShoppingCartProductsFails(data.msg_fail, data.fails);

                if (data.products != null) {
                    Object.values(data.products).forEach(function (item, key) {
                        products.push({
                            id: item.id,
                            name: item.name,
                            price: item.price,
                            amount: item.amount,
                        });
                    });
                }

                reloadLocalData()
            }
        },
        error: function () {}
    });

}

function showShoppingCartProductsFails(failMsg, fails) {
    if (failMsg != null) {
        showAlertDismissible(AlertType.ERROR, failMsg, "#alertFailMain", true);
    }
    if (fails != null) {
        let i = 0;
        Object.values(fails).forEach(function (item, key) {
            let id = "alertFail00" + i;
            let m = String.raw `
                            <div class="alert alert-dismissible text-left d-none" id="` + id + `">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <span></span>
                            </div>
                            `;

            $("#alertFails").html($("#alertFails").html() + m);
            showAlertDismissible(AlertType.ERROR, item.msg, "#" + id, true);
            i++;
        });
    }
}

function updateSummary() {
    let sumPrice = 0;
    products.forEach(function (item, index) {
        sumPrice += rePrice(parseInt(item.amount) * parseFloat(item.price));
    });
    sumPrice = rePrice(sumPrice);

    $("#summaryPrice").html(sumPrice.toFixed(2) + " " + cfg_currency);
}

function getProductByID(id) {
    let result = null;

    products.forEach(function (item, index) {
        if (item.id == id)
            result = item;
    });

    return result;
}

function rePrice(price) {
    return Math.round(price * 100) / 100;
}