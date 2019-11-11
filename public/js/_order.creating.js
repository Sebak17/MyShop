var _currentPayment = 0,
    _currentDeliver = '',
    _currentLocker = '';

var lockerMap;

$(document).ready(function () {

    bindDeliver();
    bindPayments();



});

function bindDeliver() {
    $("#orderDeliver").each(function (index) {
        $(this).change(function () {
            let val = $(this).val();
            changeDeliver(val);
        });
    });
}

function changeDeliver(deliver) {

    _currentDeliver = deliver;

    switch (deliver) {
        case 'INPOST_LOCKER':
            $("#modalDeliver").modal("show");

            setTimeout(function () {
                if (lockerMap != null)
                    return;

                easyPack.init({
                    defaultLocale: 'pl',
                    mapType: 'osm',
                    searchType: 'osm',
                    points: {
                        types: ['parcel_locker'],
                        functions: ['parcel_collect']
                    },
                    map: {
                        initialTypes: ['parcel_locker']
                    }
                });

                lockerMap = easyPack.mapWidget('easypack-map', function (point) {
                    if (point == null)
                        return;

                    _currentLocker = point.name;
                    $("#modalDeliver").modal("hide");
                });
            }, 100);
            break;
        case 'INPOST_COURIER':
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