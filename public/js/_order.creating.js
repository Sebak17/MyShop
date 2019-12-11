var _currentPayment = 0,
    _currentDeliver = '',
    _currentLocker = '';

var lockerMap;

var productsCost = 0;

const PRICE_LOCKER = 8.99,
    PRICE_COURIER = 15.99;

$(document).ready(function () {

    productsCost = parseFloat($("#summaryPrice").html());

    bindDeliver();
    bindPayments();

    $("#btnConfirm").click(function () {
        confirmData();
    });

});

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

function confirmData() {

    let d_fname = $("#inp_data_fname").val();
    let d_sname = $("#inp_data_sname").val();
    let d_phone = $("#inp_data_phone").val();

    d_fname = d_fname.replace(/\s+/g, "");
    d_sname = d_sname.replace(/\s+/g, "");
    d_phone = d_phone.replace(/\s+/g, "");

    if (!validatePersonalNameLength(d_fname)) {
        showAlert(AlertType.ERROR, Lang.FIRSTNAME_NOT_IN_RANGE);
        return;
    }

    if (!validatePersonalNameString(d_fname)) {
        showAlert(AlertType.ERROR, Lang.FIRSTNAME_ONLY_LETTERS);
        return;
    }

    if (!validatePersonalNameLength(d_sname)) {
        showAlert(AlertType.ERROR, Lang.SURNAME_NOT_IN_RANGE);
        return;
    }

    if (!validatePersonalNameString(d_sname)) {
        showAlert(AlertType.ERROR, Lang.SURNAME_ONLY_LETTERS);
        return;
    }

    if (!validatePhone(d_phone)) {
        showAlert(AlertType.ERROR, Lang.PHONENUMBER_INCORRECT);
        return;
    }

    if (_currentPayment == 0) {
        showAlert(AlertType.ERROR, Lang.ORDER_PAYMENT_CHOOSE);
        return;
    }

    if (_currentDeliver == '') {
        showAlert(AlertType.ERROR, Lang.ORDER_DELIVER_CHOOSE);
        return;
    }

    let m_deliver = [];

    if (_currentDeliver == 'INPOST_LOCKER') {
        if (_currentLocker == '') {
            showAlert(AlertType.ERROR, Lang.ORDER_DELIVER_LOCKER);
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
            showAlert(AlertType.ERROR, Lang.DISTRICT_INCORRECT);
            return;
        }

        if (!validateCityLength(d_city)) {
            showAlert(AlertType.ERROR, Lang.CITY_NOT_IN_RANGE);
            return;
        }

        if (!validateCityString(d_city)) {
            showAlert(AlertType.ERROR, Lang.CITY_NAME_INCORRECT);
            return;
        }

        if (!validateZipCode(d_zipcode)) {
            showAlert(AlertType.ERROR, Lang.ZIPCODE_INCORRECT);
            return;
        }

        if (!validateAdressLength(d_address)) {
            showAlert(AlertType.ERROR, Lang.ADDRESS_NOT_IN_RANGE);
            return;
        }

        if (!validateAdressString(d_address)) {
            showAlert(AlertType.ERROR, Lang.ADDRESS_NAME_INCORRECT);
            return;
        }


        m_deliver['type'] = "COURIER";
        m_deliver['district'] = d_district;
        m_deliver['city'] = d_city;
        m_deliver['zipcode'] = d_zipcode;
        m_deliver['address'] = d_address;
    }

    let addNote = false;
    let clientNote = $("#orderNote").val();
    if (clientNote.length != 0) {

        if (clientNote.length < 4 || clientNote.length > 1000) {
            showAlert(AlertType.ERROR, Lang.ORDER_NOTE_NOT_IN_RANGE);
            return;
        }

        addNote = true;
    }


    let orderData = [];

    orderData['paymentType'] = getPaymentName();

    orderData['clientFName'] = d_fname;
    orderData['clientSName'] = d_sname;
    orderData['clientPhone'] = d_phone;

    orderData['deliver'] = toObject(m_deliver);

    if (addNote)
        orderData['note'] = clientNote;

    showAlert(AlertType.LOADING, Lang.ORDER_CONFIRMING);

    $.ajax({
        url: "/systemUser/createOrder",
        method: "POST",
        data: toObject(orderData),
        success: function (data) {
            if (data.success == true) {
                showAlert(AlertType.SUCCESS, Lang.ORDER_CONFIRMING_SUCCESS);
                location.href = data.url;
            } else
                showAlert(AlertType.ERROR, Lang.ORDER_CONFIRMING_ERROR);
        },
        error: function () {
            showAlert(AlertType.ERROR, Lang.ORDER_CONFIRMING_ERROR);
        },
        complete: function () {}
    });
}

function bindDeliver() {
    $("#orderDeliver").each(function (index) {
        $(this).change(function () {
            let val = $(this).val();
            changeDeliver(val);
        });
    });

    $("#btnChangeLocker").each(function (index) {
        $(this).click(function () {
            changeDeliver("INPOST_LOCKER");
        });
    });
}

function changeDeliver(deliver) {

    _currentDeliver = deliver;

    $("#lockerInfo").addClass('d-none');
    $("#deliverForm").addClass('d-none');

    switch (deliver) {
        case 'INPOST_LOCKER':
            if (lockerMap != null) {
                $("#modalDeliver").modal("show");
                return;
            }

            lockerMap = easyPack.mapWidget('easypack-map', function (point) {
                if (point == null)
                    return;

                _currentLocker = point.name;

                $("#lockerInfo").removeClass('d-none');
                $("#dataLockerName").html(point.name);
                $("#dataLockerAddress").html("ul. " + point.address.line1 + " " + point.address.line2);

                $("#modalDeliver").modal("hide");
                $("#deliverPrice").html(PRICE_LOCKER);
                $("#summaryPrice").html(rePrice(productsCost + PRICE_LOCKER));
            });

            $("#modalDeliver").modal("show");

            break;
        case 'COURIER':
            $("#deliverForm").removeClass('d-none');

            $("#deliverPrice").html(PRICE_COURIER);
            $("#summaryPrice").html(rePrice(productsCost + PRICE_COURIER));
            break;
    }
}

//
// 1 - PayU
// 2 - PayPal
// 3 - Payment Card
//

function bindPayments() {
    $("[data-payment]").each(function (index) {
        $(this).click(function () {
            let method = $(this).attr('data-payment');
            changePayment(this, method);
        });
    });
}

function getPaymentName() {
    switch (_currentPayment) {
        case 1:
            return "PAYU";
        case 2:
            return "PAYPAL";
        case 3:
            return "PAYMENTCARD";
    }
}

function changePayment(element, method) {
    method = parseInt(method);

    $("[data-payment='" + _currentPayment + "']").removeClass('card-pick');

    _currentPayment = method;

    switch (method) {
        case 1:
            $(element).addClass('card-pick');
            break;
        case 2:
            $(element).addClass('card-pick');
            break;
        case 3:
            $(element).addClass('card-pick');
            break;
    }
}

function rePrice(price) {
    return Math.round(price * 100) / 100;
}