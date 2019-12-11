var _orderID = -1,
    _currentDeliver = '',
    _currentLocker = '';
var lockerMap;

$(document).ready(function () {

    _orderID = $("[data-id]").attr('data-id');

    bindButtons();

});

function bindButtons() {

    $('#btnShowHistory').click(function () {
        $('#modalHistory').modal('show');
    });

    $('#btnChangeStatusModal').click(function () {
        $('#modalChangeStatus').modal('show');
    });

    $('#btnChangeDeliverLocModal').click(function () {
        $('#modalChangeDeliverLoc').modal('show');
    });

    $('#btnChangePaymentModal').click(function () {
        $('#modalChangePayment').modal('show');
    });

    $('#btnChangeCostModal').click(function () {
        $('#modalChangeCost').modal('show');
    });



    $("#inp_orderDeliverType").each(function (index) {
        $(this).change(function () {
            let val = $(this).val();
            changeDeliver(val);
        });
    });

    $("#btnChangeDeliverLoc").click(function () {
        changeDeliverLoc();
    });

    $('#btnChangeStatus').click(function () {
        changeOrderStatus();
    });

    $('#btnChangePayment').click(function () {
        changeOrderPayment();
    });

    $('#btnChangeCost').click(function () {
        changeOrderCost();
    });

}

function changeOrderStatus() {
    let status = $("#inp_orderNewStatus").val();

    if (status == null)
        return;

    $.ajax({
        url: "/systemAdmin/orderChangeStatus",
        method: "POST",
        data: {
            id: _orderID,
            status: status,
        },
        success: function (data) {
            try {
                if (data.success == true) {
                    showAlert(AlertType.SUCCESS, Lang.ORDER_STATUS_SUCCESS, '#alert01');

                    setTimeout(function (argument) {
                        location.reload();
                    }, 800);
                } else {
                    showAlert(AlertType.ERROR, data.msg, '#alert01');
                }
            } catch (e) {
                showAlert(AlertType.ERROR, Lang.FORM_SENDING_ERROR, '#alert01');
            }
        },
        error: function () {
            showAlert(AlertType.ERROR, Lang.FORM_SENDING_ERROR, '#alert01');
        }
    });
}

function changeDeliver(deliver) {

    _currentDeliver = deliver;

    $("#modalDeliverLoc_Locker").addClass('d-none');
    $("#modalDeliverLoc_Courier").addClass('d-none');

    switch (deliver) {
        case 'INPOST_LOCKER':
            $("#modalDeliverLoc_Locker").removeClass('d-none');
            if (lockerMap != null) {
                return;
            }

            lockerMap = easyPack.mapWidget('easypack-map', function (point) {
                if (point == null)
                    return;

                _currentLocker = point.name;

                $("#dataLockerName").html(point.name);
                $("#dataLockerAddress").html("ul. " + point.address.line1 + " " + point.address.line2);
            });

            break;
        case 'COURIER':
            $("#modalDeliverLoc_Courier").removeClass('d-none');
            break;
    }
}

function changeDeliverLoc() {

    if (_currentDeliver == '') {
        showAlert(AlertType.ERROR, Lang.ORDER_DELIVER_CHOOSE, '#alert02');
        return;
    }

    let m_deliver = [];

    if (_currentDeliver == 'INPOST_LOCKER') {
        if (_currentLocker == '') {
            showAlert(AlertType.ERROR, Lang.ORDER_DELIVER_LOCKER, '#alert02');
            return;
        }

        m_deliver['type'] = "INPOST_LOCKER";
        m_deliver['lockerName'] = _currentLocker;
    }

    if (_currentDeliver == 'COURIER') {


        let d_district = $("#inp_data_district").val();
        let d_city = $("#inp_data_city").val();
        let d_zipcode = $("#inp_data_zipcode").val();
        let d_address = $("#inp_data_address").val();

        d_zipcode = d_zipcode.replace(/\s+/g, "");

        if (!validateDistrict(d_district)) {
            showAlert(AlertType.ERROR, Lang.DISTRICT_INCORRECT, '#alert02');
            return;
        }

        if (!validateCityLength(d_city)) {
            showAlert(AlertType.ERROR, Lang.CITY_NOT_IN_RANGE, '#alert02');
            return;
        }

        if (!validateCityString(d_city)) {
            showAlert(AlertType.ERROR, Lang.CITY_NAME_INCORRECT, '#alert02');
            return;
        }

        if (!validateZipCode(d_zipcode)) {
            showAlert(AlertType.ERROR, Lang.ZIPCODE_INCORRECT, '#alert02');
            return;
        }

        if (!validateAdressLength(d_address)) {
            showAlert(AlertType.ERROR, Lang.ADDRESS_NOT_IN_RANGE, '#alert02');
            return;
        }

        if (!validateAdressString(d_address)) {
            showAlert(AlertType.ERROR, Lang.ADDRESS_NAME_INCORRECT, '#alert02');
            return;
        }


        m_deliver['type'] = "COURIER";
        m_deliver['district'] = d_district;
        m_deliver['city'] = d_city;
        m_deliver['zipcode'] = d_zipcode;
        m_deliver['address'] = d_address;
    }

    let orderData = [];

    orderData['id'] = _orderID;
    orderData['deliver'] = toObject(m_deliver);

    showAlert(AlertType.LOADING, Lang.ORDER_CONFIRMING);

    $.ajax({
        url: "/systemAdmin/orderChangeDeliverLoc",
        method: "POST",
        data: toObject(orderData),
        success: function (data) {
            if (data.success == true) {
                showAlert(AlertType.SUCCESS, Lang.ORDER_CHANGE_DELIVERLOC_SUCCESS, '#alert02');

                setTimeout(function (argument) {
                    location.reload();
                }, 800);
            } else
                showAlert(AlertType.ERROR, Lang.ORDER_CHANGE_DELIVERLOC_ERROR, '#alert02');
        },
        error: function () {
            showAlert(AlertType.ERROR, Lang.ORDER_CHANGE_DELIVERLOC_ERROR, '#alert02');
        }
    });
}

function changeOrderPayment() {
    let paymentMethod = $("#inp_orderPaymentMethod").val();

    if (paymentMethod == null)
        return;

    $.ajax({
        url: "/systemAdmin/orderChangePayment",
        method: "POST",
        data: {
            id: _orderID,
            paymentMethod: paymentMethod,
        },
        success: function (data) {
            try {
                if (data.success == true) {
                    showAlert(AlertType.SUCCESS, Lang.ORDER_STATUS_SUCCESS, '#alert03');

                    setTimeout(function (argument) {
                        location.reload();
                    }, 800);
                } else {
                    showAlert(AlertType.ERROR, data.msg, '#alert03');
                }
            } catch (e) {
                showAlert(AlertType.ERROR, Lang.FORM_SENDING_ERROR, '#alert03');
            }
        },
        error: function () {
            showAlert(AlertType.ERROR, Lang.FORM_SENDING_ERROR, '#alert03');
        }
    });
}

function changeOrderCost() {
    let cost = parseInt($("#inp_orderCost").val());

    if (cost == null || isNaN(cost))
        return;

    $.ajax({
        url: "/systemAdmin/orderChangeCost",
        method: "POST",
        data: {
            id: _orderID,
            cost: cost,
        },
        success: function (data) {
            try {
                if (data.success == true) {
                    showAlert(AlertType.SUCCESS, Lang.ORDER_STATUS_SUCCESS, '#alert03');

                    setTimeout(function (argument) {
                        location.reload();
                    }, 800);
                } else {
                    showAlert(AlertType.ERROR, data.msg, '#alert03');
                }
            } catch (e) {
                showAlert(AlertType.ERROR, Lang.FORM_SENDING_ERROR, '#alert03');
            }
        },
        error: function () {
            showAlert(AlertType.ERROR, Lang.FORM_SENDING_ERROR, '#alert03');
        }
    });
}

window.easyPackAsyncInit = function () {

    easyPack.init({
        defaultLocale: 'pl',
        mapType: 'osm',
        searchType: 'osm',
        points: {
            types: ['parcel_locker']
        },
        map: {
            initialTypes: ['parcel_locker']
        }
    });
}