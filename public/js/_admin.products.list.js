window.onload = function () {

    loadProducts();

    $('#btnSearch').click(function () {
        loadProducts();
    });
}

function screenProductsEmpty() {
    $('#productsList').html(String.raw`<tr class="table-danger"><td class="text-center" colspan="5"><i class="fas fa-times-circle"></i> Nie znaleziono produktów!</td></tr>`);
    $('#productsAmount').html("0");
}

function screenLoading() {
    $('#productsList').html(String.raw`<tr><td class="text-center" colspan="5"><i class="fas fa-circle-notch fa-spin fa-3x"></i> <h4>Ładowanie produktów...</h4></td></tr>`);
    $('#productsAmount').html("?");
}

function loadProducts() {
    screenLoading();

    let params = [];
    
    let sr_name = $("#inp_name").val();
    if(sr_name != '') {
        if(!validateProductName(sr_name)) {
            showAlert(AlertType.ERROR, Lang.PRODUCT_NAME_ERROR);
            return;
        }

        params['name'] = sr_name;
    }

    let sr_minPrice = $("#inp_price1").val();
    if(sr_minPrice != '') {
        if(!validateProductPrice(sr_minPrice)) {
            showAlert(AlertType.ERROR, Lang.PRODUCT_PRICE_ERROR);
            return;
        }

        params['minPrice'] = sr_minPrice;
    }

    let sr_maxPrice = $("#inp_price2").val();
    if(sr_maxPrice != '') {
        if(!validateProductPrice(sr_maxPrice)) {
            showAlert(AlertType.ERROR, Lang.PRODUCT_PRICE_ERROR);
            return;
        }

        params['maxPrice'] = sr_maxPrice;
    }



    $.ajax({
        url: "/systemAdmin/productLoadList",
        method: "POST",
        data: toObject(params),
        success: function (data) {
            if (data.success == true) {

                if(data.list.length == 0) {
                    screenProductsEmpty();
                    return;
                }

                let row = '';

                for (let i = 0; i < data.list.length; i++) {

                    row += String.raw `<tr>
                                            <td style="width: 100px; padding: 6px;">
                                                <img class="img-fluid" width="50px" max-height="50px" src="/storage/products_images/` + data.list[i].image1 + `">
                                            </td>
                                            <td class="align-middle text-left">
                                                <h6>` + data.list[i].name + `</h6>
                                            </td>
                                            <td>
                                                ` + data.list[i].status + `
                                            </td>
                                            <td class="align-middle text-left">
                                                <h6>` + data.list[i].price + ` ` + cfg_currency +  `</h6>
                                            </td>
                                            <td class="align-middle text-right">
                                                <a href="/produkt?id=` + data.list[i].id  + `"><button class="btn btn-sm btn-info mb-1"><i class="fas fa-arrow-right"></i> Pokaż</button></a>
                                                <a href="/admin/produkty/edytuj/` + data.list[i].id  + `"><button class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edytuj</button></a>
                                            </td>
                                        </tr>`;

                }
                $('#productsList').html(row);
                $('#productsAmount').html(data.list.length);


            }
        },
        error: function () {}
    });
}