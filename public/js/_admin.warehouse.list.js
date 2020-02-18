window.onload = function () {

    loadProducts();

    $('#btnSearch').click(function () {
        loadProducts();
    });
}

function screenProductsEmpty() {
    $('#warehouseItemsList').html(String.raw`<tr class="table-danger"><td class="text-center" colspan="5"><i class="fas fa-times-circle"></i> Nie znaleziono produktów!</td></tr>`);
    $('#warehouseItemsAmount').html("0");
}

function screenLoading() {
    $('#warehouseItemsList').html(String.raw`<tr><td class="text-center" colspan="5"><i class="fas fa-circle-notch fa-spin fa-3x"></i> <h4>Ładowanie produktów...</h4></td></tr>`);
    $('#warehouseItemsAmount').html("?");
}

function loadProducts() {
    screenLoading();

    let params = [];
    
    let sr_name = $("#inp_productName").val();
    if(sr_name != '') {
        if(!validateProductName(sr_name)) {
            showAlert(AlertType.ERROR, Lang.PRODUCT_NAME_ERROR);
            return;
        }

        params['name'] = sr_name;
    }

    let sr_minAmount = $("#inp_amount1").val();
    if(sr_minAmount != '') {

        params['minAmount'] = sr_minAmount;
    }

    let sr_maxAmount = $("#inp_amount2").val();
    if(sr_maxAmount != '') {

        params['maxAmount'] = sr_maxAmount;
    }



    $.ajax({
        url: "/systemAdmin/warehouseList",
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
                    						<td></td>
                                            <td class="align-middle text-left">
                                                <h6>` + data.list[i].name + `</h6>
                                            </td>
                                            <td>
                                                ` + data.list[i].amount + `
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="` + data.list[i].url  + `"><button class="btn btn-sm btn-info mb-1"><i class="fas fa-eye"></i> Pokaż</button></a>
                                            </td>
                                        </tr>`;

                }
                $('#warehouseItemsList').html(row);
                $('#warehouseItemsAmount').html(data.list.length);


            }
        },
        error: function () {}
    });
}